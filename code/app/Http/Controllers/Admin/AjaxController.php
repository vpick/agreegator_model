<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Client;
use App\Models\Warehouse;
use App\Models\User;
use App\Models\AppLogistics;
use App\Models\UserPermission;
use App\Models\Region;
use App\Models\Weight;
use App\Models\Rate;
use App\Models\Zone;
use App\Models\Pincode;
use App\Models\LogisticsMapping;
use Response,Auth,DB,Session;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class AjaxController extends Controller
{
    public function getCompanyDetail($id) {
        $data = Company::with('state')->find($id);
        if(empty($data)){
            return Response::json(['error' => 'Data not found!!.'], 404);
        } 
        return Response::json(['data' => $data]);
    } 
    public function getClientDetail($id) {
        $data = Client::with('state','company')->find($id);
        if(empty($data)){
            return Response::json(['error' => 'Data not found!!.'], 404);
        } 
        return Response::json(['data' => $data]);
    } 
    public function getWarehouseDetail($id) {
        $data = Warehouse::with('state','company','client')->find($id);
        if(empty($data)){
            return Response::json(['error' => 'Data not found!!.'], 404);
        } 
        return Response::json(['data' => $data]);
    } 
    public function getClient($id) {
        $data = Client::where('company_id', $id)
                ->with('company')
                ->get();
        return Response::json(['data' => $data]);
    } 
    public function getWarehouse($id) {
        $data = Warehouse::where('client_id', $id)
                ->with('client')
                ->get();
        return Response::json(['data' => $data]);
    } 
    public function clientwarehousemap(Request $request) {       
        $data = User::find($request->user_id);
       
        if(empty($data)){
            return Response::json(['error' => 'Data not found!!.'], 404);
        }       
        $strClient =  $data->client_map;
        $arrClient = explode(',',$strClient);
        $reqClient = $request->client_id;
        $arrReqClient = explode(',',$reqClient);
        $filteredClientArray = array_diff($arrReqClient, $arrClient);
        // Combine the filtered array with $arr
        $concatenatedArray = array_merge($arrClient, $filteredClientArray);
        //warehouse array
        $strWare =  $data->warehouse_map;
        $arrWare = explode(',',$strWare);
        $reqWare = $request->warehouse_id;
        $arrReqWare = explode(',',$reqWare);
        if($request->inputType == 'checkbox'){
            if($request->action == 'add')
            {           
                $status="add";
                $filteredWareArray = array_diff($arrReqWare, $arrWare);
                // Combine the filtered array with $arr
                $concatenatedWareArray = array_merge($arrWare, $filteredWareArray);
                $data->client_map = implode(',', $concatenatedArray);    
                $data->warehouse_map = implode(',', $concatenatedWareArray); 
            }
            else{
                $status="remove";
                $index = array_search($request->warehouse_id, $arrWare);
                if ($index !== false) {
                    if (isset($arrWare[$index])) {
                        $removedValue = $arrWare[$index];
                        array_splice($arrWare, $index, 1);
                    }
                } else {
                    return Redirect::back()->withInput(['error' => 'warehouse not assigned']);
                }
                $data->client_map = implode(',', $concatenatedArray);    
                $data->warehouse_map = implode(',', $arrWare);     
            } 
        }
        else{
            if($request->action == 'add')
            {           
                $status="add";
                $wdd = Warehouse::select('id')
                    ->whereIn('id', $arrWare)
                    ->where('client_id', $request->client_id)
                    ->get()
                    ->pluck('id')
                    ->toArray();
                   
                if(!empty($wdd)){   
                    $uniqueArray1 = array_diff($arrWare, array_map('intval', $wdd));                    
                    // Combine the filtered array with $arr
                    $concatenatedWareArray = array_merge($uniqueArray1, $arrReqWare);
                }
                else{
                    $concatenatedWareArray = array_merge($arrWare, $arrReqWare);
                }
                $data->client_map = implode(',', $concatenatedArray);    
                $data->warehouse_map = implode(',', $concatenatedWareArray); 
            }
        }
        try{
            $data->save();
            return Response::json(['data' => $status]);
        }
        catch(Exception $e) {
            return Response::json(['error' => $e->getMessage()],  500);
        }
    } 
    public function userPermit(Request $request) {       
        $data = User::find($request->user_id);
        if(empty($data)){
            return Response::json(['error' => 'User not found!!.'], 404);
        }   
        $permission = UserPermission::where('user_id',$data->id)->where('page_id',$request->page_id)->get(); 
        if(count($permission)>0){
            $permit = UserPermission::find($permission[0]->id);
            if($request->pagename == 'read'){
                if($permit->read =='1'){
                    $permit->read ='0';
                }
                else{
                    $permit->read='1';
                }
            }
            if($request->pagename == 'write'){
                if($permit->write =='1'){
                    $permit->write ='0';
                }
                else{
                    $permit->write='1';
                }
            }
            if($request->pagename == 'update'){
                if($permit->update =='1'){
                    $permit->update ='0';
                }
                else{
                    $permit->update='1';
                }
            }
            if($request->pagename == 'delete'){
                if($permit->delete =='1'){
                    $permit->delete ='0';
                }
                else{
                    $permit->delete='1';
                }
            }
            if($request->pagename == 'download'){
                if($permit->download =='1'){
                    $permit->download ='0';
                }
                else{
                    $permit->download='1';
                }
            }
            if($request->pagename == 'print'){
                if($permit->print =='1'){
                    $permit->print ='0';
                }
                else{
                    $permit->print='1';
                }
            }
            $permit->updated_by = Auth::user()->username;
            try{
                $permit->save();
                return Response::json(['data' => 'update']);
            }
            catch(Exception $e) {
                return Response::json(['error' => $e->getMessage()],  500);
            }
        }        
        else{
           
            $permit = New UserPermission;
            $permit->user_id = $data->id;
            $permit->role_id = $data->role_id;
            $permit->page_id = $request->page_id;
            if($request->pagename == 'read'){
                $permit->read = $request->permission;
            }
            if($request->pagename == 'write'){
                $permit->write = $request->permission;
            }
            if($request->pagename == 'update'){
                $permit->update = $request->permission;
            }
            if($request->pagename == 'delete'){
                $permit->delete = $request->permission;
            }
            if($request->pagename == 'download'){
                $permit->download = $request->permission;
            }
            if($request->pagename == 'print'){
                $permit->print = $request->permission;
            }
            $permit->created_by = Auth::user()->username;
            try{
                $permit->save();
                return Response::json(['data' => 'add']);
            }
            catch(Exception $e) {
                return Response::json(['error' => $e->getMessage()],  500);
            }

        }
        
    } 
    public function getDestination($id){
       
        $data = Region::where('region', $id)->get();
        if(empty($data)){
            return Response::json(['error' => 'Data not found!!.'], 404);
        }
        return Response::json(['data' => $data]);

    }
    public function getLogisticsPartner_bkp($logistics_type) {
        
        if($logistics_type == 'Aggrigator'){
            $data['aggr'] = AppLogistics::select('logistics_name')->where('logistics_type', 'Aggrigator')->where('logistics_status','Active')->get();
            $data['cour'] = AppLogistics::select('logistics_name')->where('logistics_type','Currior')->where('logistics_status','Active')->get();
        }
        else{
            $data['cour'] = AppLogistics::select('logistics_name')->where('logistics_type','Currior')->where('logistics_status','Active')->get();
        }
        if(empty($data)){
            return Response::json(['error' => 'Data not found!!.'], 404);
        }
        return Response::json(['data' => $data]);
    }
   
    public function getWeight_bkp(Request $request){
        try 
        {
            $weight = 0.0;
            $min_weight = Weight::select('min','weight_range')->where('min', '>', 0)->first();
            if($request->weight<$min_weight->min)
            {
                $weight = $min_weight;
            }
            else
            {
                $weight = Weight::select('weight_range')
                        ->from('weights')
                        ->where('min', '<=', $request->weight)
                        ->where('max', '>=', $request->weight)
                        ->first();
            }
            if(!$weight){
                return Response::json(['error' => 'no weight range found']);
            }
            return Response::json(['data' => $weight]);
        } 
        catch (\Exception $e) {
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
    public function getZoneRate_bkp(Request $request){
        try 
        {
            $wt=$request->weight;
            $total_amount=$request->total_price;
            $payment_mode = strtoupper($request->payment_mode);
            $delivery_state = Pincode::where('pincode', $request->pincode)->value('state');
            if ($delivery_state) 
            {
                $user_type='';
                $clientID = 0;
                $companyId = 0;
                if(Auth::user()->user_type == 'isCompany')
                {
                   if(Session::has('client'))
                    { 
                        $clientID = session('client.id');
                        $companyId = Auth::user()->company->id;
                       
                    }
                    else
                    {
                        $companyId = Auth::user()->company->id;
                        $clientID = Auth::user()->client->id;
                      
                    }
                }
                else
                {
                    $clientID = Auth::user()->client->id;
                    $companyId = Auth::user()->company->id;
                    
                }
                $zone_type="company_client";
                $zone = Zone::whereRaw("FIND_IN_SET(?, zone_mapping) > 0", [$delivery_state])->where('zone_type',$zone_type)->where('company_id',$companyId)->where('client_id',$clientID)->first();
                 
                if (!empty($zone)) 
                {
                    $list = Rate::select('forward', 'cod', 'cod_percent','min_weight','courier','aggregator','shipment_mode','additional_weight','forward_additional')
                                ->where('min_weight', $request->weight)
                                ->where('contract_type','company_client')
                                ->where('company_id',$companyId)
                                ->where('client_id',$clientID)
                                ->get();
                     
                    if(count($list)>0)
                    {
                        foreach($list as $key=>$ldata)
                        {
                            $dspData = [];
                            foreach($list as $key=>$ldata)
                            {
                                if($payment_mode == 'COD'){
                                    $cod_per = $total_amount*($ldata->percent*0.01);
                                    $cod_amount = $ldata->cod;
                                    if($cod_amount>$cod_per){
                                        $cod_val = $cod_amount;
                                    }
                                    else{
                                        $cod_val = $cod_per;
                                    }
                                }
                                else{
                                    
                                    $cod_val = 0;
                                }
                                
                                $data = json_decode($ldata->forward, true);
                                $add_data = json_decode($ldata->forward_additional, true);
                                if (isset($data['forward'][$zone->zone_code])) 
                                {
                                    $zoneValue = $data['forward'][$zone->zone_code];
                                    $dspData[$key]['courier_name'] = $ldata->courier;
                                    $dspData[$key]['aggregator'] = $ldata->aggregator ? $ldata->aggregator :'';
                                    $dspData[$key]['courier_rate'] = $data['forward'][$zone->zone_code];
                                    $dspData[$key]['weight'] = $ldata->min_weight;
                                    $dspData[$key]['cod'] = $cod_val;
                                    $dspData[$key]['shipment_mode'] = $ldata->shipment_mode;
                                    $dspData[$key]['additional_charge'] = $add_data['forward_additional'][$zone->zone_code];
                                    $dspData[$key]['additional_weight'] = $ldata['additional_weight'];
                                    $dspData[$key]['final_charge'] = $zoneValue;
                                } 
                                
                            }
                            if(!empty($dspData))
                            {
                                return Response::json(['data' => $dspData]);
                            }
                            else{
                                return Response::json(['error' => 'Data not found']);
                            }
                        }
                    }
                    else{
                        $rcd = Rate::select('min_weight')
                                        ->where('min_weight','>',$wt)
                                        ->where('contract_type','company_client')
                                ->where('company_id',$companyId)
                                ->where('client_id',$clientID)
                                        ->limit(1)
                                        ->get();
                     
                        $rcd1 = Rate::select('min_weight')
                                        ->where('min_weight','<',$wt)
                                        ->orderBy('min_weight','desc')
                                        ->where('contract_type','company_client')
                                ->where('company_id',$companyId)
                                ->where('client_id',$clientID)
                                        ->limit(1)
                                        ->get();
                        
                        if(count($rcd)>0 && count($rcd1)>0)
                        {
                            $listData = Rate::select('forward', 'cod', 'cod_percent', 'min_weight', 'courier','aggregator', 'shipment_mode','forward_additional','additional_weight')
                                        ->where('min_weight', $rcd[0]['min_weight'])
                                        ->orWhere('min_weight', $rcd1[0]['min_weight'])
                                        ->where('contract_type','company_client')
                                ->where('company_id',$companyId)
                                ->where('client_id',$clientID)
                                        ->get();
                           
                            $dspData=[];
                            $tot_price1=0;
                            $tot_price2=0;
                            foreach($listData as $key=>$ldata)
                            {
                                if($rcd[0]['min_weight'] > $rcd1[0]['min_weight'])
                                {
                                    $drt = $rcd1[0]['min_weight'];
                                }
                                else
                                {
                                    $drt = $rcd[0]['min_weight'];
                                }
                                if($drt!=$ldata->min_weight)
                                {
                                    
                                    $rwt1 = $drt;
                                    $fwd_data2 = json_decode($ldata['forward'], true);
                                    $fwd_add_data2 = json_decode($ldata['forward_additional'], true);
                                    $tot_price2 = $fwd_data2['forward'][$zone->zone_code];
                                    if($payment_mode == 'COD')
                                    {
                                        $cod_per = $total_amount*($ldata->percent*0.01);
                                        $cod_amount = $ldata->cod;
                                        if($cod_amount>$cod_per)
                                        {
                                            $cod_val = $cod_amount;
                                        }
                                        else
                                        {
                                            $cod_val = $cod_per;
                                        }
                                    }
                                    else
                                    {
                                        
                                        $cod_val =0;
                                    }
                                    $dspData[$key]['courier_name'] = $ldata->courier;
                                    $dspData[$key]['aggregator'] = $ldata->aggregator ? $ldata->aggregator :'';
                                    $dspData[$key]['courier_rate'] = $fwd_data2['forward'][$zone->zone_code];
                                    $dspData[$key]['weight'] = $ldata->min_weight;
                                    $dspData[$key]['cod'] = $cod_val;
                                    $dspData[$key]['shipment_mode'] = $ldata->shipment_mode;
                                    $dspData[$key]['additional_charge'] = $fwd_add_data2['forward_additional'][$zone->zone_code];
                                    $dspData[$key]['additional_weight'] = $ldata['additional_weight'];
                                    $dspData[$key]['final_charge'] = $tot_price2;
                                }
                                else
                                {
                                   
                                   $count = 0;
                                    $rwt1 = $drt;
                                    $fwd_data1 = json_decode($ldata['forward'], true);
                                    $fwd_add_data1 = json_decode($ldata['forward_additional'], true);
                                    $addwt1 = $wt - $ldata['min_weight'];
                                    $price = $fwd_data1['forward'][$zone->zone_code];
                                    while($addwt1 > $ldata['additional_weight'])
                                    {
                                        $price += $fwd_add_data1['forward_additional'][$zone->zone_code];
                                        $addwt1 = $addwt1 - $ldata['additional_weight'];
                                        $count++;
                                    } 
                                    $tot_price1 = $price + $fwd_add_data1['forward_additional'][$zone->zone_code];
                                    if($payment_mode == 'COD'){
                                       
                                        $cod_per = $total_amount*($ldata->percent*0.01);
                                        $cod_amount = $ldata->cod;
                                        if($cod_amount>$cod_per){
                                            $cod_val = $cod_amount;
                                        }
                                        else{
                                            $cod_val = $cod_per;
                                        }
                                    }
                                    else{
                                        
                                        $cod_val =0;
                                    }
                                    $dspData[$key]['courier_name'] = $ldata->courier;
                                    $dspData[$key]['aggregator'] = $ldata->aggregator ? $ldata->aggregator :'';
                                    $dspData[$key]['courier_rate'] =$fwd_data1['forward'][$zone->zone_code];
                                    $dspData[$key]['weight'] = $ldata->min_weight;
                                    $dspData[$key]['cod'] = $cod_val;
                                    $dspData[$key]['shipment_mode'] = $ldata->shipment_mode;
                                    $dspData[$key]['additional_charge'] = $fwd_add_data1['forward_additional'][$zone->zone_code];
                                    $dspData[$key]['additional_weight'] = $ldata['additional_weight'];
                                    $dspData[$key]['final_charge'] = $tot_price1;
                                   
                                }
                            }
                       
                            if(!empty($dspData)){
                                return Response::json(['data' => $dspData]);
                            }
                            else{
                                
                                return Response::json(['error' => 'Data not found']);
                            }
                        }
                        else if(count($rcd)>0 && count($rcd1) == 0){
                            $list1 = Rate::select('forward', 'cod', 'cod_percent','min_weight','courier','aggregator','shipment_mode','additional_weight','forward_additional')
                            ->where('min_weight', $rcd[0]['min_weight'])
                            ->where('contract_type','company_client')
                                ->where('company_id',$companyId)
                                ->where('client_id',$clientID)
                            ->get();
                            $dspData = [];
                           
                            foreach($list1 as $key=>$ldata)
                            {
                                if($payment_mode == 'COD'){
                                    $cod_per = $total_amount*($ldata->percent*0.01);
                                    $cod_amount = $ldata->cod;
                                    if($cod_amount>$cod_per){
                                        $cod_val = $cod_amount;
                                    }
                                    else{
                                        $cod_val = $cod_per;
                                    }
                                }
                                else{
                                    $cod_val =0;
                                }
                                $data = json_decode($ldata->forward, true);
                                $data2 = json_decode($ldata->forward_additional, true);
                                if (isset($data['forward'][$zone->zone_code])) 
                                {
                                    $zoneValue = $data['forward'][$zone->zone_code];
                                    $dspData[$key]['courier_name'] = $ldata->courier;
                                    $dspData[$key]['aggregator'] = $ldata->aggregator ? $ldata->aggregator :'';
                                    $dspData[$key]['courier_rate'] = $data['forward'][$zone->zone_code];
                                    $dspData[$key]['weight'] = $ldata->min_weight;
                                    $dspData[$key]['cod'] = $cod_val;
                                    $dspData[$key]['shipment_mode'] = $ldata->shipment_mode;
                                    $dspData[$key]['additional_charge'] = $data2['forward_additional'][$zone->zone_code];
                                    $dspData[$key]['additional_weight'] = $ldata['additional_weight'];
                                    $dspData[$key]['final_charge'] = $zoneValue;
                                } 
                                
                            }
                            if(!empty($dspData)){
                                
                                return Response::json(['data' => $dspData]);
                            }
                            else{
                                
                                return Response::json(['error' => 'Data not found']);
                            }
                        }
                        else{
                            $listData = Rate::select('forward', 'cod', 'cod_percent', 'min_weight', 'courier','aggregator', 'shipment_mode','forward_additional','additional_weight')
                                        ->where('min_weight', $rcd1[0]['min_weight'])
                                        ->where('contract_type','company_client')
                                ->where('company_id',$companyId)
                                ->where('client_id',$clientID)
                                        ->get();
                            $dspData=[];
                            $tot_price1=0;
                            $drt = $rcd1[0]['min_weight'];
                            foreach($listData as $key=>$ldata)
                            {
                                if($drt!=$ldata->min_weight)
                                {
                                    $rwt1 = $drt;
                                    $fwd_data2 = json_decode($ldata['forward'], true);
                                    $fwd_add_data2 = json_decode($ldata['forward_additional'], true);
                                    $tot_price2 = $fwd_data2['forward'][$zone->zone_code];
                                   if($payment_mode == 'COD')
                                   {
                                       $cod_per = $total_amount*($ldata->percent*0.01);
                                        $cod_amount = $ldata->cod;
                                        if($cod_amount>$cod_per)
                                        {
                                            $cod_val = $cod_amount;
                                        }
                                        else
                                        {
                                            $cod_val = $cod_per;
                                        }
                                    }
                                    else{
                                       
                                        $cod_val =0;
                                    }
                                    $price = $tot_price2;
                                    #echo 'if'.$price;die;
                                    $dspData[$key]['courier_name'] = $ldata->courier;
                                    $dspData[$key]['aggregator'] = $ldata->aggregator ? $ldata->aggregator :'';
                                    $dspData[$key]['courier_rate'] =$fwd_data2['forward'][$zone->zone_code];
                                    $dspData[$key]['weight'] = $ldata->min_weight;
                                    $dspData[$key]['cod'] = $cod_val;
                                    $dspData[$key]['shipment_mode'] = $ldata->shipment_mode;
                                    $dspData[$key]['additional_charge'] = $fwd_add_data2['forward_additional'][$zone->zone_code];
                                    $dspData[$key]['additional_weight'] = $ldata['additional_weight'];
                                    $dspData[$key]['final_charge'] = $price;
                                }
                                else{
                                    
                                    $rwt1 = $drt;
                                    $count = 0;
                                    $fwd_data1 = json_decode($ldata['forward'], true);
                                    $fwd_add_data1 = json_decode($ldata['forward_additional'], true);
                                    $addwt1 = $wt - $ldata['min_weight'];
                                    $price = $fwd_data1['forward'][$zone->zone_code];
                                    while($addwt1 > $ldata['additional_weight'])
                                    {
                                        $price += $fwd_add_data1['forward_additional'][$zone->zone_code];
                                        $addwt1 = $addwt1 - $ldata['additional_weight'];
                                        $count++;
                                    } 
                                    
                                    $tot_price1 = $price + $fwd_add_data1['forward_additional'][$zone->zone_code];
                                    if($payment_mode == 'COD' ){
                                        
                                       $cod_per = $total_amount*($ldata->percent*0.01);
                                        $cod_amount = $ldata->cod;
                                        if($cod_amount>$cod_per){
                                            $cod_val = $cod_amount;
                                        }
                                        else{
                                            $cod_val = $cod_per;
                                        }
                                    }
                                    else{
                                        
                                        $cod_val =0;
                                    }
                                    $dspData[$key]['courier_name'] = $ldata->courier;
                                    $dspData[$key]['aggregator'] = $ldata->aggregator ? $ldata->aggregator :'';
                                    $dspData[$key]['courier_rate'] = $fwd_data1['forward'][$zone->zone_code];
                                    $dspData[$key]['weight'] = $ldata->min_weight;
                                    $dspData[$key]['cod'] = $cod_val;
                                    $dspData[$key]['shipment_mode'] = $ldata->shipment_mode;
                                    $dspData[$key]['additional_charge'] = $fwd_add_data1['forward_additional'][$zone->zone_code];
                                    $dspData[$key]['additional_weight'] = $ldata['additional_weight'];
                                    $dspData[$key]['final_charge'] = $tot_price1;
                                   
                                }
         
                            }
                            if(!empty($dspData)){
                                
                                return Response::json(['data' => $dspData]);
                            }
                            else{
                                
                                return Response::json(['error' => 'Data not found']);
                            }
                        }
                    }
                } 
                else 
                {
                     return Response::json(['error' => 'no Zone Data found']);
                }
            } 
            else{
                return Response::json(['error' => 'no pincode Data found']);
            }
        } catch (QueryException $e) {
            // Log the error
            Log::error('Database error: ' . $e->getMessage());
    
            // Return an error response
            return response()->json(['error' => 'An error occurred while processing the request'], 500);
    
        } catch (\Exception $e) {
            // Log other types of exceptions
            Log::error('Exception: ' . $e->getMessage());
    
            // Return an error response
            return response()->json(['error' => 'An unexpected error occurred'], 500);
        }
    }
    public function getZoneState($dsp)
    {
        $clientId = 0;
        $companyId = 0;
        if(Auth::user()->user_type == 'isCompany')
        {
            if(Session::has('client'))
            {
                $clientId = session('client.id');            
                $companyId = Auth::user()->company->id;
                
            }
            else
            {
                $clientId = Auth::user()->client->id;
                $companyId = Auth::user()->company->id;
                
            }
        }
        else
        {
            $clientId = Auth::user()->client->id;
            $companyId = Auth::user()->company->id;
           
        } 
        $zoneMappings = Zone::where('dsp', $dsp) ->where('company_id',$companyId)
        ->where('client_id',$clientId)->pluck('zone_mapping')->toArray();

        
        $zoneArray = []; 
        foreach ($zoneMappings as $zoneMapping) 
        {
            $zoneArray = array_merge($zoneArray, explode(',', $zoneMapping));
        }
      
        $stateData = Pincode::distinct()->pluck('state')->toArray(); // Retrieve state names
        
        
        $resultData = array_diff($stateData, $zoneArray); // Find the difference
        
        $states = array_values($resultData);
        
        if(empty($states)){
            return Response::json(['error' => 'All states are occupied!!']);
        }
        else{
            return Response::json(['data' => $states]);
        }
    }
    public function getZoneTypeState($zone_type)
    {
        $clientId = 0;
        $companyId = 0;
        if(Auth::user()->user_type == 'isCompany')
        {
            if(Session::has('client'))
            {
                $clientId = session('client.id');            
                $companyId = Auth::user()->company->id;
                
            }
            else
            {
                $clientId = Auth::user()->client->id;
                $companyId = Auth::user()->company->id;
                
            }
        }
        else
        {
            $clientId = Auth::user()->client->id;
            $companyId = Auth::user()->company->id;
           
        } 
        $zoneMappings = Zone::where('zone_type', $zone_type)
                            ->where('company_id',$companyId)
                            ->where('client_id',$clientId)
                            ->pluck('zone_mapping')->toArray();
                         
        $zoneArray = []; 
        foreach ($zoneMappings as $zoneMapping) 
        {
            $zoneArray = array_merge($zoneArray, explode(',', $zoneMapping));
        }
      
        $stateData = Pincode::distinct()->pluck('state')->toArray(); // Retrieve state names
        
        
        $resultData = array_diff($stateData, $zoneArray); // Find the difference
        
        $states = array_values($resultData);
       
        if(empty($states)){
            return Response::json(['error' => 'All states are occupied!!']);
        }
        else{
            return Response::json(['data' => $states]);
        }
    }
    public function getDspZone($dsp)
    {
        if(Auth::user()->user_type == 'isCompany')
        {
            if(Session::has('client'))
            { 
                $clientID = session('client.id');
                $companyId = Auth::user()->company->id;  
            }
            else
            {
                $companyId = Auth::user()->company->id;
                $clientID = Auth::user()->client->id;                        
            }
        }
        else
        {
            $clientID = Auth::user()->client->id;
            $companyId = Auth::user()->company->id;    
        }
        $zones = Zone::select('zone_code','description')
                    ->where('dsp', function ($query) use ($dsp) {
                        $query->select('id')
                            ->from('app_logistics')
                            ->where('logistics_name', $dsp)
                            ->limit(1); // Assuming you want only one result
                    })
                    ->where('company_id',$companyId)
                    ->where('client_id',$clientID)
                    ->get();
        if(empty($zones)){
            return Response::json(['error' => 'no Zone Data found']);
        }
        else{
            return Response::json(['data' => $zones]);
        }
    }
    public function getRtoRate(Request $request){
        // try {
        //     $wt=$request->weight;
        //     $total_amount=$request->total_price;
        //     $payment_mode = strtoupper($request->payment_mode);
        //     $delivery_state = Pincode::where('pincode', $request->pincode)->value('state');
        //     if ($delivery_state) {
        //         $zone = Zone::whereRaw("FIND_IN_SET(?, zone_mapping) > 0", [$delivery_state])->first();
        //         if (!empty($zone)) {
        //             $list = Rate::select('forward', 'cod', 'cod_percent','min_weight','courier','shipment_mode','additional_weight','forward_additional')->where('min_weight', $request->weight)->get();
        //             if(count($list)>0){
        //                 foreach($list as $key=>$ldata)
        //                 {
        //                     $dspData = [];
        //                     foreach($list as $key=>$ldata)
        //                     {

        //                         $data = json_decode($ldata->forward, true);
        //                         $add_data = json_decode($ldata->forward_additional, true);
        //                         if (isset($data['forward'][$zone->zone_code])) 
        //                         {
        //                             $zoneValue = $data['forward'][$zone->zone_code];
        //                             $dspData[$key]['courier_name'] = $ldata->courier;
        //                             $dspData[$key]['courier_rate'] = $data['forward'][$zone->zone_code];
        //                             $dspData[$key]['weight'] = $ldata->min_weight;
        //                             $dspData[$key]['shipment_mode'] = $ldata->shipment_mode;
        //                             $dspData[$key]['additional_charge'] = $add_data['forward_additional'][$zone->zone_code];
        //                             $dspData[$key]['additional_weight'] = $ldata['additional_weight'];
        //                             $dspData[$key]['final_charge'] = $zoneValue;
        //                         } 
                                
        //                     }
        //                     if(!empty($dspData)){
        //                         return Response::json(['data' => $dspData]);
        //                     }
        //                     else{
        //                         return Response::json(['error' => 'no Data']);
        //                     }
        //                 }
        //             }
        //             else{
        //                 $rcd = Rate::select('min_weight')
        //                                 ->where('min_weight','>',$wt)
        //                                 ->limit(1)
        //                                 ->get();
                     
        //                 $rcd1 = Rate::select('min_weight')
        //                                 ->where('min_weight','<',$wt)
        //                                 ->orderBy('min_weight','desc')
        //                                 ->limit(1)
        //                                 ->get();
                        
        //                 if(count($rcd)>0 && count($rcd1)>0){
                            
        //                     $listData = Rate::select('forward', 'cod', 'cod_percent', 'min_weight', 'courier', 'shipment_mode','forward_additional','additional_weight')
        //                                 ->where('min_weight', $rcd[0]['min_weight'])
        //                                 ->orWhere('min_weight', $rcd1[0]['min_weight'])
        //                                 ->get();
                           
        //                     $dspData=[];
        //                     $tot_price1=0;
        //                     $tot_price2=0;
        //                     foreach($listData as $key=>$ldata)
        //                     {
                                
        //                         if($rcd[0]['min_weight'] > $rcd1[0]['min_weight'])
        //                         {
        //                             $drt = $rcd1[0]['min_weight'];
        //                         }
        //                         else{
        //                             $drt = $rcd[0]['min_weight'];
        //                         }
        //                         if($drt!=$ldata->min_weight)
        //                         {
                                    
        //                             $rwt1 = $drt;
        //                             $fwd_data2 = json_decode($ldata['forward'], true);
        //                             $fwd_add_data2 = json_decode($ldata['forward_additional'], true);
        //                             $tot_price2 = $fwd_data2['forward'][$zone->zone_code];
                                    
        //                             $dspData[$key]['courier_name'] = $ldata->courier;
        //                             $dspData[$key]['courier_rate'] = $fwd_data2['forward'][$zone->zone_code];
        //                             $dspData[$key]['weight'] = $ldata->min_weight;
                                    
        //                             $dspData[$key]['shipment_mode'] = $ldata->shipment_mode;
        //                             $dspData[$key]['additional_charge'] = $fwd_add_data2['forward_additional'][$zone->zone_code];
        //                             $dspData[$key]['additional_weight'] = $ldata['additional_weight'];
        //                             $dspData[$key]['final_charge'] = $tot_price2;
        //                         }
        //                         else{
                                   
        //                           $count = 0;
        //                             $rwt1 = $drt;
        //                             $fwd_data1 = json_decode($ldata['forward'], true);
        //                             $fwd_add_data1 = json_decode($ldata['forward_additional'], true);
        //                             $addwt1 = $wt - $ldata['min_weight'];
        //                             $price = $fwd_data1['forward'][$zone->zone_code];
        //                             while($addwt1 > $ldata['additional_weight'])
        //                             {
        //                                 $price += $fwd_add_data1['forward_additional'][$zone->zone_code];
        //                                 $addwt1 = $addwt1 - $ldata['additional_weight'];
        //                                 $count++;
        //                             } 
        //                             $tot_price1 = $price + $fwd_add_data1['forward_additional'][$zone->zone_code];
                                    
        //                             $dspData[$key]['courier_name'] = $ldata->courier;
        //                             $dspData[$key]['courier_rate'] =$fwd_data1['forward'][$zone->zone_code];
        //                             $dspData[$key]['weight'] = $ldata->min_weight;
                                   
        //                             $dspData[$key]['shipment_mode'] = $ldata->shipment_mode;
        //                             $dspData[$key]['additional_charge'] = $fwd_add_data1['forward_additional'][$zone->zone_code];
        //                             $dspData[$key]['additional_weight'] = $ldata['additional_weight'];
        //                             $dspData[$key]['final_charge'] = $tot_price1;
                                   
        //                         }
        //                     }
                       
        //                     if(!empty($dspData)){
        //                         return Response::json(['data' => $dspData]);
        //                     }
        //                     else{
                                
        //                         return Response::json(['error' => 'no Data']);
        //                     }
        //                 }
        //                 else if(count($rcd)>0 && count($rcd1) == 0){
        //                     $list1 = Rate::select('forward', 'cod', 'cod_percent','min_weight','courier','shipment_mode','additional_weight','forward_additional')->where('min_weight', $rcd[0]['min_weight'])->get();
        //                     $dspData = [];
                           
        //                     foreach($list1 as $key=>$ldata)
        //                     {
                               
        //                         $data = json_decode($ldata->forward, true);
        //                         $data2 = json_decode($ldata->forward_additional, true);
        //                         if (isset($data['forward'][$zone->zone_code])) 
        //                         {
        //                             $zoneValue = $data['forward'][$zone->zone_code];
        //                             $dspData[$key]['courier_name'] = $ldata->courier;
        //                             $dspData[$key]['courier_rate'] = $data['forward'][$zone->zone_code];
        //                             $dspData[$key]['weight'] = $ldata->min_weight;
                                  
        //                             $dspData[$key]['shipment_mode'] = $ldata->shipment_mode;
        //                             $dspData[$key]['additional_charge'] = $data2['forward_additional'][$zone->zone_code];
        //                             $dspData[$key]['additional_weight'] = $ldata['additional_weight'];
        //                             $dspData[$key]['final_charge'] = $zoneValue;
        //                         } 
                                
        //                     }
        //                     if(!empty($dspData)){
                                
        //                         return Response::json(['data' => $dspData]);
        //                     }
        //                     else{
                                
        //                         return Response::json(['error' => 'no Data']);
        //                     }
        //                 }
        //                 else{
        //                     $listData = Rate::select('forward', 'cod', 'cod_percent', 'min_weight', 'courier', 'shipment_mode','forward_additional','additional_weight')
        //                                 ->where('min_weight', $rcd1[0]['min_weight'])
        //                                 ->get();
        //                     $dspData=[];
        //                     $tot_price1=0;
        //                     $drt = $rcd1[0]['min_weight'];
        //                     foreach($listData as $key=>$ldata)
        //                     {
        //                         if($drt!=$ldata->min_weight){
        //                             $rwt1 = $drt;
        //                             $fwd_data2 = json_decode($ldata['forward'], true);
        //                             $fwd_add_data2 = json_decode($ldata['forward_additional'], true);
        //                             $tot_price2 = $fwd_data2['forward'][$zone->zone_code];
                                   
        //                             $price = $tot_price2;
        //                             #echo 'if'.$price;die;
        //                             $dspData[$key]['courier_name'] = $ldata->courier;
        //                             $dspData[$key]['courier_rate'] =$fwd_data2['forward'][$zone->zone_code];
        //                             $dspData[$key]['weight'] = $ldata->min_weight;
                                   
        //                             $dspData[$key]['shipment_mode'] = $ldata->shipment_mode;
        //                             $dspData[$key]['additional_charge'] = $fwd_add_data2['forward_additional'][$zone->zone_code];
        //                             $dspData[$key]['additional_weight'] = $ldata['additional_weight'];
        //                             $dspData[$key]['final_charge'] = $price;
        //                         }
        //                         else{
                                    
        //                             $rwt1 = $drt;
        //                             $count = 0;
        //                             $fwd_data1 = json_decode($ldata['forward'], true);
        //                             $fwd_add_data1 = json_decode($ldata['forward_additional'], true);
        //                             $addwt1 = $wt - $ldata['min_weight'];
        //                             $price = $fwd_data1['forward'][$zone->zone_code];
        //                             while($addwt1 > $ldata['additional_weight'])
        //                             {
        //                                 $price += $fwd_add_data1['forward_additional'][$zone->zone_code];
        //                                 $addwt1 = $addwt1 - $ldata['additional_weight'];
        //                                 $count++;
        //                             } 
                                    
        //                             $tot_price1 = $price + $fwd_add_data1['forward_additional'][$zone->zone_code];
                                    
        //                             $dspData[$key]['courier_name'] = $ldata->courier;
        //                             $dspData[$key]['courier_rate'] = $fwd_data1['forward'][$zone->zone_code];
        //                             $dspData[$key]['weight'] = $ldata->min_weight;
                                    
        //                             $dspData[$key]['shipment_mode'] = $ldata->shipment_mode;
        //                             $dspData[$key]['additional_charge'] = $fwd_add_data1['forward_additional'][$zone->zone_code];
        //                             $dspData[$key]['additional_weight'] = $ldata['additional_weight'];
        //                             $dspData[$key]['final_charge'] = $tot_price1;
                                   
        //                         }
         
        //                     }
        //                     if(!empty($dspData)){
                                
        //                         return Response::json(['data' => $dspData]);
        //                     }
        //                     else{
                                
        //                         return Response::json(['error' => 'no Data']);
        //                     }
        //                 }
        //             }
        //         } 
        //         else 
        //         {
        //              return Response::json(['error' => 'no Zone Data']);
        //         }
        //     } 
        //     else{
        //         return Response::json(['error' => 'no pincode Data']);
        //     }
        // } catch (QueryException $e) {
        //     // Log the error
        //     Log::error('Database error: ' . $e->getMessage());
    
        //     // Return an error response
        //     return response()->json(['error' => 'An error occurred while processing the request'], 500);
    
        // } catch (\Exception $e) {
        //     // Log other types of exceptions
        //     Log::error('Exception: ' . $e->getMessage());
    
        //     // Return an error response
        //     return response()->json(['error' => 'An unexpected error occurred'], 500);
        // }
    }
    public function getLogisticsPartner($logistics_type)
    {
        $user_type='';
        $clientID = 0;
        $companyId = 0;
        if(Auth::user()->user_type == 'isCompany')
        {
            if(Session::has('client'))
            { 
                $clientID = session('client.id');
                $companyId = Auth::user()->company->id;  
            }
            else
            {
                $companyId = Auth::user()->company->id;
                $clientID = Auth::user()->client->id;                        
            }
        }
        else
        {
            $clientID = Auth::user()->client->id;
            $companyId = Auth::user()->company->id;    
        }
        if($logistics_type == 'Aggrigator')
        {
            $data['aggr'] = LogisticsMapping::select('app_logistics.logistics_name')
                                            ->join('app_logistics', 'logistics_mappings.partner_id', '=', 'app_logistics.id')
                                            ->where('logistics_mappings.client_id', $clientID)
                                            ->where('app_logistics.logistics_type', 'Aggrigator')
                                            ->where('logistics_mappings.status', 'Active')
                                            ->get();
        $data['cour'] = LogisticsMapping::select('app_logistics.logistics_name')
                                        ->join('app_logistics', 'logistics_mappings.partner_id', '=', 'app_logistics.id')
                                        ->where('logistics_mappings.client_id', $clientID)
                                        ->where('app_logistics.logistics_type', 'Currior')
                                        ->where('logistics_mappings.status', 'Active')
                                        ->get();
        }
        else{
            $data['cour'] = LogisticsMapping::select('app_logistics.logistics_name')
                                            ->join('app_logistics', 'logistics_mappings.partner_id', '=', 'app_logistics.id')
                                            ->where('logistics_mappings.client_id', $clientID)
                                            ->where('app_logistics.logistics_type', 'Currior')
                                            ->where('logistics_mappings.status', 'Active')
                                            ->get();
        }
        if(empty($data)){
            return Response::json(['error' => 'Data not found!!.'], 404);
        }
        return Response::json(['data' => $data]);
    }
    public function getPartnerList($logistics_type)
    {
        if (Auth::user()->user_type == 'isCompany') {
            if (Session::has('client')) {
                $clientId = session('client.id');
            } else {
                $clientId = Auth::user()->client->id;
            }
            $companyId = Auth::user()->company->id;
        } else {
            $clientId = Auth::user()->client->id;
            $companyId = Auth::user()->company->id;
        }
        if($logistics_type == 'Aggrigator'){
            
            $data = LogisticsMapping::select('app_logistics.logistics_name')
                            ->join('app_logistics', 'logistics_mappings.partner_id', '=', 'app_logistics.id')
							->where('logistics_mappings.client_id', $clientId)
                            ->where('app_logistics.logistics_type', 'Aggrigator')
							->where('logistics_mappings.status', 'Active')
							->get();
        }
        else{
            
            $data = LogisticsMapping::select('app_logistics.logistics_name')
                            ->join('app_logistics', 'logistics_mappings.partner_id', '=', 'app_logistics.id')
							->where('logistics_mappings.client_id', $clientId)
                            ->where('app_logistics.logistics_type', 'Currior')
							->where('logistics_mappings.status', 'Active')
							->get();
        }
        if(empty($data)){
            return Response::json(['error' => 'Data not found!!.'], 404);
        }
        return Response::json(['data' => $data]);
    }
    
    public function getWeight(Request $request){
        try 
        {
            $weight = 0.0;
            $min_weight = Weight::select('min','weight_range')->where('min', '>', 0)->first();
            if($request->weight<$min_weight->min)
            {
                $weight = $min_weight;
            }
            else
            {
                $weight = Weight::select('weight_range')
                        ->from('weights')
                        ->where('min', '<=', $request->weight)
                        ->where('max', '>=', $request->weight)
                        ->first();
            }
            if(!$weight){
                return Response::json(['error' => 'no weight range found']);
            }
            return Response::json(['data' => $weight]);
        } 
        catch (\Exception $e) {
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
    public function getZoneRate(Request $request)
    {
        try 
        {
            $wt=$request->weight;
            $total_amount=$request->total_price;
            $payment_mode = strtoupper($request->payment_mode);
            $delivery_state = Pincode::where('pincode', $request->pincode)->value('state');
            if ($delivery_state) 
            {
                $clientID = 0;
                $companyId = 0;
                if(Auth::user()->user_type == 'isCompany')
                {
                   if(Session::has('client'))
                    { 
                        $clientID = session('client.id');
                        $companyId = Auth::user()->company->id;  
                    }
                    else
                    {
                        $companyId = Auth::user()->company->id;
                        $clientID = Auth::user()->client->id;                        
                    }
                }
                else
                {
                    $clientID = Auth::user()->client->id;
                    $companyId = Auth::user()->company->id;    
                }
               
                $zone_type="company_client";
                
                $zone = Zone::whereRaw("FIND_IN_SET(?, zone_mapping) > 0", [$delivery_state])
                            ->where('zone_type',$zone_type)
                            ->where('company_id',$companyId)
                            ->where('client_id',$clientID)->first();
                if (!empty($zone)) 
                {
                    $list = Rate::select('forward', 'cod', 'cod_percent','min_weight','courier','aggregator','shipment_mode','additional_weight','forward_additional')
                                ->where('min_weight', $request->weight)
                                ->where('contract_type','company_client')
                                ->where('company_id',$companyId)
                                ->where('client_id',$clientID)
                                ->get();
                  
                    if(count($list)>0)
                    {
                        foreach($list as $key=>$ldata)
                        {
                            $dspData = [];
                            foreach($list as $key=>$ldata)
                            {
                                $cod_val = 0;
                                if($payment_mode == 'COD'){
                                    $cod_per = $total_amount*($ldata->percent*0.01);
                                    $cod_amount = $ldata->cod;
                                    if($cod_amount>$cod_per)
                                    {
                                        $cod_val = $cod_amount;
                                    }
                                    else
                                    {
                                        $cod_val = $cod_per;
                                    }
                                }
                                else
                                {
                                    $cod_val = 0;
                                }
                                
                                $data = json_decode($ldata->forward, true);
                                $add_data = json_decode($ldata->forward_additional, true);
                                if (isset($data['forward'][$zone->zone_code])) 
                                {
                                    $zoneValue = $data['forward'][$zone->zone_code];
                                    $dspData[$key]['courier_name'] = $ldata->courier;
                                    $dspData[$key]['aggregator'] = $ldata->aggregator ? $ldata->aggregator :'';
                                    $dspData[$key]['courier_rate'] = $data['forward'][$zone->zone_code];
                                    $dspData[$key]['weight'] = $ldata->min_weight;
                                    $dspData[$key]['cod'] = $cod_val;
                                    $dspData[$key]['shipment_mode'] = $ldata->shipment_mode;
                                    $dspData[$key]['additional_charge'] = $add_data['forward_additional'][$zone->zone_code];
                                    $dspData[$key]['additional_weight'] = $ldata['additional_weight'];
                                    $dspData[$key]['final_charge'] = $zoneValue;
                                }                                 
                            }
                            if(!empty($dspData))
                            {
                                return Response::json(['data' => $dspData]);
                            }
                            else
                            {
                                return Response::json(['error' => 'Data not found']);
                            }
                        }
                    }
                    else
                    {
                        $rcd = Rate::select('min_weight')
                                        ->where('min_weight','>',$wt)
                                        ->where('contract_type','company_client')
                                        ->where('company_id',$companyId)
                                        ->where('client_id',$clientID)
                                        ->limit(1)
                                        ->get();
                     
                        $rcd1 = Rate::select('min_weight')
                                        ->where('min_weight','<',$wt)
                                        ->where('contract_type','company_client')
                                        ->where('company_id',$companyId)
                                        ->where('client_id',$clientID)
                                        ->orderBy('min_weight','desc')
                                        ->limit(1)
                                        ->get();
                        
                        if(count($rcd)>0 && count($rcd1)>0)
                        {
                            $listData = Rate::select('forward', 'cod', 'cod_percent', 'min_weight', 'courier','aggregator', 'shipment_mode','forward_additional','additional_weight')
                                       ->where(function ($query) use ($rcd, $rcd1) {
                                            $query->where('min_weight', $rcd[0]['min_weight'])
                                                ->orWhere('min_weight', $rcd1[0]['min_weight']);
                                        })
                                        ->where('contract_type','company_client')
                                        ->where('company_id',$companyId)
                                        ->where('client_id',$clientID)
                                        ->get();
                           
                            
                            $tot_price1=0;
                            $tot_price2=0;
                            $dspData = [];
                            foreach($listData as $key=>$ldata)
                            {
                                if($rcd[0]['min_weight'] > $rcd1[0]['min_weight'])
                                {
                                    $drt = $rcd1[0]['min_weight'];
                                }
                                else
                                {
                                    $drt = $rcd[0]['min_weight'];
                                }
                                if($drt!=$ldata->min_weight)
                                {
                                    
                                    $rwt1 = $drt;
                                    $fwd_data2 = json_decode($ldata['forward'], true);
                                    $fwd_add_data2 = json_decode($ldata['forward_additional'], true);
                                    $tot_price2 = $fwd_data2['forward'][$zone->zone_code];
                                    $cod_val = 0;
                                    if($payment_mode == 'COD')
                                    {
                                        $cod_per = $total_amount*($ldata->percent*0.01);
                                        $cod_amount = $ldata->cod;
                                        if($cod_amount>$cod_per)
                                        {
                                            $cod_val = $cod_amount;
                                        }
                                        else
                                        {
                                            $cod_val = $cod_per;
                                        }
                                    }
                                    else
                                    {
                                        
                                        $cod_val =0;
                                    }
                                    $dspData[$key]['courier_name'] = $ldata->courier;
                                    $dspData[$key]['aggregator'] = $ldata->aggregator ? $ldata->aggregator :'';
                                    $dspData[$key]['courier_rate'] = $fwd_data2['forward'][$zone->zone_code];
                                    $dspData[$key]['weight'] = $ldata->min_weight;
                                    $dspData[$key]['cod'] = $cod_val;
                                    $dspData[$key]['shipment_mode'] = $ldata->shipment_mode;
                                    $dspData[$key]['additional_charge'] = $fwd_add_data2['forward_additional'][$zone->zone_code];
                                    $dspData[$key]['additional_weight'] = $ldata['additional_weight'];
                                    $dspData[$key]['final_charge'] = $tot_price2;
                                }
                                else
                                {
                                   
                                   $count = 0;
                                    $rwt1 = $drt;
                                    $fwd_data1 = json_decode($ldata['forward'], true);
                                    $fwd_add_data1 = json_decode($ldata['forward_additional'], true);
                                    $addwt1 = $wt - $ldata['min_weight'];
                                    $price = $fwd_data1['forward'][$zone->zone_code];
                                    while($addwt1 > $ldata['additional_weight'])
                                    {
                                        $price += $fwd_add_data1['forward_additional'][$zone->zone_code];
                                        $addwt1 = $addwt1 - $ldata['additional_weight'];
                                        $count++;
                                    } 
                                    $tot_price1 = $price + $fwd_add_data1['forward_additional'][$zone->zone_code];
                                    if($payment_mode == 'COD'){
                                       
                                        $cod_per = $total_amount*($ldata->percent*0.01);
                                        $cod_amount = $ldata->cod;
                                        if($cod_amount>$cod_per){
                                            $cod_val = $cod_amount;
                                        }
                                        else{
                                            $cod_val = $cod_per;
                                        }
                                    }
                                    else{
                                        
                                        $cod_val =0;
                                    }
                                    $dspData[$key]['courier_name'] = $ldata->courier;
                                    $dspData[$key]['aggregator'] = $ldata->aggregator ? $ldata->aggregator :'';
                                    $dspData[$key]['courier_rate'] =$fwd_data1['forward'][$zone->zone_code];
                                    $dspData[$key]['weight'] = $ldata->min_weight;
                                    $dspData[$key]['cod'] = $cod_val;
                                    $dspData[$key]['shipment_mode'] = $ldata->shipment_mode;
                                    $dspData[$key]['additional_charge'] = $fwd_add_data1['forward_additional'][$zone->zone_code];
                                    $dspData[$key]['additional_weight'] = $ldata['additional_weight'];
                                    $dspData[$key]['final_charge'] = $tot_price1;
                                   
                                }
                            }
                           
                            if(!empty($dspData))
                            {
                                return Response::json(['data' => $dspData]);
                            }
                            else{
                                
                                return Response::json(['error' => 'Data not found']);
                            }
                            
                        }
                        else if(count($rcd)>0 && count($rcd1) == 0)
                        {
                            $list1 = Rate::select('forward', 'cod', 'cod_percent','min_weight','courier','aggregator','shipment_mode','additional_weight','forward_additional')
                                        ->where('min_weight', $rcd[0]['min_weight'])
                                        ->where('contract_type','company_client')
                                        ->where('company_id',$companyId)
                                        ->where('client_id',$clientID)
                                        ->get();
                            $dspData = [];
                            foreach($list1 as $key=>$ldata)
                            {
                                $cod_val = 0;
                                if($payment_mode == 'COD'){
                                    $cod_per = $total_amount*($ldata->percent*0.01);
                                    $cod_amount = $ldata->cod;
                                    if($cod_amount>$cod_per)
                                    {
                                        $cod_val = $cod_amount;
                                    }
                                    else
                                    {
                                        $cod_val = $cod_per;
                                    }
                                }
                                else
                                {
                                    $cod_val =0;
                                }
                                $data = json_decode($ldata->forward, true);
                                $data2 = json_decode($ldata->forward_additional, true);
                                if (isset($data['forward'][$zone->zone_code])) 
                                {
                                    $zoneValue = $data['forward'][$zone->zone_code];
                                    $dspData[$key]['courier_name'] = $ldata->courier;
                                    $dspData[$key]['aggregator'] = $ldata->aggregator ? $ldata->aggregator :'';
                                    $dspData[$key]['courier_rate'] = $data['forward'][$zone->zone_code];
                                    $dspData[$key]['weight'] = $ldata->min_weight;
                                    $dspData[$key]['cod'] = $cod_val;
                                    $dspData[$key]['shipment_mode'] = $ldata->shipment_mode;
                                    $dspData[$key]['additional_charge'] = $data2['forward_additional'][$zone->zone_code];
                                    $dspData[$key]['additional_weight'] = $ldata['additional_weight'];
                                    $dspData[$key]['final_charge'] = $zoneValue;
                                } 
                                
                            }
                            if(!empty($dspData))
                            {
                                
                                return Response::json(['data' => $dspData]);
                            }
                            else{
                                
                                return Response::json(['error' => 'Data not found']);
                            }
                        }
                        else if(count($rcd) == 0 && count($rcd1)>0)
                        {
                            $listData = Rate::select('forward', 'cod', 'cod_percent', 'min_weight', 'courier','aggregator', 'shipment_mode','forward_additional','additional_weight')
                                        ->where('min_weight', $rcd1[0]['min_weight'])
                                        ->where('contract_type','company_client')
                                        ->where('company_id',$companyId)
                                        ->where('client_id',$clientID)
                                        ->get();
                            
                            $tot_price1=0;
                            $drt = $rcd1[0]['min_weight'];
                            $dspData = [];
                            foreach($listData as $key=>$ldata)
                            {
                                if($drt!=$ldata->min_weight)
                                {
                                    $rwt1 = $drt;
                                    $fwd_data2 = json_decode($ldata['forward'], true);
                                    $fwd_add_data2 = json_decode($ldata['forward_additional'], true);
                                    $tot_price2 = $fwd_data2['forward'][$zone->zone_code];
                                    $cod_val = 0;
                                   if($payment_mode == 'COD')
                                   {
                                       $cod_per = $total_amount*($ldata->percent*0.01);
                                        $cod_amount = $ldata->cod;
                                        if($cod_amount>$cod_per)
                                        {
                                            $cod_val = $cod_amount;
                                        }
                                        else
                                        {
                                            $cod_val = $cod_per;
                                        }
                                    }
                                    else
                                    {              
                                        $cod_val =0;
                                    }
                                    $price = $tot_price2;
                                    $dspData[$key]['courier_name'] = $ldata->courier;
                                    $dspData[$key]['aggregator'] = $ldata->aggregator ? $ldata->aggregator :'';
                                    $dspData[$key]['courier_rate'] =$fwd_data2['forward'][$zone->zone_code];
                                    $dspData[$key]['weight'] = $ldata->min_weight;
                                    $dspData[$key]['cod'] = $cod_val;
                                    $dspData[$key]['shipment_mode'] = $ldata->shipment_mode;
                                    $dspData[$key]['additional_charge'] = $fwd_add_data2['forward_additional'][$zone->zone_code];
                                    $dspData[$key]['additional_weight'] = $ldata['additional_weight'];
                                    $dspData[$key]['final_charge'] = $price;
                                }
                                else
                                {
                                    $rwt1 = $drt;
                                    $count = 0;
                                    $fwd_data1 = json_decode($ldata['forward'], true);
                                    $fwd_add_data1 = json_decode($ldata['forward_additional'], true);
                                    $addwt1 = $wt - $ldata['min_weight'];
                                    $price = $fwd_data1['forward'][$zone->zone_code];
                                    while($addwt1 > $ldata['additional_weight'])
                                    {
                                        $price += $fwd_add_data1['forward_additional'][$zone->zone_code];
                                        $addwt1 = $addwt1 - $ldata['additional_weight'];
                                        $count++;
                                    }
                                    $tot_price1 = $price + $fwd_add_data1['forward_additional'][$zone->zone_code];
                                    $cod_val = 0;
                                    if($payment_mode == 'COD' )
                                    {
                                        
                                        $cod_per = $total_amount*($ldata->percent*0.01);
                                        $cod_amount = $ldata->cod;
                                        if($cod_amount>$cod_per)
                                        {
                                            $cod_val = $cod_amount;
                                        }
                                        else
                                        {
                                            $cod_val = $cod_per;
                                        }
                                    }
                                    else{
                                        
                                        $cod_val =0;
                                    }
                                    $dspData[$key]['courier_name'] = $ldata->courier;
                                    $dspData[$key]['aggregator'] = $ldata->aggregator ? $ldata->aggregator :'';
                                    $dspData[$key]['courier_rate'] = $fwd_data1['forward'][$zone->zone_code];
                                    $dspData[$key]['weight'] = $ldata->min_weight;
                                    $dspData[$key]['cod'] = $cod_val;
                                    $dspData[$key]['shipment_mode'] = $ldata->shipment_mode;
                                    $dspData[$key]['additional_charge'] = $fwd_add_data1['forward_additional'][$zone->zone_code];
                                    $dspData[$key]['additional_weight'] = $ldata['additional_weight'];
                                    $dspData[$key]['final_charge'] = $tot_price1;
                                }
                            }
                            if(!empty($dspData))
                            {
                                
                                return Response::json(['data' => $dspData]);
                            }
                            else
                            {
                                
                                return Response::json(['error' => 'Data not found']);
                            }
                        }
                        else
                        {
                            return redirect()->back()->with(['error' => 'Rate not found']);
                        }
                    }
                } 
                else 
                {
                     return Response::json(['error' => 'no Zone Data found']);
                }
            } 
            else
            {
                return Response::json(['error' => 'no pincode Data found']);
            }
        } catch (QueryException $e) 
        {
            // Log the error
            Log::error('Database error: ' . $e->getMessage());
    
            // Return an error response
            return response()->json(['error' => 'An error occurred while processing the request'], 500);
    
        } catch (\Exception $e) 
        {
            // Log other types of exceptions
            Log::error('Exception: ' . $e->getMessage());
    
            // Return an error response
            return response()->json(['error' => 'An unexpected error occurred'], 500);
        }
    }
    public function getZoneDspRate(Request $request)
    {    
        try 
        {
            $wt = $request->weight;
            $total_amount = $request->total_price;
            $payment_mode = strtoupper($request->payment_mode);
            $contractType = $request->contract_type;
            $delivery_state = Pincode::where('pincode', $request->pincode)->value('state');
            if ($delivery_state) 
            {
                $user_type='';
                $clientID = 0;
                $companyId = 0;
                if(Auth::user()->user_type == 'isCompany')
                {
                   if(Session::has('client'))
                    { 
                        $clientID = session('client.id');
                        $companyId = Auth::user()->company->id;  
                    }
                    else
                    {
                        $companyId = Auth::user()->company->id;
                        $clientID = Auth::user()->client->id;                        
                    }
                }
                else
                {
                    $clientID = Auth::user()->client->id;
                    $companyId = Auth::user()->company->id;    
                }
                
                $couriers = Zone::select('app_logistics.id as dsp_id', 'app_logistics.logistics_name as dsp_name','app_logistics.logistics_type as type')
                            ->leftJoin('app_logistics', 'zones.dsp', '=', 'app_logistics.id')
                            ->where('zones.zone_type', $contractType)
                            ->where('zones.company_id', $companyId)
                            ->where('zones.client_id', $clientID)
                            ->groupBy('app_logistics.id', 'app_logistics.logistics_name','app_logistics.logistics_type')
                            ->get();
                
                if ($couriers->isNotEmpty()) 
                {
                    $dspData = [];
                    foreach($couriers as $key =>$courier)
                    {    
                        
                        $zone = Zone::whereRaw("FIND_IN_SET(?, zone_mapping) > 0", [$delivery_state])
                                    ->where('dsp',$courier->dsp_id)
                                    ->where('zone_type',$contractType)
                                    ->where('company_id',$companyId)
                                    ->where('client_id',$clientID)
                                    ->first();   
                        
                      
                        $result = Rate::select('forward', 'cod', 'cod_percent','min_weight','courier','aggregator','shipment_mode','additional_weight','forward_additional')
                                        ->where('min_weight', $request->weight)
                                        ->where('contract_type',$contractType)
                                        ->where('company_id',$companyId)
                                        ->where('client_id',$clientID);
                                      
                        if ($courier->type == 'Currior') 
                        {
                            $result->where('logistics_type', $courier->type)
                                ->where('aggregator', '')
                                ->where('courier', $courier->dsp_name);
                        } 
                        else 
                        {
                            $result->where('logistics_type', $courier->type)
                            ->where('aggregator', $courier->dsp_name);
                        }
                        $list = $result->get();
                         
                        if(count($list)>0)
                        {    
                            foreach($list as $key=>$ldata)
                            {
                                $cod_val = 0;
                                if($payment_mode == 'COD')
                                {
                                    $cod_per = $total_amount*($ldata->percent*0.01);
                                    $cod_amount = $ldata->cod;
                                    if($cod_amount>$cod_per)
                                    {
                                        $cod_val = $cod_amount;
                                    }
                                    else
                                    {
                                        $cod_val = $cod_per;
                                    }
                                }
                                else
                                {
                                    $cod_val = 0;
                                }                            
                                $data = json_decode($ldata->forward, true);
                                $add_data = json_decode($ldata->forward_additional, true);
                                if (isset($data['forward'][$zone->zone_code])) 
                                {
                                    $zoneValue = $data['forward'][$zone->zone_code];
                                    $dspData[$key]['courier_name'] = $ldata->courier;
                                    $dspData[$key]['aggregator'] = $ldata->aggregator ? $ldata->aggregator :'';
                                    $dspData[$key]['courier_rate'] = $data['forward'][$zone->zone_code];
                                    $dspData[$key]['weight'] = $ldata->min_weight;
                                    $dspData[$key]['cod'] = $cod_val;
                                    $dspData[$key]['shipment_mode'] = $ldata->shipment_mode;
                                    $dspData[$key]['additional_charge'] = $add_data['forward_additional'][$zone->zone_code];
                                    $dspData[$key]['additional_weight'] = $ldata['additional_weight'];
                                    $dspData[$key]['final_charge'] = $zoneValue;
                                }                                
                            }                                    
                        }
                        else
                        {
                            $rcdData = Rate::select('min_weight')
                                            ->where('min_weight','>',$wt)
                                            ->where('contract_type',$contractType)
                                            ->where('company_id',$companyId)
                                            ->where('client_id',$clientID)
                                            ->limit(1);
                            if ($courier->type == 'Currior') 
                            {
                                $rcdData->where('logistics_type', $courier->type)
                                ->where('aggregator', '')
                                    ->where('courier', $courier->dsp_name);
                            } 
                            else 
                            {
                                $rcdData->where('logistics_type', $courier->type)
                                ->where('aggregator', $courier->dsp_name);
                            }
                            $rcd = $rcdData->get();
                          
                            $rcdData1 = Rate::select('min_weight')
                                            ->where('min_weight','<',$wt)
                                            ->where('contract_type',$contractType)
                                            ->where('company_id',$companyId)
                                            ->where('client_id',$clientID)
                                            ->orderBy('min_weight','desc')
                                            ->limit(1);
                                       
                            if ($courier->type == 'Currior') {
                                $rcdData1->where('logistics_type', $courier->type)
                                ->where('aggregator', '')
                                    ->where('courier', $courier->dsp_name);
                            } else {
                                $rcdData1->where('logistics_type', $courier->type)
                                ->where('aggregator', $courier->dsp_name);
                            }
                            $rcd1 = $rcdData1->get();
                            
                            if(count($rcd)>0 && count($rcd1)>0)
                            {
                                $listDataSub = Rate::select('forward', 'cod', 'cod_percent', 'min_weight', 'courier','aggregator', 'shipment_mode','forward_additional','additional_weight')
                                            ->where(function ($query) use ($rcd, $rcd1) {
                                                $query->where('min_weight', $rcd[0]['min_weight'])
                                                    ->orWhere('min_weight', $rcd1[0]['min_weight']);
                                            })
                                            ->where('contract_type',$contractType)
                                            ->where('company_id',$companyId)
                                            ->where('client_id',$clientID);
                                        
                                if ($courier->type == 'Currior') {
                                    $listDataSub->where('logistics_type', $courier->type)
                                    ->where('aggregator', '')
                                        ->where('courier', $courier->dsp_name);
                                } else {
                                    $listDataSub->where('logistics_type', $courier->type)
                                    ->where('aggregator', $courier->dsp_name);
                                }
                                $listData = $listDataSub->get();
                                $tot_price1=0;
                                $tot_price2=0;
                                foreach($listData as $key=>$ldata)
                                {
                                    if($rcd[0]['min_weight'] > $rcd1[0]['min_weight'])
                                    {
                                        $drt = $rcd1[0]['min_weight'];
                                    }
                                    else
                                    {
                                        $drt = $rcd[0]['min_weight'];
                                    }
                                    if($drt!=$ldata->min_weight)
                                    {                                    
                                        $rwt1 = $drt;
                                        $fwd_data2 = json_decode($ldata['forward'], true);
                                        $fwd_add_data2 = json_decode($ldata['forward_additional'], true);
                                        $tot_price2 = $fwd_data2['forward'][$zone->zone_code];
                                        $cod_val = 0;
                                        if($payment_mode == 'COD')
                                        {
                                            $cod_per = $total_amount*($ldata->percent*0.01);
                                            $cod_amount = $ldata->cod;
                                            if($cod_amount>$cod_per)
                                            {
                                                $cod_val = $cod_amount;
                                            }
                                            else
                                            {
                                                $cod_val = $cod_per;
                                            }
                                        }
                                        else
                                        {    
                                            $cod_val =0;
                                        }
                                        $dspData[$key]['courier_name'] = $ldata->courier;
                                        $dspData[$key]['aggregator'] = $ldata->aggregator ? $ldata->aggregator :'';
                                        $dspData[$key]['courier_rate'] = $fwd_data2['forward'][$zone->zone_code];
                                        $dspData[$key]['weight'] = $ldata->min_weight;
                                        $dspData[$key]['cod'] = $cod_val;
                                        $dspData[$key]['shipment_mode'] = $ldata->shipment_mode;
                                        $dspData[$key]['additional_charge'] = $fwd_add_data2['forward_additional'][$zone->zone_code];
                                        $dspData[$key]['additional_weight'] = $ldata['additional_weight'];
                                        $dspData[$key]['final_charge'] = $tot_price2;
                                    }
                                    else
                                    {
                                    
                                        $count = 0;
                                        $rwt1 = $drt;
                                        $fwd_data1 = json_decode($ldata['forward'], true);
                                        $fwd_add_data1 = json_decode($ldata['forward_additional'], true);
                                        $addwt1 = $wt - $ldata['min_weight'];
                                        $price = $fwd_data1['forward'][$zone->zone_code];
                                        while($addwt1 > $ldata['additional_weight'])
                                        {
                                            $price += $fwd_add_data1['forward_additional'][$zone->zone_code];
                                            $addwt1 = $addwt1 - $ldata['additional_weight'];
                                            $count++;
                                        } 
                                        $tot_price1 = $price + $fwd_add_data1['forward_additional'][$zone->zone_code];
                                        if($payment_mode == 'COD'){
                                        
                                            $cod_per = $total_amount*($ldata->percent*0.01);
                                            $cod_amount = $ldata->cod;
                                            if($cod_amount>$cod_per)
                                            {
                                                $cod_val = $cod_amount;
                                            }
                                            else
                                            {
                                                $cod_val = $cod_per;
                                            }
                                        }
                                        else
                                        {    
                                            $cod_val = 0;
                                        }
                                        $dspData[$key]['courier_name'] = $ldata->courier;
                                        $dspData[$key]['aggregator'] = $ldata->aggregator ? $ldata->aggregator :'';
                                        $dspData[$key]['courier_rate'] =$fwd_data1['forward'][$zone->zone_code];
                                        $dspData[$key]['weight'] = $ldata->min_weight;
                                        $dspData[$key]['cod'] = $cod_val;
                                        $dspData[$key]['shipment_mode'] = $ldata->shipment_mode;
                                        $dspData[$key]['additional_charge'] = $fwd_add_data1['forward_additional'][$zone->zone_code];
                                        $dspData[$key]['additional_weight'] = $ldata['additional_weight'];
                                        $dspData[$key]['final_charge'] = $tot_price1;  
                                    }
    
                                }    
                            }
                            else if(count($rcd)>0 && count($rcd1) == 0)
                            {
                                $listData1 = Rate::select('forward', 'cod', 'cod_percent','min_weight','courier','aggregator','shipment_mode','additional_weight','forward_additional')
                                            ->where('min_weight', $rcd[0]['min_weight'])
                                            ->where('contract_type',$contractType)
                                            ->where('company_id',$companyId)
                                            ->where('client_id',$clientID);
                                            
                                if ($courier->type == 'Currior') 
                                {
                                    $listData1->where('logistics_type', $courier->type)
                                    ->where('aggregator', '')
                                        ->where('courier', $courier->dsp_name);
                                } 
                                else 
                                {
                                    $listData1->where('logistics_type', $courier->type)
                                    ->where('aggregator', $courier->dsp_name);
                                }
                                $list1 = $listData1->get();
                                foreach($list1 as $key=>$ldata)
                                {
                                    $cod_val = 0;
                                    if($payment_mode == 'COD')
                                    {
                                        $cod_per = $total_amount*($ldata->percent*0.01);
                                        $cod_amount = $ldata->cod;
                                        if($cod_amount>$cod_per)
                                        {
                                            $cod_val = $cod_amount;
                                        }
                                        else
                                        {
                                            $cod_val = $cod_per;
                                        }
                                    }
                                    else
                                    {
                                        $cod_val =0;
                                    }
                                    $data = json_decode($ldata->forward, true);
                                    $data2 = json_decode($ldata->forward_additional, true);
                                    if (isset($data['forward'][$zone->zone_code])) 
                                    {
                                        $zoneValue = $data['forward'][$zone->zone_code];
                                        $dspData[$key]['courier_name'] = $ldata->courier;
                                        $dspData[$key]['aggregator'] = $ldata->aggregator ? $ldata->aggregator :'';
                                        $dspData[$key]['courier_rate'] = $data['forward'][$zone->zone_code];
                                        $dspData[$key]['weight'] = $ldata->min_weight;
                                        $dspData[$key]['cod'] = $cod_val;
                                        $dspData[$key]['shipment_mode'] = $ldata->shipment_mode;
                                        $dspData[$key]['additional_charge'] = $data2['forward_additional'][$zone->zone_code];
                                        $dspData[$key]['additional_weight'] = $ldata['additional_weight'];
                                        $dspData[$key]['final_charge'] = $zoneValue;
                                    }     
                                }    
                            }
                            else if(count($rcd) == 0 && count($rcd1)>0)
                            {
                                $listData1 = Rate::select('forward', 'cod', 'cod_percent', 'min_weight', 'courier','aggregator', 'shipment_mode','forward_additional','additional_weight')
                                            ->where('min_weight', $rcd1[0]['min_weight'])
                                            ->where('contract_type',$contractType)
                                            ->where('company_id',$companyId)
                                            ->where('client_id',$clientID);
                                            
                                if ($courier->type == 'Currior') 
                                {
                                    $listData1->where('logistics_type', $courier->type)
                                    ->where('aggregator', '')
                                        ->where('courier', $courier->dsp_name);
                                } 
                                else 
                                {
                                    $listData1->where('logistics_type', $courier->type)
                                    ->where('aggregator', $courier->dsp_name);
                                }
                                $listData = $listData1->get();
                                $tot_price1=0;
                                $drt = $rcd1[0]['min_weight'];
                                foreach($listData as $key=>$ldata)
                                {
                                    if($drt!=$ldata->min_weight)
                                    {
                                        $rwt1 = $drt;
                                        $fwd_data2 = json_decode($ldata['forward'], true);
                                        $fwd_add_data2 = json_decode($ldata['forward_additional'], true);
                                        $tot_price2 = $fwd_data2['forward'][$zone->zone_code];
                                        $cod_val = 0;
                                        if($payment_mode == 'COD')
                                        {
                                            $cod_per = $total_amount*($ldata->percent*0.01);
                                            $cod_amount = $ldata->cod;
                                            if($cod_amount>$cod_per)
                                            {
                                                $cod_val = $cod_amount;
                                            }
                                            else
                                            {
                                                $cod_val = $cod_per;
                                            }
                                        }
                                        else
                                        {              
                                            $cod_val =0;
                                        }
                                        $price = $tot_price2;
                                        $dspData[$key]['courier_name'] = $ldata->courier;
                                        $dspData[$key]['aggregator'] = $ldata->aggregator ? $ldata->aggregator :'';
                                        $dspData[$key]['courier_rate'] =$fwd_data2['forward'][$zone->zone_code];
                                        $dspData[$key]['weight'] = $ldata->min_weight;
                                        $dspData[$key]['cod'] = $cod_val;
                                        $dspData[$key]['shipment_mode'] = $ldata->shipment_mode;
                                        $dspData[$key]['additional_charge'] = $fwd_add_data2['forward_additional'][$zone->zone_code];
                                        $dspData[$key]['additional_weight'] = $ldata['additional_weight'];
                                        $dspData[$key]['final_charge'] = $price;
                                    }
                                    else
                                    {
                                        $rwt1 = $drt;
                                        $count = 0;
                                        $fwd_data1 = json_decode($ldata['forward'], true);
                                        $fwd_add_data1 = json_decode($ldata['forward_additional'], true);
                                        $addwt1 = $wt - $ldata['min_weight'];
                                        $price = $fwd_data1['forward'][$zone->zone_code];
                                        while($addwt1 > $ldata['additional_weight'])
                                        {
                                            $price += $fwd_add_data1['forward_additional'][$zone->zone_code];
                                            $addwt1 = $addwt1 - $ldata['additional_weight'];
                                            $count++;
                                        }
                                        $tot_price1 = $price + $fwd_add_data1['forward_additional'][$zone->zone_code];
                                        $cod_val = 0;
                                        if($payment_mode == 'COD' )
                                        {
                                            $cod_per = $total_amount*($ldata->percent*0.01);
                                            $cod_amount = $ldata->cod;
                                            if($cod_amount>$cod_per)
                                            {
                                                $cod_val = $cod_amount;
                                            }
                                            else
                                            {
                                                $cod_val = $cod_per;
                                            }
                                        }
                                        else
                                        {
                                            $cod_val = 0;
                                        }
                                        $dspData[$key]['courier_name'] = $ldata->courier;
                                        $dspData[$key]['aggregator'] = $ldata->aggregator ? $ldata->aggregator :'';
                                        $dspData[$key]['courier_rate'] = $fwd_data1['forward'][$zone->zone_code];
                                        $dspData[$key]['weight'] = $ldata->min_weight;
                                        $dspData[$key]['cod'] = $cod_val;
                                        $dspData[$key]['shipment_mode'] = $ldata->shipment_mode;
                                        $dspData[$key]['additional_charge'] = $fwd_add_data1['forward_additional'][$zone->zone_code];
                                        $dspData[$key]['additional_weight'] = $ldata['additional_weight'];
                                        $dspData[$key]['final_charge'] = $tot_price1;
                                    }
                                }    
                            }
                            else
                            {
                                return redirect()->back()->with(['error' => 'Rate not found']);
                            }
                        }    
                    }
                    
                    return response()->json(['data' => $dspData]);
                }
                else
                {
                    return response()->json(['error' => 'no zone data found']);
                }
            } 
            else
            {
                return response()->json(['error' => 'no pincode data found']);
            }
        } 
        catch (QueryException $e) 
        {
            // Log the error
            Log::error('Database error: ' . $e->getMessage());
    
            // Return an error response
            return response()->json(['error' => 'An error occurred while processing the request'], 500);
    
        } catch (\Exception $e) 
        {
            // Log other types of exceptions
            Log::error('Exception: ' . $e->getMessage());
            // Return an error response
            return response()->json(['error' => 'An unexpected error occurred'], 500);
        }
    }
    public function getZoneList($contract)
    {
        $clientID = 0;
        $companyId = 0;
        if(Auth::user()->user_type == 'isCompany')
        {
            if(Session::has('client'))
            { 
                $clientID = session('client.id');
                $companyId = Auth::user()->company->id;  
            }
            else
            {
                $companyId = Auth::user()->company->id;
                $clientID = Auth::user()->client->id;                        
            }
        }
        else
        {
            $clientID = Auth::user()->client->id;
            $companyId = Auth::user()->company->id;    
        }
        $zones = Zone::select('zone_code','description')
                    ->where('zone_type',$contract)
                    ->where('company_id',$companyId)
                    ->where('client_id',$clientID)
                    ->get();
        
        if(empty($zones)){
            return Response::json(['error' => 'no Zone Data found']);
        }
        else{
            return Response::json(['data' => $zones]);
        }
    }
    public function getDspZoneList($contract,$dsp)
    {
        
        if(Session::has('client'))
        { 
            $clientID = session('client.id');
            $companyId = Auth::user()->company->id;  
        }
        else
        {
            $companyId = Auth::user()->company->id;
            $clientID = Auth::user()->client->id;                        
        }
        $zones = Zone::select('zone_code','description')
                    ->where('dsp', function ($query) use ($dsp) {
                        $query->select('id')
                            ->from('app_logistics')
                            ->where('logistics_name', $dsp)
                            ->limit(1); // Assuming you want only one result
                    })
                    ->where('zone_type',$contract)
                    ->where('company_id',$companyId)
                    ->where('client_id',$clientID)
                    ->get();
        if(empty($zones)){
            return Response::json(['error' => 'no Zone Data found']);
        }
        else{
            return Response::json(['data' => $zones]);
        }
    }
}
