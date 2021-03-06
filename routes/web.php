<?php

Route::get('/', function () {
    return redirect(route('admin.dashboard'));
});

Route::get('/confirmmultiplesodo','BulkController@storeMultiple')->name('confirmmultiplesodo');

Route::get('home', function () {
    return redirect(route('admin.dashboard'));
});

Route::get('/bulkitem','BulkController@bulk_item')->name('bulkitem');
Route::get('/bulk_category','BulkController@bulk_category')->name('bulk_category');

Route::name('admin.')->middleware('auth')->prefix('admin')->group(function () {
    Route::get('dashboard', 'DashboardController')->name('dashboard');
    Route::get('passport', 'DashboardController@passport')->name('passport');

    Route::get('users/list', 'UserController@index')->name('users.roles');
    Route::resource('users', 'UserController', [
        'names' => [
            'index' => 'users',
        ],
    ]);
});

Route::name('admin.')->prefix('admin')->middleware('auth')->group(function () {
    Route::name('import')->prefix('import')->group(function () {
        Route::name('price')->prefix('price')->group(function () {
            Route::get('/', 'DashboardController')->where('any', '.*');
            Route::get('/{any}', 'DashboardController')->where('any', '.*');
        });
    });
    /*
     * Routing Menu Marketing
     */
    Route::name('marketing.')->prefix('marketing')->group(function () {
        Route::name('sales_order.')->prefix('form_sales_order')->group(function () {
            Route::get('/', 'DashboardController')->where('any', '.*');
            Route::get('/{any}', 'DashboardController')->where('any', '.*');
        });
    });

    /*
     * Route Menu Procurement
     */
    Route::name('procurement.')->prefix('procurement')->middleware('auth')->group(function () {
        Route::name('user_procurement.')->prefix('user_procurement')->group(function () {
            Route::get('/', 'Procurement\UserProcurementController@index')->name('index');
            Route::get('/create', 'Procurement\UserProcurementController@create')->name('create');
            Route::post('/create', 'Procurement\UserProcurementController@store')->name('store');
            Route::get('/{id}/edit', 'Procurement\UserProcurementController@edit')->name('edit');
            Route::patch('/edit', 'Procurement\UserProcurementController@update')->name('update');
        });
        Route::name('item_procurement.')->prefix('item_procurement')->group(function () {
            Route::get('/', 'Procurement\ItemProcurementController@index')->name('index');
            Route::get('/create', 'Procurement\ItemProcurementController@create')->name('create');
            Route::post('/create', 'Procurement\ItemProcurementController@store')->name('store');
            Route::get('/{id}/edit', 'Procurement\ItemProcurementController@edit')->name('edit');
            Route::patch('/edit', 'Procurement\ItemProcurementController@update')->name('update');
        });
        Route::name('list_procurement.')->prefix('list_procurement')->group(function () {
            Route::get('/', 'Procurement\ListProcurementController@index')->name('index');
            Route::get('/{id}', 'Procurement\ListProcurementController@show')->name('show');
            Route::get('/reject/{id}', 'Procurement\ListProcurementController@editReject')->name('editReject');
            Route::patch('/reject', 'Procurement\ListProcurementController@updateReject')->name('updateReject');
        });
    });

    /*
     * Route Menu Procurement
     */
    Route::name('warehouseIn.')->prefix('warehouseIn')->middleware('auth')->group(function () {
        Route::name('confirm.')->prefix('confirm')->group(function () {
            Route::get('/', 'WarehouseIn\ConfirmController@index')->name('index');
            Route::get('/create', 'WarehouseIn\ConfirmController@create')->name('create');
            Route::get('/show/{id}', 'WarehouseIn\ConfirmController@show')->name('show');
        });
        Route::name('shippinglable.')->prefix('shippinglable')->group(function () {
            Route::get('/', 'ShipingLabelcontroller@index')->name('index');
            // Route::post('/print', 'ShipingLabelcontroller@printsome')->name('printsome');
            Route::get('/print', 'ShipingLabelcontroller@printsome')->name('printsome');
            Route::get('/printall', 'ShipingLabelcontroller@printall')->name('printall');
            Route::get('/show/{id}', 'ShipingLabelcontroller@show')->name('show');
        });

        Route::name('packageItem.')->prefix('packageItem')->group(function () {
            // Route::get('/', 'WarehouseIn\ConfirmController@index')->name('index');
            // Route::get('/create', 'WarehouseIn\ConfirmController@create')->name('create');
            // Route::get('/show/{id}', 'WarehouseIn\ConfirmController@show')->name('show');
            Route::get('/', 'DashboardController')->where('any', '.*');
            Route::get('/{any}', 'DashboardController')->where('any', '.*');
        });
    });

    /*
     * Route Menu Warehouse
     */
    Route::name('warehouse.')->prefix('warehouse')->middleware('auth')->group(function () {
        Route::name('delivery-order.')->prefix('delivery-order')->group(function () {
            Route::get('/', 'DashboardController')->where('any', '.*');
            Route::get('/{any}', 'DashboardController')->where('any', '.*');
        });

        Route::name('confirm_delivery_order.')->prefix('confirm-delivery-order')->group(function () {
            Route::get('/', 'DashboardController')->where('any', '.*');
            Route::get('/{any}', 'DashboardController')->where('any', '.*');
        });
        Route::name('confirm_delivery_order_new.')->prefix('confirm-delivery-order-new')->group(function () {
            Route::get('/', 'WarehouseNewController@index')->name('index');
        });

        Route::name('returned.')->prefix('returned')->group(function () {
            Route::get('/', 'Warehouse\ReturnedOrderController@index')->name('index');
            Route::get('/create', 'Warehouse\ReturnedOrderController@create')->name('create');
            Route::post('/store', 'Warehouse\ReturnedOrderController@store')->name('store');
            Route::get('/{id}/show', 'Warehouse\ReturnedOrderController@show')->name('show');
        });
    });

    /*
     * Route Finance AP
     */
    Route::name('financeAP.')->prefix('finance-ap')->middleware('auth')->group(function () {
        Route::name('replenish.')->prefix('replenish')->group(function () {
            Route::get('/', 'DashboardController')->where('any', '.*');
            Route::get('/{any}', 'DashboardController')->where('any', '.*');
        });
        Route::name('topup.')->prefix('topup')->group(function () {
            Route::get('/', 'Procurement\TopUpController@index')->name('index');
            Route::get('/approve/{id}', 'Procurement\TopUpController@approve')->name('approve');
            Route::get('/reject/{id}', 'Procurement\TopUpController@reject')->name('reject');
        });
        Route::name('requestAdvance.')->prefix('request-advance')->group(function () {
            Route::get('/', 'DashboardController')->where('any', '.*');
            Route::get('/{any}', 'DashboardController')->where('any', '.*');
        });
        Route::name('pettyCash.')->prefix('petty-cash')->group(function () {
            Route::get('/', 'DashboardController')->where('any', '.*');
            Route::get('/{any}', 'DashboardController')->where('any', '.*');
        });
        Route::name('inOutPayment.')->prefix('in-out-payment')->group(function () {
            Route::get('/', 'DashboardController')->where('any', '.*');
            Route::get('/{any}', 'DashboardController')->where('any', '.*');
        });
        Route::name('settlementFinance.')->prefix('settlement-cash-advance')->group(function () {
            Route::get('/', 'DashboardController')->where('any', '.*');
            Route::get('/{any}', 'DashboardController')->where('any', '.*');
        });
    });

    /*
     * Route Finance AR
     */
    Route::name('finance.')->prefix('finance')->middleware('auth')->group(function () {
        Route::name('invoice-order.')->prefix('invoice-order')->group(function () {
            Route::get('/', 'DashboardController')->where('any', '.*');
            Route::get('/{any}', 'DashboardController')->where('any', '.*');
        });

        Route::name('recap-invoice.')->prefix('recap-invoice')->group(function () {
            Route::get('/', 'DashboardController')->where('any', '.*');
            Route::get('/{any}', 'DashboardController')->where('any', '.*');
        });

        Route::name('submitted-recap.')->prefix('submitted-recap')->group(function () {
            Route::get('/', 'DashboardController')->where('any', '.*');
            Route::get('/{any}', 'DashboardController')->where('any', '.*');
        });

        Route::name('paid-recap.')->prefix('paid-recap')->group(function () {
            Route::get('/', 'DashboardController')->where('any', '.*');
            Route::get('/{any}', 'DashboardController')->where('any', '.*');
        });
    });

    /* Route Menu Report Data */
    Route::name('report.')->prefix('report')->middleware('auth')->group(function () {
        Route::name('reportso.')->prefix('report-so')->group(function () {
            Route::get('/', 'Report\ReportController@reportSO')->name('index');
            Route::get('/export', 'Report\ReportController@exportSO')->name('export');
        });
        Route::name('reportdo.')->prefix('report-do')->group(function () {
            Route::get('/', 'Report\ReportController@reportDO')->name('index');
            Route::get('/export', 'Report\ReportController@exportDO')->name('export');
        });

        Route::name('reportb2b.')->prefix('report-b2b')->group(function () {
            Route::get('/', 'Report\ReportController@reportb2b')->name('index');
            Route::get('/export', 'Report\ReportController@exportb2b')->name('export');
        });

        Route::name('reportFinanceAR.')->prefix('report-finance-ar')->group(function () {
            Route::get('/', 'Report\ReportController@reportFinanceAR')->name('index');
            Route::get('/export', 'Report\ReportController@exportFinanceAR')->name('export');
        });
        Route::name('reportPriceUpload.')->prefix('report-price-upload')->group(function () {
            Route::get('/', 'Report\ReportController@reportPriceUpload')->name('index');
            Route::get('/export', 'Report\ReportController@exportPriceUpload')->name('export');
        });
    });

    /*
     * Route Menu Master Data
     */
    Route::name('master_data.')->prefix('master_data')->middleware('auth')->group(function () {
        Route::name('category.')->prefix('category')->group(function () {
            Route::get('/', 'MasterData\CategoryController@index')->name('index');
            Route::get('/create', 'MasterData\CategoryController@create')->name('create');
            Route::post('/create', 'MasterData\CategoryController@store')->name('store');
            Route::get('/{id}/edit', 'MasterData\CategoryController@edit')->name('edit');
            Route::patch('/edit', 'MasterData\CategoryController@update')->name('update');
        });

        Route::name('province.')->prefix('province')->group(function () {
            Route::get('/', 'MasterData\ProvinceController@index')->name('index');
            Route::get('/create', 'MasterData\ProvinceController@create')->name('create');
            Route::post('/create', 'MasterData\ProvinceController@store')->name('store');
            Route::get('/{id}/edit', 'MasterData\ProvinceController@edit')->name('edit');
            Route::patch('/edit', 'MasterData\ProvinceController@update')->name('update');
        });

        Route::name('residence.')->prefix('residence')->group(function () {
            Route::get('/', 'MasterData\ResidenceController@index')->name('index');
            Route::get('/create', 'MasterData\ResidenceController@create')->name('create');
            Route::post('/create', 'MasterData\ResidenceController@store')->name('store');
            Route::get('/{id}/edit', 'MasterData\ResidenceController@edit')->name('edit');
            Route::patch('/edit', 'MasterData\ResidenceController@update')->name('update');
        });

        Route::name('officer.')->prefix('officer')->group(function () {
            Route::get('/', 'MasterData\DriverController@index')->name('index');
            Route::get('/create', 'MasterData\DriverController@create')->name('create');
            Route::post('/create', 'MasterData\DriverController@store')->name('store');
            Route::get('/{id}/edit', 'MasterData\DriverController@edit')->name('edit');
            Route::patch('/edit', 'MasterData\DriverController@update')->name('update');
        });

        Route::name('origin.')->prefix('origin')->group(function () {
            Route::get('/', 'MasterData\OriginController@index')->name('index');
            Route::get('/create', 'MasterData\OriginController@create')->name('create');
            Route::post('/create', 'MasterData\OriginController@store')->name('store');
            Route::get('/{id}/edit', 'MasterData\OriginController@edit')->name('edit');
            Route::patch('/edit', 'MasterData\OriginController@update')->name('update');
        });

        Route::name('uom.')->prefix('uom')->group(function () {
            Route::get('/', 'MasterData\UomController@index')->name('index');
            Route::get('/create', 'MasterData\UomController@create')->name('create');
            Route::post('/create', 'MasterData\UomController@store')->name('store');
            Route::get('/{id}/edit', 'MasterData\UomController@edit')->name('edit');
            Route::patch('/edit', 'MasterData\UomController@update')->name('update');
        });

        Route::name('item.')->prefix('item')->group(function () {
            Route::get('/', 'MasterData\ItemController@index')->name('index');
            Route::get('/create', 'MasterData\ItemController@create')->name('create');
            Route::post('/create', 'MasterData\ItemController@store')->name('store');
            Route::get('/{id}/edit', 'MasterData\ItemController@edit')->name('edit');
            Route::patch('/edit', 'MasterData\ItemController@update')->name('update');
        });

        Route::name('bank.')->prefix('bank')->group(function () {
            Route::get('/', 'MasterData\BankController@index')->name('index');
            Route::get('/create', 'MasterData\BankController@create')->name('create');
            Route::post('/create', 'MasterData\BankController@store')->name('store');
            Route::get('/{id}/edit', 'MasterData\BankController@edit')->name('edit');
            Route::patch('/edit', 'MasterData\BankController@update')->name('update');
        });

        Route::name('vendor.')->prefix('vendor')->group(function () {
            Route::get('/', 'MasterData\VendorController@index')->name('index');
            Route::get('/create', 'MasterData\VendorController@create')->name('create');
            Route::post('/create', 'MasterData\VendorController@store')->name('store');
            Route::get('/{id}/edit', 'MasterData\VendorController@edit')->name('edit');
            Route::patch('/edit', 'MasterData\VendorController@update')->name('update');
        });

        Route::name('source_order.')->prefix('source_order')->group(function () {
            Route::get('/', 'MasterData\SourceOrderController@index')->name('index');
            Route::get('/create', 'MasterData\SourceOrderController@create')->name('create');
            Route::post('/create', 'MasterData\SourceOrderController@store')->name('store');
            Route::get('/{id}/edit', 'MasterData\SourceOrderController@edit')->name('edit');
            Route::patch('/edit', 'MasterData\SourceOrderController@update')->name('update');
        });

        Route::name('customer_type.')->prefix('customer_type')->group(function () {
            Route::get('/', 'MasterData\CustomerTypeController@index')->name('index');
            Route::get('/create', 'MasterData\CustomerTypeController@create')->name('create');
            Route::post('/create', 'MasterData\CustomerTypeController@store')->name('store');
            Route::get('/{id}/edit', 'MasterData\CustomerTypeController@edit')->name('edit');
            Route::patch('/edit', 'MasterData\CustomerTypeController@update')->name('update');
        });

        Route::name('customer_group.')->prefix('customer_group')->group(function () {
            Route::get('/', 'MasterData\CustomerGroupController@index')->name('index');
            Route::get('/create', 'MasterData\CustomerGroupController@create')->name('create');
            Route::post('/create', 'MasterData\CustomerGroupController@store')->name('store');
            Route::get('/{id}/edit', 'MasterData\CustomerGroupController@edit')->name('edit');
            Route::patch('/edit', 'MasterData\CustomerGroupController@update')->name('update');
        });

        Route::name('customer.')->prefix('customer')->group(function () {
            Route::get('/', 'MasterData\CustomerController@index')->name('index');
            Route::get('/create', 'MasterData\CustomerController@create')->name('create');
            Route::post('/create', 'MasterData\CustomerController@store')->name('store');
            Route::get('/{id}/edit', 'MasterData\CustomerController@edit')->name('edit');
            Route::patch('/edit', 'MasterData\CustomerController@update')->name('update');
        });

        Route::name('warehouse.')->prefix('warehouse')->group(function () {
            Route::get('/', 'MasterData\WarehouseController@index')->name('index');
            Route::get('/create', 'MasterData\WarehouseController@create')->name('create');
            Route::post('/create', 'MasterData\WarehouseController@store')->name('store');
            Route::get('/{id}/edit', 'MasterData\WarehouseController@edit')->name('edit');
            Route::patch('/edit', 'MasterData\WarehouseController@update')->name('update');
        });

        Route::name('bank_account.')->prefix('bank_account')->group(function () {
            Route::get('/', 'MasterData\BankAccountController@index')->name('index');
            Route::get('/create', 'MasterData\BankAccountController@create')->name('create');
            Route::post('/create', 'MasterData\BankAccountController@store')->name('store');
            Route::get('/{id}/edit', 'MasterData\BankAccountController@edit')->name('edit');
            Route::patch('/edit', 'MasterData\BankAccountController@update')->name('update');
        });

        Route::name('price.')->prefix('price')->group(function () {
            // Route::get('/', 'MasterData\PriceController@index')->name('index');
            Route::get('/', 'DashboardController')->where('any', '.*')->name('index');
            Route::get('/create', 'MasterData\PriceController@create')->name('create');
            Route::post('/create', 'MasterData\PriceController@store')->name('store');
            Route::get('/{id}/edit', 'MasterData\PriceController@edit')->name('edit');
            Route::patch('/edit', 'MasterData\PriceController@update')->name('update');
        });

        Route::name('inventory.')->prefix('inventory')->group(function () {
            Route::get('/', 'MasterData\InventoryController@index')->name('index');
        });

        Route::name('modules.')->prefix('modules')->group(function () {
            Route::get('/', 'MasterData\ModulesController@index')->name('index');
        });
    });
});

