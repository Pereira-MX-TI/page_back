<?php

use App\Http\Controllers\v1\TokensController as Token;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/auth')->middleware(['cors'])->group(static function () {

    Route::post('login', [Token::class, 'login'])
        ->middleware(['decryptData', 'encryptResponse']);
});
