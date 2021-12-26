<?php

use App\Http\Controllers\DistrictController;
use App\Http\Controllers\HamletController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\WardController;
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
    Route::get('home', [HomeController::class, 'getStatisticalData']);

    Route::get('/district/list', [DistrictController::class, 'getListDistricts'])
        ->middleware('can:user-permission-province-district');
    Route::get('/ward/list', [WardController::class, 'getListWards'])
        ->middleware('can:user-permission-province-district-ward');
    Route::get('/hamlet/list', [HamletController::class, 'getListHamlets'])
        ->middleware('can:user-permission-province-district-ward-hamlet');

    Route::prefix('province')->middleware('can:user-permission-province')->group(function () {
        Route::get('list', [ProvinceController::class, 'getListProvinces']);
        Route::post('insert', [ProvinceController::class, 'createProvince']);
        Route::post('update', [ProvinceController::class, 'updateProvince']);
        Route::delete('/delete/{id}', [ProvinceController::class, 'deleteProvince']);
    });

    Route::prefix('district')->middleware('can:user-permission-district')->group(function () {
        Route::post('insert', [DistrictController::class, 'createDistrict']);
        Route::post('update', [DistrictController::class, 'updateDistrict']);
        Route::delete('/delete/{id}', [DistrictController::class, 'deleteDistrict']);
    });

    Route::prefix('ward')->middleware('can:user-permission-ward')->group(function () {
        Route::post('insert', [WardController::class, 'createWard']);
        Route::post('update', [WardController::class, 'updateWard']);
        Route::delete('/delete/{id}', [WardController::class, 'deleteWard']);
    });

    Route::prefix('hamlet')->middleware('can:user-permission-hamlet')->group(function () {
        Route::post('insert', [HamletController::class, 'createHamlet']);
        Route::post('update', [HamletController::class, 'updateHamlet']);
        Route::delete('/delete/{id}', [HamletController::class, 'deleteHamlet']);
    });

    Route::prefix('user')->middleware('can:permission-manage-user')->group(function () {
        Route::get('list', [UserController::class, 'getListUsers']);
        Route::post('update', [UserController::class, 'updateUser']);
        Route::get('get-info-address', [UserController::class, 'getInfoAddress']);
    });

    Route::prefix('citizen')->group(function () {
        Route::get('list', [CitizenController::class, 'getListCitizens']);
        Route::post('insert', [CitizenController::class, 'createCitizen'])->middleware('can:create-citizen');
    });

    Route::prefix('occupation')->group(function () {
        Route::get('list', [\App\Http\Controllers\OccupationController::class, 'getListOccupations']);
        Route::post('insert', [CitizenController::class, 'createCitizen'])->middleware('can:create-citizen');
    });

});


