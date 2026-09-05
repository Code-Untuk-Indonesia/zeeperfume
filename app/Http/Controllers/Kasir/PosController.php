<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class PosController extends Controller
{
    /**
     * Menampilkan halaman transaksi (POS) Kasir
     */
    public function index()
    {
        // Ambil ID Cabang dari Kasir yang sedang login (Fallback ke 1 jika null)
        $cabangId = auth()->user()->cabang_id ?? 1;

        // Ambil semua kategori untuk tombol filter
        $categories = Category::all();

        // Ambil produk (yang fisiknya ada), beserta varian dan stok di cabang kasir ini
        $products = Product::with(['category', 'variants' => function ($query) use ($cabangId) {
            $query->with(['branchStocks' => function ($stockQuery) use ($cabangId) {
                $stockQuery->where('cabang_id', $cabangId);
            }]);
        }])
        ->where('tipe_stok', 'ada_stok')
        ->get();

        return view('kasir.pos.index', compact('categories', 'products'));
    }
}