Route::middleware('auth')->get('logout', function () {
    Auth::logout();

    return redirect(route('login'))->withInfo('You have successfully logged out!');
})->name('logout');

Auth::routes(['verify' => true]);

Route::name('js.')->group(function () {
    Route::get('dynamic.js', 'JsController@dynamic')->name('dynamic');
});
// Get authenticated user
Route::get('users/auth', function () {
    return response()->json(['user' => Auth::check() ? Auth::user() : false]);
});

/*
 * Route For Testing
 */
Route::get('/assign', function () {
    return auth()->user()->givePermissionTo('manage-users');
});

Route::get('/testing', function () {
    $columns = [
        array('title' => 'Id', 'field' => 'id'),
        array('title' => 'Name', 'field' => 'name'),
        array('title' => 'Created By', 'field' => 'created_by_name'),
    ];

    $config = [
        //Title Required
        'title' => 'asd',
        /*
         * Route Can Be Null
         */
        //Route For Button Add
        'route-add' => 'testing.create',
        //Route For Button Edit
        'route-edit' => 'testing.edit',
        //Route For Button View
        'route-view' => 'testing.create',
        //Route For Button Search
        'route-search' => 'admin.master_data.driver.index',
    ];
    $data = \App\Model\MasterData\Category::paginate(5);

    return view('admin.crud.index', compact('columns', 'data', 'config'));
});

