<?php

use App\Http\Controllers\EnvController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::get('/envs', [EnvController::class, 'index']);
Route::get('/envs/{env}/sites/', [SiteController::class, 'index']);
Route::patch('/envs/{env}/sites/{site}', [SiteController::class, 'patchPhpVersion']);
