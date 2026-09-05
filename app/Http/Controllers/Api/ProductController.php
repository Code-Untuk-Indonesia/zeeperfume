<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // API Menampilkan Daftar Produk
    public function index()
    {
        // Mengambil produk beserta relasi kategori dan varian (lengkap dengan stoknya)
        $products = Product::with(['category', 'variants.stocks'])->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar Produk Berhasil Diambil',
            'data'    => $products
        ]);
    }
}
