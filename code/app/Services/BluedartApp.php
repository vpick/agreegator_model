<?php

namespace App\Services;

use App\Interfaces\AppOrderProcessInterface;
use App\Models\Order;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use DOMDocument;
use DOMXPath;
class BluedartApp implements AppOrderProcessInterface
{
    public function app_login($mapArray)
	{
	   
		$userAuth = array
		(
			'ClientID' => ''.$mapArray['auth_key'],
			'clientSecret' => ''.$mapArray['auth_secret']
		);
		$auth_key = $userAuth['ClientID'];
		$auth_secret = $userAuth['clientSecret'];
		$curl = curl_init();
        curl_setopt_array($curl, [
        	CURLOPT_URL => "https://apigateway-sandbox.bluedart.com/in/transportation/token/v1/login",
        	CURLOPT_RETURNTRANSFER => true,
        	CURLOPT_ENCODING => "",
        	CURLOPT_MAXREDIRS => 10,
        	CURLOPT_TIMEOUT => 30,
        	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        	CURLOPT_CUSTOMREQUEST => "GET",
        	CURLOPT_HTTPHEADER => [
        		"ClientID: $auth_key",
        		"clientSecret: $auth_secret"
        	],
        ]);
        
        $response = curl_exec($curl);
       
        $err = curl_error($curl);
        curl_close($curl);
        
        if ($err) {
        	echo "cURL Error #:" . $err;
        } else {
        	return $response;
        }
	}

