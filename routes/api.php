<?php

use Illuminate\Support\Facades\Route;

Route::get('/ping', fn () => ['pong' => true]);
Route::get('/timestamp', fn () => ['timestamp' => time()]);

Route::prefix('v1')->group(function () {
    require __DIR__.'/../app/Modules/Events/Presentation/Http/routes.php';
});
