<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Keluhan extends Model
{
    use HasFactory;

    protected $table = 'keluhan';

    protected $guarded = [];

    public function balasan()
    {
        return $this->hasMany(Balasan::class, 'id_keluhan');
    }

    /**
     * Get the user that owns the Keluhan
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_pelanggan');
    }
}
