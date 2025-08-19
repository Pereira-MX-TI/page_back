<?php

namespace App\Http\Models\v1;

use Illuminate\Database\Eloquent\Model;

class Weather extends Model
{
    protected $table = 'weathers';

    protected $fillable = [
        'id',
        'code_city',
        'name_city',
        "site_id"
    ];

    protected $hidden = [
        "site_id",
        'created_at',
        'updated_at',
    ];
}
