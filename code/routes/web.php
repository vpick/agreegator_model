<?php

use Illuminate\Support\Facades\Route;
//varsha Controllers
//use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\WarehouseController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AjaxController;
use App\Http\Controllers\Admin\MappingController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\MasterAppController;
use App\Http\Controllers\UserPermissionController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\AppClientController;
use App\Http\Controllers\AppWarehouseController;
use App\Http\Controllers\AppUserController;
use App\Http\Controllers\AppCommonController;
use App\Http\Controllers\KycController;
use App\Http\Controllers\ApiUserController;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\AppErpController;
use App\Http\Controllers\CompanyProfileController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\OrderStatusController;
use App\Http\Controllers\ExcelExportController;
use App\Http\Controllers\RuleAllocationController;
use App\Http\Controllers\ShipmentTypeController;
use App\Http\Controllers\CronJobController;
use App\Http\Controllers\ShipOrderController;
use App\Http\Controllers\WebHookController;
use App\Http\Controllers\InvoiceSettingController;
use App\Http\Controllers\WeightController;
use App\Http\Controllers\ZoneController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\RateCardController;
use App\Http\Controllers\RateCalculatorController;
//krish Controllers
use App\Http\Controllers\AppLogisticsController;
use App\Http\Controllers\AppChanelController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReveseOrderController;
use App\Http\Controllers\LogisticsMappingController;
use App\Http\Controllers\PincodeController;

use App\Http\Controllers\SystemMasterController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/', function () {
    return view('web.home');
});
Route::get('/login', function () {
    return view('common-app.login');
    
});

