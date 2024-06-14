<?php
namespace App\Models;

use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

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

    
    public function findForPassport($username)
    {
        return $this->where('username', $username)->first();
    }
    
    public function pembayarans(): HasMany
    {
        return $this->hasMany(Pembayaran::class, 'id_pelanggan');
    }
    
    public function notifications(): HasMany
    {
        return $this->hasMany(Notifikasi::class, 'id_pelanggan');
    }
    
    public function replies(): HasMany
    {
        return $this->hasMany(Balasan::class, 'id_petugas');
    }
    
    public function keluhans(): HasMany
    {
        return $this->hasMany(Keluhan::class, 'id_pelanggan');
    }
    
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
    public function get_password_client()
    {
        return DB::table('oauth_clients')->find(2);
    }
}
