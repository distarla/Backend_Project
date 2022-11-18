<?php

use App\Http\Controllers\ScheduleController;
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

Route::apiResource('clients','App\Http\Controllers\ClientController');
Route::apiResource('events','App\Http\Controllers\EventController');
Route::apiResource('menus','App\Http\Controllers\MenuController');
Route::apiResource('ranges','App\Http\Controllers\RangeController');
Route::apiResource('prices','App\Http\Controllers\PriceController');
Route::post('schedules', 'App\Http\Controllers\ScheduleController@store');
Route::delete('schedules/{event_id}/{client_id}', 'App\Http\Controllers\ScheduleController@destroy');
