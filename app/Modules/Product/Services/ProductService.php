<?php

namespace App\Modules\Product\Services;

use App\Modules\Product\Repositories\ProductRepository;
use Illuminate\Database\Eloquent\Collection;

class ProductService
{
    public function __construct(private ProductRepository $productRepository) {}

    public function getAllProduct(): Collection|array
    {
        return $this->productRepository->getAllProduct();
    }
}
