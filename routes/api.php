<?php

use App\Http\Controllers\AdminControllers\AdminBrandController;
use App\Http\Controllers\AdminControllers\AdminCategoryController;
use App\Http\Controllers\AdminControllers\AdminDashboardController;
use App\Http\Controllers\AdminControllers\AdminOrderController;
use App\Http\Controllers\AdminControllers\AdminProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\EnsureUserHasRole;
use App\Http\Middleware\UserIsActive;
use Illuminate\Http\Request;
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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::group(['prefix' => 'admin', 'middleware' => ['auth:sanctum', EnsureUserHasRole::class, UserIsActive::class]], function () {
    //manager dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index']);

    //manager user
    Route::post('/user/{id}/change-status', [AuthController::class, 'changeStatus']);
    Route::get('/users', [AuthController::class, 'users']);

    //manager product
    Route::get('/categories_brands', [AdminProductController::class, 'categoriesBrands']);
//    Route::get('/products', [AdminProductController::class, 'index']);
    Route::get('/products/{filter}', [AdminProductController::class, 'index'])->whereIn('filter', ['all', 'hidden', 'out_of_stock','active']);
    Route::get('/product/{id}', [AdminProductController::class, 'show']);
    Route::post('/product', [AdminProductController::class, 'store']);
    Route::post('/product/{id}', [AdminProductController::class, 'update']);
    Route::delete('/product/{id}', [AdminProductController::class, 'destroy']);
    Route::patch('/product/{id}/active', [AdminProductController::class, 'active']);

    //manager categories
    Route::resource('category', AdminCategoryController::class)->only([
        'store', 'update', 'destroy',
    ]);


    //manager brands
    Route::resource('brand', AdminBrandController::class)->only([
        'store', 'update', 'destroy'
    ]);
    //manager orders
    Route::get('/orders/{filter}', [AdminOrderController::class, 'index'])->whereIn('filter', ['all', 'waiting', 'shipping','completed','cancelled']);
    Route::get('/order/{id}', [AdminOrderController::class, 'show']);
    Route::put('/order/{id}/{status}', [AdminOrderController::class, 'update'])->whereIn('status', ['waiting', 'shipping','completed']);
});

Route::group(['middleware' => ['auth:sanctum', UserIsActive::class]], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/authentication', [AuthController::class, 'auth']);
    Route::put('/user', [AuthController::class, 'update']);
    Route::put('/user/change_password', [AuthController::class, 'changePassword']);
    Route::resource('carts', CartController::class)->only([
        'index', 'store', 'update', 'destroy',
    ]);
    Route::get('/orders/{filter}', [OrderController::class, 'index'])->whereIn('filter', ['all', 'to_wait', 'to_ship','completed','cancelled']);
    Route::post('orders',[OrderController::class,'store']);
    Route::put('/orders/{id}/cancel', [OrderController::class, 'cancel']);
});

Route::get('brands', [BrandController::class, 'index']);
Route::get('categories', [CategoryController::class, 'index']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/product/{id}', [ProductController::class, 'show']);
//Route::get('/test', [Test::class, 'fnTest']);
