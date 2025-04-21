<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\CampusController;
use App\Http\Controllers\StaffAuthController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\CourierController;
use App\Http\Controllers\TruckerController;

use App\Http\Middleware\AuthClient;


Route::get('/', function () {
    return view('welcome');
});

// 客户端平台 API
Route::prefix('api')->withoutMiddleware([ValidateCsrfToken::class])->group(function () {
    Route::prefix('v1/auth')->group(function () {
        Route::post('/signup', [AuthController::class, 'signup']);
        Route::post('/signin', [AuthController::class, 'signin']);
        Route::post('/signout', [AuthController::class, 'signout'])->middleware(AuthClient::class);
    });

    Route::prefix('v1')->middleware(AuthClient::class)->group(function () {
        Route::get('/track/{tracking_number}', [PackageController::class, 'track']);
        Route::get('/campus', [CampusController::class, 'getAllCampuses']);
        Route::post('/package', [PackageController::class, 'sendPackage']);
        Route::get('/package', [PackageController::class, 'getMyPackages']);
        Route::patch('/package/{package_id}', [PackageController::class, 'returnPackage']);
    });


    // 员工平台 API
    Route::prefix('v1/staff')->group(function () {
        Route::post('/signin', [StaffAuthController::class, 'signin']);
        Route::post('/signout', [StaffAuthController::class, 'signout'])->middleware(AuthClient::class);
        Route::get('/information', [StaffController::class, 'getInformation'])->middleware(AuthClient::class);
    });

    // 快递员功能
    Route::prefix('v1/courier')->middleware([AuthClient::class])->group(function () {
        Route::post('/online-toggle', [CourierController::class, 'onlineToggle']);
        Route::get('/package/pickup/pending', [CourierController::class, 'getPendingPickupPackages']);
        Route::post('/package/pickup/carry', [CourierController::class, 'carryPendingPickupPackages']);
        Route::get('/package/delivery/pending', [CourierController::class, 'getPendingDeliveryPackages']);
        Route::post('/package/delivery/carry', [CourierController::class, 'carryPendingDeliveryPackages']);
        Route::get('/package/pickedup/carried', [CourierController::class, 'getPickedUpPackages']);
        Route::post('/package/pickedup/pack', [CourierController::class, 'packPackages']);
        Route::get('/package/delivering/carried', [CourierController::class, 'getDeliveringPackages']);
        Route::patch('/package/delivered/{package_id}', [CourierController::class, 'deliveredPackage']);
        Route::get('/package/delivered', [CourierController::class, 'getDeliveredPackages']);
    });

    // 卡车司机功能
    Route::prefix('v1/trucker')->middleware([AuthClient::class])->group(function () {
        Route::get('/container', [TruckerController::class, 'getMyContainers']);
        Route::get('/container/campus', [TruckerController::class, 'getCurrentCampusContainers']);
        Route::patch('/container/load/{container_id}', [TruckerController::class, 'loadContainer']);
        Route::patch('/container/unload/{container_id}', [TruckerController::class, 'unloadContainer']);
        Route::get('/container/{container_id}/packages', [TruckerController::class, 'getPackagesInContainer']);
        Route::get('/route', [TruckerController::class, 'getMyRoute']);
        Route::post('/route/next', [TruckerController::class, 'nextCampus']);
    });
});
