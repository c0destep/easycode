<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model
{
    protected $guarded = [];

    public function address(): HasMany
    {
        return self::hasMany(Address::class, 'idUser', 'id');
    }
}