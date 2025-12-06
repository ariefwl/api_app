<?php

use App\Http\Controllers\AuhtController;
use App\Http\Controllers\FGMutationController;
use App\Http\Controllers\FinGoodsController;
use App\Http\Controllers\MatMutController;
use App\Http\Controllers\MatUseController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WasteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/login',[AuhtController::class, 'login']);
Route::post('/register',[AuhtController::class, 'register']);

Route::middleware('auth:sanctum')->group(function(){
    Route::get('/currentUser', [AuhtController::class, 'currentUser']);
    Route::post('/logout', [AuhtController::class, 'logout']);
    
    Route::get('/purchases/{period}', [PurchaseController::class, 'getPurchaseData']);
    Route::get('/matuse/{period}', [MatUseController::class, 'getMaterialUseData']);
    Route::get('/fingoods/{period}', [FinGoodsController::class, 'getFinGoodsData']);
    Route::get('/sales/{period}', [SalesController::class, 'getSalesData']);
    Route::get('/matmut/{period}', [MatMutController::class, 'getMatMutData']);
    Route::get('/fgmut/{period}', [FGMutationController::class, 'getFGMutationData']);
    Route::get('/waste/{period}', [WasteController::class, 'getWasteData']);
    Route::get('/user', [UserController::class, 'getUserData']);
});


