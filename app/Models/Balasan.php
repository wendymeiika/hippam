<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Balasan extends Model
{
    use HasFactory;

    protected $table = 'balasan';

    protected $guarded = [];

    public function keluhan()
    {
        return $this->belongsTo(Keluhan::class, 'id_keluhan');
    }
}
