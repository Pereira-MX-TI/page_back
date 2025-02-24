<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'is_active',
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'is_active',
    ];
}
