<?php

namespace App\Http\Models\v1;

use Illuminate\Database\Eloquent\Model;

class User_type extends Model
{
    protected $fillable = [
        'id',
        'type_user_name',
        'is_active'
    ];

    protected $hidden = [
        'is_active',
        'created_at',
        'updated_at',
    ];
}
