<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    use HasFactory;

    /**
     * Get all of the permissions for the Group
     */
    public function permissions(): HasMany
    {
        return $this->hasMany(Permission::class);
    }
}
