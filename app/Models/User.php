<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'user';

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Definisikan relasi Eloquent untuk mengaitkan pengguna dengan pembayaran.
     */
    public function pembayarans(): HasMany
    {
        return $this->hasMany(Pembayaran::class, 'id_pelanggan');
    }

    /**
     * Get all of the notifications for the User
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notifikasi::class, 'id_pelanggan');
    }

    public function scopeRole(Builder $query, $role): Builder
    {
        return $query->where('role', $role);
    }
}
