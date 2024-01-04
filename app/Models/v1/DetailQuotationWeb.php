<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailQuotationWeb extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = "quotation_web_details";

    protected $fillable = [
        'quotation_web_id',
        'concept_id', 
        'user_id',

        'description_id',
        'tipo_concepto', 
        'cantidad',

        'precio_unitario',
        'estatus_crud'
    ];

    protected $hidden = [
        'estatus_crud',
        'created_at', 
        'updated_at'
    ];
}
