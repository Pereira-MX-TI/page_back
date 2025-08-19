<?php

use App\Http\Controllers\v1\ImagesController as Image;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/image')->middleware(['jwt.auth', 'validationToken', 'cors'])->group(static function () {

    Route::post('registerImage', [Image::class, 'registerImage'])
        ->middleware(['decryptData', 'decryptHeaders', 'encryptResponse']);

    Route::delete('deleteImage', [Image::class, 'deleteImage'])
        ->middleware(['decryptData', 'decryptHeaders', 'encryptResponse']);
});
