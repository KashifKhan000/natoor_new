<?php

declare(strict_types=1);

use App\Http\Controllers\API\V1\Buildings\BuildingsController;
use App\Http\Controllers\API\V1\Directory\DirectoryController;
use App\Http\Controllers\API\V1\Floors\FloorsController;
use App\Http\Controllers\API\V1\ImageUploadController;
use App\Http\Controllers\API\V1\Rooms\RoomsController;
use App\Http\Controllers\API\V1\Services\ServicesController;
use App\Http\Controllers\API\V1\IssueRequest\IssueRequestController;
use Illuminate\Support\Facades\Route;
// use Stancl\Tenancy\Middleware\InitializeTenancyBySubdomain;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomainOrSubdomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'jwt',
    InitializeTenancyByDomainOrSubdomain::class,
    // PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::prefix('api/users')->group(function () {

        //CompanyServices


        //Issue Request
        Route::post('/save-issue', [IssueRequestController::class, 'saveIssueRequest']);

        Route::get('/getallissue', [IssueRequestController::class, 'getallissue']);
        // buildings
        Route::get('/get-building', [BuildingsController::class, 'getBuilding']);
        Route::get('/get-building-by-id/{id}', [BuildingsController::class, 'getBuilding']);
        Route::post('/save-building', [BuildingsController::class, 'saveBuilding']);
        Route::post('/delete-building/{id}', [BuildingsController::class, 'deleteBuilding']);
        Route::post('/update-building/{id}', [BuildingsController::class, 'updateBuilding']);
        // floors
        Route::get('/get-floor', [FloorsController::class, 'viewFloor']);
        Route::get('/get-floor-by-id/{id}', [FloorsController::class, 'viewFloor']);
        Route::post('/save-floor', [FloorsController::class, 'saveFloor']);
        Route::post('/delete-floor/{id}', [FloorsController::class, 'deleteFloor']);
        Route::post('/update-floor/{id}', [FloorsController::class, 'updateFloor']);
        // Rooms
        Route::get('/get-room', [RoomsController::class, 'viewRoom']);
        Route::get('/get-room-by-id/{id}', [RoomsController::class, 'viewRoom']);
        Route::post('/save-room', [RoomsController::class, 'saveRoom']);
        Route::post('/delete-room/{id}', [RoomsController::class, 'deleteRoom']);
        Route::post('/update-room/{id}', [RoomsController::class, 'updateRoom']);
        // service
        Route::get('/get-service', [ServicesController::class, 'getService']);
        Route::get('/get-service-by-id/{id}', [ServicesController::class, 'getService']);
        Route::post('/save-service', [ServicesController::class, 'saveService']);
        Route::post('/delete-service/{id}', [ServicesController::class, 'deleteService']);
        Route::post('/update-service/{id}', [ServicesController::class, 'updateService']);
        // Directory
        Route::get('/get-directory', [DirectoryController::class, 'getDirectory']);
        Route::get('/get-directory-by-id/{id}', [DirectoryController::class, 'getDirectory']);
        Route::post('/save-directory', [DirectoryController::class, 'saveDirectory']);
        Route::post('/delete-directory/{id}', [DirectoryController::class, 'deleteDirectory']);
        Route::post('/update-directory/{id}', [DirectoryController::class, 'updateDirectory']);
        //image upload
        Route::post('/upload-image', [ImageUploadController::class, 'uploadImage']);

        // User Room

        Route::post('/save-user-room', [RoomsController::class, 'saveuserRoom']);
        Route::get('getuserrooms', [RoomsController::class, 'getuserrooms']);
    });
});
