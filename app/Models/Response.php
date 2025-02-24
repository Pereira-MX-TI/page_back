<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    protected $fillable = [
        'code', 'name', 'description',
    ];

    protected $hidden = [
        'id', 'created_at', 'updated_at',
    ];
}
