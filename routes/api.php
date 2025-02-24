<?php

use App\Http\Controllers\v1\CarouselController as Carousel;
use App\Http\Controllers\v1\ProductController as Product;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/carousel')->middleware([])->group(static function () {
    Route::get('specificCarousel', [Carousel::class, 'specificCarousel'])
        ->middleware(['decryptData', 'encryptResponse']);
});

Route::prefix('v1/product')->middleware([])->group(static function () {
    Route::get('autoCompletedProduct', [Product::class, 'autoCompletedProduct'])
        ->middleware(['encryptResponse', 'decryptData']);

    Route::get('filtersProduct', [Product::class, 'filtersProduct'])
        ->middleware(['encryptResponse']);

    Route::get('listProduct', [Product::class, 'listProduct'])
        ->middleware(['encryptResponse', 'decryptData']);
});

Route::middleware('api')->group(static function () {
    // Route en modulo
    require base_path('app/Modules/Sitemap/Routes/SitemapRoute.php');
});
