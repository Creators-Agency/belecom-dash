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
    Route::get('/new/solar/type', 'StockController@addNewType');

    Route::post('/create/item','StockController@saveItem')->name('CreateItem');
    Route::post('/create/location','StockController@saveLocation')->name('CreateLocation');
    Route::post('/create/type','StockController@saveType')->name('CreateType');

    Route::get('/delete/{id}/item', 'StockController@deleteItem');
    Route::get('/delete/{id}/location', 'StockController@deleteLocation');
    Route::get('/delete/{id}/type', 'StockController@deleteType');

    Route::get('/edit/{id}/item', 'StockController@editType');
    Route::get('/edit/{id}/location', 'StockController@editType');
    Route::get('/edit/{id}/type', 'StockController@editType');

    Route::post('/update/{id}/item', 'StockController@updateItem')->name('UpdateItem');
    Route::post('/update/{id}/location', 'StockController@updateLocation')->name('UpdateLocation');
    Route::post('/update/type', 'StockController@updateType')->name('UpdateType');


});


Route::prefix('/client')->group(function(){
	Route::get('/','ClientController@index');
    Route::post('/create/client','ClientController@saveClient')->name('CreateClient');
    Route::get('/edit/{id}/client', 'ClientController@editClient');
    Route::post('/update/{id}/client', 'ClientController@updateClient')->name('UpdateClient');
    Route::get('/delete/{id}/client', 'ClientController@deleteClient');
});

Route::post('/ussd','USSDController@index');