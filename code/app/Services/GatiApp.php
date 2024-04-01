<?php

namespace App\Services;

use App\Interfaces\AppOrderProcessInterface;
use App\Models\Order;
class GatiApp implements AppOrderProcessInterface
{
    public function app_login($mapArray)
	{
		
	}
	 public function processOrder($shipment,$mapArray)
    {
        if($mapArray['customer_code']!='' &&  $mapArray['warehouse_ou']!='' && $mapArray['auth_key']!='' && $mapArray['auth_secret']!='')
        {
            #echo "GatiApp";die;
    		$pincode = $shipment->input('shipping_consignee.consignee_pincode');
    		$pincodeService = $this->gati_pincode_serviceability($pincode,$mapArray['auth_secret']);
    		 
            $returnLocationcode = json_decode($pincodeService);
           
            if(!empty($returnLocationcode))
            {
                #print_r($returnLocationcode);die;
                $returnDoket = $this->gati_download_doket($mapArray['auth_key']);
                $returnDoketNo = json_decode($returnDoket);
                $boxInOrder =  $shipment->input('no_of_boxes');
    			$boxTotalWeight = $shipment->input('total_weight');
    			$qtyTotal = array_sum(array_column($shipment->products,'product_quantity'));
    			$childAwbNo ='';
                if(isset($returnDoketNo->docketNo) && $returnDoketNo->docketNo !='')
                {
                    $docketPrintData = '';
                    //$clientId='30457301';
                    $docketPrint = $this->gati_doket_printing($returnDoketNo->docketNo, $mapArray['customer_code']);
                    if(isset($docketPrint) && ($docketPrint!=''))
                    {
                        $docketPrintData = $docketPrint;
                    }
                    $pakcageSeriesData = $this->gati_package_series($returnDoketNo->docketNo,$boxInOrder,$mapArray['auth_key'],$pincode);
    		        $pakcageSeries = json_decode($pakcageSeriesData);
    		        if(isset($pakcageSeries) && isset($pakcageSeries->frmNo) && $pakcageSeries->frmNo != '')
    		        {
    	                $firstPackage =  $pakcageSeries->frmNo - 1;
    			        $i = 0;
    			        $product_detail = array();
        			    foreach($shipment->box_detail as $line)
        			    {
        			        $firstPackage = $firstPackage + 1;
        			        $product_detail[$i]['pkgNo'] = $firstPackage++;
        					$product_detail[$i]['pkgLn'] = $line['length'];
        					$product_detail[$i]['pkgBr'] = $line['breadth'];
        					$product_detail[$i]['pkgHt'] = $line['height'];
        					$product_detail[$i]['pkgWt'] = $line['weight'] * 1000;
        					$i++;
        				}
        				
        				$pkgDetails = array
                        (
                            'pkginfo' => $product_detail
                        );
                        $total_amountCollect = 0;
                        $bookingBase = 4;
                        $total_amount = round($shipment->input('total_amount'),2);  
    					if ($shipment->input('payment_mode') == 'COD') 
    					{
                            $total_amountCollect = $total_amount;
                            $bookingBase = 1;
                        }
                        else 
                        {
                            $shipment->payment_mode = 'prepaid';
                        }
                        $shipPone = explode(",", $shipment->input('shipping_consignee.consignee_phone'));
    					$shipaddress2 = $shipment->input('shipping_consignee.consignee_address_2')?$shipment->input('shipping_consignee.consignee_address_2'):$shipment->input('shipping_consignee.consignee_address');
                        $details[] = array
                        (
                            "docketNo" => $returnDoketNo->docketNo,
                            "deliveryStn"  => "342001",
                            "goodsCode"  => "206",
                            "declCargoVal"  => ''.round($total_amount,2),
                            "actualWt"  => $boxTotalWeight,
                            "chargedWt"  => $boxTotalWeight,
                            "shipperCode"  => $mapArray['customer_code'],
                            "orderNo" => $shipment->input('order_no'),
                            "codAmt"  => round($total_amountCollect,2),
                            "codInFavourOf"  => "G",
                            "receiverCode"  => "99999",
                            "receiverName" => $shipment->input('shipping_consignee.consignee_name'),
                            "receiverAdd1" => preg_replace('/[^a-zA-Z0-9_ -]/s',' ',$shipment->input('shipping_consignee.consignee_address')),
                            "receiverAdd2" => ''.preg_replace('/[^a-zA-Z0-9_ -]/s',' ',$shipaddress2),
                            "receiverAdd3" => "",
                            "receiverAdd4" => "",
                            "receiverCity" => $shipment->input('shipping_consignee.consignee_city')?$shipment->input('shipping_consignee.consignee_city'):'',
                            "receiverPhoneNo" => trim($shipPone[0]),
                            "receiverMobileNo" => trim($shipPone[0]),
                            "receiverEmail"  => $shipment->input('shipping_consignee.consignee_email')?$shipment->input('shipping_consignee.consignee_email'):'cust@cust.com',
                            "receiverPinCode" => $pincode,
                            "noOfPkgs" => $boxInOrder,
                            "fromPkgNo" => $pakcageSeries->frmNo,
                            "toPkgNo" => $pakcageSeries->toNo,
                            "pkgDetails" => $pkgDetails,
                            "CustDeliveyDate"  => "",
                            "SPL_Instruction"  => "",
                            "prodServCode"  => "1",
                            "custVendCode"  => $shipment->input('origin.origin_code')?$shipment->input('origin.origin_code'):'',
                            "goodsDesc"  =>$shipment->products[0]['Instruction']?$shipment->products[0]['Instruction']:'',
                            "bookingBasis" => $bookingBase,
                            "locationCode" => $returnLocationcode->serviceDtls[0]->locationCode,
                            "UOM" => "I",
                            "consignorGSTINNo" => "36AABCG00110LZ1",
                            "ReceiverGSTINNo" => "11FAFFCG032Z2",
                            "EWAYBILL" => "",
                            "EWB_EXP_DT" => ""//"10-01-2018"
                        );
                        $orderProcess = array
            		    (
            		        "custCode" => $mapArray['customer_code'],
                			"pickupRequest"  => date('d-m-Y H:i:s'),
                			'details' => $details
            		    );
                            #echo '<pre>';
                            $orderProcess = json_encode($orderProcess);
                            $curl = curl_init();
                            curl_setopt_array($curl, array(
                            CURLOPT_URL => 'http://119.235.57.47:9080/pickupservices/GATIKWEJPICKUPLBH.jsp',
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS => $orderProcess,
                              CURLOPT_HTTPHEADER => array(
                                'Content-Type: application/json',
                                'Cookie: JSESSIONID=2FC61EC7C8E8D5A9D9EA77A71463BA38'
                              ),
                            ));
                            $response = curl_exec($curl);
                            curl_close($curl);
                           # echo $response = trim($response);
                            $response = preg_replace('/[[:cntrl:]]/', '', $response);
                            $arrayData = json_decode($response, true);
                            $childAwbNo = $pakcageSeries->frmNo.'_'.$pakcageSeries->toNo;
                            
                            if($arrayData) 
                            {
                                if($arrayData['postedData']=='successful')
                                {
                                    
                                    $warehouse_ou = $mapArray['warehouse_ou'];//'GGN';
                                    $shipping_label='';
                                    $ship_label = $this->gati_packing_slip($returnDoketNo->docketNo,$childAwbNo,$warehouse_ou,$returnLocationcode->serviceDtls[0]->locationCode);
                                    if(isset($ship_label) && $ship_label!='')
                                    {
                                        $shipping_label = $ship_label;
                                    }
                                    
                                    $status = isset($data['details'][0]['errmsg']) ? $data['details'][0]['errmsg'] :'success';
                                    $data['awb_no'] = $returnDoketNo->docketNo ? $returnDoketNo->docketNo : '';
                                    $data['shipping_label'] = $shipping_label;
                                    $data['StatusCode'] = '';
                                    $data['status'] = $status;
                                    $data['message'] = $arrayData['postedData']?$arrayData['postedData']:'';
                                    $data['courier_id'] = 0;
                                    $data['courier_name'] = "GATI";
                                    $data['packing_slip'] = '';
                                    $data['child_awbNo'] = $childAwbNo;
                                    $data['docket_print'] = $docketPrintData;
                                    $data['locationcode'] = $returnLocationcode->serviceDtls[0]->locationCode;
                                }
                                else
                                {
                                    $status = isset($data['details'][0]['errmsg']) ? $data['details'][0]['errmsg'] :'failed';
                                    $data['awb_no'] = '';
                                    $data['shipping_label'] = '';
                                    $data['StatusCode'] = '';
                                    $data['status'] = $status;
                                    $data['message'] = $arrayData['postedData']?$arrayData['postedData']:'';
                                    $data['courier_id'] =0;
                                    $data['courier_name'] = "GATI";
                                    $data['packing_slip'] = '';
                                    $data['child_awbNo'] = '';
                                    $data['docket_print'] = '';
                                    $data['locationcode'] ='';
                                }
                                return $data;
                            }
                            else
                            {
                                $errorCode = json_last_error();
                                $errorMessage = json_last_error_msg();
                                $result['message'] = 'JSON decoding failed with error code $errorCode: $errorMessage';
                				$result['status'] = false;
                				return $result;
                            }
    						
                    }
                    else
                    {
                        $result['message'] = 'Package series issue!';
        				$result['status'] = false;
        				return $result;
                    }
                }
                else
                {
                    $result['message'] = 'Docket no. issue!';
    				$result['status'] = false;
    				return $result;
                }
            }
            else
            {
                $result['message'] = 'Pincode service is not available!';
    			$result['status'] = false;
    			return $result;
            }
        }
        else
        {
            $result['message'] = 'Authentication issue occur!';
			$result['status'] = false;
			return $result;
        }
    }
    public function gati_pincode_serviceability($pincode='110075',$gatiSecuirtyCode='1C7CBE58A514BACE')
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'http://119.235.57.47:9080/pickupservices/GKEPincodeserviceablity.jsp?reqid='.$gatiSecuirtyCode.'&pincode='.$pincode,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_HTTPHEADER => array(
            'Cookie: JSESSIONID=77905B9E9FAF91FB7565071C23393B9D'
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        return $response;
    }
    public function gati_download_doket($encryptedCustid='DF74B81683CEC11B87B45B2B1A26036B')
    {
      
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'http://119.235.57.47:9080/GKEdktdownloadjson.jsp?p1='.$encryptedCustid,
          #CURLOPT_URL => 'https://justi.gati.com/webservices/GKEdktdownloadjson.jsp?p1='.$encryptedCustid,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Cookie: JSESSIONID=77905B9E9FAF91FB7565071C23393B9D'
          ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        
        return $response;
    }
    public function gati_package_series($doket='378341858',$noofBox='2',$encryptedCustid='DF74B81683CEC11B87B45B2B1A26036B',$shiptopincode='110075')
    {
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'http://119.235.57.47:9080/Custpkgseries.jsp?p1='.$doket.'&p2='.$noofBox.'&p3='.$encryptedCustid.'&p4='.$shiptopincode,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Cookie: JSESSIONID=77905B9E9FAF91FB7565071C23393B9D'
          ),
        ));
        
        $response = curl_exec($curl);
       
        curl_close($curl);
        return $response;
    }
    public function gati_doket_printing($doket='378343098',$custid='30457301')
    {
        $curl = curl_init();
        header('Content-Type: appilcation/pdf');
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://pg-uat.gati.com/InterfaceA4Print.jsp?p1='.$doket.'&p2='.$custid,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            
          ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
       
        $docketPrint ='';
        if($response != 'No Data Found')
        {
            $docketPdf = base64_encode($response);
            $docketPrint = 'data:application/pdf;base64,'.$docketPdf;
        }
        else
        {
            
            $docketPrint ='';
        }
        return $docketPrint;
    }
    public function gati_packing_slip($doket='378343098',$childAwbNo, $warehouse_ou,$locationcode='')
    {
        $noofbox = explode('_',$childAwbNo);
       
        $totalBox=array_unique($noofbox);
        $base64HTMLs = [];
        $i=0;
        foreach ($totalBox as  $box) 
        {
            $i++;
            $filePath = '';
            $text =  $doket;
            $size = 20;
            $orientation = "horizontal";
            $code_type = "code128";
            $print = false;
            $sizeFactor = 1;
            $dsp_img = asset('dsp_image/gati.png');
            $html = '<!DOCTYPE html>
                        <html>
                            <head><script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script></head>
                            <body>
                                <table border="1" class="print-wrap-full">
                                    <tr>
                                        <th colspan="4" style="text-align:center">
                                            <img src="'.$dsp_img.'" width="180" height="50%">
                                        </th>
                                        <th colspan="8">
                                            <img id="barcode"/>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th colspan="3"> FROM<br />
                                            <span style="float:right">'.$warehouse_ou.'</span>
                                        </th>
                                        <th colspan="3">TO<br />
                                            <span style="float:right">'.$locationcode.'</span>
                                        </th>
                                        <th style="border-bottom:none !important;padding-bottom: 56px; padding-top: 56px;" rowspan="2">
                                            <img id="barcode"/>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th colspan="3">
                                            DKT.NO<br />
                                            <span style="float:right" id="doketValue">
                                                '.$doket.'
                                            </span>    
                                        </th>
                                        <th colspan="3">NO. OF PKGS<br />
                                            <span style="float:right">
                                                '.$i.'/'.count($totalBox).'
                                            </span>
                                        </th>
                                    </tr> 
                                </table>
                                <script>
                                    
                                    JsBarcode("#barcode", "' . $box . '");
                                </script>
                            </body>
                        </html>
                        ';
                         
            $base64HTML = base64_encode($html);
            // Add the base64-encoded HTML to the array
            $base64HTMLs[] = $base64HTML;
            
        }
        $base64Data = implode(',', $base64HTMLs);
        // Create the data URL
        $url = 'data:text/html;base64,' . $base64Data;
       
        return $url;
    }
    public function processShipOrder($shipmentDetail,$mapArray)
    {
        if($mapArray['customer_code']!='' &&  $mapArray['warehouse_ou']!='' && $mapArray['auth_key']!='' && $mapArray['auth_secret']!='')
        {
            $jsonData = json_decode($shipmentDetail, true);
            $shipment = $jsonData['order'];
            $productData = $jsonData['product'];
    		$pincode = $shipment['shipping_pincode'];
    		$pincodeService = $this->gati_pincode_serviceability($pincode,$mapArray['auth_secret']);
            $returnLocationcode = json_decode($pincodeService);
            $childAwbNo = '';
            if(!empty($returnLocationcode))
            {
                
                #print_r($returnLocationcode);die;
                $returnDoket = $this->gati_download_doket($mapArray['auth_key']);
                $returnDoketNo = json_decode($returnDoket);
               
                $boxInOrder =  $shipment['no_of_box'];
                $boxTotalWeight = $shipment['total_weight'];
    			$productTotalWeight = array_sum(array_column($productData,'product_weight'));
        		$productTotalBox = array_sum(array_column($productData,'no_of_box'));
        		$productTotalQuantity = array_sum(array_column($productData,'product_quantity'));
                if(isset($returnDoketNo->docketNo) && $returnDoketNo->docketNo !='')
                {
                    $docketPrintData = '';
                    //$clientId='30457301';
                    $docketPrint = $this->gati_doket_printing($returnDoketNo->docketNo, $mapArray['customer_code']);
                    if(isset($docketPrint) && $docketPrint!='' && $docketPrint!='No Data Found')
                    {
                        $docketPrintData =$docketPrint;
                    }
                    $pakcageSeries = $this->gati_package_series($returnDoketNo->docketNo,$boxInOrder,$mapArray['auth_key'],$pincode);
    		        $pakcageSeries = json_decode($pakcageSeries);
    		        if(isset($pakcageSeries->frmNo) && $pakcageSeries->frmNo != '')
    		        {
    	                $firstPackage =  $pakcageSeries->frmNo - 1;
    			        $i = 0;
    			        $product_detail = array();
    			           
        			        $product_detail[$i]['pkgNo'] = $firstPackage;
        					$product_detail[$i]['pkgLn'] = $shipment['length'];
        					$product_detail[$i]['pkgBr'] = $shipment['breadth'];
        					$product_detail[$i]['pkgHt'] = $shipment['height'];
        					$product_detail[$i]['pkgWt'] = $shipment['total_weight'] * 1000;
        		
        				
        				$pkgDetails = array
                        (
                            'pkginfo' => $product_detail
                        );
                        $total_amountCollect = 0;
                        $bookingBase = 4;
                        $total_amount = round($shipment['total_amount'],2);  
    					if ($shipment['payment_mode'] == 'COD') 
    					{
                            $total_amountCollect = $total_amount;
                            $bookingBase = 1;
                        }
                        else 
                        {
                            $shipment['payment_mode'] = 'prepaid';
                        }
                        $childAwbNo = $pakcageSeries->frmNo.'_'.$pakcageSeries->toNo;
                    
                        $shipPone = explode(",", $shipment['shipping_phone_number']);
    					$shipaddress2 = $shipment['shipping_address_2']?$shipment['shipping_address_2']:$shipment['shipping_address_1'];
                        $details[] = array
                        (
                            "docketNo" => $returnDoketNo->docketNo,
                            "deliveryStn"  => "342001",
                            "goodsCode"  => "206",
                            "declCargoVal"  => ''.round($total_amount,2),
                            "actualWt"  => $boxTotalWeight,
                            "chargedWt"  => $boxTotalWeight,
                            "shipperCode"  => $mapArray['customer_code'],
                            "orderNo" => $shipment['order_no'],
                            "codAmt"  => round($total_amountCollect,2),
                            "codInFavourOf"  => "G",
                            "receiverCode"  => "99999",
                            "receiverName" => $shipment['shipping_first_name'],
                            "receiverAdd1" => preg_replace('/[^a-zA-Z0-9_ -]/s',' ',$shipment['shipping_address_1']),
                            "receiverAdd2" => ''.preg_replace('/[^a-zA-Z0-9_ -]/s',' ',$shipaddress2),
                            "receiverAdd3" => "",
                            "receiverAdd4" => "",
                            "receiverCity" => $shipment['shipping_city']?$shipment['shipping_city']:'',
                            "receiverPhoneNo" => trim($shipPone[0]),
                            "receiverMobileNo" => trim($shipPone[0]),
                            "receiverEmail"  => $shipment['shipping_email']?$shipment['shipping_email']:'cust@cust.com',
                            "receiverPinCode" => $pincode,
                            "noOfPkgs" => $boxInOrder,
                            "fromPkgNo" => $pakcageSeries->frmNo,
                            "toPkgNo" => $pakcageSeries->toNo,
                            "pkgDetails" => $pkgDetails,
                            "CustDeliveyDate"  => "",
                            "SPL_Instruction"  => "",
                            "prodServCode"  => "1",
                            "custVendCode"  => $shipment['warehouse_code']?$shipment['warehouse_code']:'',
                            "goodsDesc"  =>$productData[0]['product_description']?$productData[0]['product_description']:'',
                            "bookingBasis" => $bookingBase,
                            "locationCode" => $returnLocationcode->serviceDtls[0]->locationCode,
                            "UOM" => "I",
                            "consignorGSTINNo" => "36AABCG00110LZ1",
                            "ReceiverGSTINNo" => "11FAFFCG032Z2",
                            "EWAYBILL" => "",
                            "EWB_EXP_DT" => ""//"10-01-2018"
                        );
                        $orderProcess = array
            		    (
            		        "custCode" => $mapArray['customer_code'],
                			"pickupRequest"  => date('d-m-Y H:i:s'),
                			'details' => $details
            		    );
                            #echo '<pre>';
                            $orderProcess = json_encode($orderProcess);
                           
                            $curl = curl_init();
                            curl_setopt_array($curl, array(
                            CURLOPT_URL => 'http://119.235.57.47:9080/pickupservices/GATIKWEJPICKUPLBH.jsp',
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS => $orderProcess,
                              CURLOPT_HTTPHEADER => array(
                                'Content-Type: application/json',
                                'Cookie: JSESSIONID=2FC61EC7C8E8D5A9D9EA77A71463BA38'
                              ),
                            ));
                            $response = curl_exec($curl);
                            curl_close($curl);
                            
                           # echo $response = trim($response);
                            $response = preg_replace('/[[:cntrl:]]/', '', $response);
                            
                            $arrayData = json_decode($response, true);
                            if($arrayData) 
                            {
                                if($arrayData['postedData']=='successful')
                                {
                                    $warehouse_ou = $mapArray['warehouse_ou']; //'GGN';
                                    $shipping_label='';
                                    $ship_label = $this->gati_packing_slip($returnDoketNo->docketNo,$childAwbNo,$warehouse_ou,$returnLocationcode->serviceDtls[0]->locationCode);
                                    if(isset($ship_label) && $ship_label!='')
                                    {
                                        $shipping_label = $ship_label;
                                    }
                                    $status = isset($data['details'][0]['errmsg']) ? $data['details'][0]['errmsg'] :'success';
                                    $data['awb_number'] = $returnDoketNo->docketNo ? $returnDoketNo->docketNo : '';
                                    $data['shipping_label'] = $shipping_label;
                                    $data['StatusCode'] = '';
                                    $data['status'] = $status;
                                    $data['message'] = $arrayData['postedData']?$arrayData['postedData']:'';
                                    $data['courier_id'] = 0;
                                    $data['courier_name'] = "GATI";
                                    $data['packing_slip'] = '';
                                    $data['child_awbNo'] = $childAwbNo;
                                    $data['docket_print'] = $docketPrintData;
                                    $data['locationcode'] = $returnLocationcode->serviceDtls[0]->locationCode;
                                }
                                else
                                {
                                    $status = isset($data['details'][0]['errmsg']) ? $data['details'][0]['errmsg'] :'failed';
                                    $data['awb_number'] = '';
                                    $data['shipping_label'] = '';
                                    $data['StatusCode'] = '';
                                    $data['status'] = $status;
                                    $data['message'] = $arrayData['postedData']?$arrayData['postedData']:'';
                                    $data['courier_id'] =0;
                                    $data['courier_name'] = "GATI";
                                    $data['packing_slip'] = '';
                                    $data['child_awbNo'] = '';
                                    $data['docket_print']='';
                                    $data['locationcode'] = $returnLocationcode->serviceDtls[0]->locationCode;
                                }
                                return $data;
                            }
                            else
                            {
                                $errorCode = json_last_error();
                                $errorMessage = json_last_error_msg();
                                $result['message'] = 'JSON decoding failed with error code $errorCode: $errorMessage';
                				$result['status'] = false;
                				return $result;
                            }
    						
                    }
                    else
                    {
                        $result['message'] = 'Package series issue!';
        				$result['status'] = false;
        				return $result;
                    }
                }
                else
                {
                    $result['message'] = 'Docket no. issue!';
    				$result['status'] = false;
    				return $result;
                }
            }
            else
            {
                $result['message'] = 'Pincode service is not available!';
    				$result['status'] = false;
    				return $result;
            }
        }
        else
        {
            $result['message'] = 'Authentication issue occur!';
			$result['status'] = false;
			return $result;
        }
    }
    public function reprocessOrder($shipment,$mapArray)
    {
       if($mapArray['customer_code']!='' &&  $mapArray['warehouse_ou']!='' && $mapArray['auth_key']!='' && $mapArray['auth_secret']!='')
        {
            #echo "GatiApp";die;
    		$pincode = $shipment->input('shipping_consignee.consignee_pincode');
    		$pincodeService = $this->gati_pincode_serviceability($pincode,$mapArray['auth_secret']);
    		 
            $returnLocationcode = json_decode($pincodeService);
           
            if(!empty($returnLocationcode))
            {
                #print_r($returnLocationcode);die;
                $returnDoket = $this->gati_download_doket($mapArray['auth_key']);
                $returnDoketNo = json_decode($returnDoket);
                $boxInOrder =  $shipment->input('no_of_boxes');
    			$boxTotalWeight = $shipment->input('total_weight');
    			$qtyTotal = array_sum(array_column($shipment->products,'product_quantity'));
    			$childAwbNo ='';
                if(isset($returnDoketNo->docketNo) && $returnDoketNo->docketNo !='')
                {
                    $docketPrintData = '';
                    //$clientId='30457301';
                    $docketPrint = $this->gati_doket_printing($returnDoketNo->docketNo, $mapArray['customer_code']);
                    if(isset($docketPrint) && ($docketPrint!=''))
                    {
                        $docketPrintData = $docketPrint;
                    }
                    $pakcageSeriesData = $this->gati_package_series($returnDoketNo->docketNo,$boxInOrder,$mapArray['auth_key'],$pincode);
    		        $pakcageSeries = json_decode($pakcageSeriesData);
    		        if(isset($pakcageSeries) && isset($pakcageSeries->frmNo) && $pakcageSeries->frmNo != '')
    		        {
    	                $firstPackage =  $pakcageSeries->frmNo - 1;
    			        $i = 0;
    			        $product_detail = array();
        			    foreach($shipment->box_detail as $line)
        			    {
        			        $firstPackage = $firstPackage + 1;
        			        $product_detail[$i]['pkgNo'] = $firstPackage++;
        					$product_detail[$i]['pkgLn'] = $line['length'];
        					$product_detail[$i]['pkgBr'] = $line['breadth'];
        					$product_detail[$i]['pkgHt'] = $line['height'];
        					$product_detail[$i]['pkgWt'] = $line['weight'] * 1000;
        					$i++;
        				}
        				
        				$pkgDetails = array
                        (
                            'pkginfo' => $product_detail
                        );
                        $total_amountCollect = 0;
                        $bookingBase = 4;
                        $total_amount = round($shipment->input('total_amount'),2);  
    					if ($shipment->input('payment_mode') == 'COD') 
    					{
                            $total_amountCollect = $total_amount;
                            $bookingBase = 1;
                        }
                        else 
                        {
                            $shipment->payment_mode = 'prepaid';
                        }
                        $shipPone = explode(",", $shipment->input('shipping_consignee.consignee_phone'));
    					$shipaddress2 = $shipment->input('shipping_consignee.consignee_address_2')?$shipment->input('shipping_consignee.consignee_address_2'):$shipment->input('shipping_consignee.consignee_address');
                        $details[] = array
                        (
                            "docketNo" => $returnDoketNo->docketNo,
                            "deliveryStn"  => "342001",
                            "goodsCode"  => "206",
                            "declCargoVal"  => ''.round($total_amount,2),
                            "actualWt"  => $boxTotalWeight,
                            "chargedWt"  => $boxTotalWeight,
                            "shipperCode"  => $mapArray['customer_code'],
                            "orderNo" => $shipment->input('order_no'),
                            "codAmt"  => round($total_amountCollect,2),
                            "codInFavourOf"  => "G",
                            "receiverCode"  => "99999",
                            "receiverName" => $shipment->input('shipping_consignee.consignee_name'),
                            "receiverAdd1" => preg_replace('/[^a-zA-Z0-9_ -]/s',' ',$shipment->input('shipping_consignee.consignee_address')),
                            "receiverAdd2" => ''.preg_replace('/[^a-zA-Z0-9_ -]/s',' ',$shipaddress2),
                            "receiverAdd3" => "",
                            "receiverAdd4" => "",
                            "receiverCity" => $shipment->input('shipping_consignee.consignee_city')?$shipment->input('shipping_consignee.consignee_city'):'',
                            "receiverPhoneNo" => trim($shipPone[0]),
                            "receiverMobileNo" => trim($shipPone[0]),
                            "receiverEmail"  => $shipment->input('shipping_consignee.consignee_email')?$shipment->input('shipping_consignee.consignee_email'):'cust@cust.com',
                            "receiverPinCode" => $pincode,
                            "noOfPkgs" => $boxInOrder,
                            "fromPkgNo" => $pakcageSeries->frmNo,
                            "toPkgNo" => $pakcageSeries->toNo,
                            "pkgDetails" => $pkgDetails,
                            "CustDeliveyDate"  => "",
                            "SPL_Instruction"  => "",
                            "prodServCode"  => "1",
                            "custVendCode"  => $shipment->input('origin.origin_code')?$shipment->input('origin.origin_code'):'',
                            "goodsDesc"  =>$shipment->products[0]['Instruction']?$shipment->products[0]['Instruction']:'',
                            "bookingBasis" => $bookingBase,
                            "locationCode" => $returnLocationcode->serviceDtls[0]->locationCode,
                            "UOM" => "I",
                            "consignorGSTINNo" => "36AABCG00110LZ1",
                            "ReceiverGSTINNo" => "11FAFFCG032Z2",
                            "EWAYBILL" => "",
                            "EWB_EXP_DT" => ""//"10-01-2018"
                        );
                        $orderProcess = array
            		    (
            		        "custCode" => $mapArray['customer_code'],
                			"pickupRequest"  => date('d-m-Y H:i:s'),
                			'details' => $details
            		    );
                            #echo '<pre>';
                            $orderProcess = json_encode($orderProcess);
                            $curl = curl_init();
                            curl_setopt_array($curl, array(
                            CURLOPT_URL => 'http://119.235.57.47:9080/pickupservices/GATIKWEJPICKUPLBH.jsp',
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS => $orderProcess,
                              CURLOPT_HTTPHEADER => array(
                                'Content-Type: application/json',
                                'Cookie: JSESSIONID=2FC61EC7C8E8D5A9D9EA77A71463BA38'
                              ),
                            ));
                            $response = curl_exec($curl);
                            curl_close($curl);
                           # echo $response = trim($response);
                            $response = preg_replace('/[[:cntrl:]]/', '', $response);
                            $arrayData = json_decode($response, true);
                            $childAwbNo = $pakcageSeries->frmNo.'_'.$pakcageSeries->toNo;
                            
                            if($arrayData) 
                            {
                                if($arrayData['postedData']=='successful')
                                {
                                    
                                    $warehouse_ou = $mapArray['warehouse_ou'];//'GGN';
                                    $shipping_label='';
                                    $ship_label = $this->gati_packing_slip($returnDoketNo->docketNo,$childAwbNo,$warehouse_ou,$returnLocationcode->serviceDtls[0]->locationCode);
                                    if(isset($ship_label) && $ship_label!='')
                                    {
                                        $shipping_label = $ship_label;
                                    }
                                    
                                    $status = isset($data['details'][0]['errmsg']) ? $data['details'][0]['errmsg'] :'success';
                                    $data['awb_no'] = $returnDoketNo->docketNo ? $returnDoketNo->docketNo : '';
                                    $data['shipping_label'] = $shipping_label;
                                    $data['StatusCode'] = '';
                                    $data['status'] = $status;
                                    $data['message'] = $arrayData['postedData']?$arrayData['postedData']:'';
                                    $data['courier_id'] = 0;
                                    $data['courier_name'] = "GATI";
                                    $data['packing_slip'] = '';
                                    $data['child_awbNo'] = $childAwbNo;
                                    $data['docket_print'] = $docketPrintData;
                                    $data['locationcode'] = $returnLocationcode->serviceDtls[0]->locationCode;
                                }
                                else
                                {
                                    $status = isset($data['details'][0]['errmsg']) ? $data['details'][0]['errmsg'] :'failed';
                                    $data['awb_no'] = '';
                                    $data['shipping_label'] = '';
                                    $data['StatusCode'] = '';
                                    $data['status'] = $status;
                                    $data['message'] = $arrayData['postedData']?$arrayData['postedData']:'';
                                    $data['courier_id'] =0;
                                    $data['courier_name'] = "GATI";
                                    $data['packing_slip'] = '';
                                    $data['child_awbNo'] = '';
                                    $data['docket_print'] = '';
                                    $data['locationcode'] ='';
                                }
                                return $data;
                            }
                            else
                            {
                                $errorCode = json_last_error();
                                $errorMessage = json_last_error_msg();
                                $result['message'] = 'JSON decoding failed with error code $errorCode: $errorMessage';
                				$result['status'] = false;
                				return $result;
                            }
    						
                    }
                    else
                    {
                        $result['message'] = 'Package series issue!';
        				$result['status'] = false;
        				return $result;
                    }
                }
                else
                {
                    $result['message'] = 'Docket no. issue!';
    				$result['status'] = false;
    				return $result;
                }
            }
            else
            {
                $result['message'] = 'Pincode service is not available!';
    			$result['status'] = false;
    			return $result;
            }
        }
        else
        {
            $result['message'] = 'Authentication issue occur!';
			$result['status'] = false;
			return $result;
        }
    }
    public function trackShipment($awbNo,$mapArray)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
         CURLOPT_URL => 'http://119.235.57.47:9080/pickupservices/GatiKWEDktJTrack.jsp?p1='.$awbNo.'&p2='.$mapArray[0]['auth_secret'],
          #CURLOPT_URL => 'https://justi.gati.com/webservices/GatiKWEDktJTrack.jsp?p1=398981713&p2=1C7CBE58A514BACE',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Cookie: JSESSIONID=2FC61EC7C8E8D5A9D9EA77A71463BA38'
          ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        #echo $response;
        $responseArr = json_decode($response);
        $trackDel = $responseArr;
        if(isset($responseArr->Gatiresponse->dktinfo[0]->DOCKET_STATUS))
		{
		    $trackDel = $responseArr;
		    $dataArray = [
                'data' => [
                    [
                        'awb_number' =>$awbNo,
                        'remarks' => $trackDel->Gatiresponse->dktinfo[0]->DOCKET_STATUS.' on '.$trackDel->Gatiresponse->dktinfo[0]->DELIVERY_DATETIME.' to '.$trackDel->Gatiresponse->dktinfo[0]->RECEIVER_NAME,
                        'status' => $trackDel->Gatiresponse->dktinfo[0]->DOCKET_STATUS ?? '',
                        'history' => [],
                    ],
                ],
            ];	
            if (
                isset($trackDel->Gatiresponse->dktinfo[0]) &&
                isset($trackDel->Gatiresponse->dktinfo[0]->TRANSIT_DTLS[0]) && // Corrected the array index
                $trackDel->Gatiresponse->dktinfo[0]->TRANSIT_DTLS[0]->INTRANSIT_DATE != '' &&
                $trackDel->Gatiresponse->dktinfo[0]->TRANSIT_DTLS[0]->INTRANSIT_TIME != '' &&
                $trackDel->Gatiresponse->dktinfo[0]->TRANSIT_DTLS[0]->INTRANSIT_LOCATION != '' &&
                $trackDel->Gatiresponse->dktinfo[0]->TRANSIT_DTLS[0]->INTRANSIT_STATUS_CODE != '' &&
                $trackDel->Gatiresponse->dktinfo[0]->errmsg != '' 
                )
             {
                $dataArray['data'][0]['history'][] = [
                    'event_time'   => $trackDel->Gatiresponse->dktinfo[0]->TRANSIT_DTLS[0]->INTRANSIT_DATE . ' ' . $trackDel->Gatiresponse->dktinfo[0]->TRANSIT_DTLS[0]->INTRANSIT_TIME,
                    'location'     => $trackDel->Gatiresponse->dktinfo[0]->TRANSIT_DTLS[0]->INTRANSIT_LOCATION,
                    'status_code'  => $trackDel->Gatiresponse->dktinfo[0]->TRANSIT_DTLS[0]->INTRANSIT_STATUS_CODE,
                    'message'      => $trackDel->Gatiresponse->dktinfo[0]->errmsg,
                ];
            }
            else
            {
                $dataArray['data'][0]['history'][] = [
                    'event_time'   => '',
                    'location'     => '',
                    'status_code'  => '',
                    'message'      => '',
                ];
            }
		}
		else if(isset($trackDel->Gatiresponse->dktinfo[0]) && ($trackDel->Gatiresponse->dktinfo[0]->errmsg !=''))
		{
			$dataArray = [
                'data' => [
                    [
                        'awb_number' =>$awbNo,
                        'remarks' => $trackDel->Gatiresponse->dktinfo[0]->errmsg,
                        'status' => '',
                        'history' => [],
                    ],
                ],
            ];
            
            $dataArray['data'][0]['history'][] = [
                    'event_time'  => '',
                    'location'    => '',
                    'status_code' =>'',
                    'message'     => $trackDel->Gatiresponse->dktinfo[0]->errmsg,
                ];
			
		}
		else
		{
		    $dataArray = [
                'data' => [
                    [
                        'awb_number' =>$awbNo,
                        'remarks' => '',
                        'status' => '',
                        'history' => [],
                    ],
                ],
            ];
            
            $dataArray['data'][0]['history'][] = [
                    'event_time'  => '',
                    'location'    => '',
                    'status_code' =>'',
                    'message'     => $trackDel->Gatiresponse->dktinfo[0]->errmsg,
                ];
		}
		
		return $dataArray;
    }
    public function trackSingleShipment($awbNo,$mapArray)
    {
       
		$curl = curl_init();
        curl_setopt_array($curl, array(
         CURLOPT_URL => 'http://119.235.57.47:9080/pickupservices/GatiKWEDktJTrack.jsp?p1='.$awbNo.'&p2='.$mapArray[0]['auth_secret'],
          #CURLOPT_URL => 'https://justi.gati.com/webservices/GatiKWEDktJTrack.jsp?p1=398981713&p2=1C7CBE58A514BACE',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Cookie: JSESSIONID=2FC61EC7C8E8D5A9D9EA77A71463BA38'
          ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        #echo $response;
        $responseArr = json_decode($response);
        $trackDel = $responseArr;
        if(isset($responseArr->Gatiresponse->dktinfo[0]->DOCKET_STATUS))
		{
		    $trackDel = $responseArr;
		    $dataArray = [
                'data' => [
                    [
                        'awb_number' =>$awbNo,
                        'remarks' => $trackDel->Gatiresponse->dktinfo[0]->DOCKET_STATUS.' on '.$trackDel->Gatiresponse->dktinfo[0]->DELIVERY_DATETIME.' to '.$trackDel->Gatiresponse->dktinfo[0]->RECEIVER_NAME,
                        'status' => $trackDel->Gatiresponse->dktinfo[0]->DOCKET_STATUS ?? '',
                        'history' => [],
                    ],
                ],
            ];	
            if (
                isset($trackDel->Gatiresponse->dktinfo[0]) &&
                isset($trackDel->Gatiresponse->dktinfo[0]->TRANSIT_DTLS[0]) && // Corrected the array index
                $trackDel->Gatiresponse->dktinfo[0]->TRANSIT_DTLS[0]->INTRANSIT_DATE != '' &&
                $trackDel->Gatiresponse->dktinfo[0]->TRANSIT_DTLS[0]->INTRANSIT_TIME != '' &&
                $trackDel->Gatiresponse->dktinfo[0]->TRANSIT_DTLS[0]->INTRANSIT_LOCATION != '' &&
                $trackDel->Gatiresponse->dktinfo[0]->TRANSIT_DTLS[0]->INTRANSIT_STATUS_CODE != '' &&
                $trackDel->Gatiresponse->dktinfo[0]->errmsg != '' 
                )
             {
                $dataArray['data'][0]['history'][] = [
                    'event_time'   => $trackDel->Gatiresponse->dktinfo[0]->TRANSIT_DTLS[0]->INTRANSIT_DATE . ' ' . $trackDel->Gatiresponse->dktinfo[0]->TRANSIT_DTLS[0]->INTRANSIT_TIME,
                    'location'     => $trackDel->Gatiresponse->dktinfo[0]->TRANSIT_DTLS[0]->INTRANSIT_LOCATION,
                    'status_code'  => $trackDel->Gatiresponse->dktinfo[0]->TRANSIT_DTLS[0]->INTRANSIT_STATUS_CODE,
                    'message'      => $trackDel->Gatiresponse->dktinfo[0]->errmsg,
                ];
            }
            else
            {
                $dataArray['data'][0]['history'][] = [
                    'event_time'   => '',
                    'location'     => '',
                    'status_code'  => '',
                    'message'      => '',
                ];
            }
		}
		else if(isset($trackDel->Gatiresponse->dktinfo[0]) && ($trackDel->Gatiresponse->dktinfo[0]->errmsg !=''))
		{
			$dataArray = [
                'data' => [
                    [
                        'awb_number' =>$awbNo,
                        'remarks' => $trackDel->Gatiresponse->dktinfo[0]->errmsg,
                        'status' => '',
                        'history' => [],
                    ],
                ],
            ];
            
            $dataArray['data'][0]['history'][] = [
                    'event_time'  => '',
                    'location'    => '',
                    'status_code' =>'',
                    'message'     => $trackDel->Gatiresponse->dktinfo[0]->errmsg,
                ];
			
		}
		else
		{
		    $dataArray = [
                'data' => [
                    [
                        'awb_number' =>$awbNo,
                        'remarks' => '',
                        'status' => '',
                        'history' => [],
                    ],
                ],
            ];
            
            $dataArray['data'][0]['history'][] = [
                    'event_time'  => '',
                    'location'    => '',
                    'status_code' =>'',
                    'message'     => $trackDel->Gatiresponse->dktinfo[0]->errmsg,
                ];
		}
		
		return $dataArray;
        #echo $responseArr->Gatiresponse->dktinfo[0]->POD;
       
    }
    public function cancelledShipment($awbNo,$mapArray)
    {
        
        $clientId= $mapArray[0]['customer_code'];
       
		if(!empty($awbNo))
		{
            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => 'http://119.235.57.47:9080/pickupservices/b2bCanPickup.jsp',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS =>'{
                "pickupRequest": "06-04-2021 16:49:59",
                "custCode": "'.$clientId.'",
                "details": [{
                        "docketNo": "'.$awbNo.'",
                        "shipperCode": "'.$clientId.'",
                        "orderNo": "'.$mapArray['order_no'].'",
                        "canReason": "Customer Order cancel "
                    }
                ]
            }
            ',
              CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Cookie: JSESSIONID=26AB5E5C8D399E46B3A3E35C23577654'
              ),
            ));
            $response = curl_exec($curl);
            
            curl_close($curl);
            $arrayData = json_decode($response,true);
           
            $status = '';
            $response = [];
           
            if($arrayData['postedData']=='successful')
            {
                if(isset($arrayData['details'][0]['errmsg']) && ($arrayData['details'][0]['errmsg']) == 'Docket Cancelled')
                {
                    $status = 'cancelled';
                }
                
                $remarks = $arrayData['details'][0]['errmsg'];
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
	    if($warehouse)
	    {
	        if($mapArray && $mapArray['customer_code']!='')
	        {
	            
        	    $warehouseDetail = json_decode($warehouse,true);
        	    $address2 = $warehouseDetail['warehouse']['warehouse_address2']?$warehouseDetail['warehouse']['warehouse_address2']:$warehouseDetail['warehouse']['warehouse_address1'];
                $vendorReg = '{
                    "custCode": "'.$mapArray['customer_code'].'",
                    "details": [{ 
                        "custVendorCode": "'.$warehouseDetail['warehouse']['warehouse_code'].'",
                        "custVendorName": "'.$warehouseDetail['warehouse']['warehouse_name'].'",
                        "vendorAdd1": "'.$warehouseDetail['warehouse']['warehouse_address1'].'",
                        "vendorAdd2": "'.$warehouseDetail['warehouse']['warehouse_address2'].'",
                        "vendorAdd3": "",
                        "vendorCity": "'.$warehouseDetail['warehouse']['warehouse_city'].'",
                        "vendorPhoneNo": "'.$warehouseDetail['warehouse']['warehouse_phone'].'",
                        "vendorPincode": "'.$warehouseDetail['warehouse']['warehouse_pincode'].'",
                        "vendorEmail": "'.$warehouseDetail['warehouse']['warehouse_email'].'",
                        "vendorReceiverFlag": "V",
                        "vendorTinno": "",
                        "VendorGSTNO": ""
                    }]}';
                #echo 'K'.$vendorReg;
                #die;
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => 'http://119.235.57.47:9080/pickupservices/GKEJCustVendDtls.jsp',
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS =>$vendorReg,
                  CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                  ),
                ));
                $response = curl_exec($curl);
                $arrayData = json_decode($response,true);
                curl_close($curl);
                $success ='';
                $remark ='';
                $response = [];
                if($arrayData['postedData']=='successful')
                {
                    if(isset($arrayData['details'][0]['errmsg']) && $arrayData['details'][0]['errmsg'] =='succes')
                    {
                        $status = 'success';
                    }
                    $remarks = $arrayData['details'][0]['errmsg'];
                }
                else
                {
                    $remarks = 'failed';
                }
                $response['status'] = $status;
                $response['remark'] = $remarks;
                $response['message'] = $arrayData;
                return $response;
	        }
	        else
	        {
	            $response['status'] = 'false';
	            $response['remark'] = $remarks;
                $response['message'] = 'Authentication issue';
                return $response;
	            
	        }
	    }
	    else
	    {
	        $response['status'] = 'false';
	        $response['remark'] = $remarks;
            $response['message'] = 'warehouse detail not found';
            return $response;
	    }
	}



}