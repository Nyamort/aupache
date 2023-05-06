<?php

use App\Http\Controllers\EnvController;
use App\Http\Controllers\PHPController;
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
Route::get('/php', [PHPController::class, 'index']);
Route::get('/php/{version}/extensions', [PHPController::class, 'extensions']);
Route::post('/php/{version}/install', [PHPController::class, 'installVersion']);
Route::post('/php/{version}/uninstall', [PHPController::class, 'uninstallVersion']);
Route::post('/php/{version}/extensions/{extension}/install', [PHPController::class, 'installExtension']);
