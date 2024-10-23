<?php

use App\Http\Controllers\Api\ApichanelController;
use App\Http\Controllers\Api\ApiCurentStream;
use App\Http\Controllers\Api\ApiLoginController;
use App\Http\Controllers\Api\MovieApiController;
use App\Http\Controllers\Api\VersionAplication;
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

Route::post('/login', [ApiLoginController::class, 'login'])->middleware('guest');
Route::post('/logout', [ApiLoginController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/check-device', [ApiLoginController::class, 'checkDevice'])->middleware('auth:sanctum');
Route::post('/curentstream', [ApiCurentStream::class, 'Tambah'])->middleware('auth:sanctum');
Route::get('/chanel', [ApichanelController::class, 'index'])->middleware('auth:sanctum');
//route api movie
Route::get('/movie', [MovieApiController::class, 'index'])->middleware('auth:sanctum');
Route::get('/genre', [MovieApiController::class, 'getgenre'])->middleware('auth:sanctum');


Route::get('/category', [ApichanelController::class, 'category'])->middleware('auth:sanctum');
Route::get('/historysubscription', [ApichanelController::class, 'HistoryLangganan'])->middleware('auth:sanctum');
Route::get('/check-version', [VersionAplication::class, 'VersionLatest'])->middleware('auth:sanctum');
Route::get('/latestsubscription', [ApiLoginController::class, 'getLatestSubscription'])->middleware('auth:sanctum');


Route::post('/payment', [ApichanelController::class, 'createPayment']);
Route::post('/midtrans/notification', [ApichanelController::class, 'handleNotification']);
