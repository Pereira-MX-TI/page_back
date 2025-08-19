<?php

namespace App\Http\Models\v1;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    protected $fillable = [
        'id',
        'number',
        'is_active',
        'site_id',
    ];

    protected $hidden = [
        'site_id',
        'is_active',
        'created_at',
        'updated_at',
    ];
}
