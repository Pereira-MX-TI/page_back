<?php

namespace App\Http\Models\v1;

use Illuminate\Database\Eloquent\Model;

class Users_site extends Model
{
    protected $fillable = [
        'id',
        'user_id',
        'site_id',
        'is_active',
    ];

    protected $hidden = [
        "site_id",
        'is_active',
        'created_at',
        'updated_at',
    ];
}
