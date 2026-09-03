<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['nama_cabang', 'alamat', 'no_telepon'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'cabang_id');
    }

    public function branchStocks(): HasMany
    {
        return $this->hasMany(BranchStock::class, 'cabang_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'cabang_id');
    }

    public function stockHistories(): HasMany
    {
        return $this->hasMany(StockHistory::class, 'cabang_id');
    }
}
