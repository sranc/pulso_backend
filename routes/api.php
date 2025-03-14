<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Rutas públicas para autenticación:
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rutas protegidas con Sanctum:
Route::middleware('auth:sanctum')->group(function () {
 Route::get('/user', function (Request $request) {
  return $request->user();
 });
 Route::post('/logout', [AuthController::class, 'logout']);
});
