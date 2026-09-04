<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['kategori_id', 'nama_produk', 'deskripsi', 'tipe_stok', 'image'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'kategori_id');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class, 'produk_id');
    }

    public function branchStocks(): HasManyThrough
    {
        return $this->hasManyThrough(
            BranchStock::class,
            ProductVariant::class,
            'produk_id',
            'varian_id',
        );
    }
}
