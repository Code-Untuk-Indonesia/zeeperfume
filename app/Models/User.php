<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // 1. Tambahkan import ini

class User extends Authenticatable
{
    // 2. Sisipkan HasApiTokens di sini
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'role_id',
        'nama_lengkap',
        'username',
        'password',
        'status_aktif',
        'cabang_id',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'status_aktif' => 'boolean',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'cabang_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'kasir_id');
    }

    public function stockHistories(): HasMany
    {
        return $this->hasMany(StockHistory::class, 'user_id');
    }
}
