<?php

namespace App\Http\Models\v1;

use Illuminate\Database\Eloquent\Model;

class Parent_celebrated extends Model
{
    protected $table = 'parent_celebrateds';

    protected $fillable = [
        'id',
        'name',
        'type',
        "site_id"
    ];

    protected $hidden = [
        "site_id",
        'created_at',
        'updated_at',
    ];
}
