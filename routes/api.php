<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('login', [AuthController::class, 'login'])->name('login');


Route::group(['middleware' => ['jwt.auth']], function () {
    Route::get('role', [RoleController::class, 'getAll']);
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
    Route::get('user/{id}', [UserController::class, 'get']);
    Route::put('user/{id}', [UserController::class, 'update']);
    Route::delete('user/{id}', [UserController::class, 'delete']);

    Route::post('major', [MajorController::class, 'store']);
    Route::get('major', [MajorController::class, 'getAll']);
    Route::get('major/{id}', [MajorController::class, 'get']);
    Route::put('major/{id}', [MajorController::class, 'update']);
    Route::delete('major/{id}', [MajorController::class, 'delete']);

    Route::post('subject', [SubjectController::class, 'store']);
    Route::get('subject', [SubjectController::class, 'getAll']);
    Route::get('subject/{id}', [SubjectController::class, 'get']);
    Route::put('subject/{id}', [SubjectController::class, 'update']);
    Route::delete('subject/{id}', [SubjectController::class, 'delete']);
});
