<?php

use App\Http\Controllers\v1\SiteController as Site;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/site')->middleware(['cors'])->group(static function () {

   Route::get('infoGeneralSite', [Site::class, 'infoGeneralSite'])
        ->middleware(['decryptData', 'encryptResponse']);

   Route::get('weatherSite', [Site::class, 'weatherSite'])
        ->middleware(['decryptData', 'encryptResponse']);
});
