<?php

use App\Http\Controllers\v1\UserController as User;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/users')->middleware(['jwt.auth', 'validationToken', 'cors'])->group(static function () {
  
});

Route::prefix('v1/users')->middleware(['cors'])->group(static function () {
    Route::post('changePassword', [User::class, 'changePassword'])
        ->middleware(['decryptData', 'encryptResponse']);

    Route::post('recoverPassword', [User::class, 'recoverPassword'])
        ->middleware(['decryptData', 'encryptResponse']);

    Route::put('updateEmail', [User::class, 'updateEmail'])
        ->middleware(['decryptData', 'encryptResponse']);
});
