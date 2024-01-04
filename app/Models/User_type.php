<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_type extends Model
{
    protected $fillable = [
        'type_user_name'
    ];

    protected $hidden = [
        'created_at', 'updated_at',
    ];
}
