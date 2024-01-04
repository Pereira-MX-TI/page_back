<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';

    protected $hidden = [
        'estatus_crud', 'user_id'
    ];
}
