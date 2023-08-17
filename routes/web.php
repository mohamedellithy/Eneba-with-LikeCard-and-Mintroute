<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\EnebaApplication;
use App\Http\Controllers\LikeCardApplication;
use App\Http\Controllers\EnebaLikeCardController;
use App\Http\Controllers\AuctionApplication;
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
        Route::post('eneba/callback-stock-provision','eneba_callback_stock_provision')->name('eneba.callback_stock_provision');
        Route::post('eneba/callback-stock-reservation','eneba_callback_stock_reservation')->name('eneba.callback_stock_reservation');
        Route::get('application/eneba/products','get_products')->name('eneba.products');
        Route::get('application/eneba/codes/{eneba_id}','add_eneba_codes')->name('eneba.codes');
        Route::post('application/eneba/store_codes','store_eneba_codes')->name('eneba.store_codes');
        Route::put('application/eneba/update_codes/{code_id}','update_eneba_codes')->name('eneba.update_codes');
        Route::delete('application/eneba/delete_codes/{code_id}','delete_eneba_codes')->name('eneba.delete_codes');

    });

    Route::controller(EnebaLikeCardController::class)->group(function(){
        Route::get('application/eneba/single-product/{id}', 'get_single_product')->name('eneba.get_single_product');
        Route::get('attach-eneba-likecard/{eneba_id}/{likecard_id}','attach_eneba_likecard')->name('eneba.attach_eneba_likecard');
    });

    Route::controller(LikeCardApplication::class)->group(function(){
        Route::put('application/likecard/update/{section}','update_credentials')->name('likecard.update');
        Route::put('application/likecard/regenrate-token' ,'generate_token')->name('likecard.regenrate_token');
        Route::get('application/likecard/products','get_products')->name('likecard.products');
        Route::get('application/likecard/codes','get_codes')->name('likecard.codes');
        Route::post('application/likecard/store-codes','store_codes')->name('likecard.store_codes');
        Route::put('application/likecard/update_codes/{code_id}','update_likecard_codes')->name('likecard.update_codes');
        Route::delete('application/likecard/delete_codes/{code_id}','delete_likecard_codes')->name('likecard.delete_codes');
    });

    Route::controller(AuctionApplication::class)->group(function(){
        Route::get('application/auctions', 'index')->name('auctions');
        Route::get('application/auctions/create/{eneba_id}', 'create')->name('auctions.create');
        Route::post('application/search-on-eneba-products','ajax_search_on_eneba_products')->name('search-on-eneba-products');
    });

});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
