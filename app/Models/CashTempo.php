<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashTempo extends Model
{
    use HasFactory;

    protected $table = 'cash_tempo';

    protected $fillable = [
        'transaksi_id',
        'tanggal_jatuh_tempo',
        'jumlah_piutang',
        'sisa_piutang',
        'status_tempo',
        'status_verifikasi',
        'catatan_penagihan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_jatuh_tempo' => 'date',
            'jumlah_piutang' => 'decimal:2',
            'sisa_piutang' => 'decimal:2',
        ];
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaksi_id');
    }
}
