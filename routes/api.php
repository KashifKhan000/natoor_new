<?php

use App\Http\Controllers\API\V1\Aminities\AminitiesController;
use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\Cities\CitiesController;
use App\Http\Controllers\API\V1\Countries\CountriesController;
use App\Http\Controllers\API\V1\Packages\PackagesController;
use App\Http\Controllers\API\V1\Services\ServicesController;

use App\Http\Controllers\API\V1\Buildings\BuildingsController;
use App\Http\Controllers\API\V1\Directory\DirectoryController;
use App\Http\Controllers\API\V1\Floors\FloorsController;
use App\Http\Controllers\API\V1\ImageUploadController;
use App\Http\Controllers\API\V1\VoiceUploadController;
use App\Http\Controllers\API\V1\Rooms\RoomsController;
use App\Http\Controllers\API\V1\IssueRequest\IssueRequestController;
use Illuminate\Support\Facades\Route;

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

Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => 'jwt'], function () {
    Route::group(['prefix' => 'admin'], function () {
        // Aminities ---------------
        // post routes
        Route::post('/save-aminities', [AminitiesController::class, 'saveAminities']);
        Route::post('/delete-aminities/{id}', [AminitiesController::class, 'deleteAminities']);
        Route::post('/update-aminities/{id}', [AminitiesController::class, 'updateAminities']);
        // get routes
        Route::get('/get-aminities', [AminitiesController::class, 'getAminities']);
        Route::get('/get-aminity-by-id/{id}', [AminitiesController::class, 'getAminities']);
        // End Aminities -----------------

        // Countries --------------------------------
        // post routes
        Route::post('/save-country', [CountriesController::class, 'saveCountries']);
        Route::post('/delete-country/{id}', [CountriesController::class, 'deleteCountry']);
        Route::post('/update-country/{id}', [CountriesController::class, 'updateCountry']);
        // get routes
        Route::get('/get-countries', [CountriesController::class, 'getCountries']);
        Route::get('/get-country-by-id/{id}', [CountriesController::class, 'getCountries']);
        // End Countries --------------------------------

        // Cities --------------------------------
        // post routes
        Route::post('/save-city', [CitiesController::class, 'saveCity']);
        Route::post('/delete-city/{id}', [CitiesController::class, 'deleteCity']);
        Route::post('/update-city/{id}', [CitiesController::class, 'updateCity']);
        // get routes
        Route::get('/get-cities', [CitiesController::class, 'getCities']);
        Route::get('/get-city-by-id/{id}', [CitiesController::class, 'getCities']);
        // End Cities --------------------------------

        // Packages
        // POST Routes
        Route::post('/save-package', [PackagesController::class, 'savePackage']);
        Route::post('/delete-package/{id}', [PackagesController::class, 'deletePackage']);
        Route::post('/update-package/{id}', [PackagesController::class, 'updatePackage']);
        // get routes
        Route::get('/get-package', [PackagesController::class, 'viewPackage']);
        Route::get('/get-package-by-id/{id}', [PackagesController::class, 'viewPackage']);
        // Packages End


    });

    Route::post('/add-users', [AuthController::class, 'addAdmin']);
    Route::post('/update-profile', [AuthController::class, 'updateProfile']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/updatepassword', [AuthController::class, 'updatepassword']);
});



Route::post('/forget-password', [AuthController::class, 'checkemail']);
Route::group(['middleware' => 'jwt'], function () {
    Route::group(['prefix' => 'users'], function () {

Route::get('getservice',[ServicesController::class, 'companyservice']);
        //Issue Request
        Route::post('/save-issue', [IssueRequestController::class, 'saveIssueRequest']);

        Route::get('getuserrooms', [RoomsController::class, 'getuserrooms']);
        Route::get('/getallbuildings', [BuildingsController::class, 'getallbuildings']);

        Route::get('/getallissue', [IssueRequestController::class, 'getallissue']);

        Route::get('/getuserbuilding', [BuildingsController::class, 'getuserbuilding']);
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
        Route::post('/upload-voice', [VoiceUploadController::class, 'uploadVoice']);
    });
});
