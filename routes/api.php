<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TokenController as Token;
use App\Http\Controllers\v1\ProductController as Product;
use App\Http\Controllers\v1\CarouselController as Carousel;
use App\Http\Controllers\v1\QuotatioWebController as QuotationWeb;

Route::group(['middleware' => ['jwt.auth'], 'prefix' => 'v1'], function () {
    Route::get('/getCarousel',           [Carousel::class,'getCarousel']);
    Route::get('/getListCarousel',       [Carousel::class,'getListCarousel']);
    Route::get('/getPublicity',          [Carousel::class,'getPublicity']);

    Route::get('/autoCompletedProduct',  [Product::class,'autoCompletedProduct']);
    Route::get('/getListProduct',        [Product::class,'getListProduct']);
    Route::get('/searchListProduct',     [Product::class,'searchListProduct']);
    Route::get('/getProduct',            [Product::class,'getProduct']);

    Route::post('/registerQuotationWeb',   [QuotationWeb::class,'registerQuotationWeb']);
    Route::post('/registerInfoServiceWeb', [QuotationWeb::class,'registerInfoServiceWeb']);

});


Route::group(['middleware' => ['api']], function($router) {
    Route::get('login',[Token::class,'login']);
    Route::post('logout',[Token::class,'logout']);
});