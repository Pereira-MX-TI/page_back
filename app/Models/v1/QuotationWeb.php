<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationWeb extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';

    protected $table = 'quotation_web';

    protected $fillable = [
        'client_web_id',
        'user_id',
        'ip_address',
        'is_active',
        'status',
    ];

    protected $hidden = [
        'is_active',
        'created_at',
        'updated_at',
    ];
}
