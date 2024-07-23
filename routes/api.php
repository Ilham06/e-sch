<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('login', [AuthController::class, 'login'])->name('login');


Route::group(['middleware' => ['jwt.auth']], function () {
    Route::get('role', [RoleController::class, 'getAll'])->middleware(['permission:VIEW_ROLE']);
    Route::post('role', [RoleController::class, 'store']);
    Route::put('role/{id}', [RoleController::class, 'update']);
    Route::delete('role/{id}', [RoleController::class, 'delete']);
    Route::get('role/get/pagination', [RoleController::class, 'getPagination']);
    Route::get('role/{id}', [RoleController::class, 'get']);
    Route::get('permissions', [RoleController::class, 'getAllPermissions']);

    Route::post('user', [UserController::class, 'store']);
    Route::get('students', [UserController::class, 'getAllStudent']);
    Route::get('teachers', [UserController::class, 'getAllTeacher']);
    Route::get('administrators', [UserController::class, 'getAllAdmin']);
});
