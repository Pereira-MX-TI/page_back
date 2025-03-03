<?php

namespace App\Modules\Product\Repositories;

use App\Models\v1\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository
{
    public function __construct(private Product $productModel) {}

    public function getAllProduct(): Collection|array
    {
        //        return $this->productModel->all();
        return $this->productModel::where([['estatus_crud', 'C'],['is_web',1]])->get();
    }
}
