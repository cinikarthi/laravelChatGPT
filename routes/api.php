<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\ChatGptController;
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

Route::get('home', function () {
    return view('home');
});

Route::post('/chat', [ChatController::class, 'sendMessage'])->name('chat.send');
