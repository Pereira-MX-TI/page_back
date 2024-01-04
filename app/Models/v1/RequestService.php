<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestService extends Model
{
    use HasFactory;
    protected $table = "request_services";

    protected $fillable = [
        'contact_id',
        'ip_address',
        'name',
        'message',
        'is_active'
    ];

    protected $hidden = [
        'is_active',
        'created_at', 
        'updated_at'
    ];
}
