<?php

namespace App\Http\Models\v1;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    protected $fillable = [
        'id',
        'data',
        'user_id',
        'device',
        'is_active',
    ];

    protected $hidden = [
        'is_active',
        'created_at',
        'updated_at',
    ];
}
