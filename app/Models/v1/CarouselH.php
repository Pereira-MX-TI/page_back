<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarouselH extends Model
{
    use HasFactory;
    protected $table = "carousels";

    protected $hidden = [
        'created_at', 'updated_at','is_active'
    ];
}
