<?php

use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Sanctum\LoginController;
use App\Http\Controllers\Sanctum\LogoutController;
use Spatie\Permission\Contracts\Role;

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

// Authentication...
Route::post('login', LoginController::class)->name('login');
Route::post('logout', LogoutController::class)->middleware('auth:sanctum')->name('logout');

Route::apiResource('orders',OrderController::class)->middleware(['auth:sanctum','role:admin']);

Route::get('orders/{order}/approve', [OrderController::class,'approve'])
->name('orders.approve')
->middleware(['auth:sanctum','role:admin']);

Route::get('orders/{order}/decline', [OrderController::class,'decline'])
->name('orders.decline')
->middleware(['auth:sanctum','role:admin']);;
