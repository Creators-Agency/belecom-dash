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

Route::get('/dev-1234',function()
    {
        $alldata=[];

        Schema::table('payouts', function($table) use ($alldata)
        {
            $table->integer('balance')->after('status')->default(0);
        });
    }
);

Route::get('/', 'HomeController@home');
Route::get('/backdoor','WelcomController@register');
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

    Route::get('/list/panel/','StockController@listPanel');
    Route::get('/panel/{serialNumber}/edit', 'StockController@editSolar');
    ROute::get('/view/owner/{product}', 'StockController@viewOwner');

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
	Route::get('/perspective','ClientController@perspective');
	Route::get('/actual','ClientController@actual');
    Route::post('/create/client','ClientController@saveClient')->name('CreateClient');

    Route::get('/{id}-{dob}/edit', 'ClientController@editClient');
    Route::get('/{id}-{dob}/view', 'ClientController@viewClient');
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
    Route::get('/{id}/delete', 'PaymentController@deleteClient');

    Route::get('/list', 'PaymentController@checkPayment');
    // Route::post('/assign', 'PaymentController@assignClient')->name('assignClient');

});

/*
 *                      	Staff
 * =========================================================
 *      				CRUD Operations
 * ---------------------------------------------------------
 *  Model: User, AdministrativeLocation
 * ---------------------------------------------------------
 *	Addtional info:
 * *********************************************************
 */
Route::prefix('/staff')->group(function(){
    Route::get('/', 'StaffController@index');
    Route::get('register', 'StaffController@addStaff');
    Route::get('/{id}-{dob}/edit', 'StaffController@editStaff');
    Route::get('/{id}-{dob}/permission', 'StaffController@permissionStaff');
    Route::get('/{id}-{dob}/delete', 'StaffController@deleteStaff');
    Route::post('register', 'StaffController@staffSave')->name('Register');
});

/*
 *                      	Permission
 * =========================================================
 *      				CRUD Operations
 * ---------------------------------------------------------
 *  Model: Permission, UserPermission
 * ---------------------------------------------------------
 *	Addtional info:
 * *********************************************************
 */

Route::prefix('/permission')->group(function(){
    Route::post('/stock','PermissionController@stockUpdate')->name('StockPerm');
    Route::post('/client','PermissionController@clientUpdate')->name('ClientsPerm');
    Route::post('/staff','PermissionController@staffUpdate')->name('StaffPerm');
    Route::post('/payment','PermissionController@paymentkUpdate')->name('PaymentPerm');
});


Route::post('/backdoor','WelcomController@staffSave')->name('createBack');


/*
 *                      	USSD
 * =========================================================
 *      				CRUD Operations
 * ---------------------------------------------------------
 *  Model: Payout, Account, Solarpanel, Beneficiary
 * ---------------------------------------------------------
 *	Addtional info:
 * *********************************************************
 */
Route::post('/ussd','USSDController@welcome');

/*
*                    Callback API For Payment
 * =========================================================
 *      				CRUD Operations
 * ---------------------------------------------------------
 *  Model: Payout
 * ---------------------------------------------------------
 *	Addtional info:
 * *********************************************************
 */

Route::post('/ussd/callback', 'USSDController@paymentCallBack');
// Route::post('/ussd/callBack', function (Request $request) {

//     $ = $request->input('name');
//     $message = $request->input('message');

//     $output = "$name says: $message";

//     return $output;
// });
// Route::get('/logout', [AuthenticatedSessionController::class, 'destroy'])
//                 ->middleware('auth')
//                 ->name('logout');


/*
 *                      	import
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
Route::prefix('/system')->group(function(){
    Route::post('/import','SystemController@import')->name('import');
    Route::get('/import','SystemController@index');
    Route::get('/clients','SystemController@clients');
    Route::get('/clients/{id}/restore','SystemController@restore');
});

Route::get('/dev-1234',function()
    {
        $alldata=[];

        Schema::table('beneficiaries', function($table) use ($alldata)
        { 
            // $table->biginteger('primaryPhone')->unique(false)->default(0)->change();
            // $table->dropUnique('primaryPhone');
            $table->dropUnique(['primaryPhone'])->unique(false)->default(0)->change();
        });

        // insert system pref
    }
);