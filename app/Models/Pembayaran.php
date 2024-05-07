<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_pelanggan', 'id');
    }
}
