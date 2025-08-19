<?php

use Illuminate\Support\Facades\Route;

Route::middleware('api')->group(static function () {
    require base_path('routes/api/Auth/AuthRoute.php');
    require base_path('routes/api/User/UserRoute.php');
    require base_path('routes/api/Image/ImageRoute.php');
    require base_path('routes/api/Site/SiteRoute.php');
});
