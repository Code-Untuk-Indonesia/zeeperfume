<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function index(Request $request)
    {
        // 1. Base Query untuk Produk
        $query = Product::with(['category', 'variants.branchStocks.branch'])
            ->withCount('variants'); // Menghitung berapa varian yang dimiliki

        // 2. Filter Pencarian (Nama Produk atau Kategori)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama_produk', 'like', "%{$search}%")
                ->orWhereHas('category', function ($q) use ($search) {
                    $q->where('nama_kategori', 'like', "%{$search}%");
                });
        }

        // 3. Filter Kategori (Jika Dropdown Kategori Digunakan)
        if ($request->filled('kategori') && $request->kategori !== 'all') {
            $query->where('kategori_id', $request->kategori);
        }

        // 4. Pagination
        $products = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        // 5. Data Tambahan untuk Filter & Quick Stats
        $categories = Category::all();
        $branches = Branch::all();

        $totalProducts = Product::count();
        // Cek produk yang salah satu stok cabangnya di bawah 10
        $lowStockCount = DB::table('stok_cabang')->where('stok', '<', 10)->distinct('varian_id')->count('varian_id');

        return view('admin.stock.index', compact(
            'products',
            'categories',
            'branches',
            'totalProducts',
            'lowStockCount'
        ));
    }


    /**
     * Menampilkan Form Tambah Produk
     */
    public function create()
    {
        $categories = Category::all();
        $branches = Branch::all(); // Mengambil daftar semua cabang untuk checkbox alokasi
        return view('admin.stock.create', compact('categories', 'branches'));
    }

    /**
     * Menyimpan Produk & Stok Baru + Pencatatan Histori
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'product_type' => 'required|in:kemasan,refill',
            'category_id'  => 'required|exists:categories,id',
            'status'       => 'required|in:ada_stok,draft',
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

            // ================= MODE 1: PRODUK KEMASAN / BOTOL =================
            if ($request->product_type === 'kemasan') {
                $request->validate([
                    'variant_name'   => 'required|array',
                    'variant_price'  => 'required|array',
                ]);

                foreach ($request->variant_name as $index => $varName) {
                    $variant = \App\Models\ProductVariant::create([
                        'produk_id'   => $product->id,
                        'nama_varian' => $varName,
                        'sku'         => $request->variant_sku[$index] ?? null,
                        'harga_modal' => $request->variant_cost[$index] ?? 0,
                        'harga_jual'  => $request->variant_price[$index] ?? 0,
                        'satuan'      => 'pcs',
                    ]);

                    // Simpan Stok Pusat (Wajib)
                    $stokPusat = $request->stock_pusat_pcs[$index] ?? 0;
                    if ($stokPusat > 0) {
                        \App\Models\BranchStock::create(['varian_id' => $variant->id, 'cabang_id' => 1, 'stok' => $stokPusat]);
                        
                        \App\Models\StockHistory::create([
                            'cabang_id'     => 1,
                            'varian_id'     => $variant->id,
                            'user_id'       => $userId,
                            'jenis_riwayat' => 'masuk',
                            'qty'           => $stokPusat,
                            'keterangan'    => 'Stok awal gudang pusat',
                            'waktu'         => now(),
                        ]);
                    }

                    // Simpan Distribusi Stok Cabang
                    if ($request->has('assign_branch_pcs')) {
                        foreach ($request->assign_branch_pcs as $branchId) {
                            $stokCabang = $request->input("stock_branch_pcs.{$index}.{$branchId}") ?? 0;
                            $source     = $request->input("stock_source.{$index}.{$branchId}") ?? 'direct';

                            if ($stokCabang > 0) {
                                if ($source === 'from_pusat') {
                                    // Cek dan Potong Stok Pusat
                                    $pusatRecord = \App\Models\BranchStock::where('varian_id', $variant->id)->where('cabang_id', 1)->first();
                                    if (!$pusatRecord || $pusatRecord->stok < $stokCabang) {
                                        throw new \Exception("Stok Pusat varian {$varName} tidak cukup untuk ditransfer ke cabang.");
                                    }
                                    $pusatRecord->decrement('stok', $stokCabang);

                                    // History Keluar di Pusat
                                    \App\Models\StockHistory::create([
                                        'cabang_id' => 1, 'varian_id' => $variant->id, 'user_id' => $userId,
                                        'jenis_riwayat' => 'keluar', 'qty' => $stokCabang, 'keterangan' => "Distribusi ke Cabang ID: $branchId", 'waktu' => now()
                                    ]);

                                    // History Masuk di Cabang
                                    \App\Models\StockHistory::create([
                                        'cabang_id' => $branchId, 'varian_id' => $variant->id, 'user_id' => $userId,
                                        'jenis_riwayat' => 'masuk', 'qty' => $stokCabang, 'keterangan' => "Menerima distribusi dari Pusat", 'waktu' => now()
                                    ]);
                                } else {
                                    // Direct dari Supplier
                                    \App\Models\StockHistory::create([
                                        'cabang_id' => $branchId, 'varian_id' => $variant->id, 'user_id' => $userId,
                                        'jenis_riwayat' => 'masuk', 'qty' => $stokCabang, 'keterangan' => "Stok awal langsung dari supplier", 'waktu' => now()
                                    ]);
                                }

                                // Buat Record Cabang
                                \App\Models\BranchStock::create(['varian_id' => $variant->id, 'cabang_id' => $branchId, 'stok' => $stokCabang]);
                            }
                        }
                    }
                }
            }

            // ================= MODE 2: BIANG / REFILL =================
            if ($request->product_type === 'refill') {
                $request->validate(['refill_price_per_ml' => 'required|numeric|min:0']);

                $variant = \App\Models\ProductVariant::create([
                    'produk_id'   => $product->id,
                    'nama_varian' => $request->name . ' (Biang)',
                    'sku'         => $request->refill_sku ?? null,
                    'harga_modal' => $request->refill_cost_per_ml ?? 0,
                    'harga_jual'  => $request->refill_price_per_ml,
                    'satuan'      => 'ml',
                ]);

                // Simpan Stok Pusat Biang
                $stokPusatInput = $request->refill_stock_pusat ?? 0;
                $multiplier     = ($request->refill_unit_pusat === 'liter') ? 1000 : 1;
                $stokPusat      = $stokPusatInput * $multiplier;

                if ($stokPusat > 0) {
                    \App\Models\BranchStock::create(['varian_id' => $variant->id, 'cabang_id' => 1, 'stok' => $stokPusat]);
                    
                    \App\Models\StockHistory::create([
                        'cabang_id' => 1, 'varian_id' => $variant->id, 'user_id' => $userId,
                        'jenis_riwayat' => 'masuk', 'qty' => $stokPusat, 'keterangan' => 'Stok awal biang pusat', 'waktu' => now()
                    ]);
                }

                // Distribusi Stok Cabang Biang
                if ($request->has('assign_branch_refill')) {
                    foreach ($request->assign_branch_refill as $branchId) {
                        $stokCabangInput = $request->input("refill_stock_branch.{$branchId}") ?? 0;
                        $unitCabang      = $request->input("refill_unit_branch.{$branchId}") ?? 'ml';
                        $stokCabang      = $stokCabangInput * (($unitCabang === 'liter') ? 1000 : 1);
                        $source          = $request->input("refill_stock_source.{$branchId}") ?? 'direct';

                        if ($stokCabang > 0) {
                            if ($source === 'from_pusat') {
                                $pusatRecord = \App\Models\BranchStock::where('varian_id', $variant->id)->where('cabang_id', 1)->first();
                                if (!$pusatRecord || $pusatRecord->stok < $stokCabang) {
                                    throw new \Exception("Stok Pusat Biang tidak mencukupi untuk ditransfer ke cabang.");
                                }
                                $pusatRecord->decrement('stok', $stokCabang);

                                // History Pusat
                                \App\Models\StockHistory::create([
                                    'cabang_id' => 1, 'varian_id' => $variant->id, 'user_id' => $userId,
                                    'jenis_riwayat' => 'keluar', 'qty' => $stokCabang, 'keterangan' => "Transfer biang ke Cabang ID: $branchId", 'waktu' => now()
                                ]);

                                // History Cabang
                                \App\Models\StockHistory::create([
                                    'cabang_id' => $branchId, 'varian_id' => $variant->id, 'user_id' => $userId,
                                    'jenis_riwayat' => 'masuk', 'qty' => $stokCabang, 'keterangan' => "Menerima biang dari Gudang Pusat", 'waktu' => now()
                                ]);
                            } else {
                                \App\Models\StockHistory::create([
                                    'cabang_id' => $branchId, 'varian_id' => $variant->id, 'user_id' => $userId,
                                    'jenis_riwayat' => 'masuk', 'qty' => $stokCabang, 'keterangan' => "Stok awal biang (Langsung Supplier)", 'waktu' => now()
                                ]);
                            }

                            \App\Models\BranchStock::create(['varian_id' => $variant->id, 'cabang_id' => $branchId, 'stok' => $stokCabang]);
                        }
                    }
                }
            }

            DB::commit();
            return redirect()->route('admin.stock.index')->with('success', 'Produk baru dan riwayat stok berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan produk: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan Form Edit Produk
     */
    public function edit($id)
    {
        $product = Product::with(['variants.branchStocks'])->findOrFail($id);
        $categories = Category::all();
        $branches = Branch::all();
        
        // Deteksi apakah ini produk kemasan (botol) atau biang (ml)
        $firstVariant = $product->variants->first();
        $productType = ($firstVariant && strtolower($firstVariant->satuan) === 'ml') ? 'refill' : 'kemasan';

        return view('admin.stock.edit', compact('product', 'categories', 'branches', 'productType'));
    }

