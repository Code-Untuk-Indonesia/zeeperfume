<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data user dari token Sanctum
        $user = $request->user();

        // Ambil ID Cabang dari Kasir yang sedang login (Fallback ke 1 jika null)
        $cabangId = $user ? $user->cabang_id : 1;

        // Ambil semua kategori untuk tombol filter di aplikasi mobile
        $categories = Category::all();

        // Ambil produk (yang fisiknya ada), beserta varian dan stok di cabang kasir ini
        $products = Product::with(['category', 'variants' => function ($query) use ($cabangId) {
            $query->with(['branchStocks' => function ($stockQuery) use ($cabangId) {
                $stockQuery->where('cabang_id', $cabangId);
            }]);
        }])
        ->where('tipe_stok', 'ada_stok')
        ->get();

        // Kembalikan respons dalam format JSON
        return response()->json([
            'success' => true,
            'message' => 'Data produk berhasil diambil.',
            'data'    => [
                'categories' => $categories,
                'products'   => $products
            ]
        ], 200);
    }
}
