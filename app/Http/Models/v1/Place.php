<?php

namespace App\Http\Models\v1;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    protected $fillable = [
        'id',
        'name',
        'address',
        "longitude",
        "latitude",
        "type",
        "site_id",
        "date_time"
    ];

    protected $hidden = [
        "site_id",
        'created_at',
        'updated_at',
    ];
}