    public function processOrder($shipment,$mapArray)
    {
		if(!empty($shipment))
		{
			$token = $this->app_login($mapArray);
			
			if($token)
			{
			    $tokenData = json_decode($token);
                if ($tokenData->JWTToken !='')
                {
    				$boxInOrder =  $shipment->input('no_of_boxes');
            		$boxTotalWeight = $shipment->input('total_weight');
    				$productTotalWeight = array_sum(array_column($shipment->products,'product_weight'));
    				$productTotalBox = array_sum(array_column($shipment->products,'no_of_box'));
    				$productTotalQuantity = array_sum(array_column($shipment->products,'product_quantity'));
                    
    				$Items = array(); // Initialize the $Items array
                    $i = 0;
                    $created_date = time() * 1000;
                    $order_date = '/Date('.$created_date.')/';
                    $comodities = [];
    				foreach($shipment->products as $product) 
    				{
                        $Items[$i]['CGSTAmount'] = (float)$product['cgst_amount'] ? (float)$product['cgst_amount']:0;
                        $Items[$i]['HSCode'] = $product['product_hsn_code'] ? $product['product_hsn_code'] : '';
                        $Items[$i]['IGSTAmount'] = (float)$product['igst_amount']?$product['igst_amount']:0;
                        $Items[$i]['IGSTRate'] = (float)$product['igst_rate'] ? (float)$product['igst_rate']:0;
                        $Items[$i]['Instruction'] = $product['Instruction'] ? $product['Instruction'] : '';
                        $Items[$i]['InvoiceDate'] = $order_date;
                        $Items[$i]['InvoiceNumber'] = $product['invoice_no'] ? $product['invoice_no'] : '';
                        $Items[$i]['ItemID'] = $product['product_code'] ? $product['product_code'] : '';
                        $Items[$i]['ItemName'] = $product['product_name'] ? $product['product_name'] : '';
                        $Items[$i]['ItemValue'] = (float)$product['product_price'] ? (float)$product['product_price'] : 0.0;
                        $Items[$i]['Itemquantity'] = (float)$product['product_quantity'] ? (float)$product['product_quantity'] : 1;
                        $Items[$i]['PlaceofSupply'] = '';
                        $Items[$i]['ProductDesc1'] = $product['product_name'] ? $product['product_name'] : '';
                        $Items[$i]['ProductDesc2'] = $product['Instruction'] ? $product['Instruction'] : '';
                        $Items[$i]['ReturnReason'] = '';
                        $Items[$i]['SGSTAmount'] = (float)$product['SGSTAmount']? (float)$product['SGSTAmount']: 0;
                        $Items[$i]['SKUNumber'] = $product['SKUNumber']? $product['SKUNumber']: '';
                        $Items[$i]['SellerGSTNNumber'] = '';
                        $Items[$i]['SellerName'] = '';
                        $Items[$i]['TaxableAmount'] = (float)$product['TaxableAmount']? (float)$product['TaxableAmount']: 0;
                        $Items[$i]['TotalValue'] =  (float)$product['TotalValue']? (float)$product['TotalValue']: 0;
                        $Items[$i]['cessAmount'] = 0;
                        $Items[$i]['countryOfOrigin'] = '';
                        $Items[$i]['docType'] = '';
                        $Items[$i]['subSupplyType'] = 0;
                        $Items[$i]['supplyType'] = '';
                        $comodities['CommodityDetail'.$i] = $Items[$i]['ItemName'];
                        $i++;
                    }
                    $total_amount = round($shipment->input('total_amount'),2);  
                    $SubProductCode='';
                    if($shipment->input('order_type') == 'B2B')
                    {
    				    $SubProductCode ='';
    				    $total_amountCollect=0;
    				}
    				else
    				{
    				    $total_amountCollect = 0;
                        $pay_method='';
                        if($shipment->input('payment_mode')=='COD')
                        {
                            $total_amountCollect = $total_amount + $shipment->input('shipping_charges');
                            $pay_method = 'C';
                        }
                        else
                        {
                            $pay_method = 'P';
                        }    
                        $SubProductCode = $pay_method;
    				}
                    
                    $ordAmount = $total_amount;
                    $order_data = array(
                        "Request" => array(
                            "Consignee" => array(
                                "AvailableDays" => "",
                                "AvailableTiming" => "",
                                "ConsigneeAddress1" => preg_replace('/[^a-zA-Z0-9_ -]/s',' ',$shipment->input('shipping_consignee.consignee_address')),
                                "ConsigneeAddress2" => preg_replace('/[^a-zA-Z0-9_ -]/s',' ',$shipment->input('shipping_consignee.consignee_address_2')),
                                "ConsigneeAddress3" => "",
                                "ConsigneeAddressType" => "",
                                "ConsigneeAddressinfo" => "",
                                "ConsigneeAttention" => "",
                                "ConsigneeEmailID" => $shipment->input('shipping_consignee.consignee_email'),
                                "ConsigneeFullAddress" => "",
                                "ConsigneeGSTNumber" => "",
                                "ConsigneeLatitude" => "",
                                "ConsigneeLongitude" => "",
                                "ConsigneeMaskedContactNumber" => "",
                                "ConsigneeMobile" => $shipment->input('shipping_consignee.consignee_phone'),
                                "ConsigneeName" => $shipment->input('shipping_consignee.consignee_name'),
                                "ConsigneePincode" => $shipment->input('shipping_consignee.consignee_pincode'),//"400703",
                                "ConsigneeTelephone" => $shipment->input('shipping_consignee.consignee_phone'),
                                
                            ),
                            "Returnadds" => array(
                                "ManifestNumber" => "",
                                "ReturnAddress1" => $shipment->input('origin.origin_address'),
                                "ReturnAddress2" => $shipment->input('origin.origin_address_2'),
                                "ReturnAddress3" => "",
                                "ReturnAddressinfo" => "",
                                "ReturnContact" => $shipment->input('origin.origin_code'),
                                "ReturnEmailID" => '',
                                "ReturnLatitude" => "",
                                "ReturnLongitude" => "",
                                "ReturnMaskedContactNumber" => "",
                                "ReturnMobile" => $shipment->input('origin.origin_phone'),
                                "ReturnPincode" => $shipment->input('origin.origin_pincode'), //"400057",
                                "ReturnTelephone" => $shipment->input('origin.origin_phone')
                            ),
                            "Services" => array(
                                "AWBNo" => "",
                                "ActualWeight" => (float)$shipment->input('total_weight')/1000,
                                "CollectableAmount" => $shipment->input('cod_amount')?$shipment->input('cod_amount'):0,
                                "Commodity" => $comodities,
                                "CreditReferenceNo" => $shipment->input('order_no'),
                                "CreditReferenceNo2" => "",
                                "CreditReferenceNo3" => "",
                                "DeclaredValue" => (float)$ordAmount?(float)$ordAmount:1,
                                "DeliveryTimeSlot" => "",
                                "Dimensions" => array(
                                    array(
                                        "Breadth" => $shipment->input('length'),
                                        "Count" => 1, //$shipment->input('total_quantity'),
                                        "Height" => $shipment->input('breadth'),
                                        "Length" => $shipment->input('height')
                                    )
                                    
                                ),
                                
                                "FavouringName" => "",
                                "ForwardAWBNo" => "",
                                "ForwardLogisticCompName" => "",
                                "InsurancePaidBy" => "",
                                "InvoiceNo" =>'', //$shipheader_data['no'],
                                "IsChequeDD" => "",
                                "IsForcePickup" => false,
                                "IsPartialPickup" => false,
                                "IsReversePickup" => false,
                                "ItemCount" => $productTotalQuantity,
                                "OTPBasedDelivery"=> "0",
                                "OTPCode" =>"",
                                "Officecutofftime"=> "",
                               
                                "PDFOutputNotRequired" => false,
                                "PackType" => "",
                                "ParcelShopCode" => "",
                                "PayableAt" => "",
                                "PayerGSTVAT" => 0.0,
                                "PickupDate" => $order_date,
                                "PickupMode" => "",
                                "PickupTime" => "1600",
                                "PickupType" => "",
                                "PieceCount" => "1",
                                "PreferredPickupTimeSlot" => "",
                                "ProductCode" => $mapArray['product_code'],
                                "ProductFeature" => "",
                                "ProductType" => 1,
                                "RegisterPickup" => false,
                                "SpecialInstruction" => $shipment->input('invoice.invoice_number'),
                                "SubProductCode" => $SubProductCode,
                                "TotalCashPaytoCustomer" => 0.0,
                                "itemdtl"=> $Items,
                                "noOfDCGiven" => 0
                            ),
                            "Shipper" => array(
                                "CustomerAddress1" => $shipment->input('origin.origin_address'),
                                "CustomerAddress2" => $shipment->input('origin.origin_address_2'),
                                "CustomerAddress3" => "",
                                "CustomerAddressinfo" => "",
                                "CustomerCode" => $mapArray['customer_code'],
                                "CustomerEmailID" => $shipment->input('origin.origin_email'),
                                "CustomerGSTNumber" => "",
                                "CustomerLatitude" => "",
                                "CustomerLongitude" => "",
                                "CustomerMaskedContactNumber" => "",
                                "CustomerMobile" => $shipment->input('origin.origin_phone'),
                                "CustomerName" => $shipment->input('origin.origin_name'),
                                "CustomerPincode" => '400080', //$shipment->input('origin.origin_pincode'),
                                "CustomerTelephone" => "",
                                "IsToPayCustomer" => false,
                                "OriginArea" => $mapArray['area'],
                                "Sender" => "",
                                "VendorCode" => ""
                            )
                        ),
                        "Profile" => array(
                            "Api_type" => $mapArray['api_type'],
                            "LicenceKey" =>$mapArray['licence_key'],
                            "LoginID" => $mapArray['login_id'],
                        )
                    );
                    $requestJson = json_encode($order_data);
                  
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => 'https://apigateway-sandbox.bluedart.com/in/transportation/waybill/v1/GenerateWayBill',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS =>$requestJson,
    
                       CURLOPT_HTTPHEADER => array
                       (
                         'Content-Type: application/json',
                         'JWTToken: '.$tokenData->JWTToken,
                       ),
                     ));
                        $response = curl_exec($curl);
                        curl_close($curl);
                        $data = json_decode($response, true);
                        if(isset($data['GenerateWayBillResult']))
                        {
                            $processedShipment = $data['GenerateWayBillResult'];
                            // Access the AWBNo
                            $awbNo = $processedShipment['AWBNo'];
                            $status = $processedShipment['IsError'];
                            $recieved_shipment_id = $processedShipment['CCRCRDREF'];
                            $labelURL = $processedShipment['AWBPrintContent'];
                            $statusCode = $processedShipment['Status'][0]['StatusCode'];
                            $msg = $processedShipment['Status'][0]['StatusInformation'];
                            // Add new variables to $data array
                            $data['awb_no'] = $processedShipment['AWBNo']?$processedShipment['AWBNo']:'';
                            $data['IsError'] = $processedShipment['IsError']?$processedShipment['IsError']:'';
                            if($data['IsError'] == false){
                                $status='success';
                            }
                            else{
                                $status='failed';
                            }
                            $data['status'] = $status;
                            $data['order_no'] = $processedShipment['CCRCRDREF']?$processedShipment['CCRCRDREF']:'';
                            if($processedShipment['AWBPrintContent']!='')
                            {
                                $label = $labelURL;  // Assuming $order->shipping_label is already an array
                                $pdfBinary = call_user_func_array('pack', array_merge(['C*'], $label));
                                $pdfBase64 = base64_encode($pdfBinary);
                                $pdfDataUri = 'data:application/pdf;base64,' . $pdfBase64;
                            }                    
                            else{
                                $pdfDataUri = '';
                            }
                            $data['shipping_label'] = $processedShipment['AWBPrintContent']?$processedShipment['AWBPrintContent']:'';
                            $data['StatusCode'] = $processedShipment['Status'][0]['StatusCode']?$processedShipment['Status'][0]['StatusCode']:'';
                            $data['message'] = $processedShipment['Status'][0]['StatusInformation']?$processedShipment['Status'][0]['StatusInformation']:'';
                            $data['courier_id'] =0;
                            $data['courier_name'] = "Bluedart";
                            $data['packing_slip'] = $pdfDataUri;
                            return $data;
                        }
                        else
                        {
                            $msg = '';
                            if(isset($data['error-response']))
                            {
                                if (isset($data['error-response'][0]['Status'])) {
                                    
                                    if($data['error-response'][0]['Status'][0]['StatusInformation'])
                                    {
                                        $msg = $data['error-response'][0]['Status'][0]['StatusInformation'];
                                    }
                                }
                            }
                            $result['message'] = $msg;
                            $result['courier_name'] = "Bluedart";
                            $result['status'] = false;
                			return $result;
                        }
        				
                    }
    			else
    			{
    				$result['message'] = 'Token not found..!';
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
		{
			$result['message'] = 'Order Detail Not found..!';
			$result['status'] = false;
			return $result;
		}
    }

    public function processShipOrder($shipmentDetail,$mapArray)
    {
        $jsonData = json_decode($shipmentDetail, true);
        $shipment = $jsonData['order'];
        $productData = $jsonData['product'];
        if(!empty($shipment))
		{
			$token = $this->app_login($mapArray);
			if($token)
			{
			    $tokenData = json_decode($token);
                if ($tokenData->JWTToken !='')
                {
    				$boxInOrder =  $shipment['no_of_box'];
    				
            		$boxTotalWeight = $shipment['total_weight'];
    				$productTotalWeight = array_sum(array_column($productData,'product_weight'));
    			
    				$productTotalBox = array_sum(array_column($productData,'no_of_box'));
    				$productTotalQuantity = array_sum(array_column($productData,'product_quantity'));
        
    				$Items = array(); // Initialize the $Items array
                    $i = 0;
                    $created_date = time() * 1000;
                    $order_date = '/Date('.$created_date.')/';
                    $comodities = [];
    				foreach($productData as $product) 
    				{
    				   
                        $Items[$i]['CGSTAmount'] = 0;
                        $Items[$i]['HSCode'] = $product['product_hsn_code'] ? $product['product_hsn_code'] : '';
                        $Items[$i]['IGSTAmount'] = 0;
                        $Items[$i]['IGSTRate'] = 0;
                        $Items[$i]['Instruction'] = $product['product_code'] ? $product['product_code'] : '';
                        $Items[$i]['InvoiceDate'] = $order_date;
                        $Items[$i]['InvoiceNumber'] = $shipment['invoice_no'] ? $shipment['invoice_no'] : '';
                        $Items[$i]['IsMEISS'] = "";
                        $Items[$i]['ItemID'] = $product['product_code'] ? $product['product_code'] : '';
                        $Items[$i]['ItemName'] = $product['product_description'] ? $product['product_description'] : '';
                        $Items[$i]['ItemValue'] = (float)$product['product_price']*(float)$product['product_quantity'] ? (float)$product['product_price']*(float)$product['product_quantity'] : 0.0;
                        $Items[$i]['Itemquantity'] = (float)$product['product_quantity'] ? (float)$product['product_quantity'] : 1;
                        $Items[$i]['PlaceofSupply'] = '';
                        $Items[$i]['ProductDesc1'] = $product['product_description'] ? $product['product_description'] : '';
                        $Items[$i]['ProductDesc2'] = $product['product_code'] ? $product['product_code'] : '';
                        $Items[$i]['ReturnReason'] = '';
                        $Items[$i]['SGSTAmount'] = 0;
                        $Items[$i]['SKUNumber'] = $product['product_code']? $product['product_code']: '';
                        $Items[$i]['SellerGSTNNumber'] = '';
                        $Items[$i]['SellerName'] = '';
                        $Items[$i]['TaxableAmount'] = 0;
                        $Items[$i]['TotalValue'] =  $Items[$i]['ItemValue'];
                        $Items[$i]['cessAmount'] = 0;
                        $Items[$i]['countryOfOrigin'] = '';
                        $Items[$i]['docType'] = '';
                        $Items[$i]['subSupplyType'] = 0;
                        $Items[$i]['supplyType'] = '';
                        $comodities['CommodityDetail'.$i] = $Items[$i]['ItemName'];
                        $i++;
                    }
                    $ordAmount = round($shipment['total_amount'],2);  
                    $total_amountCollect=0;
                    $SubProductCode='';
                    if($shipment['order_type'] == 'B2B')
                    {
    				   
    				    $SubProductCode ='';
    				    $total_amountCollect=0;
    				}
    				else
    				{
    				    
    				    $total_amountCollect = 0;
                        $payment_mode='';
                        if($shipment['payment_mode']=='COD')
                        {
                            $total_amountCollect = $ordAmount;
                            $payment_mode = 'C';
                        }
                        else
                        {
                            $payment_mode = 'P';
                        }    
                        $SubProductCode = $payment_mode;
    				}
                    $order_data = array(
                        "Request" => array(
                            "Consignee" => array(
                                "AvailableDays" => "",
                                "AvailableTiming" => "",
                                "ConsigneeAddress1" => preg_replace('/[^a-zA-Z0-9_ -]/s',' ',$shipment['shipping_address_1']),
                                "ConsigneeAddress2" => preg_replace('/[^a-zA-Z0-9_ -]/s',' ',$shipment['shipping_address_2']),
                                "ConsigneeAddress3" => "",
                                "ConsigneeAddressType" => "",
                                "ConsigneeAddressinfo" => "",
                                "ConsigneeAttention" => "",
                                "ConsigneeEmailID" => $shipment['shipping_email'],
                                "ConsigneeFullAddress" => "",
                                "ConsigneeGSTNumber" => "",
                                "ConsigneeLatitude" => "",
                                "ConsigneeLongitude" => "",
                                "ConsigneeMaskedContactNumber" => "",
                                "ConsigneeMobile" => $shipment['shipping_phone_number'],
                                "ConsigneeName" => $shipment['shipping_first_name'],
                                "ConsigneePincode" => $shipment['shipping_pincode'],
                                "ConsigneeTelephone" => $shipment['shipping_alternate_phone'],
                            ),
                            "Returnadds" => array(
                                "ManifestNumber" => "",
                                "ReturnAddress1" => $shipment['warehouse_address'],
                                "ReturnAddress2" => $shipment['warehouse_address_2'],
                                "ReturnAddress3" => "",
                                "ReturnAddressinfo" => "",
                                "ReturnContact" => $shipment['warehouse_code'],
                                "ReturnEmailID" => '',
                                "ReturnLatitude" => "",
                                "ReturnLongitude" => "",
                                "ReturnMaskedContactNumber" => "",
                                "ReturnMobile" => $shipment['warehouse_phone_number'],
                                "ReturnPincode" => $shipment['warehouse_pincode'], 
                                "ReturnTelephone" => $shipment['warehouse_phone_number']
                            ),
                            "Services" => array(
                                "AWBNo" => "",
                                "ActualWeight" => (float)$shipment['total_weight']/1000,
                                "CollectableAmount" => $total_amountCollect,
                                "Commodity" => $comodities,
                                "CreditReferenceNo" => $shipment['order_no'],
                                "CreditReferenceNo2" => "",
                                "CreditReferenceNo3" => "",
                                "DeclaredValue" => (float)$ordAmount?(float)$ordAmount:1,
                                "DeliveryTimeSlot" => "",
                                "Dimensions" => array(
                                    array(
                                        "Breadth" => $shipment['length'],
                                        "Count" => 1, 
                                        "Height" => $shipment['breadth'],
                                        "Length" => $shipment['height']
                                    )
                                    
                                ),
                                
                                "FavouringName" => "",
                                "ForwardAWBNo" => "",
                                "ForwardLogisticCompName" => "",
                                "InsurancePaidBy" => "",
                                "InvoiceNo" =>'', 
                                "IsChequeDD" => "",
                                "IsForcePickup" => false,
                                "IsPartialPickup" => false,
                                "IsReversePickup" => false,
                                "ItemCount" => $productTotalQuantity,
                                "OTPBasedDelivery"=> "0",
                                "OTPCode"=> "",
                                "Officecutofftime"=> "",
                                "PDFOutputNotRequired" => false,
                                "PackType" => "",
                                "ParcelShopCode" => "",
                                "PayableAt" => "",
                                "PayerGSTVAT" => 0.0,
                                "PickupDate" => $order_date,
                                "PickupMode" => "",
                                "PickupTime" => "1600",
                                "PickupType" => "",
                                "PieceCount" => "1",
                                "PreferredPickupTimeSlot" => "",
                                "ProductCode" => $mapArray['product_code'],
                                "ProductFeature" => "",
                                "ProductType" => 1,
                                "RegisterPickup" => false,
                                "SpecialInstruction" => $shipment['invoice_no'],
                                "SubProductCode" => $payment_mode,
                                "itemdtl"=> $Items,
                                "noOfDCGiven" => 0
                            ),
                            "Shipper" => array(
                                "CustomerAddress1" => $shipment['warehouse_address'],
                                "CustomerAddress2" => $shipment['warehouse_address_2'],
                                "CustomerAddress3" => "",
                                "CustomerAddressinfo" => "",
                                "CustomerCode" => $mapArray['customer_code'],
                                "CustomerEmailID" => '',
                                "CustomerGSTNumber" => "",
                                "CustomerLatitude" => "",
                                "CustomerLongitude" => "",
                                "CustomerMaskedContactNumber" => "",
                                "CustomerMobile" =>  $shipment['warehouse_phone_number'],
                                "CustomerName" => $shipment['warehouse_name'],
                                "CustomerPincode" => $shipment['warehouse_pincode'],
                                "CustomerTelephone" => "",
                                "IsToPayCustomer" => false,
                                "OriginArea" => $mapArray['area'],
                                "Sender" => "",
                                "VendorCode" => ""
                            )
                        ),
                        "Profile" => array(
                            "Api_type" => $mapArray['api_type'],
                            "LicenceKey" => $mapArray['licence_key'],
                            "LoginID" => $mapArray['login_id'],
                        )
                         
                    );
                    $requestJson = json_encode($order_data);
                    
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => 'https://apigateway-sandbox.bluedart.com/in/transportation/waybill/v1/GenerateWayBill',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS =>$requestJson,
    
                       CURLOPT_HTTPHEADER => array
                       (
                         'Content-Type: application/json',
                         'JWTToken: '.$tokenData->JWTToken,
                       ),
                     ));
                
    
                        $response = curl_exec($curl);
                      
                        curl_close($curl);
                        $data = json_decode($response, true);
                        
                       if(isset($data['GenerateWayBillResult']))
                       {
                        $processedShipment = $data['GenerateWayBillResult'];
                        // Access the AWBNo
                        $awbNo = $processedShipment['AWBNo'];
                        $status = $processedShipment['IsError'];
                        $recieved_shipment_id = $processedShipment['CCRCRDREF'];
                        $labelURL = $processedShipment['AWBPrintContent'];
                        $statusCode = $processedShipment['Status'][0]['StatusCode'];
                        $msg = $processedShipment['Status'][0]['StatusInformation'];
                        $data['awb_number'] = $processedShipment['AWBNo']?$processedShipment['AWBNo']:'';
                        $data['IsError'] = $processedShipment['IsError']?$processedShipment['IsError']:'';
                        if($data['IsError'] == false){
                            $status='success';
                        }
                        else{
                            $status='failed';
                        }
                        $data['status'] = $status;
                        $data['order_no'] = $processedShipment['CCRCRDREF']?$processedShipment['CCRCRDREF']:'';
                        if($processedShipment['AWBPrintContent']!='')
                        {
                            $label = $labelURL;  // Assuming $order->shipping_label is already an array
                            $pdfBinary = call_user_func_array('pack', array_merge(['C*'], $label));
                            $pdfBase64 = base64_encode($pdfBinary);
                            $pdfDataUri = 'data:application/pdf;base64,' . $pdfBase64;
                        }                    
                        else
                        {
                            $pdfDataUri = '';
                        }
    
                        $data['shipping_label'] = $processedShipment['AWBPrintContent']?$processedShipment['AWBPrintContent']:'';
                        
                        $data['StatusCode'] = $processedShipment['Status'][0]['StatusCode']?$processedShipment['Status'][0]['StatusCode']:'';
                        $data['message'] = $processedShipment['Status'][0]['StatusInformation']?$processedShipment['Status'][0]['StatusInformation']:'';
                        $data['courier_id'] = 0;
                        $data['courier_name'] = "Bluedart";
                        $data['packing_slip'] = $pdfDataUri;
                        #dd($data);
        				return $data;
                       }
                       else
                       {
                          # dd($data['error-response']);
                          if(isset($data['error-response']))
                          {
                            $msg = $data['error-response'][0]['Status'][0]['StatusInformation'];
                          }
                          $result['message'] = $data['error-response'][0]['Status'][0]['StatusInformation'];
                          $result['status'] = false;
            			  return $result;
                           
                       }
                    }
    			else
    			{
    				$result['message'] = 'Token not found..!';
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
		{
			$result['message'] = 'Order Detail Not found..!';
			$result['status'] = false;
			return $result;
		}
    }
    public function reprocessOrder($shipment,$mapArray)
    {
		if(!empty($shipment))
		{
			$token = $this->app_login($mapArray);
			
			if($token)
			{
			    $tokenData = json_decode($token);
                if ($tokenData->JWTToken !='')
                {
    				$boxInOrder =  $shipment->input('no_of_boxes');
            		$boxTotalWeight = $shipment->input('total_weight');
    				$productTotalWeight = array_sum(array_column($shipment->products,'product_weight'));
    				$productTotalBox = array_sum(array_column($shipment->products,'no_of_box'));
    				$productTotalQuantity = array_sum(array_column($shipment->products,'product_quantity'));
                    
    				$Items = array(); // Initialize the $Items array
                    $i = 0;
                    $created_date = time() * 1000;
                    $order_date = '/Date('.$created_date.')/';
                    $comodities = [];
    				foreach($shipment->products as $product) 
    				{
                        $Items[$i]['CGSTAmount'] = (float)$product['cgst_amount'] ? (float)$product['cgst_amount']:0;
                        $Items[$i]['HSCode'] = $product['product_hsn_code'] ? $product['product_hsn_code'] : '';
                        $Items[$i]['IGSTAmount'] = (float)$product['igst_amount']?$product['igst_amount']:0;
                        $Items[$i]['IGSTRate'] = (float)$product['igst_rate'] ? (float)$product['igst_rate']:0;
                        $Items[$i]['Instruction'] = $product['Instruction'] ? $product['Instruction'] : '';
                        $Items[$i]['InvoiceDate'] = $order_date;
                        $Items[$i]['InvoiceNumber'] = $product['invoice_no'] ? $product['invoice_no'] : '';
                        $Items[$i]['ItemID'] = $product['product_code'] ? $product['product_code'] : '';
                        $Items[$i]['ItemName'] = $product['product_name'] ? $product['product_name'] : '';
                        $Items[$i]['ItemValue'] = (float)$product['product_price'] ? (float)$product['product_price'] : 0.0;
                        $Items[$i]['Itemquantity'] = (float)$product['product_quantity'] ? (float)$product['product_quantity'] : 1;
                        $Items[$i]['PlaceofSupply'] = '';
                        $Items[$i]['ProductDesc1'] = $product['product_name'] ? $product['product_name'] : '';
                        $Items[$i]['ProductDesc2'] = $product['Instruction'] ? $product['Instruction'] : '';
                        $Items[$i]['ReturnReason'] = '';
                        $Items[$i]['SGSTAmount'] = (float)$product['SGSTAmount']? (float)$product['SGSTAmount']: 0;
                        $Items[$i]['SKUNumber'] = $product['SKUNumber']? $product['SKUNumber']: '';
                        $Items[$i]['SellerGSTNNumber'] = '';
                        $Items[$i]['SellerName'] = '';
                        $Items[$i]['TaxableAmount'] = (float)$product['TaxableAmount']? (float)$product['TaxableAmount']: 0;
                        $Items[$i]['TotalValue'] =  (float)$product['TotalValue']? (float)$product['TotalValue']: 0;
                        $Items[$i]['cessAmount'] = 0;
                        $Items[$i]['countryOfOrigin'] = '';
                        $Items[$i]['docType'] = '';
                        $Items[$i]['subSupplyType'] = 0;
                        $Items[$i]['supplyType'] = '';
                        $comodities['CommodityDetail'.$i] = $Items[$i]['ItemName'];
                        $i++;
                    }
                    #dd($Items);
                    
                    $total_amount = round($shipment->input('total_amount'),2);  
                    $SubProductCode='';
                    if($shipment->input('order_type') == 'B2B')
                    {
    				   
    				    $SubProductCode ='';
    				    $total_amountCollect=0;
    				}
    				else
    				{
    				    
    				    $total_amountCollect = 0;
                        $pay_method='';
                        if($shipment->input('payment_mode')=='COD')
                        {
                            $total_amountCollect = $total_amount + $shipment->input('shipping_charges');
                            $pay_method = 'C';
                        }
                        else
                        {
                            $pay_method = 'P';
                        }    
                        $SubProductCode = $pay_method;
    				}
                    
                    $ordAmount = $total_amount;      
                    $order_data = array(
                        "Request" => array(
                            "Consignee" => array(
                                "AvailableDays" => "",
                                "AvailableTiming" => "",
                                "ConsigneeAddress1" => preg_replace('/[^a-zA-Z0-9_ -]/s',' ',$shipment->input('shipping_consignee.consignee_address')),
                                "ConsigneeAddress2" => preg_replace('/[^a-zA-Z0-9_ -]/s',' ',$shipment->input('shipping_consignee.consignee_address_2')),
                                "ConsigneeAddress3" => "",
                                "ConsigneeAddressType" => "",
                                "ConsigneeAddressinfo" => "",
                                "ConsigneeAttention" => "",
                                "ConsigneeEmailID" => $shipment->input('shipping_consignee.consignee_email'),
                                "ConsigneeFullAddress" => "",
                                "ConsigneeGSTNumber" => "",
                                "ConsigneeLatitude" => "",
                                "ConsigneeLongitude" => "",
                                "ConsigneeMaskedContactNumber" => "",
                                "ConsigneeMobile" => $shipment->input('shipping_consignee.consignee_phone'),
                                "ConsigneeName" => $shipment->input('shipping_consignee.consignee_name'),
                                "ConsigneePincode" => $shipment->input('shipping_consignee.consignee_pincode'),//"400703",
                                "ConsigneeTelephone" => $shipment->input('shipping_consignee.consignee_phone'),
                                
                            ),
                            "Returnadds" => array(
                                "ManifestNumber" => "",
                                "ReturnAddress1" => $shipment->input('origin.origin_address'),
                                "ReturnAddress2" => $shipment->input('origin.origin_address_2'),
                                "ReturnAddress3" => "",
                                "ReturnAddressinfo" => "",
                                "ReturnContact" => $shipment->input('origin.origin_code'),
                                "ReturnEmailID" => '',
                                "ReturnLatitude" => "",
                                "ReturnLongitude" => "",
                                "ReturnMaskedContactNumber" => "",
                                "ReturnMobile" => $shipment->input('origin.origin_phone'),
                                "ReturnPincode" => $shipment->input('origin.origin_pincode'), //"400057",
                                "ReturnTelephone" => $shipment->input('origin.origin_phone')
                            ),
                            "Services" => array(
                                "AWBNo" => "",
                                "ActualWeight" => (float)$shipment->input('total_weight')/1000,
                                "CollectableAmount" => $shipment->input('cod_amount')?$shipment->input('cod_amount'):0,
                                "Commodity" => $comodities,
                                "CreditReferenceNo" => $shipment->input('order_no'),
                                "CreditReferenceNo2" => "",
                                "CreditReferenceNo3" => "",
                                "DeclaredValue" => (float)$ordAmount?(float)$ordAmount:1,
                                "DeliveryTimeSlot" => "",
                                "Dimensions" => array(
                                    array(
                                        "Breadth" => $shipment->input('length'),
                                        "Count" => 1, //$shipment->input('total_quantity'),
                                        "Height" => $shipment->input('breadth'),
                                        "Length" => $shipment->input('height')
                                    )
                                    
                                ),
                                
                                "FavouringName" => "",
                                "ForwardAWBNo" => "",
                                "ForwardLogisticCompName" => "",
                                "InsurancePaidBy" => "",
                                "InvoiceNo" =>'', //$shipheader_data['no'],
                                "IsChequeDD" => "",
                                "IsForcePickup" => false,
                                "IsPartialPickup" => false,
                                "IsReversePickup" => false,
                                "ItemCount" => $productTotalQuantity,
                                "OTPBasedDelivery"=> "0",
                                "OTPCode" =>"",
                                "Officecutofftime"=> "",
                               
                                "PDFOutputNotRequired" => false,
                                "PackType" => "",
                                "ParcelShopCode" => "",
                                "PayableAt" => "",
                                "PayerGSTVAT" => 0.0,
                                "PickupDate" => $order_date,
                                "PickupMode" => "",
                                "PickupTime" => "1600",
                                "PickupType" => "",
                                "PieceCount" => "1",
                                "PreferredPickupTimeSlot" => "",
                                "ProductCode" => $mapArray['product_code'],
                                "ProductFeature" => "",
                                "ProductType" => 1,
                                "RegisterPickup" => false,
                                "SpecialInstruction" => $shipment->input('invoice.invoice_number'),
                                "SubProductCode" => $SubProductCode,
                                "TotalCashPaytoCustomer" => 0.0,
                                "itemdtl"=> $Items,
                                "noOfDCGiven" => 0
                            ),
                            "Shipper" => array(
                                "CustomerAddress1" => $shipment->input('origin.origin_address'),
                                "CustomerAddress2" => $shipment->input('origin.origin_address_2'),
                                "CustomerAddress3" => "",
                                "CustomerAddressinfo" => "",
                                "CustomerCode" => $mapArray['customer_code'],
                                "CustomerEmailID" => $shipment->input('origin.origin_email'),
                                "CustomerGSTNumber" => "",
                                "CustomerLatitude" => "",
                                "CustomerLongitude" => "",
                                "CustomerMaskedContactNumber" => "",
                                "CustomerMobile" => $shipment->input('origin.origin_phone'),
                                "CustomerName" => $shipment->input('origin.origin_name'),
                                "CustomerPincode" => '400080', //$shipment->input('origin.origin_pincode'),
                                "CustomerTelephone" => "",
                                "IsToPayCustomer" => false,
                                "OriginArea" => $mapArray['area'],
                                "Sender" => "",
                                "VendorCode" => ""
                            )
                        ),
                        "Profile" => array(
                            "Api_type" => $mapArray['api_type'],
                            "LicenceKey" =>$mapArray['licence_key'],
                            "LoginID" =>$mapArray['login_id'],
                        )
                    );
                    $requestJson = json_encode($order_data);
                  
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => 'https://apigateway-sandbox.bluedart.com/in/transportation/waybill/v1/GenerateWayBill',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS =>$requestJson,
    
                       CURLOPT_HTTPHEADER => array
                       (
                         'Content-Type: application/json',
                         'JWTToken: '.$tokenData->JWTToken,
                       ),
                     ));
                        $response = curl_exec($curl);
                        curl_close($curl);
                        $data = json_decode($response, true);
                        if(isset($data['GenerateWayBillResult']))
                        {
                            $processedShipment = $data['GenerateWayBillResult'];
                            // Access the AWBNo
                            $awbNo = $processedShipment['AWBNo'];
                            $status = $processedShipment['IsError'];
                            $recieved_shipment_id = $processedShipment['CCRCRDREF'];
                            $labelURL = $processedShipment['AWBPrintContent'];
                            $statusCode = $processedShipment['Status'][0]['StatusCode'];
                            $msg = $processedShipment['Status'][0]['StatusInformation'];
                            // Add new variables to $data array
                            $data['awb_no'] = $processedShipment['AWBNo']?$processedShipment['AWBNo']:'';
                            $data['IsError'] = $processedShipment['IsError']?$processedShipment['IsError']:'';
                            if($data['IsError'] == false){
                                $status='success';
                            }
                            else{
                                $status='failed';
                            }
                            $data['status'] = $status;
                            $data['order_no'] = $processedShipment['CCRCRDREF']?$processedShipment['CCRCRDREF']:'';
                            if($processedShipment['AWBPrintContent']!='')
                            {
                                $label = $labelURL;  // Assuming $order->shipping_label is already an array
                                $pdfBinary = call_user_func_array('pack', array_merge(['C*'], $label));
                                $pdfBase64 = base64_encode($pdfBinary);
                                $pdfDataUri = 'data:application/pdf;base64,' . $pdfBase64;
                            }                    
                            else{
                                $pdfDataUri = '';
                            }
                            $data['shipping_label'] = $processedShipment['AWBPrintContent']?$processedShipment['AWBPrintContent']:'';
                            $data['StatusCode'] = $processedShipment['Status'][0]['StatusCode']?$processedShipment['Status'][0]['StatusCode']:'';
                            $data['message'] = $processedShipment['Status'][0]['StatusInformation']?$processedShipment['Status'][0]['StatusInformation']:'';
                            $data['courier_id'] =0;
                            $data['courier_name'] = "Bluedart";
                            $data['packing_slip'] = $pdfDataUri;
                            #dd($data);
                            return $data;
                        }
                        else
                        {
                            $msg = '';
                            if(isset($data['error-response']))
                            {
                               
                                #$msg = 'error';//$data['error-response'][0]['Status'][0]['StatusInformation'];
                                if (isset($data['error-response'][0]['Status'])) {
                                    
                                    if($data['error-response'][0]['Status'][0]['StatusInformation'])
                                    {
                                        $msg = $data['error-response'][0]['Status'][0]['StatusInformation'];
                                    }
                                }
                            }
                            $result['message'] = $msg;
                            $result['courier_name'] = "Bluedart";
                            $result['status'] = false;
                			return $result;
                        }
        				
                    }
    			else
    			{
    				$result['message'] = 'Token not found..!';
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
		{
			$result['message'] = 'Order Detail Not found..!';
			$result['status'] = false;
			return $result;
		}
    }
    public function trackShipment($awbNo,$mapArray)
    {
        $resultAuth = $this->app_login($mapArray[0]);
        if($resultAuth)
		{
			header('Content-Type: text/plain');
            $curl = curl_init();
            //81439997096
            curl_setopt_array($curl, array(
              #CURLOPT_URL => 'https://api.bluedart.com/servlet/RoutingServlet?handler=tnt&action=custawbquery&loginid='.$mapArray['login_id'].'&awb=awb&numbers='.$awb_no.'&format=html&lickey='.$mapArray['tracking_licence'].'&verno='.$mapArray['version'].'&scan=1',
              #CURLOPT_URL =>'https://api.bluedart.com/servlet/RoutingServlet?handler=tnt&action=custawbquery&loginid=BOM63945&awb=awb&numbers='.$awbNo.'&format=xml&lickey=3oonplnklmr8ljjmrporrrkgkufuexre&verno=1.10&scan=1',
              CURLOPT_URL => 'https://apigateway.bluedart.com/in/transportation/tracking/v1/shipment?handler=tnt&loginid='.$mapArray['login_id'].'&numbers='.$awbNo.'&format=xml&lickey='.$mapArray['tracking_licence'].'&scan=1&action=custawbquery&verno='.$mapArray['version'].'&awb=awb',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'GET',
              CURLOPT_HTTPHEADER => array(
                
                 'Cookie: BIGipServerpl_api-bluedart.dhl.com_443=!JEfsRCTld210CRNaiCvVO+HDtM6b0gyIv+NfD3TaFbDRh8TG2UFpiCi63pgvz00WEgLI+RmrRKLGbZY='
              ),
            ));
            $response = curl_exec($curl);
            
            curl_close($curl);
		  
                $xmlString = '';

                $xml = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA);
                $json = json_encode($xml);
                $array = json_decode($json, true);
                $dataArray = [
                'data' => [
                    [
                        'service' => $array['Shipment']['Service'] ?? '',
                        'awb_number' => $array['Shipment']['@attributes']['WaybillNo'] ?? '',
                        'prod_code' => $array['Shipment']['Prodcode'] ?? '',
                        'origin' => $array['Shipment']['Origin'] ?? '',
                        'origin_area_code' => $array['Shipment']['OriginAreaCode'] ?? '',
                        'destination' => $array['Shipment']['Destination'] ?? '',
                        'destination_area_code' => $array['Shipment']['DestinationAreaCode'] ?? '',
                        'product_type' => $array['Shipment']['ProductType'] ?? '',
                        'status' => $array['Shipment']['Status'] ?? '',
                        'status_type' => $array['Shipment']['StatusType'] ?? '',
                        'status_date' => $array['Shipment']['StatusDate'] ?? '',
                        'status_time' => $array['Shipment']['StatusTime'] ?? '',
                        'history' => [],
                    ],
                ],
            ];
            if (
            isset($array['Shipment']['Scans']['ScanDetail']['ScanDate']) &&
            isset($array['Shipment']['Scans']['ScanDetail']['ScanTime']) &&
            isset($array['Shipment']['Scans']['ScanDetail']['ScannedLocation']) &&
            isset($array['Shipment']['Scans']['ScanDetail']['ScanType']) &&
            isset($array['Shipment']['Scans']['ScanDetail']['Scan'])) 
            {
                $dataArray['data'][0]['history'][] = [
                    'event_time'  => $array['Shipment']['Scans']['ScanDetail']['ScanDate'] . ' ' . $array['Shipment']['Scans']['ScanDetail']['ScanTime'],
                    'location'    => $array['Shipment']['Scans']['ScanDetail']['ScannedLocation'],
                    'status_code' => $array['Shipment']['Scans']['ScanDetail']['ScanType'],
                    'message'     => $array['Shipment']['Scans']['ScanDetail']['Scan'],
                ];
            } 
            else 
            {
                $dataArray['data'][0]['history'][]=
                [
                    'event_time'  => '',
                    'location'    => '',
                    'status_code' => '',
                    'message' => ''
                ];
            }
            return $dataArray;
		}
    }
    public function trackSingleShipment_new($awbNo,$mapArray)
    {
		$resultAuth = $this->app_login($mapArray[0]);
		if($resultAuth)
		{
		    $tokenData = json_decode($resultAuth);
		    if($tokenData->JWTToken)
		    {
    			header('Content-Type: text/plain');
                $curl = curl_init();
                //81439997096
                curl_setopt_array($curl,[
                  //CURLOPT_URL => 'https://api.bluedart.com/servlet/RoutingServlet?handler=tnt&action=custawbquery&loginid='.$returnAuth[0]->login_id.'&awb=awb&numbers='.$awb_no.'&format=html&lickey='.$returnAuth[0]->tracking_licence.'&verno='.$returnAuth[0]->version.'&scan=1',
                  #CURLOPT_URL =>'https://api.bluedart.com/servlet/RoutingServlet?handler=tnt&action=custawbquery&loginid=BOM63945&awb=awb&numbers='.$awbNo.'&format=xml&lickey=3oonplnklmr8ljjmrporrrkgkufuexre&verno=1.10&scan=1',
                  #CURLOPT_URL => 'https://apigateway.bluedart.com/in/transportation/tracking/v1/shipment?handler=tnt&loginid=BOM63945&numbers='.$awbNo.'&format=xml&lickey=3oonplnklmr8ljjmrporrrkgkufuexre&scan=1&action=custawbquery&verno=1&awb=awb',
                  CURLOPT_URL => 'https://apigateway.bluedart.com/in/transportation/tracking/v1/shipment?handler=tnt&loginid='.$mapArray['login_id'].'&numbers='.$awbNo.'&format=xml&lickey='.$mapArray['tracking_licence'].'&scan=1&action=custawbquery&verno='.$mapArray['version'].'&awb=awb',
                  CURLOPT_RETURNTRANSFER => true,
                    	CURLOPT_ENCODING => "",
                    	CURLOPT_MAXREDIRS => 10,
                    	CURLOPT_TIMEOUT => 30,
                    	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    	CURLOPT_CUSTOMREQUEST => "GET",
                        CURLOPT_HTTPHEADER => [
        		            "JWTToken:".$tokenData->JWTToken
                          ],
                        ]);
                    
                $response = curl_exec($curl);
                curl_close($curl);
                $xmlString = '';
                $xml = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA);
                $json = json_encode($xml);
                $array = json_decode($json, true);
                $dataArray = [
                'data' => [
                    [
                        'service' => $array['Shipment']['Service'] ?? '',
                        'awb_number' => $array['Shipment']['@attributes']['WaybillNo'] ?? '',
                        'prod_code' => $array['Shipment']['Prodcode'] ?? '',
                        'origin' => $array['Shipment']['Origin'] ?? '',
                        'origin_area_code' => $array['Shipment']['OriginAreaCode'] ?? '',
                        'destination' => $array['Shipment']['Destination'] ?? '',
                        'destination_area_code' => $array['Shipment']['DestinationAreaCode'] ?? '',
                        'product_type' => $array['Shipment']['ProductType'] ?? '',
                        'status' => $array['Shipment']['Status'] ?? '',
                        'status_type' => $array['Shipment']['StatusType'] ?? '',
                        'status_date' => $array['Shipment']['StatusDate'] ?? '',
                        'status_time' => $array['Shipment']['StatusTime'] ?? '',
                        'history' => [],
                    ],
                ],
            ];
           
           if (
            isset($array['Shipment']['Scans']['ScanDetail']['ScanDate']) &&
            isset($array['Shipment']['Scans']['ScanDetail']['ScanTime']) &&
            isset($array['Shipment']['Scans']['ScanDetail']['ScannedLocation']) &&
            isset($array['Shipment']['Scans']['ScanDetail']['ScanType']) &&
            isset($array['Shipment']['Scans']['ScanDetail']['Scan'])) 
            {
                $dataArray['data'][0]['history'][] = [
                    'event_time'  => $array['Shipment']['Scans']['ScanDetail']['ScanDate'] . ' ' . $array['Shipment']['Scans']['ScanDetail']['ScanTime'],
                    'location'    => $array['Shipment']['Scans']['ScanDetail']['ScannedLocation'],
                    'status_code' => $array['Shipment']['Scans']['ScanDetail']['ScanType'],
                    'message'     => $array['Shipment']['Scans']['ScanDetail']['Scan'],
                ];
            } 
            else 
            {
                $dataArray['data'][0]['history'][]=
                [
                    'event_time'  => '',
                    'location'    => '',
                    'status_code' => '',
                    'message' => ''
                ];
            }
		    return $dataArray;
		        
		  }
		    else
			{
				$result['message'] = 'Token not found..!';
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
    public function trackSingleShipment($awbNo,$mapArray)
    {
		$resultAuth = $this->app_login($mapArray[0]);
	
		if($resultAuth)
		{
		    #dd($mapArray);
			#$orderDetails = json_encode($shipmentAWB);
			#dd($orderDetails);
			
			header('Content-Type: text/plain');
            $curl = curl_init();
            //81439997096
            curl_setopt_array($curl, array(
              //CURLOPT_URL => 'https://api.bluedart.com/servlet/RoutingServlet?handler=tnt&action=custawbquery&loginid='.$returnAuth[0]->login_id.'&awb=awb&numbers='.$awb_no.'&format=html&lickey='.$returnAuth[0]->tracking_licence.'&verno='.$returnAuth[0]->version.'&scan=1',
              CURLOPT_URL =>'https://api.bluedart.com/servlet/RoutingServlet?handler=tnt&action=custawbquery&loginid=BOM63945&awb=awb&numbers='.$awbNo.'&format=xml&lickey=3oonplnklmr8ljjmrporrrkgkufuexre&verno=1.10&scan=1',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'GET',
              CURLOPT_HTTPHEADER => array(
                
                 'Cookie: BIGipServerpl_api-bluedart.dhl.com_443=!JEfsRCTld210CRNaiCvVO+HDtM6b0gyIv+NfD3TaFbDRh8TG2UFpiCi63pgvz00WEgLI+RmrRKLGbZY='
              ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
		  
                $xmlString = '';

                $xml = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA);
                $json = json_encode($xml);
                $array = json_decode($json, true);
                $dataArray = [
                'data' => [
                    [
                        'service' => $array['Shipment']['Service'] ?? '',
                        'awb_number' => $array['Shipment']['@attributes']['WaybillNo'] ?? '',
                        'prod_code' => $array['Shipment']['Prodcode'] ?? '',
                        'origin' => $array['Shipment']['Origin'] ?? '',
                        'origin_area_code' => $array['Shipment']['OriginAreaCode'] ?? '',
                        'destination' => $array['Shipment']['Destination'] ?? '',
                        'destination_area_code' => $array['Shipment']['DestinationAreaCode'] ?? '',
                        'product_type' => $array['Shipment']['ProductType'] ?? '',
                        'status' => $array['Shipment']['Status'] ?? '',
                        'status_type' => $array['Shipment']['StatusType'] ?? '',
                        'status_date' => $array['Shipment']['StatusDate'] ?? '',
                        'status_time' => $array['Shipment']['StatusTime'] ?? '',
                        'history' => [],
                    ],
                ],
            ];
            if (
            isset($array['Shipment']['Scans']['ScanDetail']['ScanDate']) &&
            isset($array['Shipment']['Scans']['ScanDetail']['ScanTime']) &&
            isset($array['Shipment']['Scans']['ScanDetail']['ScannedLocation']) &&
            isset($array['Shipment']['Scans']['ScanDetail']['ScanType']) &&
            isset($array['Shipment']['Scans']['ScanDetail']['Scan'])) 
            {
                $dataArray['data'][0]['history'][] = [
                    'event_time'  => $array['Shipment']['Scans']['ScanDetail']['ScanDate'] . ' ' . $array['Shipment']['Scans']['ScanDetail']['ScanTime'],
                    'location'    => $array['Shipment']['Scans']['ScanDetail']['ScannedLocation'],
                    'status_code' => $array['Shipment']['Scans']['ScanDetail']['ScanType'],
                    'message'     => $array['Shipment']['Scans']['ScanDetail']['Scan'],
                ];
            } 
            else 
            {
                $dataArray['data'][0]['history'][]=
                [
                    'event_time'  => '',
                    'location'    => '',
                    'status_code' => '',
                    'message' => ''
                ];
            }
		    return $dataArray;
		}
		
    }
    
    public function cancelledShipment($awbNo,$mapArray)
    {
        if($shipment !='')
        {
            $resultAuth = $this->app_login($mapArray[0]);
			if($resultAuth['status'])
			{    
                $postFields = json_encode([
                        "Request" => [
                            "AWBNo" => $awbNo
                        ],
                        "Profile" => [
                            "Api_type" => $mapArray[0]['api_type'],
                            "LicenceKey" => $mapArray[0]['licence_key'],
                            "LoginID" => $mapArray[0]['login_id']
                        ]
                    ]);
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                      //CURLOPT_URL => 'https://netconnect.bluedart.com/Ver1.10/ShippingAPI/WayBill/WayBillGeneration.svc/rest/CancelWaybill',
                      CURLOPT_URL =>'https://apigateway-sandbox.bluedart.com/in/transportation/waybill/v1/CancelWaybill',
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_ENCODING => '',
                      CURLOPT_MAXREDIRS => 10,
                      CURLOPT_TIMEOUT => 0,
                      CURLOPT_FOLLOWLOCATION => true,
                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                      CURLOPT_CUSTOMREQUEST => 'POST',
                      CURLOPT_POSTFIELDS => $postFields,
                      CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json',
                        'JWTToken: '.$tokenData->JWTToken,
                        ),
                    ));
                    $response = curl_exec($curl);
                     
                    curl_close($curl);
                    $result = json_decode($response, true);
                    $status = '';
                    $response = [];
                    $remark ='';
                    if($result['CancelWaybillResult'])
                    {
                        if(isset($result['CancelWaybillResult']['IsError']) && $result['CancelWaybillResult']['Status'][0]['StatusInformation'])
                        {
                            $status = $result['CancelWaybillResult']['IsError'];
                            $remark = $result['CancelWaybillResult']['Status'][0]['StatusInformation'];
                            if($status == 0)
                            {
                                $status = 'cancelled';
                            }
                        }
                        
                    }
                    else
                    {
                        $remarks = 'cancelled failed';
                    }
                    $response['status'] = $status;
                    $response['remarks'] = $remarks;
                    return $response;
        		    
			}
        }
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
	function processWarehouse($warehouse,$mapArray)
	{
	    
	}
}