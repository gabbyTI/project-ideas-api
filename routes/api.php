<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
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

Route::group(['prefix' => 'v1'], function () {
    // PUBLIC ROUTES

    // Route group for authenticated users only
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('logout', [AuthController::class, 'logout']);

        //projects
        Route::get('projects', [ProjectController::class, 'getProjects']);
        Route::get('project/{project}', [ProjectController::class, 'getProject']);
        Route::post('projects', [ProjectController::class, 'createProject']);
        Route::put('projects/{project}', [ProjectController::class, 'updateProject']);
        Route::delete('projects/{project}', [ProjectController::class, 'deleteProject']);
    });

    // Route group for guest users only
    Route::group(['middleware' => ['guest:sanctum']], function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);

    });
});
