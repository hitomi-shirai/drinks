<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Auth::routes(); 

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//下記、ユーザー新規登録画面
Route::post('/register', [RegisterController::class, 'register'])->name('register');

//下記、商品一覧画面
Route::get('/index', [ProductController::class, 'index'])->name('products.index');

//下記、検索画面
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// 非同期検索用ルート
Route::get('/products/search', [ProductController::class, 'searchAjax'])->name('products.searchAjax');

//下記、商品の削除
Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

//下記、商品新規登録画面
Route::get('/create', [ProductController::class, 'create'])->name('products.create');
Route::post('/products', [ProductController::class, 'store'])->name('products.store');

//下記、商品情報詳細画面
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

//下記、商品情報編集画面
Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
