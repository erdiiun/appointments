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
        Route::get('/user-profile', [AuthController::class, 'userProfile']);
    });

    Route::group(['prefix' => 'v1'], function () {

        // Appointment
        Route::post('/appointment', [\App\Http\Controllers\v1\AppointmentController::class, 'appointment']);

        // Company
        Route::get('/companies', [\App\Http\Controllers\v1\CompanyController::class, 'list']);
        Route::post('/company/services', [\App\Http\Controllers\v1\CompanyController::class, 'service']);
    });


});

