<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';

    protected $hidden = [
        'brand_id', 'material_id', 'messuare_id',
        'category_id', 'provider_id',
        'estatus', 'tipo', 'estatus_crud', /* ,'is_web' */
        'user_id', 'created_at', 'updated_at',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function messuare()
    {
        return $this->belongsTo(Messuare::class, 'messuare_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }
}
