<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaksi_id',
        'no_resi',
        'biaya_kirim',
        'jenis_pengiriman',
        'nama_penerima',
        'no_telepon_penerima',
        'alamat_tujuan',
        'catatan_kurir',
    ];

    protected function casts(): array
    {
        return [
            'biaya_kirim' => 'decimal:2',
        ];
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaksi_id');
    }
}