//varsha route list
Route::get('/login', [LoginController::class,'index'])->name('login');
Route::post('login', [LoginController::class,'login']); 
Route::group(['middleware' => 'auth'], function () {
    Route::get('/logout', function () {
         Session::flush();
        Auth::logout();
        return Redirect('/login');
    });
    /*For Root or System users*/
    Route::group(['middleware' => 'checkrole:isSystem'], function () {
        Route::get('/admin-dashboard', function () {
            return view('admin-app.admin-dashboard');
        });
        Route::get('app-profile',[AppUserController::class,'app_user_profile'])->name('app.user.profile');
        Route::post('app-update-profile',[AppUserController::class,'app_update_profile'])->name('app.profile.update');
        Route::get('app-change-password',[AppUserController::class,'app_change_password'])->name('app.user.password');
        Route::post('app-update-password',[AppUserController::class,'app_update_password'])->name('app.password.update');
        Route::resource('company',CompanyController::class);
        Route::resource('app-client',AppClientController::class);
        Route::resource('app-warehouse',AppWarehouseController::class);
        Route::get('app-warehouse-data/{id}',[AppWarehouseController::class,'view'])->name('warehouse.view');
        Route::resource('app-user',AppUserController::class);
        Route::get('/app-clients/export', [AppClientController::class, 'exportAppClient'])->name('app-clients.export');
        Route::post('/app-clients/import', [AppClientController::class, 'importAppClient'])->name('app-clients.import');
        Route::get('app-clients/data', [AppClientController::class, 'getAppClients'])->name('app-clients.data');
        Route::resource('master-app',MasterAppController::class);
        Route::resource('app-erp',AppErpController::class);
        Route::resource('order-status',OrderStatusController::class);
        Route::post('order_status',[OrderStatusController::class,'store'])->name('status.store');
        //Krish routes
        Route::get('app-chanels', [AppChanelController::class, 'index']);
        Route::get('app-add-chanel', [AppChanelController::class, 'add_chanel']);
        Route::post('app-store-chanel', [AppChanelController::class, 'store']);
       
        Route::get('show-chanel', [AppChanelController::class, 'show']);
        Route::put('edit-chanel', [AppChanelController::class, 'edit']);
        
        Route::get('app-partners', [AppLogisticsController::class, 'index']);
        Route::get('app-add-logistics', [AppLogisticsController::class, 'add_logistics']);
        Route::post('app-store-logistics', [AppLogisticsController::class, 'store']);
       
        Route::get('show-partners', [AppLogisticsController::class, 'show']);
        Route::put('edit-partners', [AppLogisticsController::class, 'edit']);
       
        Route::get('/app-aggrigators', [AppLogisticsController::class, 'get_aggrigators']);
        
        Route::get('/app-settings', function () {
            return view('admin-app.admin-settings');
        });
        Route::get('/order-list', [OrderController::class, 'index']);
        Route::get('/company_list',[AppCommonController::class,'company_list']);
        Route::get('/client-list/{id}',[AppCommonController::class,'companyclient_list']);
        Route::get('/warehouse-list/{id}',[AppCommonController::class,'clientwarehouse_list']);
        Route::get('/track-order/{id}', [OrderController::class, 'track_order'])->name('admin-app.track');
        Route::get('/set-company-session/{id}',[AppCommonController::class,'set_company_session']);
        
        Route::get('/system-master',[SystemMasterController::class,'index']);
		Route::get('/pincode-master',[PincodeController::class,'index']);
		Route::get('/add-pincode',[PincodeController::class,'create']);
		Route::get('/download-pincode-sample',[PincodeController::class,'downloadSample']);
		Route::post('/import-pincode',[PincodeController::class,'import']);
        Route::resource('weight-range',WeightController::class);
        Route::resource('zone',ZoneController::class);
        Route::resource('region',RegionController::class);
        
        
//      Route::get('/zone-master',[ZoneController::class,'index']);
// 		Route::get('/add-zonecode',[ZoneController::class,'create']);
// 		Route::get('/download-zone-sample',[ZoneController::class,'downloadSample']);
// 		Route::post('/import-zonecode',[ZoneController::class,'import']);
        
        Route::get('/shipmentType',[ShipmentTypeController::class,'index']);
		Route::get('/add-shipmentType',[ShipmentTypeController::class,'create']);
		Route::post('/store-shipmentType',[ShipmentTypeController::class,'store'])->name('shipmentType.store');
		Route::get('/edit-shipmentType/{id}',[ShipmentTypeController::class,'edit'])->name('shipmentType.edit');
		Route::put('/update-shipmentType',[ShipmentTypeController::class,'update']);
		Route::get('/download-shipmentType-sample',[ShipmentTypeController::class,'downloadSample']);
		Route::post('/import-shipment-type',[ShipmentTypeController::class,'import']);
    });
    Route::group(['middleware' => ['checkrole:isCompany,isSystem,isClient']], function () {
        Route::resource('rate-card',RateCardController::class);
        
        Route::get('/download-ratecontract-sample/{contract_type}',[RateCardController::class,'b2CRateContractDownloadSample']);
        Route::get('/download-ratecontractdsp-sample/{dsp}/{contract_type}',[RateCardController::class,'b2CRateContractDspDownloadSample']);
        Route::post('/import-ratecontract-b2c',[RateCardController::class,'import_b2c']);
        Route::post('/import-ratecontractdsp-b2c',[RateCardController::class,'importdsp_b2c']);
        Route::get('rate-card-b2b',[RateCardController::class,'add_b2b'])->name('add_b2b');
        Route::get('rate-edit-b2b/{id}',[RateCardController::class,'edit_b2b'])->name('edit_b2b');
        Route::put('rate-update-b2b/{id}',[RateCardController::class,'update_b2b'])->name('update_b2b');
        Route::post('rate-card-store-b2b',[RateCardController::class,'store_b2b'])->name('store_b2b');
        Route::get('rate-list-b2b',[RateCardController::class,'b2b_list'])->name('b2b_list');
        Route::get('rate-card-edit/{id}',[RateCardController::class,'edit_card'])->name('edit-card');
    });
    /*For Company*/
    Route::group(['middleware' => 'checkrole:isCompany'], function () {
        Route::group(['as' => 'admin.',], function() {    
            Route::get('companyDashboard',[MasterController::class,'companyDashboard']);
            Route::resource('client',ClientController::class);
          
            
        });
        Route::get('company-profile',[CompanyProfileController::class,'company_profile'])->name('company.profile');
        Route::post('company-profile-update/{id}',[CompanyProfileController::class,'com_profile_update'])->name('com.profile.update');
        Route::get('/get-client/{id}',[AppCommonController::class,'get_client_list']);
        Route::get('/company-settings', function () {
            return view('company-app.company-settings');
        });
        
    });
    //for client
    Route::group(['middleware' => 'checkrole:isClient'], function () {
         Route::get('clientDashboard',[MasterController::class,'clientDashboard']);
        //  Route::get('/client-dashboard', function () {
        //     return view('client-app/client-dashboard');
        // });
        Route::get('get/orders', [OrderController::class, 'get_orders']);
        Route::get('/client-setting', function () {
            return view('client-app/client-setting');
        });
        // Route::get('/rate-calculator', function () {
        //     return view('client-app/rate-calculator-card');
        // });
        
        
        Route::get('client-profile',[ClientController::class,'client_profile'])->name('client.profile');
        Route::post('client-profile-update/{id}',[ClientController::class,'client_profile_update'])->name('client.profile.update');
        Route::get('enable-key/{id}',[ApiUserController::class,'change_key'])->name('key.change');
        Route::resource('rule-allocation',RuleAllocationController::class);
        Route::get('invoice-settings', [PrintController::class, 'invoice_create'])->name('invoice.settings');
        
        // Route::get('send-mail', function () {
            
        //     $details = [
        //         'title' => 'Mail from Logistic App',
        //         'body' => 'This is for testing email using smtp'
        //     ];
          
        //     \Mail::to('varsha123verma@gmail.com')->send(new \App\Mail\UserMail($details));
           
        //     dd("Email is Sent.");
        // });
    });
    //for user
    Route::group(['middleware' => 'checkrole:isUser'], function () {
        Route::get('warehouseDashboard',[MasterController::class,'warehouseDashboard']);
        Route::get('/warehouse-setting', function () {
            return view('warehouse-app/warehouse-setting');
        });
    }); 
    
    //for company,client
    Route::group(['middleware' => ['checkrole:isCompany,isClient']], function () {
        
        Route::get('/all-orders',[OrderController::class,'all_orders'])->name('order.all');
        Route::resource('warehouse',WarehouseController::class);
        Route::get('warehouse-dsp-create',[WarehouseController::class,'warehouse_dsp_create'])->name('warehouse_dsp.create');
        Route::post('warehouse-dsp-send',[WarehouseController::class,'warehouse_dsp_send'])->name('warehouse_dsp.send');
        Route::resource('user',UserController::class);
        
        //mapping route
        Route::get('mapping/{id}', [MappingController::class,'get']); 
        Route::post('load/map', [AjaxController::class, 'clientwarehousemap']);
        Route::get('get-zone', [ZoneController::class, 'zone_list'])->name('zone.get');
        Route::get('add-zone', [ZoneController::class, 'view'])->name('zone.view');
        Route::post('save-zone', [ZoneController::class, 'save'])->name('zone.save');
        Route::get('fetch-zone/{id}', [ZoneController::class, 'fetch'])->name('zone.fetch');
        Route::put('modified-zone/{id}', [ZoneController::class, 'modified'])->name('zone.modified');
        //permission route
        Route::get('permission/{id}', [UserPermissionController::class,'get']);   
        Route::post('load/permission', [AjaxController::class, 'userPermit']);
        
        //list route
        Route::get('/get-warehouses/{id}',[AppCommonController::class,'get_warehouse_list']);
        
        Route::post('map-partner', [LogisticsMappingController::class, 'store']);
        Route::get('mapping-list/{id}', [LogisticsMappingController::class, 'get_mapping_partner']);
        Route::get('mapping-list-comp/{id}', [LogisticsMappingController::class, 'get_mapping_partner_onbhalf_company']);
        Route::get('mapping-list-clinet/{id}', [LogisticsMappingController::class, 'get_mapping_partner_onbhalf_client']);
        Route::resource('kyc',KycController::class);
        Route::post('profile',[KycController::class,'update_profile'])->name('user.profile');
        Route::get('our-channels', [AppChanelController::class, 'chanel_list']);
        Route::get('our-logistics', [AppLogisticsController::class, 'logistics_list']);
        Route::get('our-aggrigators', [AppLogisticsController::class, 'aggrigators_list']);
         Route::get('add-field/{id}', [AppLogisticsController::class, 'add_field'])->name('add_field');
        Route::post('store-field', [AppLogisticsController::class, 'store_field'])->name('store_field');
        Route::post('field_mapping', [AppLogisticsController::class, 'field_mapping'])->name('field_mapping');
        Route::get('erp-data/{id}',[AppErpController::class,'fetch']);
        Route::post('erp-store',[AppErpController::class,'map'])->name('erp.map');
        
        Route::resource('rate-calculator',RateCalculatorController::class);
        Route::get('b2c_calculator',[RateCalculatorController::class,'b2c_calculator'])->name('b2c_calculator');
        
    });
    //for company,client,user
     Route::group(['middleware' => ['checkrole:isCompany,isClient,isUser']], function () {
        Route::resource('master',MasterController::class);
        //order routes
        Route::get('add-orders', [OrderController::class, 'create']);
        Route::post('store-order', [OrderController::class, 'store']);
        Route::get('add-rorders', [ReveseOrderController::class, 'create']);
		Route::post('store-rorder', [ReveseOrderController::class, 'store']);
        
        Route::get('get-pincode', [PincodeController::class, 'pincode_list'])->name('pincode.get');
        Route::get('show-order', [OrderController::class, 'show']);
        Route::get('show-rorder', [ReveseOrderController::class, 'show']);
        Route::post('update-order', [OrderController::class, 'update']);
        Route::put('edit-order', [OrderController::class, 'edit']);
        Route::put('edit-rorder', [ReveseOrderController::class, 'edit']);
        Route::post('ship-order', [ShipOrderController::class, 'ship'])->name('ship.order');
        Route::get('/download-order-sample',[OrderController::class,'downloadSample']);
        Route::get('/download-rorder-sample',[ReveseOrderController::class,'downloadSample']);
        Route::post('/import-order',[OrderController::class,'import']);
        Route::post('/import-rorder',[ReveseOrderController::class,'import']);
        Route::get('view-order', [OrderController::class, 'view']);
        Route::get('view-rorder', [ReveseOrderController::class, 'view']);
        Route::get('invoice_print/{invoice_no}', [PrintController::class, 'invoicePrint'])->name('invoice.print');
        Route::get('print', [PrintController::class, 'create'])->name('label.print');
        Route::post('/setting/label', [PrintController::class, 'store'])->name('label.setting');
        Route::post('bulk-order-status', [OrderController::class, 'bulk_change'])->name('order.bulk.status');
        Route::post('order-status', [OrderController::class, 'change'])->name('order.status');
        Route::get('/track/{id}', [OrderController::class, 'track'])->name('app.track');
        Route::get('/single-tracking/{id}',[TrackingController::class,'single_track'])->name('single.track');
        Route::get('/single-tracking-card/{id}',[OrderController::class,'single_track_card'])->name('single.track.card');
        Route::get('/track-shipment',[TrackingController::class,'shipment_track'])->name('tracking.shipment');
        Route::get('/single-track-shipment/{id}',[TrackingController::class,'single_shipment_track'])->name('single.tracking.shipment');
        Route::get('/billings', function () {
            return view('common-app.list.billing');
        });
       
        Route::get('/job-scheduler', [CronJobController::class, 'index'])->name('cron-job.index');
        Route::get('/reports', function () {
            return view('common-app.report-list.reports');
        });
        Route::get('erp-list',[AppErpController::class,'get'])->name('erp.get');
        Route::get('profile',[UserController::class,'user_profile'])->name('user.profile');
        Route::post('update_profile',[UserController::class,'update_profile'])->name('profile.update');
        Route::get('change-password',[UserController::class,'change_password'])->name('user.password');
        Route::post('update-password',[UserController::class,'update_password'])->name('password.update');
     });
     // order route
   
    Route::group(['middleware' => ['checkrole:isClient,isUser']], function () {
        Route::get('/ndrlist', function () {
            return view('common-app.list.ndrlist');
        });
        Route::resource('webhook',WebhookController::class);
        Route::resource('api-user',ApiUserController::class);
        Route::resource('invoice-settings',InvoiceSettingController::class);
    });
    
    //session route
    Route::get('/set-client-session/{id}',[AppCommonController::class,'set_client_session']);
    Route::get('/set-session/{id}',[AppCommonController::class,'set_session']);
    Route::get('orders', [OrderController::class, 'index']);
    Route::get('rorders', [ReveseOrderController::class, 'index']);
    Route::post('orders/ship', [OrderController::class, 'orderShip'])->name('order.ship');
    
    // ajax call load subcategory
    Route::get('get/weight', [AjaxController::class, 'getWeight'])->name('get.weight');
    Route::get('get/zone_rate', [AjaxController::class, 'getZoneRate']);
    Route::get('get/dsp_zone_rate', [AjaxController::class, 'getZoneDspRate']);
    Route::get('get/rto_rate', [AjaxController::class, 'getRtoRate']);
    Route::get('get/company/{id}', [AjaxController::class, 'getCompanyDetail']);
    Route::get('get/client/{id}', [AjaxController::class, 'getClientDetail']);
    Route::get('get/warehouse/{id}', [AjaxController::class, 'getWarehouseDetail']);
    Route::get('load/client/{id}', [AjaxController::class, 'getClient']);
    Route::get('load/warehouse/{id}', [AjaxController::class, 'getWarehouse']);
    Route::get('/export-orders', [ExcelExportController::class, 'exportOrder']);
    Route::get('/export-rorders', [ExcelExportController::class, 'exportReverseOrder']);
    Route::get('/export-users', [ExcelExportController::class, 'exportUser']);
    Route::get('/export-pincode', [ExcelExportController::class, 'exportPincode']);
    Route::get('/export-shipment-type', [ExcelExportController::class, 'exportShipmentType']);
    Route::get('load/logistics_partner/{id}', [AjaxController::class, 'getLogisticsPartner']);
    Route::get('load/destination/{id}', [AjaxController::class, 'getDestination']);
    Route::get('load/zone-state/{id}', [AjaxController::class, 'getZoneState']);
    Route::get('load/zonetype-state/{zone_type}', [AjaxController::class, 'getZoneTypeState']); 
    
    Route::get('load/dsp-zone/{id}', [AjaxController::class, 'getDspZone']);
    Route::get('load/partner-list/{id}', [AjaxController::class, 'getPartnerList']);
    Route::get('load/zone_list/{contract_type}', [AjaxController::class, 'getZoneList']); 
    Route::get('load/zone_dsp_list/{contract_type}/{dsp}', [AjaxController::class, 'getDspZoneList']); 
});