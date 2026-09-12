<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\BranchStock;
use App\Models\StockHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'variants.branchStocks.branch'])->withCount('variants');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama_produk', 'like', "%{$search}%")
                ->orWhereHas('category', function ($q) use ($search) {
                    $q->where('nama_kategori', 'like', "%{$search}%");
                });
        }

        if ($request->filled('kategori') && $request->kategori !== 'all') {
            $query->where('kategori_id', $request->kategori);
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        $categories = Category::all();
        $branches = Branch::all();
        $totalProducts = Product::count();
        $lowStockCount = DB::table('stok_cabang')->where('stok', '<', 10)->distinct('varian_id')->count('varian_id');

        return view('owner.stock.index', compact('products', 'categories', 'branches', 'totalProducts', 'lowStockCount'));
    }

    public function create()
    {
        $categories = Category::all();
        $branches = Branch::all();
        return view('owner.stock.create', compact('categories', 'branches'));
    }

    public function storeAjax(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:categories,nama_kategori',
        ]);

        try {
            $category = Category::create(['nama_kategori' => $request->nama_kategori]);
            return response()->json(['success' => true, 'category' => $category, 'message' => 'Kategori berhasil ditambahkan!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'status'      => 'required|in:ada_stok,draft',
            'variants'    => 'required|array|min:1',
            'variants.*.name'  => 'required|string',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $userId = auth()->id() ?? 1;

            // 1. Simpan Produk Induk
            $product = Product::create([
                'nama_produk' => $request->name,
                'deskripsi'   => $request->description,
                'kategori_id' => $request->category_id,
                'tipe_stok'   => $request->status,
            ]);

            // 2. Loop Semua Varian yang diinput
            foreach ($request->variants as $varId => $varData) {

                // Upload Gambar
                $imagePath = null;
                if ($request->hasFile("variants.$varId.image")) {
                    $image = $request->file("variants.$varId.image");
                    $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('uploads/variants'), $imageName);
                    $imagePath = 'uploads/variants/' . $imageName;
                }

                // Simpan Varian
                $variant = ProductVariant::create([
                    'produk_id'   => $product->id,
                    'nama_varian' => $varData['name'],
                    'sku'         => $varData['sku'] ?? null,
                    'harga_beli'  => $varData['cost'] ?? 0,
                    'harga_jual'  => $varData['price'],
                    'satuan'      => $varData['unit'] ?? 'pcs',
                    'image'       => $imagePath,
                ]);

                // Simpan Stok Pusat (Wajib)
                $stokPusat = $varData['stock_pusat'] ?? 0;
                if ($stokPusat > 0) {
                    BranchStock::create(['varian_id' => $variant->id, 'cabang_id' => 1, 'stok' => $stokPusat]);
                    StockHistory::create([
                        'cabang_id' => 1,
                        'varian_id' => $variant->id,
                        'user_id' => $userId,
                        'jenis_riwayat' => 'masuk',
                        'qty' => $stokPusat,
                        'keterangan' => 'Stok awal gudang pusat',
                        'waktu' => now(),
                    ]);
                }

                // Distribusi Stok ke Cabang Lain (Jika Diaktifkan)
                if (isset($varData['distribute']) && isset($varData['branches'])) {
                    foreach ($varData['branches'] as $branchId => $bData) {
                        if (!isset($bData['assign'])) continue; // Jika checkbox cabang tidak dicentang, lewati

                        $stokCabang = $bData['stock'] ?? 0;
                        $source = $bData['source'] ?? 'direct';

                        if ($stokCabang > 0) {
                            if ($source === 'from_pusat') {
                                $pusatRecord = BranchStock::where('varian_id', $variant->id)->where('cabang_id', 1)->first();
                                if (!$pusatRecord || $pusatRecord->stok < $stokCabang) {
                                    throw new \Exception("Stok Pusat varian '{$varData['name']}' tidak cukup untuk ditransfer ke cabang.");
                                }
                                $pusatRecord->decrement('stok', $stokCabang);

                                StockHistory::create([
                                    'cabang_id' => 1,
                                    'varian_id' => $variant->id,
                                    'user_id' => $userId,
                                    'jenis_riwayat' => 'keluar',
                                    'qty' => -$stokCabang,
                                    'keterangan' => "Distribusi ke Cabang ID: $branchId",
                                    'waktu' => now()
                                ]);
                                StockHistory::create([
                                    'cabang_id' => $branchId,
                                    'varian_id' => $variant->id,
                                    'user_id' => $userId,
                                    'jenis_riwayat' => 'masuk',
                                    'qty' => $stokCabang,
                                    'keterangan' => "Menerima distribusi dari Pusat",
                                    'waktu' => now()
                                ]);
                            } else {
                                StockHistory::create([
                                    'cabang_id' => $branchId,
                                    'varian_id' => $variant->id,
                                    'user_id' => $userId,
                                    'jenis_riwayat' => 'masuk',
                                    'qty' => $stokCabang,
                                    'keterangan' => "Stok awal (Langsung Supplier)",
                                    'waktu' => now()
                                ]);
                            }

                            BranchStock::create(['varian_id' => $variant->id, 'cabang_id' => $branchId, 'stok' => $stokCabang]);
                        }
                    }
                }
            }

            DB::commit();
            return redirect()->route('owner.stock.index')->with('success', 'Produk dan Varian berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan produk: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $product = Product::with(['variants.branchStocks'])->findOrFail($id);
        $categories = Category::all();
        $branches = Branch::all();
        return view('owner.stock.edit', compact('product', 'categories', 'branches'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'status'      => 'required|in:ada_stok,draft',
            'variants'    => 'required|array|min:1',
            'variants.*.name'  => 'required|string',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $product = Product::findOrFail($id);
            $userId  = auth()->id() ?? 1;

            $product->update([
                'nama_produk' => $request->name,
                'deskripsi'   => $request->description,
                'kategori_id' => $request->category_id,
                'tipe_stok'   => $request->status,
            ]);

            foreach ($request->variants as $varId => $varData) {
                $variant = ProductVariant::find($varId);
                if (!$variant) continue;

                $updateData = [
                    'nama_varian' => $varData['name'],
                    'sku'         => $varData['sku'] ?? null,
                    'harga_beli'  => $varData['cost'] ?? 0,
                    'harga_jual'  => $varData['price'],
                    'satuan'      => $varData['unit'] ?? 'pcs',
                ];

                if ($request->hasFile("variants.$varId.image")) {
                    $image = $request->file("variants.$varId.image");
                    $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('uploads/variants'), $imageName);
                    $updateData['image'] = 'uploads/variants/' . $imageName;
                }

                $variant->update($updateData);

                // Update Stok Pusat
                $stokPusatBaru = $varData['stock_pusat'] ?? 0;
                $pusatRecord = BranchStock::firstOrCreate(['varian_id' => $variant->id, 'cabang_id' => 1], ['stok' => 0]);
                $diffPusat = $stokPusatBaru - $pusatRecord->stok;

                if ($diffPusat != 0) {
                    $pusatRecord->update(['stok' => $stokPusatBaru]);
                    StockHistory::create([
                        'cabang_id' => 1,
                        'varian_id' => $variant->id,
                        'user_id' => $userId,
                        'jenis_riwayat' => $diffPusat > 0 ? 'masuk' : 'keluar',
                        'qty' => $diffPusat,
                        'keterangan' => 'Penyesuaian stok pusat',
                        'waktu' => now(),
                    ]);
                }

                // Update Stok Cabang Lain
                if (isset($varData['branches'])) {
                    foreach ($varData['branches'] as $branchId => $bData) {
                        $stokCabangBaru = $bData['stock'] ?? 0;
                        $cabangRecord = BranchStock::firstOrCreate(['varian_id' => $variant->id, 'cabang_id' => $branchId], ['stok' => 0]);
                        $diffCabang = $stokCabangBaru - $cabangRecord->stok;
                        $source = $bData['source'] ?? 'direct';

                        if ($diffCabang > 0) {
                            if ($source === 'from_pusat') {
                                $pusatRecord->refresh();
                                if ($pusatRecord->stok < $diffCabang) {
                                    throw new \Exception("Stok Pusat varian '{$variant->nama_varian}' tidak cukup untuk ditransfer.");
                                }
                                $pusatRecord->decrement('stok', $diffCabang);

                                StockHistory::create([
                                    'cabang_id' => 1,
                                    'varian_id' => $variant->id,
                                    'user_id' => $userId,
                                    'jenis_riwayat' => 'keluar',
                                    'qty' => -$diffCabang,
                                    'keterangan' => "Transfer ke Cabang ID: $branchId",
                                    'waktu' => now(),
                                ]);
                                StockHistory::create([
                                    'cabang_id' => $branchId,
                                    'varian_id' => $variant->id,
                                    'user_id' => $userId,
                                    'jenis_riwayat' => 'masuk',
                                    'qty' => $diffCabang,
                                    'keterangan' => "Terima transfer dari Pusat",
                                    'waktu' => now(),
                                ]);
                            } else {
                                StockHistory::create([
                                    'cabang_id' => $branchId,
                                    'varian_id' => $variant->id,
                                    'user_id' => $userId,
                                    'jenis_riwayat' => 'masuk',
                                    'qty' => $diffCabang,
                                    'keterangan' => 'Penambahan stok langsung',
                                    'waktu' => now(),
                                ]);
                            }
                        } elseif ($diffCabang < 0) {
                            StockHistory::create([
                                'cabang_id' => $branchId,
                                'varian_id' => $variant->id,
                                'user_id' => $userId,
                                'jenis_riwayat' => 'keluar',
                                'qty' => $diffCabang,
                                'keterangan' => 'Penyesuaian pengurangan stok',
                                'waktu' => now(),
                            ]);
                        }
                        $cabangRecord->update(['stok' => $stokCabangBaru]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('owner.stock.index')->with('success', 'Produk berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal update produk: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $product = Product::with('variants')->findOrFail($id);
            if ($product->variants) {
                foreach ($product->variants as $variant) {
                    BranchStock::where('varian_id', $variant->id)->delete();
                    StockHistory::where('varian_id', $variant->id)->delete();
                    $variant->delete();
                }
            }
            $product->delete();
            DB::commit();
            return redirect()->route('owner.stock.index')->with('success', 'Produk berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }
}
