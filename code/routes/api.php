<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiOrderController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\WebHookController;
use App\Http\Controllers\TrackingController;

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

Route::post('api-login',[LoginController::class,'api_login']);
Route::middleware('auth:client')->get('/clientuser', function (Request $request) {
    $user = Auth::guard('client')->user();
    
});
Route::get('clientData', [LoginController::class, 'get_client']);
Route::post('api-order', [ApiOrderController::class, 'store']);
Route::get('api-order-show', [ApiOrderController::class, 'show']);
Route::post('api-order-cancelled', [ApiOrderController::class, 'destroy']);
Route::post('api-check-serviceability', [ApiOrderController::class, 'serviceability']);
Route::get('api-check-serviceability', [ApiOrderController::class, 'serviceabilitylist']);
Route::get('api-check-courier', [ApiOrderController::class, 'courier']);
Route::get('api-status-shipment', [ApiOrderController::class, 'shipment_track']);
Route::get('api-ndr-shipment', [ApiOrderController::class, 'ndr_shipment']);
Route::post('api-ndr-process-shipment', [ApiOrderController::class, 'ndr_processed']);
Route::post('api-webhook', [WebHookController::class, 'order_track']);
Route::post('api-cancel', [ApiOrderController::class, 'destroy']);
Route::post('api-warehouse-creation', [ApiOrderController::class, 'warehouse_creation']);
