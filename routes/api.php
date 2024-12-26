<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\KategoriController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PendapatanController;
use App\Http\Controllers\Api\PengeluaranController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\RiwayatController;

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

Route::get('/tes', function() {
    return response()->json("tes");
});
Route::post('/login', [AuthController::class,'authenticate']);
Route::post('/register', [AuthController::class,'register']);


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class,'logout']);
    Route::resource('/product', ProductController::class);
    Route::post('/pendapatan', [PendapatanController::class, 'store'] );
    Route::post('/pengeluaran', [PengeluaranController::class, 'store'] );
    Route::get('/riwayat-pendapatan', [RiwayatController::class, 'index'] );
    Route::get('/riwayat-beban', [RiwayatController::class, 'indexBeban'] );
    Route::get('/me', function (Request $request) {
        return $request->user()->load(['usaha','role']);
    });
    Route::get('/dashboard', [DashboardController::class, 'index'] );
    Route::get('/kategori', [KategoriController::class,'index']);
    Route::post('/kategori', [KategoriController::class,'store']);
    
});


