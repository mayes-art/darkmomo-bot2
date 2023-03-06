<?php

use App\Http\Controllers\LobbyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('info', function () {
    phpinfo();
});

Route::get('/', [LobbyController::class, 'lineGet']);
Route::post('/', [LobbyController::class, 'linePost']);

Route::post('test', [LobbyController::class, 'test']);
Route::get('test-reply', [LobbyController::class, 'testReply']);
