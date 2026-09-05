<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    // API Menampilkan Riwayat Transaksi
    public function index()
    {
        // Mengambil transaksi beserta detail barang, varian produk, dan data member pembeli
        $transactions = Transaction::with(['details.variant', 'member'])->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'message' => 'Riwayat Transaksi Berhasil Diambil',
            'data'    => $transactions
        ]);
    }
}
