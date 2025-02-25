<?php

use App\Modules\Sitemap\Controllers\SitemapController as sitemapController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/sitemap')->group(static function () {

    Route::Get('', [SitemapController::class, 'createSitemap']);
});
