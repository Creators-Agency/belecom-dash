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
Route::get('/login', 'HomeController@login');
/*
 *                      	Stock
 * =========================================================
 *      				CRUD Operations 
 * ---------------------------------------------------------
 *  Model: Stock, SolarPanel, ActivityLog, SolarPanelType,
 *	User, AdministrativeLocation
 * ---------------------------------------------------------
 *	Addtional info:
 * *********************************************************
 */
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

/*
 *                      	Client
 * =========================================================
 *      				CRUD Operations 
 * ---------------------------------------------------------
 *  Model:Referee, Account, SolarPanel, ActivityLog, 
 *	Beneficiary, Payout, SolarPanelType, 
 *	AdministrativeLocation
 * ---------------------------------------------------------
 *	Addtional info:
 * *********************************************************
 */

Route::prefix('client')->group(function(){
	Route::get('/','ClientController@index');
    Route::post('/create/client','ClientController@saveClient')->name('CreateClient');

    Route::get('/{id}-{dob}/edit', 'ClientController@editClient');
    Route::post('/', 'ClientController@updateClient')->name('UpdateClient');
    Route::get('/{id}/delete', 'ClientController@deleteClient');

    Route::get('/{id}/assign', 'ClientController@assign');
    Route::post('/assign', 'ClientController@assignClient')->name('assignClient');

});

/*
 *                      	Payment
 * =========================================================
 *      				CRUD Operations 
 * ---------------------------------------------------------
 *  Model:Referee, Account, SolarPanel, ActivityLog, 
 *	Beneficiary, Payout, SolarPanelType, 
 *	AdministrativeLocation
 * ---------------------------------------------------------
 *	Addtional info:
 * *********************************************************
 */

Route::prefix('/payment')->group(function(){
	Route::get('/','PaymentController@index');
	Route::get('/charges','PaymentController@charge');
    Route::post('/charges','PaymentController@saveCharges')->name('CreateCharges');

    Route::get('/{id}/edit', 'PaymentController@editClient');
    // Route::post('/{id}/update', 'PaymentController@updateClient')->name('UpdateClient');
    Route::get('/{id}/delete', 'PaymentController@deleteClient');

    Route::get('/{id}/assign', 'PaymentController@assign');
    // Route::post('/assign', 'PaymentController@assignClient')->name('assignClient');
 
});

Route::post('/ussd','USSDController@index');