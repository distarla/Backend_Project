<?php


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

Route::prefix('v1')->group(function() {
    Route::post('login','App\Http\Controllers\AuthController@login');
});

Route::prefix('v1')->middleware('jwt.auth')->group(function() {
    Route::post('logout','App\Http\Controllers\AuthController@logout'); Route::post('refresh','App\Http\Controllers\AuthController@refresh');
    Route::post('me','App\Http\Controllers\AuthController@me');

    Route::apiResource('roles','App\Http\Controllers\RoleController');
    Route::apiResource('users','App\Http\Controllers\UserController');

    Route::apiResource('clients','App\Http\Controllers\ClientController');
    Route::apiResource('events','App\Http\Controllers\EventController');
    Route::get('events/{id}/price', 'App\Http\Controllers\EventController@show_price');
    Route::apiResource('menus','App\Http\Controllers\MenuController');
    Route::apiResource('ranges','App\Http\Controllers\RangeController');
    Route::apiResource('prices','App\Http\Controllers\PriceController');
    Route::get('prices/{id}/events', 'App\Http\Controllers\PriceController@show_events');
    Route::post('schedules', 'App\Http\Controllers\ScheduleController@store');
    Route::delete('schedules/{event_id}/{client_id}', 'App\Http\Controllers\ScheduleController@destroy');
});
