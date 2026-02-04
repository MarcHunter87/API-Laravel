<?php

use App\Http\Controllers\AnimalesController;
use App\Http\Controllers\DuenosController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Api Resources automatiza las rutas a partir de los nombres epecíficos puestos en los controllers
Route::apiResources([
    'animales' => AnimalesController::class,
    'duenos' => DuenosController::class,
]);
