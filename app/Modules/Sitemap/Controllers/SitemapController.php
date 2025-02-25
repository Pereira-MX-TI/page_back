<?php

namespace App\Modules\Sitemap\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Product\Services\ProductService;
use Carbon\Carbon;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    public function __construct(private ProductService $productService) {}

    public function createSitemap()
    {
        $pathOfProductFrontend = 'Productos/Vista?data=';
        $sitemap = Sitemap::create();

        foreach ($this->productService->getAllProduct() as $product) {
            $sitemap->add(Url::create($pathOfProductFrontend.base64_encode($product->id))
                ->setLastModificationDate($product->updated_at ?? Carbon::now())
                ->setChangeFrequency('daily')
                ->setPriority(0.7)
            );
        }

        return response($sitemap->render())
            ->header('Content-Type', 'application/xml');

    }
}
