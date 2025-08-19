<?php

namespace App\Http\Models\v1;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $fillable = [
        'id',
        'url',
        'site_id',
        'is_active',
    ];

    protected $hidden = [
        'site_id',
        'is_active',
        'created_at',
        'updated_at',
    ];
}
