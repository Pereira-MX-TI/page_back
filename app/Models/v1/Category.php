<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';

    protected $table = 'categories';

    protected $hidden = [
        'estatus_crud', 'user_id',
    ];
}
