<?php

namespace App\Services;

use App\Interfaces\AppOrderProcessInterface;
use App\Models\Order;
class EkartApp implements AppOrderProcessInterface
{
    public function app_login($mapArray)
	{
	    
	   
		/*Login on nimbuspost for auth varify*/
		/*
			Sample Request :-
			curl --location 'https://api.nimbuspost.com/v1/users/login' \
			--header 'content-type: application/json' \
			--data-raw '{
				"email" : "abc@gmail.com",
				"password" : "1223432"
			}'
		*/
		
		$userAuth = array
		(
			'auth_key' => ''.$mapArray[0]['auth_key'],
			//'password' => ''.$mapArray[0]['password']
		);
	
		$loginNimbus = json_encode($userAuth);
		#echo $loginNimbus;dd();
		$url = 'https://api.nimbuspost.com/v1/users/login';
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $loginNimbus);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
		$resultAuth = curl_exec($ch);
		$resultAuth = json_decode($resultAuth, true);
        
		curl_close($ch);
		
		return $resultAuth;
	}
    public function processOrder($shipment,$mapArray)
    {
     
	   #echo 'EkartApp';die;
	   $result['message'] = 'No configuration on on EkartApp';
		$result['status'] = false;
		return $result;
		// send data to nimbuspost using cUrl
// 		if(!empty($shipment))
// 		{
// 			$resultAuth = $this->app_login($mapArray);
			
// 			if($resultAuth['status'])
// 			{
// 				$boxInOrder =  $shipment->input('no_of_boxes');
//         		$boxTotalWeight = $shipment->input('total_weight');
// 				$productTotalWeight = array_sum(array_column($shipment->products,'product_weight'));
// 				$productTotalBox = array_sum(array_column($shipment->products,'no_of_box'));
//         		if(($productTotalBox == $boxInOrder) && ($boxTotalWeight == $productTotalWeight))
// 				{
//         			if(($boxInOrder > 1) || ($boxTotalWeight/1000 > 20))
//     				{
//     					/*Nimbus Shipment is B2B*/ 
//     					$pickupAddress = array();
// 						$pickupAddress['warehouse_name'] = $shipment->input('origin.origin_code');
// 						$pickupAddress['name'] = $shipment->input('origin.origin_name');
// 						$pickupAddress['address'] = $shipment->input('origin.origin_address');
// 						$pickupAddress['address_2'] = $shipment->input('origin.origin_address_2');
// 						$pickupAddress['city'] = $shipment->input('origin.origin_city');
// 						$pickupAddress['state'] = $shipment->input('origin.origin_state');
// 						$pickupAddress['pincode'] = $shipment->input('origin.origin_pincode');
// 						$pickupAddress['phone'] = $shipment->input('origin.origin_phone');
						
// 						$invoice = array();
// 						$invoice[0]['invoice_number'] = $shipment->input('invoice.invoice_number');
// 						$invoice[0]['invoice_date'] = $shipment->input('invoice.invoice_date');
// 						$invoice[0]['invoice_value'] = ''.round($shipment->input('invoice.invoice_value'),2);
// 						$invoice[0]['ebn_number'] = "1234";
// 						$invoice[0]['ebn_expiry_date'] = date("Y-m-d", strtotime('tomorrow'));
    					
//     					/*Nimbus Shipment is B2B*/ 
// 						$i = 0;
// 						$product_detail = array();
// 						foreach ($shipment->products as $product) 
// 						{
// 								$product_detail[$i]['no_of_box'] = '1';
// 								$product_detail[$i]['product_tax_per'] = '0.00';
// 								$product_detail[$i]['product_lbh_unit'] = $product['product_lbh_unit']?$product['product_lbh_unit']:'cm';
// 								$product_detail[$i]['product_weight_unit'] = $product['product_weight_unit']?$product['product_weight_unit']:'gram';
// 								$product_detail[$i]['product_name'] = $product['product_name']?$product['product_name']:'';
// 								$product_detail[$i]['product_price'] = $product['product_price']?$product['product_price']:1;
// 								$product_detail[$i]['product_hsn_code'] = $product['product_hsn_code']?$product['product_hsn_code']:'';
// 								$product_detail[$i]['product_length'] = $product['product_length']?$product['product_length']:'1';
// 								$product_detail[$i]['product_breadth'] = $product['product_breadth']?$product['product_breadth']:'1';
// 								$product_detail[$i]['product_height'] = $product['product_height']?$product['product_height']:'1';
// 								$product_detail[$i]['product_weight'] = $product['product_weight']?$product['product_weight']:'1000';
								
// 								$i++;
// 						}
// 						$order_data = array
// 						(
// 								"order_id"  => $shipment->input('order_no'),
// 								"payment_method"  => $shipment->input('payment_mode'),
// 								"consignee_name" => $shipment->input('shipping_consignee.consignee_name'),
// 								"consignee_company_name" => $shipment->input('shipping_consignee.consignee_name'),
// 								"consignee_phone" => $shipment->input('shipping_consignee.consignee_phone'),
// 								"consignee_email" => $shipment->input('shipping_consignee.consignee_email'),
// 								"consignee_gst_number" => strlen($shipment->input('gst_no')) > 14 ? $shipment->input('gst_no') : '',
// 								"consignee_address" => preg_replace('/[^a-zA-Z0-9_ -]/s',' ',$shipment->input('shipping_consignee.consignee_address')),
// 								"consignee_pincode" => $shipment->input('shipping_consignee.consignee_pincode'),
// 								"consignee_city" => $shipment->input('shipping_consignee.consignee_city'),
// 								"consignee_state" => $shipment->input('shipping_consignee.consignee_state'),
// 								"no_of_invoices" => 1,
// 								"no_of_boxes" => (int)$boxInOrder,
// 								"courier_id" => '',
// 								"request_auto_pickup" => "yes",
// 								"invoice" => $invoice, 
// 								"pickup" => $pickupAddress,
// 								"products" => $product_detail
// 						);
// 						$orderDetails = json_encode($order_data);
// 						$curl = curl_init();
// 						curl_setopt_array($curl, array
// 						(
// 								  CURLOPT_URL => "https://api.nimbuspost.com/v1/b2b",
// 								  CURLOPT_RETURNTRANSFER => true,
// 								  CURLOPT_ENCODING => "",
// 								  CURLOPT_MAXREDIRS => 10,
// 								  CURLOPT_TIMEOUT => 0,
// 								  CURLOPT_FOLLOWLOCATION => true,
// 								  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
// 								  CURLOPT_CUSTOMREQUEST => "POST",
// 								  CURLOPT_POSTFIELDS => $orderDetails,
// 								  CURLOPT_HTTPHEADER => array
// 								  (
// 									  "Content-Type: application/json",
// 									  "Authorization:Bearer ".$resultAuth['data']
// 								  ),
// 						));
// 						$result = curl_exec($curl);
// 						$result = json_decode($result, true); 
// 						curl_close($curl);
// 						return $result;
//     				}
//     				else
//     				{
//     					/*Nimbus Shipment is B2C*/
//     					$addressCustomer = array();
    					
//     					$addressCustomer['name'] = $shipment->input('shipping_consignee.consignee_name');
//     					$addressCustomer['address'] = preg_replace('/[^a-zA-Z0-9_ -]/s',' ',$shipment->input('shipping_consignee.consignee_address'));
//     					$addressCustomer['address_2'] = preg_replace('/[^a-zA-Z0-9_ -]/s',' ',$shipment->input('shipping_consignee.consignee_address_2'));
//     					$addressCustomer['city'] = $shipment->input('shipping_consignee.consignee_city');
//     					$addressCustomer['state'] = $shipment->input('shipping_consignee.consignee_state');
//     					$addressCustomer['pincode'] = $shipment->input('shipping_consignee.consignee_pincode');
//     					$addressCustomer['phone'] = $shipment->input('shipping_consignee.consignee_phone');
    					
//     					$pickupAddress = array();
//     					$pickupAddress['warehouse_name'] = $shipment->input('origin.origin_code');
//     					$pickupAddress['name'] = $shipment->input('origin.origin_name');
//     					$pickupAddress['address'] = $shipment->input('origin.origin_address');
//     					$pickupAddress['address_2'] = $shipment->input('origin.origin_address_2');
//     					$pickupAddress['city'] = $shipment->input('origin.origin_city');
//     					$pickupAddress['state'] = $shipment->input('origin.origin_state');
//     					$pickupAddress['pincode'] = $shipment->input('origin.origin_pincode');
//     					$pickupAddress['phone'] = $shipment->input('origin.origin_phone');
    					
//     					$i = 0;
//     					$product_detail = array();
//     					foreach ($shipment->products as $product) 
//     					{
//     						$product_detail[$i]['name'] = $product['product_name']?$product['product_name']:'';
//     						$product_detail[$i]['qty'] = $product['product_quantity']?$product['product_quantity']:1;
//     						$product_detail[$i]['sku'] = $product['product_code']?$product['product_code']:'';
//     						$product_detail[$i]['price'] = $product['product_price']?$product['product_price']:1;
//     						$i++;
//     					}
//     					$ordAmount = round($shipment->input('total_amount'),2);
//     					$order_data = array
//     					(
//     						"order_number"  => $shipment->input('order_no'),
//     						"shipping_charges"  => $shipment->input('shipping_charges')?$shipment->input('shipping_charges'):0,
//     						"discount"  => $shipment->input('discount_amount')?$shipment->input('discount_amount'):0,
//     						"cod_charges"  => $shipment->input('cod_amount')?$shipment->input('cod_amount'):0,
//     						"payment_type"  => $shipment->input('payment_mode'),
//     						"order_amount"  => $ordAmount?$ordAmount:1,
//     						"package_weight"  => $shipment->input('total_weight'),
//     						"package_length"  => $shipment->input('length'),
//     						"package_breadth" => $shipment->input('breadth'),
//     						"package_height" => $shipment->input('height'),
//     						"consignee" => $addressCustomer, 
//     						"pickup" => $pickupAddress,
//     						"order_items" => $product_detail
//     					);
//     					$orderDetails = json_encode($order_data);
    					
//     					$curl = curl_init();
//     					curl_setopt_array($curl, array
//     					(
//     						  CURLOPT_URL => "https://api.nimbuspost.com/v1/shipments",
//     						  CURLOPT_RETURNTRANSFER => true,
//     						  CURLOPT_ENCODING => "",
//     						  CURLOPT_MAXREDIRS => 10,
//     						  CURLOPT_TIMEOUT => 0,
//     						  CURLOPT_FOLLOWLOCATION => true,
//     						  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//     						  CURLOPT_CUSTOMREQUEST => "POST",
//     						  CURLOPT_POSTFIELDS => $orderDetails,
//     						  CURLOPT_HTTPHEADER => array
//     						  (
//     							  "Content-Type: application/json",
//     							  "Authorization:Bearer ".$resultAuth['data']
//     						  ),
//     					));
//     					$result = curl_exec($curl);
//     					$result = json_decode($result, true); 
//     					curl_close($curl);
//     					return $result;
//     				}
// 				}
// 				else
// 				{
// 					$result['message'] = 'Product total weight and child products weight OR total box and child box are not match';
// 					$result['status'] = false;
// 					return $result;
// 				}
// 			}
// 			else
// 			{
			    
// 				$result['message'] = 'Authentication failed..!';
// 				$result['status'] = false;
// 				return $result;
// 			}
// 		}
    }
    public function processShipOrder($shipmentDetail,$mapArray){
        // send data to nimbuspost using cUrl
        $shipmentData = json_decode($shipmentDetail);
        
        $shipment = $shipmentData->order[0];
       
        $productData = $shipmentData->product;
       
		if(!empty($shipment))
		{
		    
			$resultAuth = $this->app_login($mapArray);
			
			if($resultAuth['status'])
			{
				$boxInOrder =  $shipment->no_of_box;
        		$boxTotalWeight = $shipment->total_weight;
				$productTotalWeight = collect($productData)->sum('product_weight');
				$productTotalBox = collect($productData)->sum('no_of_box');
				
        		if(($productTotalBox == $boxInOrder) && ($boxTotalWeight == $productTotalWeight))
				{
        			if(($boxInOrder > 1) || ($boxTotalWeight/1000 > 20))
    				{
    					/*Nimbus Shipment is B2B*/ 
    					$pickupAddress = array();
						$pickupAddress['warehouse_name'] = $shipment->warehouse_code;
						$pickupAddress['name'] = $shipment->warehouse_name;
						$pickupAddress['address'] = $shipment->warehouse_address;
						$pickupAddress['address_2'] = $shipment->warehouse_address_2;
						$pickupAddress['city'] = $shipment->warehouse_city;
						$pickupAddress['state'] = $shipment->warehouse_state;
						$pickupAddress['pincode'] = $shipment->warehouse_pincode;
						$pickupAddress['phone'] = $shipment->warehouse_phone_number;
						
						$invoice = array();
						$invoice[0]['invoice_number'] = $shipment->invoice_no;
						$invoice[0]['invoice_date'] = $shipment->invoice_date;
						$invoice[0]['invoice_value'] = ''.round($shipment->invoice_amount,2);
						$invoice[0]['ebn_number'] = "1234";
						$invoice[0]['ebn_expiry_date'] = date("Y-m-d", strtotime('tomorrow'));
    					
    					/*Nimbus Shipment is B2B*/ 
						$i = 0;
						$product_detail = array();
						foreach ($productData as $product) 
						{
						    
								$product_detail[$i]['no_of_box'] = '1';
								$product_detail[$i]['product_tax_per'] = '0.00';
								$product_detail[$i]['product_lbh_unit'] = $product->product_lbh_unit?$product->product_lbh_unit:'cm';
								$product_detail[$i]['product_weight_unit'] = $product->product_weight_unit?$product->product_weight_unit:'gram';
								$product_detail[$i]['product_name'] = $product->product_name?$product->product_name:'';
								$product_detail[$i]['product_price'] = $product->product_price?$product->product_price:1;
								$product_detail[$i]['product_hsn_code'] = $product->product_hsn_code?$product->product_hsn_code:'';
								$product_detail[$i]['product_length'] = $product->product_length?$product->product_length:'1';
								$product_detail[$i]['product_breadth'] = $product->product_breadth?$product->product_breadth:'1';
								$product_detail[$i]['product_height'] = $product->product_height?$product->product_height:'1';
								$product_detail[$i]['product_weight'] = $product->product_weight?$product->product_weight:'1000';
								
								$i++;
						}
						
						$order_data = array
						(
								"order_id"  => $shipment->order_no,
								"payment_method"  => $shipment->payment_mode,
								"consignee_name" => $shipment->shipping_first_name,
								"consignee_company_name" => $shipment->shipping_company_name,
								"consignee_phone" => $shipment->shipping_phone_number,
								"consignee_email" => $shipment->shipping_email,
								"consignee_gst_number" => strlen($shipment->gst_no) > 14 ? $shipment->gst_no : '',
								"consignee_address" => preg_replace('/[^a-zA-Z0-9_ -]/s',' ',$shipment->shipping_address_1),
								"consignee_pincode" => $shipment->shipping_pincode,
								"consignee_city" => $shipment->shipping_city,
								"consignee_state" => $shipment->shipping_state,
								"no_of_invoices" => 1,
								"no_of_boxes" => (int)$boxInOrder,
								"courier_id" => '',
								"request_auto_pickup" => "yes",
								"invoice" => $invoice, 
								"pickup" => $pickupAddress,
								"products" => $product_detail
						);
						$orderDetails = json_encode($order_data);
						$curl = curl_init();
						curl_setopt_array($curl, array
						(
								  CURLOPT_URL => "https://api.nimbuspost.com/v1/b2b",
								  CURLOPT_RETURNTRANSFER => true,
								  CURLOPT_ENCODING => "",
								  CURLOPT_MAXREDIRS => 10,
								  CURLOPT_TIMEOUT => 0,
								  CURLOPT_FOLLOWLOCATION => true,
								  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
								  CURLOPT_CUSTOMREQUEST => "POST",
								  CURLOPT_POSTFIELDS => $orderDetails,
								  CURLOPT_HTTPHEADER => array
								  (
									  "Content-Type: application/json",
									  "Authorization:Bearer ".$resultAuth['data']
								  ),
						));
						$result = curl_exec($curl);
						$result = json_decode($result, true); 
						curl_close($curl);
						return $result;
    				}
    				else
    				{
    					/*Nimbus Shipment is B2C*/
    					$addressCustomer = array();
    					
    					$addressCustomer['name'] = $shipment->shipping_first_name;
    					$addressCustomer['address'] = preg_replace('/[^a-zA-Z0-9_ -]/s',' ',$shipment->shipping_address_1);
    					$addressCustomer['address_2'] = preg_replace('/[^a-zA-Z0-9_ -]/s',' ',$shipment->shipping_address_2);
    					$addressCustomer['city'] = $shipment->shipping_city;
    					$addressCustomer['state'] = $shipment->shipping_state;
    					$addressCustomer['pincode'] = $shipment->shipping_pincode;
    					$addressCustomer['phone'] = $shipment->shipping_phone_number;
    					
    					$pickupAddress = array();
    					$pickupAddress['warehouse_name'] = $shipment->warehouse_code;
    					$pickupAddress['name'] = $shipment->warehouse_name;
    					$pickupAddress['address'] = $shipment->warehouse_address;
    					$pickupAddress['address_2'] = $shipment->warehouse_address_2;
    					$pickupAddress['city'] = $shipment->warehouse_city;
    					$pickupAddress['state'] = $shipment->warehouse_state;
    					$pickupAddress['pincode'] = $shipment->warehouse_pincode;
    					$pickupAddress['phone'] = $shipment->warehouse_phone_number;
    					
    					$i = 0;
    					$product_detail = array();
    					foreach ($productData as $product) 
    					{
    					   
    						$product_detail[$i]['name'] = $product->product_description?$product->product_description:'';
    						$product_detail[$i]['qty'] = $product->product_quantity?$product->product_quantity:1;
    						$product_detail[$i]['sku'] = $product->product_code?$product->product_code:'';
    						$product_detail[$i]['price'] = $product->product_price?$product->product_price:1;
    						$i++;
    					}
    				
    					$ordAmount = round($shipment->total_amount,2);
    					$order_data = array
    					(
    						"order_number"  => $shipment->order_no,
    						"shipping_charges"  =>$shipment->shipping_charges?$shipment->shipping_charges:0,
    						"discount"  => $shipment->discount_amount?$shipment->discount_amount:0,
    						"cod_charges"  => $shipment->cod_amount?$shipment->cod_amount:0,
    						"payment_type"  => strtolower($shipment->payment_mode),
    						"order_amount"  => $ordAmount?$ordAmount:1,
    						"package_weight"  => (float)$shipment->total_weight,
    						"package_length"  => $shipment->length,
    						"package_breadth" => $shipment->breadth,
    						"package_height" => $shipment->height,
    						"consignee" => $addressCustomer, 
    						"pickup" => $pickupAddress,
    						"order_items" => $product_detail
    					);
    					$orderDetails = json_encode($order_data);
    					
    					$curl = curl_init();
    					curl_setopt_array($curl, array
    					(
    						  CURLOPT_URL => "https://api.nimbuspost.com/v1/shipments",
    						  CURLOPT_RETURNTRANSFER => true,
    						  CURLOPT_ENCODING => "",
    						  CURLOPT_MAXREDIRS => 10,
    						  CURLOPT_TIMEOUT => 0,
    						  CURLOPT_FOLLOWLOCATION => true,
    						  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    						  CURLOPT_CUSTOMREQUEST => "POST",
    						  CURLOPT_POSTFIELDS => $orderDetails,
    						  CURLOPT_HTTPHEADER => array
    						  (
    							  "Content-Type: application/json",
    							  "Authorization:Bearer ".$resultAuth['data']
    						  ),
    					));
    					$result = curl_exec($curl);
    					$result = json_decode($result, true); 
    					curl_close($curl);
    					return $result;
    				}
				}
				else
				{
					$result['message'] = 'Product total weight and child products weight OR total box and child box are not match';
					$result['status'] = false;
					return $result;
				}
			}
			else
			{
				$result['message'] = 'Authentication failed..!';
				$result['status'] = false;
				return $result;
			}
		}
    }
    
    public function reprocessOrder($shipment,$mapArray)
    {
        $result['message'] = 'No configuration on on EkartApp';
		$result['status'] = false;
		return $result;
		// send data to nimbuspost using cUrl
// 		if(!empty($shipment))
// 		{
// 			$resultAuth = $this->app_login($mapArray);
// 			if($resultAuth['status'])
// 			{
// 				$boxInOrder =  $shipment->input('no_of_boxes');
//         		$boxTotalWeight = $shipment->input('total_weight');
// 				$productTotalWeight = array_sum(array_column($shipment->products,'product_weight'));
// 				$productTotalBox = array_sum(array_column($shipment->products,'no_of_box'));
//         		if(($productTotalBox == $boxInOrder) && ($boxTotalWeight == $productTotalWeight))
// 				{
//         			if(($boxInOrder > 1) || ($boxTotalWeight/1000 > 20))
//     				{
//     					/*Nimbus Shipment is B2B*/ 
//     					$pickupAddress = array();
// 						$pickupAddress['warehouse_name'] = $shipment->input('origin.origin_code');
// 						$pickupAddress['name'] = $shipment->input('origin.origin_name');
// 						$pickupAddress['address'] = $shipment->input('origin.origin_address');
// 						$pickupAddress['address_2'] = $shipment->input('origin.origin_address_2');
// 						$pickupAddress['city'] = $shipment->input('origin.origin_city');
// 						$pickupAddress['state'] = $shipment->input('origin.origin_state');
// 						$pickupAddress['pincode'] = $shipment->input('origin.origin_pincode');
// 						$pickupAddress['phone'] = $shipment->input('origin.origin_phone');
						
// 						$invoice = array();
// 						$invoice[0]['invoice_number'] = $shipment->input('invoice.invoice_number');
// 						$invoice[0]['invoice_date'] = $shipment->input('invoice.invoice_date');
// 						$invoice[0]['invoice_value'] = ''.round($shipment->input('invoice.invoice_value'),2);
// 						$invoice[0]['ebn_number'] = "1234";
// 						$invoice[0]['ebn_expiry_date'] = date("Y-m-d", strtotime('tomorrow'));
    					
//     					/*Nimbus Shipment is B2B*/ 
// 						$i = 0;
// 						$product_detail = array();
// 						foreach ($shipment->products as $product) 
// 						{
// 								$product_detail[$i]['no_of_box'] = '1';
// 								$product_detail[$i]['product_tax_per'] = '0.00';
// 								$product_detail[$i]['product_lbh_unit'] = $product['product_lbh_unit']?$product['product_lbh_unit']:'cm';
// 								$product_detail[$i]['product_weight_unit'] = $product['product_weight_unit']?$product['product_weight_unit']:'gram';
// 								$product_detail[$i]['product_name'] = $product['product_name']?$product['product_name']:'';
// 								$product_detail[$i]['product_price'] = $product['product_price']?$product['product_price']:1;
// 								$product_detail[$i]['product_hsn_code'] = $product['product_hsn_code']?$product['product_hsn_code']:'';
// 								$product_detail[$i]['product_length'] = $product['product_length']?$product['product_length']:'1';
// 								$product_detail[$i]['product_breadth'] = $product['product_breadth']?$product['product_breadth']:'1';
// 								$product_detail[$i]['product_height'] = $product['product_height']?$product['product_height']:'1';
// 								$product_detail[$i]['product_weight'] = $product['product_weight']?$product['product_weight']:'1000';
								
// 								$i++;
// 						}
// 						$order_data = array
// 						(
// 								"order_id"  => $shipment->input('order_no'),
// 								"payment_method"  => $shipment->input('payment_mode'),
// 								"consignee_name" => $shipment->input('shipping_consignee.consignee_name'),
// 								"consignee_company_name" => $shipment->input('shipping_consignee.consignee_name'),
// 								"consignee_phone" => $shipment->input('shipping_consignee.consignee_phone'),
// 								"consignee_email" => $shipment->input('shipping_consignee.consignee_email'),
// 								"consignee_gst_number" => strlen($shipment->input('gst_no')) > 14 ? $shipment->input('gst_no') : '',
// 								"consignee_address" => preg_replace('/[^a-zA-Z0-9_ -]/s',' ',$shipment->input('shipping_consignee.consignee_address')),
// 								"consignee_pincode" => $shipment->input('shipping_consignee.consignee_pincode'),
// 								"consignee_city" => $shipment->input('shipping_consignee.consignee_city'),
// 								"consignee_state" => $shipment->input('shipping_consignee.consignee_state'),
// 								"no_of_invoices" => 1,
// 								"no_of_boxes" => (int)$boxInOrder,
// 								"courier_id" => '',
// 								"request_auto_pickup" => "yes",
// 								"invoice" => $invoice, 
// 								"pickup" => $pickupAddress,
// 								"products" => $product_detail
// 						);
// 						$orderDetails = json_encode($order_data);
// 						$curl = curl_init();
// 						curl_setopt_array($curl, array
// 						(
// 								  CURLOPT_URL => "https://api.nimbuspost.com/v1/b2b",
// 								  CURLOPT_RETURNTRANSFER => true,
// 								  CURLOPT_ENCODING => "",
// 								  CURLOPT_MAXREDIRS => 10,
// 								  CURLOPT_TIMEOUT => 0,
// 								  CURLOPT_FOLLOWLOCATION => true,
// 								  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
// 								  CURLOPT_CUSTOMREQUEST => "POST",
// 								  CURLOPT_POSTFIELDS => $orderDetails,
// 								  CURLOPT_HTTPHEADER => array
// 								  (
// 									  "Content-Type: application/json",
// 									  "Authorization:Bearer ".$resultAuth['data']
// 								  ),
// 						));
// 						$result = curl_exec($curl);
// 						$result = json_decode($result, true); 
// 						curl_close($curl);
// 						return $result;
//     				}
//     				else
//     				{
//     					/*Nimbus Shipment is B2C*/
//     					$addressCustomer = array();
    					
//     					$addressCustomer['name'] = $shipment->input('shipping_consignee.consignee_name');
//     					$addressCustomer['address'] = preg_replace('/[^a-zA-Z0-9_ -]/s',' ',$shipment->input('shipping_consignee.consignee_address'));
//     					$addressCustomer['address_2'] = preg_replace('/[^a-zA-Z0-9_ -]/s',' ',$shipment->input('shipping_consignee.consignee_address_2'));
//     					$addressCustomer['city'] = $shipment->input('shipping_consignee.consignee_city');
//     					$addressCustomer['state'] = $shipment->input('shipping_consignee.consignee_state');
//     					$addressCustomer['pincode'] = $shipment->input('shipping_consignee.consignee_pincode');
//     					$addressCustomer['phone'] = $shipment->input('shipping_consignee.consignee_phone');
    					
//     					$pickupAddress = array();
//     					$pickupAddress['warehouse_name'] = $shipment->input('origin.origin_code');
//     					$pickupAddress['name'] = $shipment->input('origin.origin_name');
//     					$pickupAddress['address'] = $shipment->input('origin.origin_address');
//     					$pickupAddress['address_2'] = $shipment->input('origin.origin_address_2');
//     					$pickupAddress['city'] = $shipment->input('origin.origin_city');
//     					$pickupAddress['state'] = $shipment->input('origin.origin_state');
//     					$pickupAddress['pincode'] = $shipment->input('origin.origin_pincode');
//     					$pickupAddress['phone'] = $shipment->input('origin.origin_phone');
    					
//     					$i = 0;
//     					$product_detail = array();
//     					foreach ($shipment->products as $product) 
//     					{
//     						$product_detail[$i]['name'] = $product['product_name']?$product['product_name']:'';
//     						$product_detail[$i]['qty'] = $product['product_quantity']?$product['product_quantity']:1;
//     						$product_detail[$i]['sku'] = $product['product_code']?$product['product_code']:'';
//     						$product_detail[$i]['price'] = $product['product_price']?$product['product_price']:1;
//     						$i++;
//     					}
//     					$ordAmount = round($shipment->input('total_amount'),2);
//     					$order_data = array
//     					(
//     						"order_number"  => $shipment->input('order_no'),
//     						"shipping_charges"  => $shipment->input('shipping_charges')?$shipment->input('shipping_charges'):0,
//     						"discount"  => $shipment->input('discount_amount')?$shipment->input('discount_amount'):0,
//     						"cod_charges"  => $shipment->input('cod_amount')?$shipment->input('cod_amount'):0,
//     						"payment_type"  => $shipment->input('payment_mode'),
//     						"order_amount"  => $ordAmount?$ordAmount:1,
//     						"package_weight"  => $shipment->input('total_weight'),
//     						"package_length"  => $shipment->input('length'),
//     						"package_breadth" => $shipment->input('breadth'),
//     						"package_height" => $shipment->input('height'),
//     						"consignee" => $addressCustomer, 
//     						"pickup" => $pickupAddress,
//     						"order_items" => $product_detail
//     					);
//     					$orderDetails = json_encode($order_data);
    					
//     					$curl = curl_init();
//     					curl_setopt_array($curl, array
//     					(
//     						  CURLOPT_URL => "https://api.nimbuspost.com/v1/shipments",
//     						  CURLOPT_RETURNTRANSFER => true,
//     						  CURLOPT_ENCODING => "",
//     						  CURLOPT_MAXREDIRS => 10,
//     						  CURLOPT_TIMEOUT => 0,
//     						  CURLOPT_FOLLOWLOCATION => true,
//     						  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//     						  CURLOPT_CUSTOMREQUEST => "POST",
//     						  CURLOPT_POSTFIELDS => $orderDetails,
//     						  CURLOPT_HTTPHEADER => array
//     						  (
//     							  "Content-Type: application/json",
//     							  "Authorization:Bearer ".$resultAuth['data']
//     						  ),
//     					));
//     					$result = curl_exec($curl);
//     					$result = json_decode($result, true); 
//     					curl_close($curl);
//     					return $result;
//     				}
// 				}
// 				else
// 				{
// 					$result['message'] = 'Product total weight and child products weight OR total box and child box are not match';
// 					$result['status'] = false;
// 					return $result;
// 				}
// 			}
// 			else
// 			{
// 				$result['message'] = 'Authentication failed..!';
// 				$result['status'] = false;
// 				return $result;
// 			}
// 		}
    }
    public function trackShipment($awbNo,$mapArray)
    {
            $resultAuth = $this->app_login($mapArray);
            #dd($awbNo);
			if($resultAuth['status'])
			{
			    $shipmentAWB = array();
				$shipmentAWB['awb'] =  $awbNo;
				$orderDetails = json_encode($shipmentAWB);
				$curl = curl_init();
				curl_setopt_array($curl, array
				(
					  CURLOPT_URL => "https://api.nimbuspost.com/v1/shipments/track/bulk",
					  CURLOPT_RETURNTRANSFER => true,
					  CURLOPT_ENCODING => "",
					  CURLOPT_MAXREDIRS => 10,
					  CURLOPT_TIMEOUT => 0,
					  CURLOPT_FOLLOWLOCATION => true,
					  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					  CURLOPT_CUSTOMREQUEST => "POST",
					  CURLOPT_POSTFIELDS => $orderDetails,
					  CURLOPT_HTTPHEADER => array
					  (
						  "Content-Type: application/json",
						  "Authorization:Bearer ".$resultAuth['data']
					  ),
				));
				$result = curl_exec($curl);
				$result = json_decode($result, true); 
				curl_close($curl);
				#dd($result);
				return $result;
			}
    }
    public function trackSingleShipment($awbNo,$mapArray)
    {
		
            #dd($awbNo);
            $userAuth = array
			(
				'email' => ''.$mapArray['user_name'],
 				'password' => ''.$mapArray['password']
			);
			#dd($userAuth);
			$loginNimbus = json_encode($userAuth);
			$url = 'https://api.nimbuspost.com/v1/users/login';
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL, $url);
			curl_setopt($ch,CURLOPT_POSTFIELDS, $loginNimbus);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
			$resultAuth = curl_exec($ch);
			$resultAuth = json_decode($resultAuth, true);
			#dd($resultAuth);
			curl_close($ch);
			if($resultAuth['status'])
			{
			    $shipmentAWB = array();
				$shipmentAWB['awb'][0] =  $awbNo;
				#dd($shipmentAWB['awb']);
				$orderDetails = json_encode($shipmentAWB);
				#dd($orderDetails);
				$curl = curl_init();
				curl_setopt_array($curl, array
				(
					  CURLOPT_URL => "https://api.nimbuspost.com/v1/shipments/track/bulk",
					  CURLOPT_RETURNTRANSFER => true,
					  CURLOPT_ENCODING => "",
					  CURLOPT_MAXREDIRS => 10,
					  CURLOPT_TIMEOUT => 0,
					  CURLOPT_FOLLOWLOCATION => true,
					  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					  CURLOPT_CUSTOMREQUEST => "POST",
					  CURLOPT_POSTFIELDS => $orderDetails,
					  CURLOPT_HTTPHEADER => array
					  (
						  "Content-Type: application/json",
						  "Authorization:Bearer ".$resultAuth['data']
					  ),
				));
				$result = curl_exec($curl);
				$result = json_decode($result, true); 
				curl_close($curl);
				return $result;
			}
    }
    public function cancelledShipment($shipment,$mapArray)
    {
        if($shipment !='')
        {
            $resultAuth = $this->app_login($mapArray);
			if($resultAuth['status'])
			{    
                $shipmentAwb = array
        		(
        			'awb' => $shipment
        		);
        		$shiomentCancelled = json_encode($shipmentAwb);
                $curl = curl_init();
				curl_setopt_array($curl, array
				(
					  CURLOPT_URL => "https://api.nimbuspost.com/v1/shipments/cancel",
					  CURLOPT_RETURNTRANSFER => true,
					  CURLOPT_ENCODING => "",
					  CURLOPT_MAXREDIRS => 10,
					  CURLOPT_TIMEOUT => 0,
					  CURLOPT_FOLLOWLOCATION => true,
					  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					  CURLOPT_CUSTOMREQUEST => "POST",
					  CURLOPT_POSTFIELDS => $shiomentCancelled,
					  CURLOPT_HTTPHEADER => array
					  (
						  "Content-Type: application/json",
						  "Authorization:Bearer ".$resultAuth['data']
					  ),
				));
        		$result = curl_exec($curl);
        		$result = json_decode($result, true);
        		#dd($result);
        		curl_close($curl);
        		return $result;
			}
        }
    }
    public function serviceability($shipment,$mapArray)
    {
            $resultAuth = $this->app_login($mapArray);
			if($resultAuth['status'])
			{
			    $curl = curl_init();
        		curl_setopt_array($curl, array
        		(
        			  CURLOPT_URL => "https://api.nimbuspost.com/v1/courier/serviceability",
        			  CURLOPT_RETURNTRANSFER => true,
        			  CURLOPT_ENCODING => "",
        			  CURLOPT_MAXREDIRS => 10,
        			  CURLOPT_TIMEOUT => 0,
        			  CURLOPT_FOLLOWLOCATION => true,
        			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        			  CURLOPT_CUSTOMREQUEST => "POST",
        			  CURLOPT_POSTFIELDS => $shipment,
        			  CURLOPT_HTTPHEADER => array
        			  (
        				  "Content-Type: application/json",
        				  "Authorization:Bearer ".$resultAuth['data']
        			  ),
        		));
        		$result = curl_exec($curl);
        		$result = json_decode($result, true); 
        		curl_close($curl);
        		return $result;
			}
    }
    public function serviceabilitylist($mapArray)
    {
            $resultAuth = $this->app_login($mapArray);
			if($resultAuth['status'])
			{
			    $curl = curl_init();
        		curl_setopt_array($curl, array
        		(
        			  CURLOPT_URL => "https://api.nimbuspost.com/v1/courier/serviceability",
        			  CURLOPT_RETURNTRANSFER => true,
        			  CURLOPT_ENCODING => "",
        			  CURLOPT_MAXREDIRS => 10,
        			  CURLOPT_TIMEOUT => 0,
        			  CURLOPT_FOLLOWLOCATION => true,
        			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        			  CURLOPT_CUSTOMREQUEST => "GET",
        			  CURLOPT_POSTFIELDS => "",
        			  CURLOPT_HTTPHEADER => array
        			  (
        				  "Content-Type: application/json",
        				  "Authorization:Bearer ".$resultAuth['data']
        			  ),
        		));
        		$result = curl_exec($curl);
        		$result = json_decode($result, true); 
        		curl_close($curl);
        		return $result;
			}
    }
    public function courier($mapArray)
    {
            $resultAuth = $this->app_login($mapArray);
			if($resultAuth['status'])
			{
			    $curl = curl_init();
        		curl_setopt_array($curl, array
        		(
        			  CURLOPT_URL => "https://api.nimbuspost.com/v1/courier",
        			  CURLOPT_RETURNTRANSFER => true,
        			  CURLOPT_ENCODING => "",
        			  CURLOPT_MAXREDIRS => 10,
        			  CURLOPT_TIMEOUT => 0,
        			  CURLOPT_FOLLOWLOCATION => true,
        			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        			  CURLOPT_CUSTOMREQUEST => "GET",
        			  CURLOPT_POSTFIELDS => "",
        			  CURLOPT_HTTPHEADER => array
        			  (
        				  "Content-Type: application/json",
        				  "Authorization:Bearer ".$resultAuth['data']
        			  ),
        		));
        		$result = curl_exec($curl);
        		$result = json_decode($result, true); 
        		curl_close($curl);
        		return $result;
			}
    }
    public function ndr_shipment($mapArray)
    {
            $resultAuth = $this->app_login($mapArray);
			if($resultAuth['status'])
			{
			    $curl = curl_init();
				curl_setopt_array($curl, array
				(
					  CURLOPT_URL => "https://api.nimbuspost.com/v1/ndr",
					  CURLOPT_RETURNTRANSFER => true,
					  CURLOPT_ENCODING => "",
					  CURLOPT_MAXREDIRS => 10,
					  CURLOPT_TIMEOUT => 0,
					  CURLOPT_FOLLOWLOCATION => true,
					  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					  CURLOPT_CUSTOMREQUEST => "GET",
					  CURLOPT_POSTFIELDS => "",
					  CURLOPT_HTTPHEADER => array
					  (
						  "Content-Type: application/json",
						  "Authorization:Bearer ".$resultAuth['data']
					  ),
				));
				$result = curl_exec($curl);
				$result = json_decode($result, true); 
				curl_close($curl);
				#dd($result);
				return $result;
			}
    }
	public function ndr_processed($ndrdata,$mapArray)
    {
		    /*
            curl --location 'https://api.nimbuspost.com/v1/ndr/action' \
            --header 'Content-Type: application/json' \
            --data '[
                {
                    "awb" : "NMBC0002111111",
                    "action" : "re-attempt",
                    "action_data" : {
                        "re_attempt_date" : "2021-06-03"
                    }
            
                },
                {
                    "awb" : "NMBC0002111112",
                    "action" : "change_address",
                    "action_data" : {
                        "name" : "customer name here",
                        "address_1" : "Address 1 here",
                        "address_2" : "Address 2 here"
                    }
            
                },
                {
                    "awb" : "NMBC0002111113",
                    "action" : "change_phone",
                    "action_data" : {
                        "phone" : "9999999999"
                    }
            
                }
            ]'
            */
            $resultAuth = $this->app_login($mapArray);
			if($resultAuth['status'])
			{
				#dd($ndrdata);
			    $orderDetails = json_encode($ndrdata);
				$curl = curl_init();
				curl_setopt_array($curl, array
				(
					  CURLOPT_URL => "https://api.nimbuspost.com/v1/ndr/action",
					  CURLOPT_RETURNTRANSFER => true,
					  CURLOPT_ENCODING => "",
					  CURLOPT_MAXREDIRS => 10,
					  CURLOPT_TIMEOUT => 0,
					  CURLOPT_FOLLOWLOCATION => true,
					  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					  CURLOPT_CUSTOMREQUEST => "POST",
					  CURLOPT_POSTFIELDS => $orderDetails,
					  CURLOPT_HTTPHEADER => array
					  (
						  "Content-Type: application/json",
						  "Authorization:Bearer ".$resultAuth['data']
					  ),
				));
				$result = curl_exec($curl);
				$result = json_decode($result, true); 
				curl_close($curl);
				#dd($result);
				return $result;
			}
    }
    public function manifest($awbNo,$mapArray)
    {
		   /*
		   curl --location 'https://api.nimbuspost.com/v1/shipments/manifest' \
            --header 'Content-Type: application/json' \
            --data '{
                "awbs": [
                    "4152911775885",
                    "NMBC0001789312"
                ]
            }*/
            $resultAuth = $this->app_login($mapArray);
			if($resultAuth['status'])
			{
			    $manifest = array();
                $manifest['awbs'][0] = $awbNo;
			    $manifestNo = json_encode($manifest);
			    $curl = curl_init();
				curl_setopt_array($curl, array
				(
					  CURLOPT_URL => "https://api.nimbuspost.com/v1/shipments/manifest",
					  CURLOPT_RETURNTRANSFER => true,
					  CURLOPT_ENCODING => "",
					  CURLOPT_MAXREDIRS => 10,
					  CURLOPT_TIMEOUT => 0,
					  CURLOPT_FOLLOWLOCATION => true,
					  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					  CURLOPT_CUSTOMREQUEST => "POST",
					  CURLOPT_POSTFIELDS => $manifestNo,
					  CURLOPT_HTTPHEADER => array
					  (
						  "Content-Type: application/json",
						  "Authorization:Bearer ".$resultAuth['data']
					  ),
				));
				$result = curl_exec($curl);
				$result = json_decode($result, true); 
				curl_close($curl);
				#dd($result);
				return $result;
			}
    }
    function is_JSON($string)
	{
		return (is_null(json_decode($string))) ? FALSE : TRUE;
	}
	function webhook_response()
	{
		/*Nimbus
		{ "awb_number": "4152912381315", "status": "in transit", "event_time": "2021-02-26 16:19:59", "location": "Delhi", "message": "Reached at nearest hub", "rto_awb": "" }
		';
		$json = json_decode($json_data);
		echo  print_r(' Status- '.$json->Remarks.' AWBNO- '.$json->AWBNO .' StatusCode-> '.$json->StatusCode) ;
		*/
		$json = trim(file_get_contents('php://input'));
		$check_json = $this->is_JSON($json);
		if($check_json)
		{
			$json = json_decode($json);
			if($json->awb_number)
			{
				/*Update status of order*/
				$recordExist = Order::where('awb_no', $json->awb_number)
								->where('request_partner','NimbusApp')
								->select('id','awb_no','order_status','order_no')
								->first();
				if($recordExist)
				{					
					if($recordExist->awb_no!='')
					{
						$recordExist->order_status = $json->status;
						$recordExist->remarks = $json->message.' at '.$json->event_time;
						$recordExist->save();
					}
				}
				return $json;
			}
		}
	}
}