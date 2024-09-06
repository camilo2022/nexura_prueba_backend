<?php

use App\Http\Controllers\Api\V1\AreaController;
use App\Http\Controllers\Api\V1\EmployeeController;
use App\Http\Controllers\Api\V1\RoleController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('/Areas')->group(function () {
    Route::controller(AreaController::class)->group(function () {
        Route::get('/All', 'all')->name('Dashboard.Areas.All');
        Route::post('/Index', 'index')->name('Dashboard.Areas.Index');
        Route::post('/Store', 'store')->name('Dashboard.Areas.Store');
        Route::post('/Edit/{id}', 'edit')->name('Dashboard.Areas.Edit');
        Route::put('/Update/{id}', 'update')->name('Dashboard.Areas.Update');
    });
});
Route::prefix('/Roles')->group(function () {
    Route::controller(RoleController::class)->group(function () {
        Route::get('/All', 'all')->name('Dashboard.Roles.All');
        Route::post('/Index', 'index')->name('Dashboard.Roles.Index');
        Route::post('/Store', 'store')->name('Dashboard.Roles.Store');
        Route::post('/Edit/{id}', 'edit')->name('Dashboard.Roles.Edit');
        Route::put('/Update/{id}', 'update')->name('Dashboard.Roles.Update');
    });
});
Route::prefix('/Employees')->group(function () {
    Route::controller(EmployeeController::class)->group(function () {
        Route::post('/Index', 'index')->name('Dashboard.Employees.Index');
        Route::post('/Store', 'store')->name('Dashboard.Employees.Store');
        Route::post('/Edit/{id}', 'edit')->name('Dashboard.Employees.Edit');
        Route::put('/Update/{id}', 'update')->name('Dashboard.Employees.Update');
        Route::delete('/Delete', 'delete')->name('Dashboard.Employees.Delete');
        Route::put('/Restore', 'restore')->name('Dashboard.Employees.Restore');
    });
});
