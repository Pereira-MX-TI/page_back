<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarouselD extends Model
{
    use HasFactory;

    protected $table = 'carousel_details';

    protected $hidden = [
        'created_at', 'updated_at', 'is_active',
    ];
}
