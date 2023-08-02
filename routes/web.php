<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\EnebaApplication;
use App\Http\Controllers\LikeCardApplication;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard',[DashboardController::class,'index'])->name('dashboard');

Route::group(['prefix' => 'applications','as' => 'application.'],function(){
    Route::controller(ApplicationController::class)->group(function(){
        Route::get('/application/eneba','index_eneba')->name('eneba');
        Route::get('/application/like-card','index_likecard')->name('like_card');
        Route::get('/application/mintroute','index_mintroute')->name('mintroute');
    });

    Route::controller(EnebaApplication::class)->group(function(){
        Route::put('application/eneba/update/{section}','update_credentials')->name('eneba.update');
        Route::put('application/eneba/regenrate-token' ,'generate_token')->name('eneba.regenrate_token');
        Route::get('application/eneba/callback','eneba_callback')->name('eneba.callback');
        Route::get('application/eneba/products','get_products')->name('eneba.products');
    });

    Route::controller(LikeCardApplication::class)->group(function(){
        Route::put('application/likecard/update/{section}','update_credentials')->name('likecard.update');
        Route::put('application/likecard/regenrate-token' ,'generate_token')->name('likecard.regenrate_token');
        Route::get('application/likecard/products','get_products')->name('likecard.products');
        Route::get('application/likecard/codes','get_codes')->name('likecard.codes');
        Route::post('application/likecard/store-codes','store_codes')->name('likecard.store_codes');
    });

});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
