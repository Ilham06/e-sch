<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\RoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('login', [AuthController::class, 'login'])->name('login');


Route::group(['middleware' => ['jwt.auth']], function () {
    Route::get('role', [RoleController::class, 'getAll']);
    Route::post('role', [RoleController::class, 'store']);
    Route::get('role/{id}', [RoleController::class, 'get']);
});
