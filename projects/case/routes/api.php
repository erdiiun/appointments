<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login'])->name('login');

Route::group(['middleware' => 'auth:api'], function () {

    Route::group(['prefix' => 'auth'],function (){
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
    });

    Route::group(['prefix' => 'v1'], function () {

        // Appointment
        Route::post('/appointments', [\App\Http\Controllers\v1\AppointmentController::class, 'appointment']);

        // Company
        Route::get('/companies', [\App\Http\Controllers\v1\CompanyController::class, 'list']);
        Route::post('/companies/services', [\App\Http\Controllers\v1\CompanyController::class, 'service']);
        Route::post('/companies/work-day', [\App\Http\Controllers\v1\CompanyController::class, 'workDay']);

        // User
        Route::get('/users', [\App\Http\Controllers\v1\UserController::class, 'userInfo']);
        Route::post('/users', [\App\Http\Controllers\v1\UserController::class, 'userUpdate']);

    });


});

