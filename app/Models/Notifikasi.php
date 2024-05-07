<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'notifikasi';

    protected $guarded = [];

    /**
     * Definisikan relasi Eloquent untuk mengaitkan notifikasi dengan pengguna.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_pelanggan', 'id');
    }
}
