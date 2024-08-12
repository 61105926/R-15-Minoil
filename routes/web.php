<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\ServiceController;

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

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();
Route::get('/consult/', [MerchantController::class, 'index']);


Route::get('/home/{chain?}', [HomeController::class, 'index']);
Route::get('/get-rooms/{chainId}', [HomeController::class, 'getRooms']);
Route::get('/get-lineas/{groupId}', [HomeController::class, 'getLineasByGroup']);
Route::get('/get-productos/{lineaId}', [HomeController::class, 'getProductosByLinea']);
Route::post('/save-data', [HomeController::class, 'saveData']);


