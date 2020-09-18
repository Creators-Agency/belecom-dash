<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'HomeController@home');

Route::prefix('stock')->group(function(){
    Route::get('/', 'StockController@index');
    Route::get('/new/item', 'StockController@addNewItem');
    Route::get('/new/location', 'StockController@addNewLocation');
    Route::post('/create','StockController@save')->name('CreateProducts');
    Route::post('/delete/{id}','StockController@delete');
    Route::get('/edit/{id}','StockController@edit');
    Route::post('/update/{id}','StockController@update')->name('UpdateProducts');
});