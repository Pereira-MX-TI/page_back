<?php

namespace App\Http\Models\v1;

use Illuminate\Database\Eloquent\Model;

class Presentation extends Model
{
    protected $fillable = [
        'id',
        "celebrated",
        'title',
        'message',
        'site_id',
    ];

    protected $hidden = [
        'site_id',
        'created_at',
        'updated_at',
    ];
}
