<?php

namespace App\Http\Models\v1;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        'id',
        'url',
        'reference_id',
        'reference_type',
        'is_active',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'reference_id',
        'reference_type',
        'is_active',
    ];
}
