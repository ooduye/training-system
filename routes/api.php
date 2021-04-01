<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\PassportAuthController;

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

Route::prefix('v1/')->group(function () {
    Route::get('', function () {
        return 'Hello World!';
    });

    Route::post('user', [PassportAuthController::class, 'register'])->middleware(['auth.board']);
    Route::post('auth/login', [PassportAuthController::class, 'login']);
    Route::get('auth/logout', [PassportAuthController::class, 'logout']);

    Route::middleware(['auth.expert'])->group(function () {
        Route::resource('activity', ActivityController::class);
    });

    Route::middleware(['auth.loggedin'])->group(function () {
        Route::get('activities/{skill_id}', [ActivityController::class, 'show']);
        Route::resource('skill', SkillController::class);
    });
});
