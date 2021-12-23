<?php

use App\Http\Controllers\DistrictController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\CitizenController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::group(['middleware' => 'auth.api'], function () {
        Route::get('get-user', [AuthController::class, 'getUser']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});

Route::group(['middleware' => 'auth.api'], function () {
    Route::prefix('province')->middleware('can:user-permission-province')->group(function () {
        Route::get('list', [ProvinceController::class, 'getListProvinces']);
        Route::post('insert', [ProvinceController::class, 'createProvince']);
        Route::post('update', [ProvinceController::class, 'updateProvince']);
        Route::delete('/delete/{id}', [ProvinceController::class, 'deleteProvince']);
    });

    Route::prefix('district')->middleware('can:user-permission-district')->group(function () {
        Route::post('insert', [DistrictController::class, 'createDistrict']);
        Route::get('list/{id}', [DistrictController::class, 'getListDistricts']);
    });

    Route::prefix('user')->middleware('can:permission-manage-user')->group(function () {
        Route::get('list', [UserController::class, 'getListUsers']);
        Route::post('insert', [UserController::class, 'createUser']);
    });

    Route::prefix('citizen')->group(function () {
        Route::get('list', [CitizenController::class, 'getListCitizens']);
        Route::post('insert', [CitizenController::class, 'createCitizen'])->middleware('can:create-citizen');
    });
});


