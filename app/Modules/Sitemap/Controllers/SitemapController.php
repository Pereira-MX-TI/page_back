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
        $pathOfProductFrontend = 'https://medidordeagua.mx/Productos/Vista/';
        $sitemap = Sitemap::create();

        foreach ($this->productService->getAllProduct() as $product) {

            $encodedId = $this->convertSearch($product->nombre).'~'.$this->base64UrlEncode($product->id);

            $sitemap->add(Url::create($pathOfProductFrontend . $encodedId)
                ->setLastModificationDate($product->updated_at ?? Carbon::now())
                ->setChangeFrequency('daily')
                ->setPriority(0.7)
            );
        }

        return response($sitemap->render())
            ->header('Content-Type', 'application/xml');

    }

    private function base64UrlEncode($input)
    {
        return rtrim(strtr(base64_encode($input), '+/', '-_'), '=');
    }

    function convertSearch(string $res): string
    {
        return str_replace(
            ['(', ')'],
            ['%28', '%29'],
            rawurlencode(trim($res))
        );
    }
}
