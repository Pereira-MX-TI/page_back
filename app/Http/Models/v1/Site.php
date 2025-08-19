<?php

namespace App\Http\Models\v1;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    protected $fillable = [
        'id',
        'type',
        'expiration',
        'is_active',
    ];

    protected $hidden = [
        'is_active',
        'created_at',
        'updated_at',
    ];
}
