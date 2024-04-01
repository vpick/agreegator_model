<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\LogisticsMapping;
use App\Models\OrderStatus;
use App\Models\Client;
use App\Interfaces\AppOrderProcessInterface;
use Auth,Redirect;
class TrackingController extends Controller
{
    public function trackShipment($request,$mapArray,$orderType)
    {
        
		// Resolve the appropriate service based on the order type
        $myService = app()->make(AppOrderProcessInterface::class, ['ordersendTo' => $orderType]);
        // Now you can use $myService based on the order type.
        // For example, if $orderType is 'type1', the corresponding ServiceOne will be injected.
        return $myService->trackShipment($request,$mapArray);
    }
   
    public function shipment_track()
    {
	    $ignore = OrderStatus::pluck('order_status')->toArray();
        $oderDetails = Order::select('order_no','client_code','awb_no','request_partner')
                            ->where('awb_no','!=','')
                            ->whereNotIn('order_status',$ignore)
                            ->where('client_code',Auth::user()->client->client_code)
                            ->get(); 
        
        foreach($oderDetails as $oderDetail)
        {
            $order_no = $oderDetail->order_no;
            $client = $oderDetail->client_code;      
            $partner = $oderDetail->request_partner;
            $ords = Order::select('awb_no')->where('client_code',$client)->where('request_partner',$partner)->where('awb_no','!=','')->whereNotIn('order_status',$ignore)->get();
            $awbNo = []; 
            foreach ($ords as $ord) 
            {
                $awbNo[] = $ord->awb_no; 
            }
            $creds[] = Client::select('log.*')
              		->join('logistics_mappings as log', 'log.client_id', '=', 'clients.id')
                    ->where('log.partner_name',$oderDetail->request_partner)
              		->where('clients.client_code',$oderDetail->client_code)
              		->get()->toArray();    
                          
            foreach ($creds as $credList) 
            {
                $credData = array();          
                foreach ($credList as $cred) 
                {
                    $credData = $cred;
                }    
            }  
            $records[] = array
            (
                'awb_no' => $awbNo,
                'client' => $client,
                'partner' => $partner,
                'track' => $credData
            );                  
        }   
          
        $collection = collect($records);
        $groups = $collection->groupBy(['client','partner']);            
        $groups->all();
        $inputArray=$collection->toArray();
        
        $keyGenerator = function ($subarray) {
            return $subarray['client'] . '|' . $subarray['partner'];
        };

        // Group the subarrays by their unique keys
        $groupedArrays = [];
        foreach ($inputArray as $subarray) {
            $key = $keyGenerator($subarray);
            $groupedArrays[$key][] = $subarray;
        }

        // Create the final array without duplicates
        $finalArray = [];
        foreach ($groupedArrays as $group) {
            $finalArray[] = reset($group); // Pick the first subarray from each group
        }
       #dd($finalArray);
        foreach($finalArray as $final){
            
            $ordersendTo = $final['partner'];    //'NimbusAppTrack';
            $finalResponse[] = app(ApiOrderController::class)->trackShipment($final['awb_no'],$final['track'],$ordersendTo);              
        }
        #dd($finalResponse);   
        foreach($finalResponse as $response){
            if($response!=''){                        
                foreach($response['data'] as $detail){                     
                    $record = Order::where('order_no', $detail['order_number'])
                                    ->where('awb_no', $detail['awb_number'])
                                    ->first();  
                             
                    if ($record){
                        $record->order_status = ucwords($detail['status']);
                        $record->tracking_history = $detail;
                        $record->save();                            
                    }
                }
            }
        }           
        return $finalResponse;           
    }
    public function trackSingleShipment($request,$mapArray,$orderType)
    {
       
		// Resolve the appropriate service based on the order type
        $myService = app()->make(AppOrderProcessInterface::class, ['ordersendTo' => $orderType]);
        // Now you can use $myService based on the order type.
        // For example, if $orderType is 'type1', the corresponding ServiceOne will be injected.
        return $myService->trackSingleShipment($request,$mapArray);
    }
    public function single_shipment_track(Request $request){
      
        $seg = $request->segment(2);
        $awbNo = \Crypt::decrypt($seg);
        $ignore = OrderStatus::pluck('order_status')->toArray();
        $oderDetail = Order::where('awb_no',$awbNo)->get();
        if($oderDetail){
    		$mapArray = Client::select('log.*')
                  		->join('logistics_mappings as log', 'log.client_id', '=', 'clients.id')
                        ->where('log.partner_name',$oderDetail[0]->request_partner)
                  		->where('clients.client_code',$oderDetail[0]->client_code)
                  		->get()->toArray();   
            if($mapArray){
                
        		$ordersendTo =$oderDetail[0]->request_partner;  //NimbusAppSingleTrack
                $finalResponse = app(TrackingController::class)->trackSingleShipment($awbNo,$mapArray,$ordersendTo);
              
                if($finalResponse && !empty($finalResponse['data'][0]))
                {
                     
                    /*Update the order status*/
                    // Fetch the record based on order_no and awb_number
                    $record = Order::where('order_no', $oderDetail[0]->order_no)
                                          ->where('awb_no', $finalResponse['data'][0]['awb_number'])
                                          ->first();
                                          
                    if ($record) 
                    {
                       
                        // Update the required fields
                        if((strtolower($finalResponse['data'][0]['status']) == strtolower('Online Shipment Booked')) || (strtolower($finalResponse['data'][0]['status']) == strtolower('Incorrect Waybill number or No Information')) || $finalResponse['data'][0]['status']=='') {
                            
                            $current_status = 'Booked';
                        }
                        else
                        {
                            
                            $current_status = $finalResponse['data'][0]['status'];
                        }
                        $record->remarks = isset($finalResponse['data'][0]['remarks']) ? $finalResponse['data'][0]['remarks'] :$finalResponse['data'][0]['status'] ;
                        $record->order_status = ucwords($current_status);
                        $record->tracking_history = $finalResponse['data'][0];
                        // Add more fields you want to update
                       
                        try{  
                          
                            $record->save();         
                            return redirect()->route('app.track',(\Crypt::encrypt($record->id)));
                        }
                        catch(Exception $e) 
                        {
                            return Redirect::back()->with(['error' => $e->getMessage()]);
                        } 
                    }
                    else
                    {
                        return Redirect::back()->with(['error' => 'Tracking not updated']);
                    }
                }
                else
                {
                    return Redirect::back()->with(['error' => 'Tracking not available']);
                }
            }
            else
            {
                return Redirect::back()->with(['error' => 'configuration issue found']);
            }
        }
        else
        {
            return Redirect::back()->with(['error' => 'Order detail not found']);
        }
		
    }
    public function single_track(Request $request)
    {
        $seg = $request->segment(2);
        $awbNo = \Crypt::decrypt($seg);
        $ignore = OrderStatus::pluck('order_status')->toArray();
        $oderDetail = Order::where('awb_no',$awbNo)->get();
        if($oderDetail)
        {
    		$mapArray = Client::select('log.*')
                  		->join('logistics_mappings as log', 'log.client_id', '=', 'clients.id')
                        ->where('log.partner_name',$oderDetail[0]->request_partner)
                  		->where('clients.client_code',$oderDetail[0]->client_code)
                  		->get()->toArray();   
            if($mapArray)
            {

        		$ordersendTo = $oderDetail[0]->request_partner; //'NimbusAppSingleTrack';
                $finalResponse = app(TrackingController::class)->trackSingleShipment($awbNo,$mapArray,$ordersendTo);
                
                if($finalResponse && !empty($finalResponse['data'][0]))
                {
                    /*Update the order status*/
                    // Fetch the record based on order_no and awb_number
                    $record = Order::where('order_no', $oderDetail[0]->order_no)
                                          ->where('awb_no', $finalResponse['data'][0]['awb_number'])
                                          ->first();
                    if ($record) 
                    {
                       
                        if((strtolower($finalResponse['data'][0]['status']) == strtolower('Online Shipment Booked')) || (strtolower($finalResponse['data'][0]['status']) == strtolower('Incorrect Waybill number or No Information')) || $finalResponse['data'][0]['status']=='') {
                            
                            $current_status = 'Booked';
                        }
                        else
                        {
                            
                            $current_status = $finalResponse['data'][0]['status'];
                        }
                        $record->remarks = isset($finalResponse['data'][0]['remarks']) ? $finalResponse['data'][0]['remarks'] :$finalResponse['data'][0]['status'] ;
                        $record->order_status = ucwords($current_status);
                        $record->tracking_history = $finalResponse['data'][0];
                
                        try{  
                            
                            $record->save();         
                            return redirect()->route('single.track.card',(\Crypt::encrypt($record->id)));
                        }
                        catch(Exception $e) 
                        {
                            return Redirect::back()->with(['error' => $e->getMessage()]);
                        } 
                    }
                    else
                    {
                        return Redirect::back()->with(['error' => 'Tracking not updated']);
                    }
                }
                else
                {
                    return Redirect::back()->with(['error' => 'Tracking not available']);
                }
            }
            else
            {
                return Redirect::back()->with(['error' => 'configuration issue found']);
            }
        }
        else
        {
            return Redirect::back()->with(['error' => 'Order detail not found']);
        }
    }
}
