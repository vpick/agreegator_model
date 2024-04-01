<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Warehouse;
use App\Models\LogisticsMapping;
use App\Models\Order;
use App\Models\ApiUser;
use App\Models\ProductDetails;
use App\Models\RuleAllocation;
use App\Models\Weight;
use App\Models\Pincode;
use App\Models\Zone;
use App\Models\OrderStatus;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Auth;

use App\Interfaces\AppOrderProcessInterface;

class ApiOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	public function __construct()
    {
    }
    public function processOrder($request,$mapArray,$orderType)
    {
       #dd($orderType);
		// Resolve the appropriate service based on the order type
        $myService = app()->makeWith(AppOrderProcessInterface::class, ['ordersendTo' => $orderType]);
        
        // Now you can use $myService based on the order type.
        // For example, if $orderType is 'type1', the corresponding ServiceOne will be injected.
        
        if (!$myService instanceof AppOrderProcessInterface) {
			//throw new \RuntimeException("Service resolution failed for order type: $orderType");
			echo 'You try to call invalid service class';dd();
		}
        
        return $myService->processOrder($request,$mapArray);
    }
    public function cancelledShipment($request,$mapArray,$orderType)
    {
       #dd($orderType);
		// Resolve the appropriate service based on the order type
        $myService = app()->makeWith(AppOrderProcessInterface::class, ['ordersendTo' => $orderType]);
        
        // Now you can use $myService based on the order type.
        // For example, if $orderType is 'type1', the corresponding ServiceOne will be injected.
        
        if (!$myService instanceof AppOrderProcessInterface) {
			//throw new \RuntimeException("Service resolution failed for order type: $orderType");
			echo 'You try to call invalid service class';dd();
		}
        
        return $myService->cancelledShipment($request,$mapArray);
    }
    public function reprocessOrder($request,$mapArray,$orderType)
    {
		// Resolve the appropriate service based on the order type
        $myService = app()->makeWith(AppOrderProcessInterface::class, ['ordersendTo' => $orderType]);
        
        // Now you can use $myService based on the order type.
        // For example, if $orderType is 'type1', the corresponding ServiceOne will be injected.
        if (!$myService instanceof AppOrderProcessInterface) {
			//throw new \RuntimeException("Service resolution failed for order type: $orderType");
			echo 'You try to call invalid service class';dd();
		}
        return $myService->reprocessOrder($request,$mapArray);
    }
    public function trackShipment($request,$mapArray,$orderType)
    {
		// Resolve the appropriate service based on the order type
        $myService = app()->makeWith(AppOrderProcessInterface::class, ['ordersendTo' => $orderType]);
        // Now you can use $myService based on the order type.
        // For example, if $orderType is 'type1', the corresponding ServiceOne will be injected.
        if (!$myService instanceof AppOrderProcessInterface) {
			//throw new \RuntimeException("Service resolution failed for order type: $orderType");
			echo 'You try to call invalid service class';dd();
		}
        return $myService->trackShipment($request,$mapArray);
    }
    public function trackSingleShipment($request,$mapArray,$orderType)
    {
		// Resolve the appropriate service based on the order type
        $myService = app()->makeWith(AppOrderProcessInterface::class, ['ordersendTo' => $orderType]);
        // Now you can use $myService based on the order type.
        // For example, if $orderType is 'type1', the corresponding ServiceOne will be injected.
        if (!$myService instanceof AppOrderProcessInterface) {
			//throw new \RuntimeException("Service resolution failed for order type: $orderType");
			echo 'You try to call invalid service class';dd();
		}
        return $myService->trackSingleShipment($request,$mapArray);
    }
    public function manifest($request,$mapArray,$orderType)
    {
		// Resolve the appropriate service based on the order type
        $myService = app()->makeWith(AppOrderProcessInterface::class, ['ordersendTo' => $orderType]);
        // Now you can use $myService based on the order type.
        // For example, if $orderType is 'type1', the corresponding ServiceOne will be injected.
        if (!$myService instanceof AppOrderProcessInterface) {
			//throw new \RuntimeException("Service resolution failed for order type: $orderType");
			echo 'You try to call invalid service class';dd();
		}
        return $myService->manifest($request,$mapArray);
    }
    public function serviceability_list($mapArray,$orderType)
    {
		// Resolve the appropriate service based on the order type
        $myService = app()->makeWith(AppOrderProcessInterface::class, ['ordersendTo' => $orderType]);
        // Now you can use $myService based on the order type.
        // For example, if $orderType is 'type1', the corresponding ServiceOne will be injected.
        if (!$myService instanceof AppOrderProcessInterface) {
			//throw new \RuntimeException("Service resolution failed for order type: $orderType");
			echo 'You try to call invalid service class';dd();
		}
        return $myService->serviceabilitylist($mapArray);
    }
    public function ndr_shipment_list($mapArray,$orderType)
    {
		// Resolve the appropriate service based on the order type
        $myService = app()->makeWith(AppOrderProcessInterface::class, ['ordersendTo' => $orderType]);
        // Now you can use $myService based on the order type.
        // For example, if $orderType is 'type1', the corresponding ServiceOne will be injected.
        if (!$myService instanceof AppOrderProcessInterface) {
			//throw new \RuntimeException("Service resolution failed for order type: $orderType");
			echo 'You try to call invalid service class';dd();
		}
        return $myService->ndr_shipment($mapArray);
    }
    public function index()
    {
        //
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
   
    public function store(Request $request)
    {
            $request->headers->set('Accept', 'application/json');
    		$response = array();
    		/*check Authentication*/
    	    $apiUser = ApiUser::where('access_token',$request->bearerToken())->first();
    	    if(empty($apiUser))
    	    {
    	       #return response()->json(['status'=>false,'message' => 'unauthorised access'], 401); 
    	       $response['message'] =  'unauthorised access';
                $response['status'] = false;
                return response()->json(['response' => $response], 201);
    	    }
    	    /*Validate the warehouse*/
    		$originCode = $request->input('origin.origin_code');
    		$whVerify = Warehouse::join('clients', 'warehouses.client_id', '=', 'clients.id')
                         ->where('warehouses.warehouse_code', $originCode)
                         ->select('warehouses.client_id', 'clients.client_code')
                         ->get();
           
            if ($whVerify->isNotEmpty())
    		{
    			$clientId = $whVerify->pluck('client_id')->first();
    			$ruleCount = RuleAllocation::where('client_id', $clientId)->count();
                if ($ruleCount == 0) {
                    #return response()->json(['status' => false, 'message' => 'Please set Auto Ship rule first', 'response' => $response], 400);
                    $response['message'] =  'Please set Auto Ship rule first';
                	$response['status'] = false;
                	return response()->json(['response' => $response], 201);
                }
              
			    $clientCode = $whVerify->pluck('client_code')->first();
				//Varify Order already exist or not
				$orderNo = $request->input('order_no');
				$recordExist = Order::where('order_no', $orderNo)
								->where('client_code',$clientCode)
								->where('warehouse_code',$request->input('origin.origin_code'))
								->select('id','awb_no', 'shipping_label', 'courier_name', 'courrier_id', 'omnee_order','manifest_url')
								->first();
			    $total_weight = $request->input('total_weight')/1000;
			    
			    $payment_mode = $request->input('payment_mode');
			    $shipment_mode = $request->input('shipment_mode')? $request->input('shipment_mode') :'Surface';
			    
			    $order_type = $request->input('order_type')? $request->input('order_type') :'B2C';
			    $pin = $request->input('shipping_consignee.consignee_pincode');
			    #dd($pin);
			    //find state of pincode
			    $pincodeData = Pincode::select('state')->where('pincode',$pin)->first();
			    #dd($pincodeData);
			    if(empty($pincodeData))
			    {
			        #return response()->json(['status' => false, 'message' => 'pincode not found', 'response' => $response], 400);
			        $response['message'] =  'check ship to pincode';
                	$response['status'] = false;
                	return response()->json(['response' => $response], 201);
			    }
			    $state_name = $pincodeData->state;
			    /*Validate the warehouse*/
                #$zone = Zone::whereRaw('FIND_IN_SET(?, zone_mapping) > 0', [$state_name])->where('zone_type','isClient')->get();
                $zone = Zone::whereRaw('FIND_IN_SET(?, zone_mapping) > 0', [$state_name])
							->where('zone_type','company_client')
							->where('client_id',$clientId)
							->get();
                if(empty($zone))
                {
			        #return response()->json(['status' => false, 'message' => 'zone not found', 'response' => $response], 400);
			         $response['message'] =  'zone not found';
                	$response['status'] = false;
                	return response()->json(['response' => $response], 201);
			    }
			    $zone_code = $zone[0]->zone_code;
			    //find rule 
                $con = array
                (
                    'client_id' => $clientId,
                    'weight' => $total_weight,
                    'payment_mode' =>$payment_mode,
                    'shipment_mode' => $shipment_mode,
                    'order_type' => $order_type,
                    'zone_code' => $zone_code
                );
              
                //rule allocation
                $checkRule = $this->courierSelection($con);
               
                if(empty($checkRule)){
                    #return response()->json(['status' => false, 'message' => 'Something Went wrong', 'response' => $response], 400);
                    $response['message'] =  'Something Went wrong';
                	$response['status'] = false;
                	return response()->json(['response' => $response], 201);
                }
                else if(!empty($checkRule) && $checkRule['cred'] == ''){
                    #return response()->json(['status' => false, 'message' => $checkRule['message'], 'response' => $response], 400);
                    $response['message'] =  $checkRule['message'];
                	$response['status'] = false;
                	return response()->json(['response' => $response], 201);
                }
                
                //validate client courier mapping 
             	$mapVarify = LogisticsMapping::where('client_id', $clientId)->where('partner_name',$checkRule['cred']['request_partner'])->first();
                 
                if(empty($mapVarify))
 		        {
 		            $response['message'] = 'Configuration issue occurred';
                	$response['status'] = false;
                	return response()->json(['response' => $response], 201);
 		        }
 		        $mapArray = $mapVarify->toArray();
     		    if($recordExist == null) 
				{ 
    				    $rules = 
        				[
        					'order_no' => 'required|string',
        					'payment_mode' => 'required|string',
        					'consignment_type' => 'required|string',
        					'no_of_invoices' => 'required|numeric',
        					'total_quantity' => 'required|numeric',
        					'shipping_charges' => 'required|numeric',
        					'total_amount' => 'required|numeric',
        					'tax_amount' => 'required|numeric',
        					'discount_amount' => 'required|numeric',
        					'cod_amount' => 'required|numeric',
        					'no_of_boxes' => 'required|numeric',
        					'weight_unit' => 'required|string',
        					'dimensions_unit' => 'required|string',
        					'length' => 'required|numeric',
        					'breadth' => 'required|numeric',
        					'height' => 'required|numeric',
        					'courier_id' => 'required|numeric',
        					'shipping_consignee.consignee_name' => 'required|string|max:50',
        					'shipping_consignee.consignee_company' => 'required|string',
        					'shipping_consignee.consignee_phone' => ['required', 'regex:/^[0-9]{10}$/'],
        					'shipping_consignee.consignee_email' => 'required|email',
        					'shipping_consignee.consignee_address' => 'required|string|max:100',
        					'shipping_consignee.consignee_pincode' => 'required|integer|min:100000|max:999999',
        					'shipping_consignee.consignee_city' => 'required|string',
        					'shipping_consignee.consignee_state' => 'required|string',
        					'shipping_consignee.consignee_country' => 'required|string',
        					'billing_consignee.consignee_name' => 'required|string|max:50',
        					'billing_consignee.consignee_company' => 'required|string',
        					'billing_consignee.consignee_phone' => ['required', 'regex:/^[0-9]{10}$/'],
        					//'billing_consignee.consignee_email' => 'required|email',
        					'billing_consignee.consignee_address' => 'required|string|max:100',
        					'billing_consignee.consignee_pincode' => 'required|integer|min:100000|max:999999',
        					'billing_consignee.consignee_city' => 'required|string',
        					'billing_consignee.consignee_state' => 'required|string',
        					'billing_consignee.consignee_country' => 'required|string',
        					'origin.origin_name' => 'required|string',
        					'origin.origin_code' => 'required|string',
        					'origin.origin_address' => 'required|string',
        					'origin.origin_address_2' => 'required|string',
        					'origin.origin_city' => 'required|string',
        					'origin.origin_state' => 'required|string',
        					'origin.origin_pincode' => 'required|integer|min:100000|max:999999',
        					'origin.origin_phone' => ['required', 'regex:/^[0-9]{10}$/'],
        					'products.*.product_code' => 'required|string',
        					'products.*.product_name' => 'required|string',
        					'products.*.product_hsn_code' => 'required|string',
        					'products.*.product_quantity' => 'required|numeric',
        					'products.*.product_price' => 'required|numeric',
        					'invoice.invoice_number' => 'required|string',
        					'invoice.invoice_date' => 'required|date',
        					'invoice.invoice_value' => 'required|numeric',
        				];
        				$validator = Validator::make($request->all(),$rules);
        				if ($validator->fails()) 
        				{
        					// Validation failed
        					$errors = $validator->errors()->all();
        					// Handle validation errors appropriately
        					$response['error_message'] = $errors;
        					$response['status'] = false;
        					return response()->json(['status' => false, 'message' => 'Validation failed', 'response' => $response], 400);
        				} 
        				
        				// Validation passed
    			        $business_acc = $checkRule['cred']['business_acc'];
    					$request_partner = $checkRule['cred']['request_partner'];
    				
    					if($business_acc==null && $request_partner==null)
    					{
    					    $response['message'] = 'Unble to assign courier partner';
        					$response['status'] = false;
        					return response()->json(['response' => $response], 201);
    					}
    					 
    				    $validatedData = $validator->validated();
        				
        				$validatedData['shipping_first_name'] = $request->input('shipping_consignee.consignee_name');
        				$validatedData['shipping_company_name'] = $request->input('shipping_consignee.consignee_company');
        				$validatedData['shipping_address_1'] = $request->input('shipping_consignee.consignee_address');
        				$validatedData['shipping_address_2'] = $request->input('shipping_consignee.consignee_address_2');
        				$validatedData['shipping_phone_number'] = $request->input('shipping_consignee.consignee_phone');
        				$validatedData['shipping_alternate_phone'] = $request->input('shipping_consignee.consignee_phone_2');
        				$validatedData['shipping_email'] = $request->input('shipping_consignee.consignee_email');
        				$validatedData['shipping_city'] = $request->input('shipping_consignee.consignee_city');
        				$validatedData['shipping_state'] = $request->input('shipping_consignee.consignee_state');
        				$validatedData['shipping_country'] = $request->input('shipping_consignee.consignee_country');
        				$validatedData['shipping_pincode'] = $request->input('shipping_consignee.consignee_pincode');
        				
        				$validatedData['billing_first_name'] = $request->input('billing_consignee.consignee_name');
        				$validatedData['billing_company_name'] = $request->input('billing_consignee.consignee_company');
        				$validatedData['billing_address_1'] = $request->input('billing_consignee.consignee_address');
        				$validatedData['billing_address_2'] = $request->input('billing_consignee.consignee_address_2');
        				$validatedData['billing_phone_number'] = $request->input('billing_consignee.consignee_phone');
        				$validatedData['billing_alternate_phone'] = $request->input('billing_consignee.consignee_phone_2');
        				$validatedData['billing_email'] = $request->input('billing_consignee.consignee_email');
        				$validatedData['billing_city'] = $request->input('billing_consignee.consignee_city');
        				$validatedData['billing_state'] = $request->input('billing_consignee.consignee_state');
        				$validatedData['billing_country'] = $request->input('billing_consignee.consignee_country');
        				$validatedData['billing_pincode'] = $request->input('billing_consignee.consignee_pincode');
        				
        				$validatedData['invoice_no'] = $request->input('invoice.invoice_number');
        				$validatedData['invoice_amount'] = $request->input('invoice.invoice_value');
        				$validatedData['invoice_date'] = $request->input('invoice.invoice_date');
        				
        				$validatedData['total_amount'] = $request->input('total_amount');
        				$validatedData['cod_amount'] = $request->input('cod_amount');
        				$validatedData['discount_amount'] = $request->input('discount_amount');
        				$validatedData['tax_amount'] = $request->input('tax_amount');
        				
        				$validatedData['dimension_unit'] = $request->input('dimensions_unit');
        				$validatedData['weight_unit'] = $request->input('weight_unit');
        				
        				$validatedData['total_weight'] = $request->input('total_weight');
        				$validatedData['length'] = $request->input('length');
        				$validatedData['breadth'] = $request->input('breadth');
        				$validatedData['height'] = $request->input('height');
        				$validatedData['courrier_id'] = $request->input('courier_id');
        				$validatedData['volumetric_weight'] = ($validatedData['length'] * $validatedData['breadth'] * $validatedData['height'])/5000;
    				
    				
        				$validatedData['warehouse_name'] = $request->input('origin.origin_name');
        				$validatedData['warehouse_code'] = $request->input('origin.origin_code');
        				$validatedData['warehouse_address'] = $request->input('origin.origin_address');
        				$validatedData['warehouse_address_2'] = $request->input('origin.origin_address_2');
        				$validatedData['warehouse_city'] = $request->input('origin.origin_city');
        				$validatedData['warehouse_state'] = $request->input('origin.origin_state');
        				$validatedData['warehouse_pincode'] = $request->input('origin.origin_pincode');
        				$validatedData['warehouse_phone_number'] = $request->input('origin.origin_phone');
        				$validatedData['order_status'] = 'Booked';
        				$validatedData['status'] = 1;
        				$validatedData['no_of_invoice'] = $request->input('no_of_invoices');
        				$validatedData['no_of_box'] = $request->input('no_of_boxes');
        				$validatedData['gst_no'] = $request->input('gst_no');
        				$validatedData['order_type'] = $order_type;
        				$validatedData['shipment_mode'] = $shipment_mode;
        				$validatedData['source'] = 'Online';
        				$validatedData['channel'] = $request->input('channel');
        				$validatedData['omnee_order'] = 'MW'.$orderNo;
        				$validatedData['client_code'] = $clientCode;
        				$validatedData['order_request'] = json_encode($request->toArray());
        				$validatedData['request_partner'] = $request_partner;
        				$validatedData['business_account'] = $business_acc ? $business_acc:'';
        				$validatedData['remarks'] = 'order created';
            		    #dd($validatedData);
        				DB::beginTransaction();
        				try
        				{
        					$order = Order::create($validatedData);
        					$order->refresh(); // Refresh the model from the database to get the latest ID
        					$orderId = $order->id;
        					if($orderId > 0)
        					{
        						foreach ($request->products as $product) 
        						{
        							$ProductDetails = new ProductDetails;
        							$ProductDetails->order_id = $orderId;
        							$ProductDetails->product_hsn_code = $product['product_hsn_code']?$product['product_hsn_code']:'';
        							$ProductDetails->product_code = $product['product_code']?$product['product_code']:'';
        							$ProductDetails->product_description = $product['product_name']?$product['product_name']:'';
        							$ProductDetails->product_quantity = $product['product_quantity']?$product['product_quantity']:1;
        							$ProductDetails->product_price = $product['product_price']?$product['product_price']:0.10;
        							
        							$ProductDetails->no_of_box = $product['no_of_box']?$product['no_of_box']:1;
        							$ProductDetails->product_weight_unit = $product['product_weight_unit']?$product['product_weight_unit']:'grams';
        							$ProductDetails->product_weight = $product['product_weight']?$product['product_weight']:0.00;
        							$ProductDetails->product_lbh_unit = $product['product_lbh_unit']?$product['product_lbh_unit']:'cm';
        							$ProductDetails->product_breadth = $product['product_breadth']?$product['product_breadth']:1;
        							$ProductDetails->product_height = $product['product_height']?$product['product_height']:1;
        							$ProductDetails->product_length = $product['product_length']?$product['product_length']:1;
        							
        							$ProductDetails->save();
        						}
        						DB::commit();
        					
        						$ordersendTo = $request_partner;
        						#dd($ordersendTo);
        						$result = app(ApiOrderController::class)->processOrder($request,$mapArray,$ordersendTo);
        					
        						if(!empty($result))
        						{
            					    $record = Order::find($orderId);
            					    if($record) 
            					    {
                					    if($result['status'] == 'success')
            							{
            							    try
            							    {
            								    // Update the desired column
                								if(isset($result['message']))
                								{
                								   $record->remarks = $result['message']?$result['message']:'Shipment Booked';
                								}
                								$record->sending_status = $result['status'];
                								$record->shipping_label = $result['shipping_label'];
                								$record->courier_name = $result['courier_name'];
                								$record->awb_no = $result['awb_no'];
                								if(isset($result['child_awbNo']))
                								{
                								  	$record->child_awbno = $result['child_awbNo'] ? $result['child_awbNo'] :'';
                								}
                							    if(isset($result['docket_print']))
                								{
                								  	$record->docket_print = $result['docket_print'] ? $result['docket_print'] :'';
                								}
                								if(isset($result['locationcode']))
                								{
                								  	$record->route = $result['locationcode'] ? $result['locationcode'] :'';
                								}
                								if($result['awb_no'])
                								{
                								    /*$ManifestDetails = app(ApiOrderController::class)->manifest($finalResponse['data']['awb_number'],$mapArray,'NimbusManifest');
                								    if($ManifestDetails['status'])
                								    {
                								       $record->manifest_url = $ManifestDetails['data']?$ManifestDetails['data']:'';
                								       $response['manifest_slip'] = $ManifestDetails['data']?$ManifestDetails['data']:'';
                								    }*/
                								}
                								else
                								{
                								    $record->order_status = 'Booked';
        								            $record->status = 1;
                								}
                								
                								$record->courrier_id = $result['courier_id'];
                								#dd($record);
                								$record->save();
    
                    							$response['message'] = 'Shipment Booked';
                        						$response['status'] = 'Booked';
                        						$response['courier_id'] = $result['courier_id'];
                        						$response['awb_no'] = $result['awb_no'];
                        						$response['courier_name'] = $result['courier_name'];
                        						$response['shipping_label'] = $result['shipping_label']; //$result['shipping_label'];
                        						if(isset($result['docket_print']))
                								{
                        						    $response['docket_print'] = $result['docket_print'] ? $result['docket_print']:'';
                								}
                								else
                								{
                								    $response['docket_print']='';
                								}
                								if(isset($result['locationcode']))
                								{
                        						    $response['route'] = $result['locationcode'] ? $result['locationcode']:'';
                								}
                								else
                								{
                								    $response['route']='';
                								}
                        						$response['shipment_id'] = $validatedData['omnee_order']?$validatedData['omnee_order']:'';
                        						if(isset($result['child_awbNo']))
                								{
                								  	$response['child_awbno'] = $result['child_awbNo'] ? $result['child_awbNo'] :'';
                								}
                        						else
                        						{
                        						    $response['child_awbno'] = '';
                        						}
                        						#$response['docketPrint'] =isset($result['docketPrint']) ? $result['docketPrint'] : '';
                							}
            								catch(Exception $e) 
            								{
                    						    
                    						    $response['message'] = $e->getMessage();
        							            $response['status'] = false;
                    						    
                                            }
            							}
            							else
                						{
                						    
                						    if(isset($result['message']))
                							{
                							   $record->remarks = $result['message']?$result['message']:'Shipment Failed';
                							}
                							$record->sending_status = 'success';
                							$record->order_status = 'Failed';
                							$record->save();
                							$response['courier_name'] = isset($result['courier_name'])?$result['courier_name']:'';
                						    $response['message'] = $result['message']?$result['message']:'Shipment Failed';
                						    $response['status'] = false;
                						}
            					    }
        							else
        							{
        							    $response['message'] = 'Unable to book shipment';
    							        $response['status'] = false;
        							}
        						
        						}
        						else
        						{
        							$response['message'] = 'Unble to create order';
        							$response['status'] = false;
        						}
        					}
        					else
        					{
        					    $response['message'] = 'Something went wrong';
							    $response['status'] = false;
        					}
        				}
        				catch (\Exception $e) 
        				{
        					// Something went wrong, rollback the transaction
        					DB::rollback();
        					// Optionally, you can handle the exception or log it
        					// Log::error($e->getMessage());
        					$response['message'] = $e->getMessage();
        					$response['status'] = false;
        				}
            	    }	
				else
				{
				    if($recordExist->awb_no!='')
					{
						$response['message'] = 'Order exist';
						$response['status'] = 'Booked';
						$response['courier_id'] = $recordExist->courrier_id?$recordExist->courrier_id:'';
						$response['awb_no'] = $recordExist->awb_no?$recordExist->awb_no:'';
						$response['courier_name'] = $recordExist->courier_name?$recordExist->courier_name:'';
						$response['shipping_label'] = $recordExist->shipping_label?$recordExist->shipping_label:'';
						$response['shipment_id'] = $recordExist->omnee_order?$recordExist->omnee_order:'';
						$response['manifest_slip'] = $recordExist->manifest_url?$recordExist->manifest_url:'';
					}
					else
					{
			            $mapArray = $mapVarify->toArray();
			 	        #$ordersendTo = 'NimbusAppReOrder';
			 	        $ordersendTo =  $checkRule['cred']['request_partner'];
						$result = app(ApiOrderController::class)->reprocessOrder($request,$mapArray,$ordersendTo);
					    
						if(!empty($result))
						{
						    if($result['status'])
							{
        						    $record = Order::find($recordExist->id);
        						    
        							if($record) 
        							{
        							    if($result['status'] == 'success')
            							{
        								    // Update the desired column
            								if(isset($record['message']))
            								{
            								   $record->remarks = $result['message']?$result['message']:'Shipment Booked';
            								}
            								$record->sending_status = $result['status'];
            								$record->shipping_label = $result['packing_slip'];
            								$record->courier_name = $result['courier_name'];
            								$record->awb_no = $result['awb_no'];
            								if(isset($result['child_awbNo']))
            								{
            								  	$record->child_awbno = $result['child_awbNo'] ? $result['child_awbNo'] :'';
            								}
            								if(isset($result['docket_print']))
            								{
            								  	$record->docket_print = $result['docket_print'] ? $result['docket_print'] :'';
            								}
            								if(isset($result['locationcode']))
            								{
            								  	$record->route = $result['locationcode'] ? $result['locationcode'] :'';
            								}
            								if($result['awb_no'])
            								{
            								    /*$ManifestDetails = app(ApiOrderController::class)->manifest($finalResponse['data']['awb_number'],$mapArray,'NimbusManifest');
            								    if($ManifestDetails['status'])
            								    {
            								       $record->manifest_url = $ManifestDetails['data']?$ManifestDetails['data']:'';
            								       $response['manifest_slip'] = $ManifestDetails['data']?$ManifestDetails['data']:'';
            								    }*/
            								}
            								else
            								{
            								    $record->order_status = 'Booked';
    								            $record->status = 0;
            								}
        								
            								$record->courrier_id = $result['courier_id'];
            								#dd($record);
            								$record->save();
    
                							$response['message'] = 'Shipment Booked';
                    						$response['status'] = 'Booked';
                    						$response['courier_id'] = $result['courier_id'];
                    						$response['awb_no'] = $result['awb_no'];
                    						$response['courier_name'] = $result['courier_name'];
                    						$response['shipping_label'] = $result['packing_slip']; //$result['shipping_label'];
                    						$response['shipment_id'] = $validatedData['omnee_order']?$validatedData['omnee_order']:'';
                    						if(isset($result['docket_print']))
            								{
                    						    $response['docket_print'] = $result['docket_print'] ? $result['docket_print']:'';
            								}
            								else
            								{
            								    $response['docket_print']='';
            								}
            								if(isset($result['locationcode']))
            								{
                    						    $response['route'] = $result['locationcode'] ? $result['locationcode']:'';
            								}
            								else
            								{
            								    $response['route']='';
            								}
                						
                    						if(isset($result['child_awbNo']))
            								{
            								  	$response['child_awbno'] = $result['child_awbNo'] ? $result['child_awbNo'] :'';
            								}
                    						else
                    						{
                    						    $response['child_awbno'] = '';
                    						}
            							}
            							else
            							{
            							    if(isset($result['message']))
                							{
                							   $record->remarks = $result['message']?$result['message']:'Shipment Failed';
                							}
                							$record->sending_status = 'success';
                							$record->order_status = 'Failed';
                							$record->save();
                							$response['courier_name'] = isset($result['courier_name'])?$result['courier_name']:'';
                						    $response['message'] = $result['message']?$result['message']:'Shipment Failed';
                						    $response['status'] = false;
            							}
        							}
        							else
        							{
        							    $response['message'] = 'Unable to book shipment';
    							        $response['status'] = false;
        							}
							}
							else
							{
							    $response['message'] = $result['message']?$result['message']:'Unable to book shipment';
    							$response['status'] = false;
    						}
						}
						else
						{
							$response['message'] = 'Unble to create order';
							$response['status'] = false;
						}
					}
				}
    		}
    		else
    		{
    			$response['message'] = 'Origin code '.$request->input('origin.origin_code').' dose not match';
    			$response['status'] = false;
    		}
    	
    		return response()->json(['response' => $response], 201);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::findOrFail($id);
        return response()->json($order);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy_bkp(Request $request)
    {
       
       $finalResponse = $this->NimbusApp->shipment_cancelled($request->input('awb_no'));
       return $finalResponse;
    }
    public function destroy(Request $request)
    {
        $request->headers->set('Accept', 'application/json');
        $response = array();
        $ignore = OrderStatus::pluck('order_status')->toArray();
        $awb_no = $request->input('awb_no');
        $apiUser = ApiUser::where('access_token',$request->bearerToken())->first();
	    if(empty($apiUser))
	    {
	       $response['message'] =  'unauthorised access';
            $response['status'] = false;
            return response()->json(['response' => $response], 201);
	    }
	    /*Validate the warehouse*/
		$oderDetail = Order::where('awb_no',$awb_no)->first();
		
        if($oderDetail)
        {
    		$mapArray = Client::select('log.*')
                        ->join('logistics_mappings as log', 'log.client_id', '=', 'clients.id')
                        ->where('log.partner_name', $oderDetail->request_partner)
                        ->where('clients.client_code', $oderDetail->client_code)
                        ->get()->toArray();  
            if($mapArray){
                $mapArray['order_no'] = $oderDetail->order_no;
               
        		$ordersendTo =$oderDetail->request_partner;
                $finalResponse = app(ApiOrderController::class)->cancelledShipment($awb_no,$mapArray,$ordersendTo);
                
                if($finalResponse)
                {
                    $order = Order::find($oderDetail->id);
                    if($finalResponse['status'] == 'cancelled')
                    {
                        $status = 'Cancelled';
                    }
                    else
                    {
                        $status = $order->order_status;
                    }
                    $order->order_status = $status;
                    $order->remarks = $finalResponse['remarks'];
                    $order->save();
                    $response['status'] = true;
                    $response['message'] =  $finalResponse['remarks'];
                }
                else
                {
                    $response['message'] =  'Something went wrong!';
                    $response['status'] = false;
                }
            }
        }
        else
        {
            $response['message'] =  'AWB not found!';
            $response['status'] = false;
            
        }
       return response()->json(['response' => $response], 201);
    }
    public function serviceability(Request $request)
    {
        $shipment['origin'] = $request->input('origin');
        $shipment['destination'] = $request->input('destination');
        $shipment['payment_type'] = $request->input('payment_type');
        $shipment['order_amount'] = $request->input('order_amount');
        $shipment['weight'] = $request->input('weight');
        $shipment['length'] = $request->input('length');
        $shipment['breadth'] = $request->input('breadth');
        $shipment['height'] = $request->input('height');
        $shipment = json_encode($shipment, true);
        $finalResponse = $this->NimbusApp->serviceability($shipment);
        return $finalResponse;
    }
    public function serviceabilitylist()
    {
        $mapArray[0]['user_name'] = 'scm@warehousity.com';
		$mapArray[0]['password'] = 'reset@321';
		$ordersendTo = 'NimbusAppServiceabilitylist';
        $finalResponse = app(ApiOrderController::class)->serviceability_list($mapArray,$ordersendTo);
		return $finalResponse;
    }
    public function courier()
    {
        $finalResponse = $this->NimbusApp->courier();
        return $finalResponse;
    }
    public function shipment_track_bkp(Request $request)
    {
       
		$awbNo = $request->input('awb_no');
        #$finalResponse = $this->NimbusApp->track_shipment($awbNo);
		$mapArray[0]['user_name'] = 'scm@warehousity.com';
		$mapArray[0]['password'] = 'reset@321';
		#dd($mapArray);
		$ordersendTo = 'NimbusAppTrack';
        $finalResponse = app(ApiOrderController::class)->trackShipment($awbNo,$mapArray,$ordersendTo);
        if($finalResponse['data'])
        {
            $record = Order::where('order_no', $finalResponse['data'][0]['order_number'])
                                  ->where('awb_no', $finalResponse['data'][0]['awb_number'])
                                  ->first();
            if ($record) 
            {
                // Update the required fields
                $record->order_status = ucwords($finalResponse['data'][0]['status']);
                $record->tracking_history = $finalResponse['data'][0];
                // Add more fields you want to update
        
                // Save the changes
                $record->save();
            }
        }
		return $finalResponse;
    }
    public function shipment_track(Request $request)
    {
		$awbNo = $request->input('awb_no');
		#$ignore = OrderStatus::pluck('order_status')->toArray();
        $oderDetail = Order::where('awb_no',$awbNo)->get();
        if($oderDetail)
        {
    		$mapArray = Client::select('log.*')
                  		->join('logistics_mappings as log', 'log.client_id', '=', 'clients.id')
                        ->where('log.partner_name',$oderDetail[0]->request_partner)
                  		->where('clients.client_code',$oderDetail[0]->client_code)
                  		->get()->toArray();   
            if($mapArray){
               
                
        		$ordersendTo =$oderDetail[0]->request_partner;  //NimbusAppSingleTrack
                $finalResponse = app(ApiOrderController::class)->trackShipment($awbNo,$mapArray,$ordersendTo);
                if($finalResponse['data'])
                {
                    
                    $record = Order::where('order_no', $oderDetail[0]->order_no)
                                          ->where('awb_no', $finalResponse['data'][0]['awb_number'])
                                          ->first();
                        if($record) 
                        {
                            if($finalResponse['data'][0]['status']!='')
                            {
                                // Update the required fields
                                $record->order_status = ucwords($finalResponse['data'][0]['status']);
                                $record->tracking_history = $finalResponse['data'][0];
                                $record->remarks = $finalResponse['data'][0]['remarks'] ? $finalResponse['data'][0]['remarks'] : '';
                                $record->save();
                            }
                            else
                            {
                                $record->tracking_history = $finalResponse['data'][0];
                                $record->remarks = $finalResponse['data'][0]['remarks'] ? $finalResponse['data'][0]['remarks'] : '';
                                $record->save();
                            }
                            $response['status'] = true;
                            $response['order_status'] = $finalResponse['data'][0]['status'];
                            $response['tracking_response'] = $finalResponse['data'][0];
                            $response['message'] =  $finalResponse['data'][0]['remarks'];
                        }
                }
                else
                {
                     $response['status'] = true;
                     $response['message'] =  'Something went wrong!';
                }
            }
            else
            {
                $response['status'] = true;
                $response['message'] =  'Authentication issue!';
            }
            
        }
        else
        {
            $response['status'] = true;
            $response['message'] =  'Order detaul not forund!';
        }
		return $finalResponse;
    }
    public function ndr_shipment()
    {
        #$finalResponse = $this->NimbusApp->ndr_shipment();
        #return $finalResponse;
        
        $mapArray[0]['user_name'] = 'scm@warehousity.com';
		$mapArray[0]['password'] = 'reset@321';
		$ordersendTo = 'NimbusAppNDR';
        $finalResponse = app(ApiOrderController::class)->ndr_shipment_list($mapArray,$ordersendTo);
		return $finalResponse;
        
    }
	public function ndr_processed(Request $request)
    {
		#dd($request->all());
		$finalResponse = $this->NimbusApp->ndr_processed($request->all());
        return $finalResponse;
    }
    
    public function courierSelection($con)
    {
		$tot_wt = $con['weight'];
	    $client_id = $con['client_id'];
	    $payment_mode = ucfirst($con['payment_mode']);
	    $zone_code = $con['zone_code'];
	    $shipment_mode = $con['shipment_mode'];
	    $order_type = $con['order_type'];
	    #echo $tot_wt.$client_id.$payment_mode.$zone_code.$shipment_mode.$order_type;die;
		$rule = RuleAllocation::where('client_id', $client_id)
                            ->where('order_type', $order_type)
                            ->where('shipment_mode', $shipment_mode)
                            ->where('payment_mode', $payment_mode)
                            ->where('zone', $zone_code)
                            ->where('rule_status','1')
                            ->whereIn('weight', function ($query) use ($tot_wt) {
                                $query->select('weight_range')
                                    ->from('weights')
                                    ->where('min', '<', $tot_wt)
                                    ->where('max', '>=', $tot_wt);
                            })
                            ->first();
               
        if(empty($rule))
        {
            $cred = '';
		    $credData = array
            (
                'message' => 'Auto Ship Rule is not defind',
                'cred' => $cred
            );
            return $credData;
            
        }
		$courierPriority = $rule->courier_priority;
		$dataPriority = json_decode($courierPriority, true);
	    $priority = $dataPriority['courier_priority'][0];
	    
		$cred = LogisticsMapping::join('app_logistics', 'logistics_mappings.partner_id', '=', 'app_logistics.id')
                    			->where('logistics_mappings.client_id', $con['client_id'])
                    			->where('app_logistics.logistics_name', $priority['Priority 1'])
                    			->where('logistics_mappings.status', 'Active')
                    			->first();	
        if(empty($cred))
        {
            $errMsg = 'configration issues';
            $cred = '';
            $credData = array
            (
                'message' => $errMsg,
                'cred' => $cred
            );
            return $credData;
        }
		$business_acc = $cred->business_acc;
		$request_partner = $cred->partner_name;
		$cred = array(
			'business_acc' => $business_acc,
			'request_partner' => $request_partner,
			'rule_id' => $rule,
	    );
	    $credData = array
        (
            'message' => 'rule matched',
            'cred' => $cred
        );
        return $credData;
		
	}
	public function warehouse_creation(Request $request)
    {
        $request->headers->set('Accept', 'application/json');
        $response = array();
        $apiUser = ApiUser::where('access_token',$request->bearerToken())->first();
	    if(empty($apiUser))
	    {
	        $response['message'] =  'unauthorised access';
            $response['status'] = false;
            return response()->json(['response' => $response], 201);
	    }
	    $whVerify = Warehouse::join('clients', 'warehouses.client_id', '=', 'clients.id')
                         ->where('warehouses.warehouse_code', $request->warehouse_code)
                         ->select('warehouses.client_id', 'clients.client_code')
                         ->get();
           
        if ($whVerify->isNotEmpty())
    	{
    		$clientId = $whVerify->pluck('client_id')->first();
    // 		$mapVarify = LogisticsMapping::where('client_id', $request->client_id)->where('partner_id',$request->partner)->first();
    // 		$ordersendTo =$oderDetail[0]->request_partner;  //NimbusAppSingleTrack
    //         $finalResponse = app(ApiOrderController::class)->trackShipment($awbNo,$mapArray,$ordersendTo);
        }
        return response()->json(['response' => $response], 201);
    }

}