/**
     * Memproses Update Data Produk & History Stok
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'status'      => 'required|in:ada_stok,draft',
        ]);

        DB::beginTransaction();
        try {
            $product = Product::findOrFail($id);
            $userId  = auth()->id() ?? 1; // Mendapatkan ID Admin yang mengedit
            
            // 1. Update Induk Produk
            $product->update([
                'nama_produk' => $request->name,
                'deskripsi'   => $request->description,
                'kategori_id' => $request->category_id,
                'tipe_stok'   => $request->status,
            ]);

            // ================= JIKA PRODUK KEMASAN =================
            if ($request->product_type === 'kemasan' && $request->has('variant_id')) {
                foreach ($request->variant_id as $index => $varId) {
                    $variant = \App\Models\ProductVariant::find($varId);
                    if ($variant) {
                        $variant->update([
                            'nama_varian' => $request->variant_name[$index],
                            'sku'         => $request->variant_sku[$index],
                            'harga_modal' => $request->variant_cost[$index],
                            'harga_jual'  => $request->variant_price[$index],
                        ]);

                        // --- UPDATE STOK PUSAT ---
                        $stokPusatBaru = $request->stock_pusat[$index] ?? 0;
                        $pusatRecord = \App\Models\BranchStock::firstOrCreate(
                            ['varian_id' => $variant->id, 'cabang_id' => 1],
                            ['stok' => 0]
                        );
                        
                        $diffPusat = $stokPusatBaru - $pusatRecord->stok;
                        
                        // Jika ada perubahan stok di pusat, catat history-nya
                        if ($diffPusat != 0) {
                            $pusatRecord->update(['stok' => $stokPusatBaru]);
                            
                            \App\Models\StockHistory::create([
                                'cabang_id'     => 1,
                                'varian_id'     => $variant->id,
                                'user_id'       => $userId,
                                'jenis_riwayat' => $diffPusat > 0 ? 'masuk' : 'keluar', // atau 'penyesuaian'
                                'qty'           => $diffPusat,
                                'keterangan'    => 'Update/Penyesuaian stok pusat',
                                'waktu'         => now(),
                            ]);
                        }

                        // --- UPDATE STOK CABANG LAIN ---
                        if ($request->has("stock_branch.{$index}")) {
                            foreach ($request->input("stock_branch.{$index}") as $branchId => $stokCabangBaru) {
                                $cabangRecord = \App\Models\BranchStock::firstOrCreate(
                                    ['varian_id' => $variant->id, 'cabang_id' => $branchId],
                                    ['stok' => 0]
                                );
                                
                                $diffCabang = $stokCabangBaru - $cabangRecord->stok;
                                $source = $request->input("stock_source.{$index}.{$branchId}") ?? 'direct';

                                if ($diffCabang > 0) { // Jika Cabang Nambah Stok
                                    if ($source === 'from_pusat') {
                                        // Potong dari pusat & Catat History Pusat Keluar
                                        $pusatRecord->refresh(); // Ambil data pusat terbaru
                                        if ($pusatRecord->stok < $diffCabang) {
                                            throw new \Exception("Stok Pusat varian '{$variant->nama_varian}' tidak cukup untuk ditransfer.");
                                        }
                                        $pusatRecord->decrement('stok', $diffCabang);
                                        
                                        \App\Models\StockHistory::create([
                                            'cabang_id'     => 1,
                                            'varian_id'     => $variant->id,
                                            'user_id'       => $userId,
                                            'jenis_riwayat' => 'keluar',
                                            'qty'           => -$diffCabang,
                                            'keterangan'    => "Transfer stok ke Cabang ID: $branchId",
                                            'waktu'         => now(),
                                        ]);

                                        // Catat History Cabang Masuk
                                        \App\Models\StockHistory::create([
                                            'cabang_id'     => $branchId,
                                            'varian_id'     => $variant->id,
                                            'user_id'       => $userId,
                                            'jenis_riwayat' => 'masuk',
                                            'qty'           => $diffCabang,
                                            'keterangan'    => "Menerima transfer dari Pusat",
                                            'waktu'         => now(),
                                        ]);
                                    } else {
                                        // Jika langsung dari supplier luar
                                        \App\Models\StockHistory::create([
                                            'cabang_id'     => $branchId,
                                            'varian_id'     => $variant->id,
                                            'user_id'       => $userId,
                                            'jenis_riwayat' => 'masuk',
                                            'qty'           => $diffCabang,
                                            'keterangan'    => 'Penambahan stok langsung',
                                            'waktu'         => now(),
                                        ]);
                                    }
                                } elseif ($diffCabang < 0) { // Jika Cabang Kurang Stok (Barang Rusak/Expired)
                                    \App\Models\StockHistory::create([
                                        'cabang_id'     => $branchId,
                                        'varian_id'     => $variant->id,
                                        'user_id'       => $userId,
                                        'jenis_riwayat' => 'keluar',
                                        'qty'           => $diffCabang, // nilainya sudah minus
                                        'keterangan'    => 'Penyesuaian pengurangan stok',
                                        'waktu'         => now(),
                                    ]);
                                }

                                // Update angka akhir di tabel stok_cabang
                                $cabangRecord->update(['stok' => $stokCabangBaru]);
                            }
                        }
                    }
                }
            }

            // ================= JIKA PRODUK REFILL / BIANG =================
            if ($request->product_type === 'refill' && $request->has('refill_variant_id')) {
                $variant = \App\Models\ProductVariant::find($request->refill_variant_id);
                if ($variant) {
                    $variant->update([
                        'sku'         => $request->refill_sku,
                        'harga_modal' => $request->refill_cost_per_ml,
                        'harga_jual'  => $request->refill_price_per_ml,
                    ]);

                    // --- UPDATE STOK BIANG PUSAT ---
                    $stokPusatBaru = $request->refill_stock_pusat ?? 0;
                    $pusatRecord = \App\Models\BranchStock::firstOrCreate(
                        ['varian_id' => $variant->id, 'cabang_id' => 1],
                        ['stok' => 0]
                    );

                    $diffPusat = $stokPusatBaru - $pusatRecord->stok;
                    if ($diffPusat != 0) {
                        $pusatRecord->update(['stok' => $stokPusatBaru]);
                        
                        \App\Models\StockHistory::create([
                            'cabang_id'     => 1,
                            'varian_id'     => $variant->id,
                            'user_id'       => $userId,
                            'jenis_riwayat' => $diffPusat > 0 ? 'masuk' : 'keluar',
                            'qty'           => $diffPusat,
                            'keterangan'    => 'Update/Penyesuaian stok biang pusat',
                            'waktu'         => now(),
                        ]);
                    }

                    // --- UPDATE STOK BIANG CABANG LAIN ---
                    if ($request->has('refill_stock_branch')) {
                        foreach ($request->refill_stock_branch as $branchId => $stokCabangBaru) {
                            $cabangRecord = \App\Models\BranchStock::firstOrCreate(
                                ['varian_id' => $variant->id, 'cabang_id' => $branchId],
                                ['stok' => 0]
                            );
                            
                            $diffCabang = $stokCabangBaru - $cabangRecord->stok;
                            $source = $request->input("refill_stock_source.{$branchId}") ?? 'direct';

                            if ($diffCabang > 0) {
                                if ($source === 'from_pusat') {
                                    $pusatRecord->refresh();
                                    if ($pusatRecord->stok < $diffCabang) {
                                        throw new \Exception("Stok Pusat Biang tidak mencukupi untuk ditransfer ke cabang.");
                                    }
                                    $pusatRecord->decrement('stok', $diffCabang);

                                    // History Pusat (Keluar)
                                    \App\Models\StockHistory::create([
                                        'cabang_id'     => 1,
                                        'varian_id'     => $variant->id,
                                        'user_id'       => $userId,
                                        'jenis_riwayat' => 'keluar',
                                        'qty'           => -$diffCabang,
                                        'keterangan'    => "Transfer biang ke Cabang ID: $branchId",
                                        'waktu'         => now(),
                                    ]);

                                    // History Cabang (Masuk)
                                    \App\Models\StockHistory::create([
                                        'cabang_id'     => $branchId,
                                        'varian_id'     => $variant->id,
                                        'user_id'       => $userId,
                                        'jenis_riwayat' => 'masuk',
                                        'qty'           => $diffCabang,
                                        'keterangan'    => "Menerima biang dari Pusat",
                                        'waktu'         => now(),
                                    ]);
                                } else {
                                    // Direct
                                    \App\Models\StockHistory::create([
                                        'cabang_id'     => $branchId,
                                        'varian_id'     => $variant->id,
                                        'user_id'       => $userId,
                                        'jenis_riwayat' => 'masuk',
                                        'qty'           => $diffCabang,
                                        'keterangan'    => 'Penambahan biang (Langsung)',
                                        'waktu'         => now(),
                                    ]);
                                }
                            } elseif ($diffCabang < 0) {
                                \App\Models\StockHistory::create([
                                    'cabang_id'     => $branchId,
                                    'varian_id'     => $variant->id,
                                    'user_id'       => $userId,
                                    'jenis_riwayat' => 'keluar',
                                    'qty'           => $diffCabang,
                                    'keterangan'    => 'Penyesuaian pengurangan biang',
                                    'waktu'         => now(),
                                ]);
                            }

                            $cabangRecord->update(['stok' => $stokCabangBaru]);
                        }
                    }
                }
            }

            DB::commit();
            return redirect()->route('admin.stock.index')->with('success', 'Data stok dan riwayat berhasil diperbarui secara otomatis!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal update produk: ' . $e->getMessage());
        }
    }
}
