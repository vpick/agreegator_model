<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Warehouse;
use App\Models\ProductDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use RealRashid\SweetAlert\Facades\Alert;
use Response,Auth;
use App\Models\AppLogistics;
use App\Models\LogisticsMapping;
use App\Models\Client;
use App\Models\ApiUser;
use Illuminate\Support\Arr;


use App\Interfaces\AppOrderProcessInterface;

class ShipOrderController extends Controller
{
    public function processShipOrder($request,$mapArray,$orderType)
    {
       
		// Resolve the appropriate service based on the order type
        $myService = app()->makeWith(AppOrderProcessInterface::class, ['ordersendTo' => $orderType]);
        
        // Now you can use $myService based on the order type.
        // For example, if $orderType is 'type1', the corresponding ServiceOne will be injected.
        
        if (!$myService instanceof AppOrderProcessInterface) {
			//throw new \RuntimeException("Service resolution failed for order type: $orderType");
			echo 'You try to call invalid service class';dd();
		}
        
        return $myService->processShipOrder($request,$mapArray);
    }
    public function ship(Request $request)
    {
        if(empty($request->order_no))
        {
            $response['message'] = 'Order number is required';
			$response['status'] = false;
		    return response()->json(['response' => $response], 201);
        }   
        $order_no = $request->order_no;
        $order = Order::where('order_no',$order_no)->first();

        if(empty($order))
        {
            $response['message'] = 'Order not found';
			$response['status'] = false;
		    return response()->json(['response' => $response], 201);
        }  
        $product = ProductDetails::where('order_id',$order['id'])->get();
        if(empty($product))
        {
            $response['message'] = 'Order Product not found';
			$response['status'] = false;
		    return response()->json(['response' => $response], 201);
        } 
        $whVerify = Warehouse::join('clients', 'warehouses.client_id', '=', 'clients.id')
                     ->where('warehouses.warehouse_code', $order['warehouse_code'])
                     ->select('warehouses.client_id', 'clients.client_code')
                     ->get();
       
        if ($whVerify->isNotEmpty())
		{
			$clientId = $whVerify->pluck('client_id')->first();
			
			$mapVarify = LogisticsMapping::where('client_id', $clientId)->where('partner_name',$order['request_partner'])->first();
			 
			if(!empty($mapVarify))
			{
    			$ordersendTo = $mapVarify['partner_name'];
    			$orderDetail['order'] = $order;
    			$orderDetail['product'] = $product;
    			$resultData = json_encode($orderDetail);
    		
    		    $finalResponse = app(ShipOrderController::class)->processShipOrder($resultData,$mapVarify,$ordersendTo);
    		   
    		    if(!empty($finalResponse))
        		{
        		    $record = Order::find($order['id']);
        		    if(!empty($record))
        		    {
        		        if($finalResponse['status'] =='success')
						{
							// Update the desired column
							if(isset($finalResponse['message']))
							{
							   $record->remarks = $finalResponse['message']?$finalResponse['message']:'Shipment Booked';
							}
							$record->sending_status = 'success';
							$record->shipping_label = $finalResponse['shipping_label']?$finalResponse['shipping_label']:'';
							$record->courier_name = $finalResponse['courier_name']?$finalResponse['courier_name']:'';
							$record->awb_no = $finalResponse['awb_number']?$finalResponse['awb_number']:'';
							$record->courrier_id = $finalResponse['courier_id']?$finalResponse['courier_id']:0;
							$record->docket_print = isset($finalResponse['docket_print'])?$finalResponse['docket_print']:'';
							$record->child_awbno = isset($finalResponse['child_awbNo']) ? $finalResponse['child_awbNo'] :'';
							$record->route = isset($finalResponse['locationcode']) ? $finalResponse['locationcode'] :'';
							/*if($finalResponse['data']['awb_number'])
							{
							    /*$ManifestDetails = app(ApiOrderController::class)->manifest($finalResponse['data']['awb_number'],$mapArray,'NimbusManifest');
							    if($ManifestDetails['status'])
							    {
							       $record->manifest_url = $ManifestDetails['data']?$ManifestDetails['data']:'';
							       $response['manifest_slip'] = $ManifestDetails['data']?$ManifestDetails['data']:'';
							    }*/
							    
							/*}
							else
							{
							    
							}*/
							$record->order_status = 'Booked';
							$record->save();
							$response['message'] = 'Shipment Booked';
    						$response['status'] = 'Booked';
    						$response['courier_id'] = $finalResponse['courier_id']?$finalResponse['courier_id']:0;
    						$response['awb_no'] = $finalResponse['awb_number']?$finalResponse['awb_number']:'';
    						$response['child_awbNo'] = isset($finalResponse['child_awbNo'])?$finalResponse['child_awbNo']:'';
    						$response['courier_name'] = $finalResponse['courier_name']?$finalResponse['courier_name']:'';
    						$response['packing_slip'] = $finalResponse['shipping_label']?$finalResponse['shipping_label']:'';
    						$response['shipment_id'] =  $order['omnee_order']?$order['omnee_order']:'';
    						$response['docket_print'] =  isset($finalResponse['docket_print'])?$finalResponse['docket_print']:'';
    						$response['route'] =  isset($finalResponse['locationcode']) ? $finalResponse['locationcode'] :'';
						}
						else
						{
						    if(isset($finalResponse['message']))
							{
							   $record->remarks = $finalResponse['message']?$finalResponse['message']:'Shipment Failed';
							}
							$record->sending_status = 'success';
							$record->order_status = 'Failed';
							$record->save();
						    $response['message'] = $finalResponse['message']?$finalResponse['message']:'Shipment Failed';
						    $response['status'] = false;
						}
					}
					else
					{
					    if(isset($finalResponse['message']))
						{
						   $record->remarks = $finalResponse['message']?$finalResponse['message']:'Unable to book shipment';
						}
						$record->sending_status = 'Failed';
						$record->order_status = 'Failed';
						$record->save();
					    $response['message'] = 'Unable to book shipment';
					   $response['status'] = false;
					}
        
        		}
        		else
        		{
					$record->remarks = 'Unble to create order';
					$record->sending_status = 'Failed';
					$record->order_status = 'Failed';
					$record->save();
        			$response['message'] = 'Unble to create order';
        			$response['status'] = false;
        		}
    	    }
            else
    		{
    		   	$response['message'] = 'Configuration issue occurred';
    			$response['status'] = false;
    		}
		}
		else
		{
			$response['message'] = 'Origin code '.$request->origin_code.' dose not match';
			$response['status'] = false;
		}
		return response()->json(['response' => $response], 201);
    }
    
}