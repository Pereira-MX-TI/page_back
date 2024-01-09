<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TokenController as Token;
use App\Http\Controllers\v1\ProductController as Product;
use App\Http\Controllers\v1\CarouselController as Carousel;
use App\Http\Controllers\v1\QuotatioWebController as QuotationWeb;



//Route::group(['middleware' => ['jwt.auth'], 'prefix' => 'v1'], function () {
//    Route::get('/getCarousel',           [Carousel::class,'getCarousel']);
//    Route::get('/getListCarousel',       [Carousel::class,'getListCarousel']);
//    Route::get('/getPublicity',          [Carousel::class,'getPublicity']);
//
//    Route::get('/autoCompletedProduct',  [Product::class,'autoCompletedProduct']);
//    Route::get('/getListProduct',        [Product::class,'getListProduct']);
//    Route::get('/searchListProduct',     [Product::class,'searchListProduct']);
//    Route::get('/getProduct',            [Product::class,'getProduct']);
//
//    Route::post('/registerQuotationWeb',   [QuotationWeb::class,'registerQuotationWeb']);
//    Route::post('/registerInfoServiceWeb', [QuotationWeb::class,'registerInfoServiceWeb']);
//
//});

//Route::group(['middleware' => ['api']], function($router) {
//    Route::get('login',[Token::class,'login']);
//    Route::post('logout',[Token::class,'logout']);
//});

// TODO: Cambio al front que agregue common
Route::prefix('v1/common')->middleware(['jwt.auth'])->group(static function () {

    Route::get('getCarousel', [Carousel::class,'getCarousel'])
        ->middleware(['decryptData','encryptResponse']);

    Route::get('getListCarousel',[Carousel::class,'getListCarousel'])
        ->middleware(['decryptData','encryptResponse']);

    Route::get('getPublicity',[Carousel::class,'getPublicity'])
        ->middleware(['decryptData','encryptResponse']);

    Route::get('autoCompletedProduct',[Product::class,'autoCompletedProduct'])
        ->middleware(['decryptData','encryptResponse']);

    Route::get('getListProduct',[Product::class,'getListProduct'])
        ->middleware(['decryptData','encryptResponse']);

    Route::get('searchListProduct',[Product::class,'searchListProduct'])
        ->middleware(['decryptData','encryptResponse']);

    Route::get('getProduct',[Product::class,'getProduct'])
        ->middleware(['decryptData','encryptResponse']);

    Route::post('registerQuotationWeb',   [QuotationWeb::class,'registerQuotationWeb'])
        ->middleware(['decryptData','encryptResponse']);

    Route::post('registerInfoServiceWeb', [QuotationWeb::class,'registerInfoServiceWeb'])
        ->middleware(['decryptData','encryptResponse']);
});


//TODO: Cambios al front que agregue auth
Route::prefix('v1/auth')->middleware([])->group(static function () {

    // TODO: Cambio de get a post
    Route::post('login',[Token::class,'login'])
        ->middleware(['decryptData','encryptResponse']);

    Route::post('logout',[Token::class,'logout']);
});
