<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\API\V1\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/admin/dashboard', [AdminController::class, 'index']);

Route::get('/', function () {
    return view('welcome');
});

// php artisan route:list

Route::get('route-list', function () {
    Artisan::call('route:cache');
    dd('route Cache Cleared');
});
Route::get('key-generate', function () {
    Artisan::call('key:generate');
    Artisan::call('optimize');
    Artisan::call('config:cache');
    dd('gey generated');
});
Route::get('cache-clear', function () {
    Artisan::call('config:cache');
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('optimize');
    Artisan::call('config:cache');
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    dd('Cache Cleared');
});

Route::get('/password/reset/{token}', [AuthController::class, 'reset_form']);
Route::get('/emailverify/{email}', [AuthController::class, 'verify']);
Route::post('/updatePass', [AuthController::class, 'resetPassword']);
Route::get('/success', [AuthController::class, 'success']);