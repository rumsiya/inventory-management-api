<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\StockTransactionController;


Route::post('/login',[AuthController::class,'login']);
Route::post('/register',[AuthController::class,'register']);

Route::middleware('auth:api')->group(function(){
    Route::apiResource('users',Usercontroller::class);
    Route::apiResource('products',ProductController::class);
    Route::apiResource('units',UnitController::class);
    Route::apiResource('categories',CategoryController::class);
    Route::apiResource('suppliers',SupplierController::class);
    Route::apiResource('stocks' , StockTransactionController::class);
    Route::get('reports' , [StockTransactionController::class,'getReports']);

});

Route::get('/debug-image', function () {
    $path = storage_path('app/public/assets');

    return [
        'folder_exists' => is_dir($path),
        'files' => is_dir($path) ? scandir($path) : 'no folder',
        'file_exists' => file_exists(
            storage_path('app/public/assets/VlU3NTOtpUjMThdGN84G4rhVKEAalYeQmBHYIOOx.webp')
        )
    ];
});
