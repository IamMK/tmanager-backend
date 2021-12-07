<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TasksController;
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




Route::middleware('auth:api')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/tasks', 'App\Http\Controllers\TasksController@index');

    Route::post('/logout', 'App\Http\Controllers\AuthController@logout');

    Route::post('/tasks', 'App\Http\Controllers\TasksController@store');

    Route::patch('/tasks/{task}', 'App\Http\Controllers\TasksController@update');

    Route::delete('/tasks/{task}', 'App\Http\Controllers\TasksController@destroy');

    Route::patch('/tasksCheckAll', 'App\Http\Controllers\TasksController@updateAll');

    Route::delete('/tasksDeleteCompleted', 'App\Http\Controllers\TasksController@destroyCompleted');
});

Route::post('/login', 'App\Http\Controllers\AuthController@login');
Route::post('/register', 'App\Http\Controllers\AuthController@register');
