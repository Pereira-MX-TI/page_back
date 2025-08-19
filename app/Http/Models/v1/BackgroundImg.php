<?php

namespace App\Http\Models\v1;

use Illuminate\Database\Eloquent\Model;

class BackgroundImg extends Model
{
    protected $table = 'img_backgrounds';

    protected $fillable = [
        'id',
        "url",
        'type',
        'site_id',
        'is_active',
    ];

    protected $hidden = [
        'is_active',
        'site_id',
        'created_at',
        'updated_at',
    ];
}