Route::get('/testing/{id}/edit', function ($id) {
    //Form Generator
    $forms = [
        array('type' => 'text', 'label' => 'Category Name', 'name' => 'name', 'place_holder' => 'asdsa', 'mandatory' => true),
        array('type' => 'textarea', 'label' => 'Remarks', 'name' => 'remarks', 'place_holder' => '', 'mandatory' => false),
    ];
    $config = [
        //Form Title
        'title' => 'Edit Category',
        //Form Action Using Route Name
        'action' => 'testing.create',
        //Form Method
        'method' => 'PATCH',
        //Back Button Using Route Name
        'back-button' => 'testing.create',
    ];

    $data = \App\Model\MasterData\Category::findOrFail($id);

    return view('admin.crud.create_or_edit', compact('forms', 'data', 'config'));
})->name('testing.edit');

Route::get('/testing/create', function () {
    //Form Generator
    $forms = [
        array('type' => 'text', 'label' => 'Category Name', 'name' => 'name', 'place_holder' => 'asdsa', 'mandatory' => true),
        array('type' => 'textarea', 'label' => 'Remarks', 'name' => 'remarks', 'place_holder' => '', 'mandatory' => false),
    ];
    $config = [
        //Form Title
        'title' => 'Create Category',
        //Form Action Using Route Name
        'action' => 'testing.create',
        //Form Method
        'method' => 'POST',
        //Back Button Using Route Name
        'back-button' => 'testing.create',
    ];

    $data = \App\Model\MasterData\Category::paginate(5);

    return view('admin.crud.create_or_edit', compact('forms', 'data', 'config'));
})->name('testing.create');

Route::get('/testing/delete', function () {
    //Form Generator
    $forms = [
        array('type' => 'text', 'label' => 'Category Name', 'name' => 'name', 'place_holder' => 'asdsa', 'mandatory' => true),
        array('type' => 'textarea', 'label' => 'Remarks', 'name' => 'remarks', 'place_holder' => '', 'mandatory' => false),
    ];
    $config = [
        //Form Title
        'title' => 'Create Category',
        //Form Action Using Route Name
        'action' => 'testing.create',
        //Form Method
        'method' => 'POST',
        //Back Button Using Route Name
        'back-button' => 'testing.create',
    ];

    $data = \App\Model\MasterData\Category::paginate(5);

    return view('admin.crud.create_or_edit', compact('forms', 'data', 'config'));
})->name('testing.delete');

Route::get('roles', function () {
    return auth()->user()->getRoleNames();
});

Route::get('/usertest', function () {
    return Auth::user()->UserProfile;
});
