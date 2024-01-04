<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactWeb extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = "contact_web";

    protected $fillable = [
        'name',
        'email', 
        'phone',
        'cp',
        'is_active'
    ];

    protected $hidden = [
        'is_active',
        'created_at', 
        'updated_at'
    ];
}
