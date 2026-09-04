<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'kasir_id',
        'member_id',
        'nomor_nota',
        'tanggal_waktu',
        'subtotal',
        'diskon_persen',
        'diskon_nominal',
        'deskripsi_diskon',
        'total_belanja',
        'nominal_bayar',
        'kembalian',
        'metode_bayar',
        'cabang_id',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_waktu' => 'datetime',
            'subtotal' => 'decimal:2',
            'diskon_persen' => 'decimal:2',
            'diskon_nominal' => 'decimal:2',
            'total_belanja' => 'decimal:2',
            'nominal_bayar' => 'decimal:2',
            'kembalian' => 'decimal:2',
        ];
    }

    public function cashier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'kasir_id');
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'cabang_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(TransactionDetail::class, 'transaksi_id');
    }

    public function cashTempo(): HasOne
    {
        return $this->hasOne(CashTempo::class, 'transaksi_id');
    }

    public function shipment(): HasOne
    {
        return $this->hasOne(Shipment::class, 'transaksi_id');
    }

    public function stockHistories(): HasMany
    {
        return $this->hasMany(StockHistory::class, 'transaksi_id');
    }
}
