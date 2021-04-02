<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
})->name('home');

//AUTH

Route::any('login', [\App\Http\Controllers\TrainingController::class, 'login'])->name('login')->middleware('auth.hastoken');

Route::get('logout', [\App\Http\Controllers\TrainingController::class, 'logout'])->name('logout');

Route::get('skills', [\App\Http\Controllers\TrainingController::class, 'getAllSkills'])->name('skills');

Route::get('skill/{skill_id}', [\App\Http\Controllers\TrainingController::class, 'getSkillActivities'])->name('skill');


