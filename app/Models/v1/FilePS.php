<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FilePS extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = "files";

    protected $hidden = [
        'register_id','register_type','formato','estatus_crud', 'user_id'
    ];
}
