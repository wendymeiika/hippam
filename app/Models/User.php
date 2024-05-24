<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    protected $casts = [
        'password' => 'hashed',
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

    /**
     * Get all of the replies for the User
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Balasan::class, 'id_petugas');
    }

    /**
     * Get all of the keluhans for the User
     */
    public function keluhans(): HasMany
    {
        return $this->hasMany(Keluhan::class, 'id_pelanggan');
    }

    /**
     * Get the role that owns the User
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
}
