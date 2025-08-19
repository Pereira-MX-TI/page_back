<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/public/docs/postman', 'PostmanController@index')->name('scribe_public.postman');
Route::get('/public/docs/openapi', 'OpenAPIController@index')->name('scribe_public.openapi');

// Ruta para la documentación pública
Route::get('/public/docs', function () {
    return view('scribe_public.index');
});

Route::get('/private/docs/postman', 'PostmanController@index')->name('scribe_private.postman')
    ->middleware(['accessDocs']);

Route::get('/private/docs/openapi', 'OpenAPIController@index')->name('scribe_private.openapi')
    ->middleware(['accessDocs']);

// Ruta para la documentación privada
Route::get('/private/docs', function () {
    return view('scribe_private.index');
})->middleware(['accessDocs']);

Route::get('/', function () {
    return view('welcome');
});
