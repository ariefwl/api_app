<?php

use App\Http\Controllers\AuhtController;
use App\Http\Controllers\FGMutationController;
use App\Http\Controllers\KaBer\AssetMutationController;
use App\Http\Controllers\KaBer\FGMutationController as KaBerFGMutationController;
use App\Http\Controllers\FinGoodsController;
use App\Http\Controllers\KaBer\WasteMutationController;
use App\Http\Controllers\MatMutController;
use App\Http\Controllers\KaBer\MatMutController as KaBerMatMutController;
use App\Http\Controllers\MatUseController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\KaBer\PurchaseController as KaBerPurchaseController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\KaBer\SalesController as KaBerSalesController;
use App\Http\Controllers\KaBer\wipController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WasteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/login', [AuhtController::class, 'login']);
Route::post('/register', [AuhtController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/currentUser', [AuhtController::class, 'currentUser']);
    Route::post('/logout', [AuhtController::class, 'logout']);

    Route::get('/purchases', [PurchaseController::class, 'getPurchaseData']);
    Route::get('/matuse/{period}', [MatUseController::class, 'getMaterialUseData']);
    Route::get('/fgmut', [FGMutationController::class, 'getFGMutationData']);
    Route::get('/fingoods', [FinGoodsController::class, 'getFinGoodsData']);
    Route::get('/sales/{period}', [SalesController::class, 'getSalesData']);
    Route::get('/matmut/{period}', [MatMutController::class, 'getMatMutData']);
    Route::get('/waste/{period}', [WasteController::class, 'getWasteData']);
    Route::get('/user', [UserController::class, 'getUserData']);

    Route::get('/fgmut/export', [FGMutationController::class, 'exportFGMutation']);
    Route::get('/fingoods/export', [FinGoodsController::class, 'exportFinGoods']);

    Route::get('/purchase/count/{period}', [PurchaseController::class, 'countItem']);
    Route::get('/waste/count/{period}', [WasteController::class, 'countItem']);
    Route::get('/sales/count/{period}', [SalesController::class, 'countItem']);
    Route::get('/fgmut/count/{period}', [FGMutationController::class, 'countItem']);
    Route::get('/matuse/count/{period}', [MatUseController::class, 'countItem']);
    Route::get('/fingoods/count/{period}', [FinGoodsController::class, 'countItem']);
    Route::get('/fgmut/count/{period}', [FGMutationController::class, 'countItem']);
    Route::get('/matmut/count/{period}', [MatMutController::class, 'countItem']);

    // Bounded Zone
    Route::get('/purchase-kb', [KaBerPurchaseController::class, 'getPurchaseData']);
    Route::get('/sales-kb', [KaBerSalesController::class, 'getSalesData']);
    Route::get('/matmut-kb', [KaBerMatMutController::class, 'getMatMutData']);
    Route::get('/wip-kb', [wipController::class, 'getWIPData']);
    Route::get('/fgmut-kb', [KaBerFGMutationController::class, 'getFGMutationData']);
    Route::get('/assetmut-kb', [AssetMutationController::class, 'getAssetMutationData']);
    Route::get('/wastemut-kb', [WasteMutationController::class, 'getWasteMutationData']);
});


