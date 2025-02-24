<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Description extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';

    protected $hidden = [
        'service_id', 'product_id', 'estatus_crud', 'user_id',
    ];
}
