<?php

use App\Http\Resources\MasterData\UomResource;
use App\Http\Resources\MasterData\VendorResource;
use App\Http\Resources\Mobile\AssignListResource;
use App\Http\Resources\Mobile\UserProcResource;
use App\Model\MasterData\Uom;
use App\Model\MasterData\Vendor;
use App\User;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1'], function () {
    /**
     * API MOBILE
     * Auth Route
     * Login / Register / Logout
     */
    Route::post('login', 'API\AuthAPIController@login');
    Route::post('register', 'API\AuthAPIController@register');
    Route::get('logout', 'API\AuthAPIController@logout');

    Route::group(['prefix' => 'users', 'middleware' => 'auth:api'], function () {
        Route::get('assign', function () {
            return auth()->user()->procurement->assign_proc;
        });
        Route::get('proc', function () {
            return new UserProcResource(auth()->user());
        });
    });
    /**
     * Marketing Route
     */
    Route::group(['prefix' => 'marketing/'], function () {
        Route::group(['prefix' => 'sales_order'], function () {
            Route::get('/', 'API\FormSalesOrderAPIController@index');
            Route::get('/show', 'API\FormSalesOrderAPIController@show');
            Route::get('/{id}/edit', 'API\FormSalesOrderAPIController@edit');
            Route::post('/store', 'API\FormSalesOrderAPIController@store');
            Route::delete('detail/{id}', 'API\FormSalesOrderAPIController@deleteOrderDetails');
            Route::patch('/update', 'API\FormSalesOrderAPIController@updateSalesOrderDetails');
            Route::get('/download/{file}', 'Marketing\FormSalesOrderController@DownloadFile');

            Route::get('sales_order_details/{id}', 'Marketing\FormSalesOrderController@getSalesOrderDetails');
        });

    });
    /**
     * Route API Warehouse
     */
    Route::group(['prefix' => 'warehouse/'], function () {

        Route::group(['prefix' => 'delivery_order'], function () {
            Route::get('/create', 'API\DeliveryOrderAPIController@create');
            Route::get('/show/{id}', 'API\DeliveryOrderAPIController@show');
            Route::post('/', 'Warehouse\FormDeliveryOrderController@store');
        });
    });

    /**
     * Route API Route
     */
    Route::group(['prefix' => 'finance'], function () {
        Route::group(['prefix' => 'invoice_order'], function () {
            Route::get('/', 'API\InvoiceAPIController@index');
            Route::get('/create', 'API\InvoiceAPIController@create');
            Route::get('/show', 'API\InvoiceAPIController@show');
            Route::post('/store', 'API\InvoiceAPIController@store');
            Route::post('/print', 'API\InvoiceAPIController@print');
            Route::get('/printRecap/{customer_id}', 'API\InvoiceAPIController@printRecap');

        });
    });

    /**
     * Master Data Route
     */
    Route::group(['prefix' => 'master_data/', 'middleware' => 'auth:api'], function () {

        Route::group(['prefix' => 'customer'], function () {
            Route::get('/', 'API\CustomerAPIController@index')->name('api.customer');
            Route::get('/list', 'API\CustomerAPIController@all');
            Route::get('/list_has_recap', 'API\CustomerAPIController@ListCustomerHasRecap');

        });
        Route::group(['prefix' => 'price'], function () {
            Route::get('/', 'API\MasterPriceController@index')->name('api.price');
            Route::get('/{id}', 'API\MasterPriceController@show');
            Route::get('customer/{id}', 'API\MasterPriceController@CustomerPrice');
            Route::get('/{customer_id}/{skuid}', 'API\MasterPriceController@show');
        });
        Route::group(['prefix' => 'source_order'], function () {
            Route::get('/', 'API\MasterPriceController@index');
            Route::get('/list', 'API\SourceOrderAPIController@all');
        });
        Route::group(['prefix' => 'driver'], function () {
            Route::get('/', 'API\DriverAPIController@index');
        });

        Route::group(['prefix' => 'uom'], function () {
            Route::get('/', function () {
                return UomResource::collection(Uom::all());
            });
        });
        Route::group(['prefix' => 'vendor'], function () {
            Route::get('/', function () {
                return VendorResource::collection(Vendor::all());
            });
        });

        Route::get('customer', 'API\CustomerAPIController@index')->name('api.customer');
        Route::get('list_customer', 'API\CustomerAPIController@all');

        //    Route::get('price_customer/{id}', 'API\MasterPriceController@CustomerPrice');
//        Route::get('uom', 'MasterData\UomController@index');
//        Route::post('uom', 'MasterData\UomController@store');
    });
});


/**
 * Testing Route
 */

Route::get('/testing', function () {
    $data = \App\Model\Marketing\SalesOrder::get();
    return $data;
});

Route::get('/bidding', function () {
});
Route::get('users/roles', 'UserController@roles')->name('users.roles');


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/users ', function (Request $request) {
    $length = $request->input('length');
    $column = $request->input('column'); //Index
    $orderBy = $request->input('dir', 'asc');
    $searchValue = $request->input('query');

    $query = \App\User::dataTableQuery($column, $orderBy, $searchValue);
    $data = $query->paginate($length);

    return new \JamesDordoy\LaravelVueDatatable\Http\Resources\DataTableCollectionResource($data);
});
