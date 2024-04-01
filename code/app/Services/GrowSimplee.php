<?php

namespace App\Services;

use App\Interfaces\AppOrderProcessInterface;
use App\Models\Order;
class GrowSimplee implements AppOrderProcessInterface
{
    public function app_login($mapArray)
	{
	}
    public function processOrder($shipment,$mapArray)
    {
	    echo "GrowSimplee";die;
		$userAuth = array
		(
			'username' => ''.$mapArray[0]['user_name'],
			'password' => ''.$mapArray[0]['password']
		);
		$login_data = json_encode($userAuth);
		try
		{
			#https://xv24xrhpxa.execute-api.ap-south-1.amazonaws.com/v1/waybill//' 
			$curl = curl_init();
			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://xv24xrhpxa.execute-api.ap-south-1.amazonaws.com/v1/authToken",
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 0,
			  CURLOPT_FOLLOWLOCATION => true,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "POST",
			  CURLOPT_POSTFIELDS =>$login_data,
			  CURLOPT_HTTPHEADER => array(
			  "Content-Type: application/json"
			  ),
			));
			$response = curl_exec($curl);
			curl_close($curl);
			$res = json_decode($response);
			$token = null;
			#Get login Authentication token
			$token = $res->token;
			if($res->token)
			{
				/*
				 Type : POST
				 https://xv24xrhpxa.execute-api.ap-south-1.amazonaws.com/v1/waybill//
				  {
					"serviceType": "String",
					"handOverMode": "String",
					"returnShipmentFlag": "false",
					"Shipment": 
					{
						"code": "String",
						"orderCode": "Taker292",
						"orderDate": "Date",
						"fullFilllmentTat": "Date",
						"weight": "150",
						"length": "30",
						"height": "10",
						"breadth": "15",
						"items": 
						[
							{
								"name": "String",
								"description": "String",
								"quantity": "2"
							},
							{
								"name": "String",
								"description": "String",
								"quantity": "1"
							}
						]
					},
					"deliveryAddressId": "String",
					"deliveryAddressDetails": 
					{
						"name": "dd",
						"email": "saurabh@gmail.com",
						"phone": "8888888888",
						"address1": "String",
						"address2": "String",
						"pincode": "560037",
						"city": "Gurgaon",
						"state": "Haryana",
						"country": "India"
					},
					"pickupAddressId": "String",
					"pickupAddressDetails": 
					{
						"name": "ee",
						"email": "String",
						"phone": "9999999999",
						"address": "String",
						"address2": "String",
						"pincode": "560037",
						"city": "Mumbai",
						"state": "Maharashtra",
						"country": "India"
					},
					"currencyCode": "INR",
					"paymentMode": "PREPAID",
					"totalAmount": "200.00",
					"collectableAmount": "200",
					"courierName": "String"
				}
				*/
				$token =  $res->token; 
				$i = 0;
				$product_detail = array();
				foreach ($shipment->products as $product) 
				{
					#dd($product);
					$product_detail[$i]['name'] = $product['product_name']?$product['product_name']:'';
					$product_detail[$i]['skuCode'] = $product['product_code']?$product['product_code']:'';
					$product_detail[$i]['description'] = $product['product_code']?$product['product_code']:'';
					$product_detail[$i]['quantity'] = $product['product_quantity']?$product['product_quantity']:0;
					$product_detail[$i]['item_value'] = $product['product_price']?$product['product_price']:0.00;
					$i++;
				}
				try
				{
					$order_data = array
					(
						"serviceType" => "Order",
						"handOverMode" => "Yes",
						"returnShipmentFlag" => "false",
						'Shipment' => array
						(
								'code' => $shipment->input('order_no'),
								'orderCode' => $shipment->input('order_no'),
								'orderDate' => date('Y-m-d'),
								'fullFilllmentTat' => date('Y-m-d'),
								'weight' => $shipment->input('total_weight'),
								'length' => $shipment->input('length'),
								'height' => $shipment->input('breadth'),
								'breadth' => $shipment->input('height'),
								'items' => $product_detail
						),
						'deliveryAddressDetails' => array
						 (
							'name' => $shipment->input('shipping_consignee.consignee_name'),
							'email' => $shipment->input('shipping_consignee.consignee_email'),
							'phone'=> $shipment->input('shipping_consignee.consignee_phone'),
							'address1'=> preg_replace('/[^a-zA-Z0-9_ -]/s',' ',$shipment->input('shipping_consignee.consignee_address')),
							'address2'=> preg_replace('/[^a-zA-Z0-9_ -]/s',' ',$shipment->input('shipping_consignee.consignee_address_2')),
							'pincode'=> $shipment->input('shipping_consignee.consignee_pincode'),
							'city'=> $shipment->input('shipping_consignee.consignee_city'),
							'state'=> $shipment->input('shipping_consignee.consignee_state'),
							'country'=> "INDIA"
						),
						'pickupAddressDetails' => array
						(
							'name' => $shipment->input('origin.origin_code'),
							'email' => '',
							'phone'=> $shipment->input('origin.origin_phone'),
							'address1'=> $shipment->input('origin.origin_address'),
							'address2'=> $shipment->input('origin.origin_address_2'),
							'pincode'=> $shipment->input('origin.origin_pincode'),
							'city'=> $shipment->input('origin.origin_city'),
							'state'=> $shipment->input('origin.origin_state'),
							'country'=> 'India'
						),
						"currencyCode" => "INR",
						"paymentMode"  => $shipment->input('payment_mode'),
						"totalAmount"  => ''.round($shipment->input('total_amount'),2),
						"collectableAmount"  => $shipment->input('cod_amount')?$shipment->input('cod_amount'):0,
						"courierName"  => "GROW Simplee"
					);
					$orderDetails = json_encode($order_data);
					#dd();
					$curl = curl_init();
					curl_setopt_array($curl, array(
					  CURLOPT_URL => "https://xv24xrhpxa.execute-api.ap-south-1.amazonaws.com/v1/waybill/",
					  //CURLOPT_URL => "http://Grow-Simplee-NLB-Prod-a264c46571856f67.elb.ap-south-1.amazonaws.com/api/shipping/",
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
						  "Authorization: Bearer {$token}"
					  ),
					));
					$response = curl_exec($curl);
					curl_close($curl);
					$res = json_decode($response);
					return $res; 
				}
				catch (\Exception $e) 
				{
				  echo $e;
				}
			}
			else
			{
				echo 'No Auth';
			}
		}
		catch (\Exception $e) 
		{
		  echo $e;
		}
	}
	 public function reprocessOrder($shipment,$mapArray)
    {
        echo "GrowSimplee";die;
    }
    public function processShipOrder($shipmentDetail,$mapArray){}
	function track_grow($awb_no="SF112101533GRS")
    {
		$userAuth = array
		(
			'username' => ''.$mapArray[0]['user_name'],
			'password' => ''.$mapArray[0]['password']
		);
        $login_data = json_encode($userAuth);
        try
        {
            /*
              Request
                curl --location --request GET
                'https://xv24xrhpxa.execute-api.ap-south-1.amazonaws.com/v1/waybillDetails/?waybills="8622112249774"' \
                --header 'Authorization: {token}'
            */
            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => "https://xv24xrhpxa.execute-api.ap-south-1.amazonaws.com/v1/authToken",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "POST",
              CURLOPT_POSTFIELDS =>$login_data,
              CURLOPT_HTTPHEADER => array(
              "Content-Type: application/json"
              ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $res = json_decode($response);
            $token = null;
            #Get login Authentication token
            $token = $res->token;
            if($res->token)
            {
                $token =  $res->token; 
                #echo 'https://xv24xrhpxa.execute-api.ap-south-1.amazonaws.com/v1/waybillDetails/?waybills="SF112101533GRS"';
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  #CURLOPT_URL => 'https://xv24xrhpxa.execute-api.ap-south-1.amazonaws.com/v1/waybillDetails/?waybills="SF112101533GRS"',
                  CURLOPT_URL => 'https://xv24xrhpxa.execute-api.ap-south-1.amazonaws.com/v1/waybillDetails/?waybills="'.$awb_no.'"',
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => "GET",
                  CURLOPT_HTTPHEADER => array
                  (
                      "Content-Type: application/json",
                      "Authorization: {$token}"
                  ),
                ));
                $response = curl_exec($curl);
                curl_close($curl);
                $res = json_decode($response);
            }
        }
        catch (\Exception $e) 
        {
          echo $e;
        }
    }
    function track_grow_full($awb_no="SF112101533GRS")
    {
		$userAuth = array
		(
			'username' => ''.$mapArray[0]['user_name'],
			'password' => ''.$mapArray[0]['password']
		);
        $login_data = json_encode($userAuth);
        try
        {
            /*
              Request
                curl --location --request GET
                'https://xv24xrhpxa.execute-api.ap-south-1.amazonaws.com/v1/waybillDetails/?waybills="8622112249774"' \
                --header 'Authorization: {token}'
            */
            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => "https://xv24xrhpxa.execute-api.ap-south-1.amazonaws.com/v1/authToken",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "POST",
              CURLOPT_POSTFIELDS =>$login_data,
              CURLOPT_HTTPHEADER => array(
              "Content-Type: application/json"
              ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $res = json_decode($response);
            $token = null;
            #Get login Authentication token
            $token = $res->token;
            if($res->token)
            {
                $token =  $res->token; 
                $track_details =
                [
                    'field' => 'shipment',
        			'value' => $awb_no
                ];
                $trackD = json_encode($track_details);
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => 'https://oyvm2iv4xj.execute-api.ap-south-1.amazonaws.com/v1/tracking',
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => "POST",
                  CURLOPT_POSTFIELDS =>$trackD,
                  CURLOPT_HTTPHEADER => array
                  (
                      "Content-Type: application/json",
                      "Authorization: {$token}"
                  ),
                ));
                $response = curl_exec($curl);
                curl_close($curl);
                $res = json_decode($response);
                $grwoArr = get_object_vars($res);
                $grwoArr2 = get_object_vars($grwoArr['result'][0]);
                if($grwoArr2['tracking'][0])
                {
                    #echo "<pre>";
                    #print_r($grwoArr2['tracking'][0]);
                    return $grwoArr2['tracking'][0];
                }
            }
        }
        catch (\Exception $e) 
        {
          echo $e;
        }
    }
    public function trackShipment($awbNo,$mapArray)
    {
            
    }
    public function trackSingleShipment($awbNo,$mapArray)
    {
		
        
    }
    public function cancelledShipment($shipment,$mapArray)
    {
       
    }
    public function serviceability($shipment,$mapArray)
    {
           
    }
    public function serviceabilitylist($mapArray)
    {
            
    }
    public function courier($mapArray)
    {
           
    }
    public function ndr_shipment($mapArray)
    {
           
    }
	public function ndr_processed($ndrdata,$mapArray)
    {
		    
    }
    public function manifest($awbNo,$mapArray)
    {
		
    }
    function is_JSON($string)
	{
		
	}
	function webhook_response()
	{
		
	}
}