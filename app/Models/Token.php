<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    protected $fillable = [
        'data', 'user_id', 'device', 'is_active',
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'is_active',
    ];
}
