<?php

namespace App\Http\Controllers;
use App\Models\Zone;
use App\Models\Rate;
use App\Models\Pincode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Session,Auth;
class RateCalculatorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_bkp()
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
                $zone_type="isClient";
            }
            else
            {
                $companyId = Auth::user()->company->id;
                $clientID = Auth::user()->client->id;
                $zone_type = "isCompany";
            }
        }
        else
        {
            $clientID = Auth::user()->client->id;
            $companyId = Auth::user()->company->id;
            $zone_type="isClient";
        }
        $zones = Zone::where('zone_type',$zone_type)->where('company_id',$companyId)->where('client_id',$clientID)->get();
        $rates = Rate::orderby('courier')
                    ->orderBy('aggregator')
                    ->orderBy('min_weight')
                    ->paginate(10);
        $courier_data =[];
        $requestData=[];
       
         return view('client-app.rate-calculator-card', compact('rates','zones','courier_data','requestData'));
    }
    public function index()
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
        $zones = Zone::where('zone_type',$zone_type)->where('company_id',$companyId)->where('client_id',$clientID)->get();
        $rates = Rate::orderby('courier')
                    ->where('contract_type','company_client')
                    ->where('company_id',$companyId)
                    ->where('client_id',$clientID)
                    ->orderBy('aggregator')
                    ->orderBy('min_weight')
                    ->paginate(10);
        $courier_data =[];
        $requestData=[];
       
         return view('client-app.rate-calculator-card', compact('rates','zones','courier_data','requestData'));
    }
    public function b2c_calculator1(Request $request){
        #dd($request->all());
        $origin = $request->origin;
        $delivery = $request->destination;
        $weight = $request->weight;
        $vol_weight = ($request->length*$request->breadth*$request->height)/5000;
        if($vol_weight>$weight){
            $wt = $vol_weight;
        }
        else{
            $wt = $weight;
        }
        $cod = $request->cod;
        $origin_state = Pincode::select('state')->where('pincode',$origin)->first();
        $delivery_state = Pincode::where('pincode', $delivery)->value('state');
        if ($delivery_state) {
            $zone = Zone::whereRaw("FIND_IN_SET(?, zone_mapping) > 0", [$delivery_state])->first();
            if (!empty($zone)) {
                    $list = Rate::select('forward', 'cod', 'cod_percent','min_weight','courier','shipment_mode')->where('min_weight', $wt)->get();
                    if (!empty($list)) 
                    {
                        $dspData = [];
                        foreach($list as $key=>$ldata)
                        {
                            if($cod=="yes") {
                                $cod_val = $ldata->cod;
                            }
                            else{
                                $cod_val = 0;
                            }
                            $data = json_decode($ldata->forward, true);
                            if (isset($data['forward'][$zone->zone_code])) 
                            {
                                $zoneValue = $data['forward'][$zone->zone_code];
                                $dspData[$key]['courier_name'] = $ldata->courier;
                                $dspData[$key]['courier_rate'] = $zoneValue;
                                $dspData[$key]['weight'] = $wt;
                                $dspData[$key]['cod'] = $cod_val;
                                $dspData[$key]['shipment_mode'] = $ldata->shipment_mode;
                            } 
                            else 
                            {
                               
                                dd('Zone mapping not found in forward data');
                            }
                        }
                        $zones = Zone::all();
                        $rates = Rate::orderby('courier')
                                ->orderBy('aggregator')
                                ->orderBy('min_weight')
                                ->get();
                        $requestData = $request->all();
                        if(!empty($dspData)){
                        $courier_data = $dspData;
                        return view('client-app.rate-calculator-card', compact('rates','zones','courier_data','requestData'));
                        }
                        else{
                            $courier_data = [];
                            return view('client-app.rate-calculator-card', compact('rates','zones','courier_data','requestData'));
                        }
                    } 
                    else 
                    {
                        
                        dd('No rate found for the specified weight');
                    }
                
            } 
            else {
              
                dd('No zone found');
            }
        } else {
            dd('no state availabe on this delivery pincode');  
        }
       
        
    }
    public function b2c_calculator_04_12(Request $request){
      
        $origin = $request->origin;
        $delivery = $request->destination;
        $weight = $request->weight;
        $vol_weight = ($request->length*$request->breadth*$request->height)/5000;
        if($vol_weight>$weight){
            $wt = $vol_weight;
        }
        else{
            $wt = $weight;
        }
        $cod = $request->cod;
        $origin_state = Pincode::select('state')->where('pincode',$origin)->first();
        $delivery_state = Pincode::where('pincode', $delivery)->value('state');
        if ($delivery_state) {
            $zone = Zone::whereRaw("FIND_IN_SET(?, zone_mapping) > 0", [$delivery_state])->first();
         
            if (!empty($zone)) {
                $list = Rate::select('forward', 'cod', 'cod_percent','min_weight','courier','shipment_mode')->where('min_weight', $wt)->get();
                if(count($list)>0){
                    foreach($list as $key=>$ldata)
                    {
                        $dspData = [];
                        foreach($list as $key=>$ldata)
                        {
                            if($cod=="yes") {
                                $cod_val = $ldata->cod;
                            }
                            else{
                                $cod_val = 0;
                            }
                            $data = json_decode($ldata->forward, true);
                            if (isset($data['forward'][$zone->zone_code])) 
                            {
                                $zoneValue = $data['forward'][$zone->zone_code];
                                $dspData[$key]['courier_name'] = $ldata->courier;
                                $dspData[$key]['courier_rate'] = $zoneValue;
                                $dspData[$key]['weight'] = $wt;
                                $dspData[$key]['cod'] = $cod_val;
                                $dspData[$key]['shipment_mode'] = $ldata->shipment_mode;
                            } 
                            
                        }
                        $zones = Zone::all();
                        $rates = Rate::orderby('courier')
                                ->orderBy('aggregator')
                                ->orderBy('min_weight')
                                ->get();
                        $requestData = $request->all();
                        if(!empty($dspData)){
                            $courier_data = $dspData;
                            return view('client-app.rate-calculator-card', compact('rates','zones','courier_data','requestData'));
                        }
                        else{
                            $courier_data = [];
                            return view('client-app.rate-calculator-card', compact('rates','zones','courier_data','requestData'));
                        }
                    }
                }
                else{
                   $rcd = Rate::select('min_weight')
                    ->where('min_weight','>',$wt)
                    ->limit(1)
                    ->get();
                 
                $rcd1 = Rate::select('min_weight')
                    ->where('min_weight','<',$wt)
                    ->orderBy('min_weight','desc')
                    ->limit(1)
                    ->get();
                  
                if(count($rcd)>0){
                    #echo $rcd[0]['min_weight'].','.$rcd1[0]['min_weight'];die;
                    $listData = Rate::select('forward', 'cod', 'cod_percent', 'min_weight', 'courier', 'shipment_mode','forward_additional')
                                ->where('min_weight', $rcd[0]['min_weight'])
                                ->orWhere('min_weight', $rcd1[0]['min_weight'])
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
                        else{
                            $drt = $rcd[0]['min_weight'];
                        }
                        if($drt!=$ldata->min_weight)
                        {
                            $rwt1 = $drt;
                            $fwd_data2 = json_decode($ldata['forward'], true);
                            $tot_price2 = $fwd_data2['forward'][$zone->zone_code];
                            if($cod=="yes") {
                                $cod_val = (float)$ldata->cod;
                            }
                            else{
                                $cod_val = 0;
                            }
                            $price = $tot_price2;
                            $dspData[$key]['courier_name'] = $ldata->courier;
                            $dspData[$key]['courier_rate'] =$price;
                            $dspData[$key]['weight'] = $ldata->min_weight;
                            $dspData[$key]['cod'] = $cod_val;
                            $dspData[$key]['shipment_mode'] = $ldata->shipment_mode;
                        }
                        else{
                            #echo "else";
                            $rwt1 = $drt;
                            $fwd_data1 = json_decode($ldata['forward'], true);
                            $fwd_add_data1 = json_decode($ldata['forward_additional'], true);
                            $addwt1 = $wt - $ldata['min_weight'];
                            $times1 =($addwt1/$rwt1); 
                            $round_no = round($times1);
                            if($round_no == 0){
                                $no1 = 1;
                            }
                            else{
                                $no1 =$round_no;
                            }
                            // echo $addwt1.','.$times1;
                            // echo "<br>";
                           # echo $no1.','.$fwd_data1['forward'][$zone->zone_code].','.$fwd_add_data1['forward_additional'][$zone->zone_code];die;
                            $tot_price1 = $fwd_data1['forward'][$zone->zone_code] + (($fwd_add_data1['forward_additional'][$zone->zone_code])*$no1);
                           # dd($tot_price1);
                           if($cod=="yes") {
                                $cod_val = (float)$ldata->cod;
                            }
                            else{
                                $cod_val = 0;
                            }
                            $price = $tot_price1;
                            #echo 'if'.$price;die;
                            $dspData[$key]['courier_name'] = $ldata->courier;
                            $dspData[$key]['courier_rate'] =$price;
                            $dspData[$key]['weight'] = $ldata->min_weight;
                            $dspData[$key]['cod'] = $cod_val;
                            $dspData[$key]['shipment_mode'] = $ldata->shipment_mode;
                           
                        }
                        
                     
                       #echo $tot_price1.','.$tot_price2;die;
                        
                        // if($cod=="yes") {
                        //     $cod_val = (float)$ldata->cod;
                        // }
                        // else{
                        //     $cod_val = 0;
                        // }
                       
                        // if($tot_price1 > $tot_price2){
                           
                        //     $price = $tot_price1;
                        //     #echo 'if'.$price;die;
                        //     $dspData[$key]['courier_name'] = $ldata->courier;
                        //     $dspData[$key]['courier_rate'] =$price;
                        //     $dspData[$key]['weight'] = $ldata->min_weight;
                        //     $dspData[$key]['cod'] = $cod_val;
                        //     $dspData[$key]['shipment_mode'] = $ldata->shipment_mode;
                        // }
                        // else{
                           
                        //     $price = $tot_price1;
                        //   # echo "else".$price;die;
                        //     $dspData[$key]['courier_name'] = $ldata->courier;
                        //     $dspData[$key]['courier_rate'] =$price;
                        //     $dspData[$key]['weight'] = $ldata->min_weight;
                        //     $dspData[$key]['cod'] = $cod_val;
                        //     $dspData[$key]['shipment_mode'] = $ldata->shipment_mode;
                        // }
                        
                    }
                    $zones = Zone::all();
                    $rates = Rate::orderby('courier')
                            ->orderBy('aggregator')
                            ->orderBy('min_weight')
                            ->get();
                            
                    $requestData = $request->all();
                    #dd($dspData);
                    if(!empty($dspData)){
                    $courier_data = $dspData;
                    return view('client-app.rate-calculator-card', compact('rates','zones','courier_data','requestData'));
                    }
                    else{
                        $courier_data = [];
                        return view('client-app.rate-calculator-card', compact('rates','zones','courier_data','requestData'));
                    }
                }
                else{
                    $listData = Rate::select('forward', 'cod', 'cod_percent', 'min_weight', 'courier', 'shipment_mode','forward_additional')
                                ->where('min_weight', $rcd1[0]['min_weight'])
                                ->get();
                  
                    $dspData=[];
                    $tot_price1=0;
                    $drt = $rcd1[0]['min_weight'];
                    foreach($listData as $key=>$ldata)
                    {
                        if($drt!=$ldata->min_weight){
                            
                           
                            $rwt1 = $drt;
                          
                            $fwd_data2 = json_decode($ldata['forward'], true);
                            $tot_price2 = $fwd_data2['forward'][$zone->zone_code];
                            if($cod=="yes") {
                                $cod_val = (float)$ldata->cod;
                            }
                            else{
                                $cod_val = 0;
                            }
                            $price = $tot_price2;
                            #echo 'if'.$price;die;
                            $dspData[$key]['courier_name'] = $ldata->courier;
                            $dspData[$key]['courier_rate'] =$price;
                            $dspData[$key]['weight'] = $ldata->min_weight;
                            $dspData[$key]['cod'] = $cod_val;
                            $dspData[$key]['shipment_mode'] = $ldata->shipment_mode;
                        }
                        else{
                            #echo "else";
                            $rwt1 = $drt;
                            #$rwt2 =$rcd[0]['min_weight'];
                            $fwd_data1 = json_decode($ldata['forward'], true);
                            $fwd_add_data1 = json_decode($ldata['forward_additional'], true);
                            $addwt1 = $wt - $ldata['min_weight'];
                            $times1 =($addwt1/$rwt1); 
                            $round_no = round($times1);
                            if($round_no == 0){
                                $no1 = 1;
                            }
                            else{
                                $no1 =$round_no;
                            }
                            // echo $addwt1.','.$times1;
                            // echo "<br>";
                           # echo $no1.','.$fwd_data1['forward'][$zone->zone_code].','.$fwd_add_data1['forward_additional'][$zone->zone_code];die;
                            $tot_price1 = $fwd_data1['forward'][$zone->zone_code] + (($fwd_add_data1['forward_additional'][$zone->zone_code])*$no1);
                           # dd($tot_price1);
                           if($cod=="yes") {
                                $cod_val = (float)$ldata->cod;
                            }
                            else{
                                $cod_val = 0;
                            }
                            $price = $tot_price1;
                            #echo 'if'.$price;die;
                            $dspData[$key]['courier_name'] = $ldata->courier;
                            $dspData[$key]['courier_rate'] =$price;
                            $dspData[$key]['weight'] = $ldata->min_weight;
                            $dspData[$key]['cod'] = $cod_val;
                            $dspData[$key]['shipment_mode'] = $ldata->shipment_mode;
                           
                        }
                        
                     
                       #echo $tot_price1.','.$tot_price2;die;
                        
                        // if($cod=="yes") {
                        //     $cod_val = (float)$ldata->cod;
                        // }
                        // else{
                        //     $cod_val = 0;
                        // }
                       
                        // if($tot_price1 > $tot_price2){
                           
                        //     $price = $tot_price1;
                        //     #echo 'if'.$price;die;
                        //     $dspData[$key]['courier_name'] = $ldata->courier;
                        //     $dspData[$key]['courier_rate'] =$price;
                        //     $dspData[$key]['weight'] = $ldata->min_weight;
                        //     $dspData[$key]['cod'] = $cod_val;
                        //     $dspData[$key]['shipment_mode'] = $ldata->shipment_mode;
                        // }
                        // else{
                           
                        //     $price = $tot_price1;
                        //   # echo "else".$price;die;
                        //     $dspData[$key]['courier_name'] = $ldata->courier;
                        //     $dspData[$key]['courier_rate'] =$price;
                        //     $dspData[$key]['weight'] = $ldata->min_weight;
                        //     $dspData[$key]['cod'] = $cod_val;
                        //     $dspData[$key]['shipment_mode'] = $ldata->shipment_mode;
                        // }
                        
                    }
                    $zones = Zone::all();
                    $rates = Rate::orderby('courier')
                            ->orderBy('aggregator')
                            ->orderBy('min_weight')
                            ->get();
                            
                    $requestData = $request->all();
                    #dd($dspData);
                    if(!empty($dspData)){
                    $courier_data = $dspData;
                    return view('client-app.rate-calculator-card', compact('rates','zones','courier_data','requestData'));
                    }
                    else{
                        $courier_data = [];
                        return view('client-app.rate-calculator-card', compact('rates','zones','courier_data','requestData'));
                    }
                }
                 
                    
                }
            } 
            else {
              
                dd('No zone found');
            }
        } else {
            dd('no state availabe on this delivery pincode');  
        }
       
        
    }
    public function b2c_calculator_run(Request $request){
      
        $origin = $request->origin;
        $delivery = $request->destination;
        $weight = $request->weight;
        $vol_weight = ($request->length*$request->breadth*$request->height)/5000;
        if($vol_weight>$weight){
            $wt = $vol_weight;
        }
        else{
            $wt = $weight;
        }
        $cod = $request->cod;
        $origin_state = Pincode::select('state')->where('pincode',$origin)->first();
        $delivery_state = Pincode::where('pincode', $delivery)->value('state');
        if ($delivery_state) {
            $zone = Zone::whereRaw("FIND_IN_SET(?, zone_mapping) > 0", [$delivery_state])->first();
         
            if (!empty($zone)) {
                $list = Rate::select('forward', 'cod', 'cod_percent','min_weight','courier','shipment_mode')->where('min_weight', $wt)->get();
                if(count($list)>0){
                    foreach($list as $key=>$ldata)
                    {
                        $dspData = [];
                        foreach($list as $key=>$ldata)
                        {
                            if($cod=="yes") {
                                $cod_val = $ldata->cod;
                            }
                            else{
                                $cod_val = 0;
                            }
                            $data = json_decode($ldata->forward, true);
                            if (isset($data['forward'][$zone->zone_code])) 
                            {
                                $zoneValue = $data['forward'][$zone->zone_code];
                                $dspData[$key]['courier_name'] = $ldata->courier;
                                $dspData[$key]['courier_rate'] = $zoneValue;
                                $dspData[$key]['weight'] = $wt;
                                $dspData[$key]['cod'] = $cod_val;
                                $dspData[$key]['shipment_mode'] = $ldata->shipment_mode;
                            } 
                            
                        }
                        $zones = Zone::all();
                        $rates = Rate::orderby('courier')
                                ->orderBy('aggregator')
                                ->orderBy('min_weight')
                                ->get();
                        $requestData = $request->all();
                        if(!empty($dspData)){
                            $courier_data = $dspData;
                            return view('client-app.rate-calculator-card', compact('rates','zones','courier_data','requestData'));
                        }
                        else{
                            $courier_data = [];
                            return view('client-app.rate-calculator-card', compact('rates','zones','courier_data','requestData'));
                        }
                    }
                }
                else{
                   $rcd = Rate::select('min_weight')
                    ->where('min_weight','>',$wt)
                    
                    ->limit(1)
                    ->get();
                 
                $rcd1 = Rate::select('min_weight')
                    ->where('min_weight','<',$wt)
                    ->orderBy('min_weight','desc')
                    ->limit(1)
                    ->get();
                  
                if(count($rcd)>0){
                    #echo $rcd[0]['min_weight'].','.$rcd1[0]['min_weight'];die;
                    $listData = Rate::select('forward', 'cod', 'cod_percent', 'min_weight', 'courier', 'shipment_mode','forward_additional')
                                ->where('min_weight', $rcd[0]['min_weight'])
                                ->orWhere('min_weight', $rcd1[0]['min_weight'])
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
                        else{
                            $drt = $rcd[0]['min_weight'];
                        }
                        if($drt!=$ldata->min_weight)
                        {
                            $rwt1 = $drt;
                            $fwd_data2 = json_decode($ldata['forward'], true);
                            $tot_price2 = $fwd_data2['forward'][$zone->zone_code];
                            if($cod=="yes") {
                                $cod_val = (float)$ldata->cod;
                            }
                            else{
                                $cod_val = 0;
                            }
                            $price = $tot_price2;
                            $dspData[$key]['courier_name'] = $ldata->courier;
                            $dspData[$key]['courier_rate'] =$price;
                            $dspData[$key]['weight'] = $ldata->min_weight;
                            $dspData[$key]['cod'] = $cod_val;
                            $dspData[$key]['shipment_mode'] = $ldata->shipment_mode;
                        }
                        else{
                            #echo "else";
                            $rwt1 = $drt;
                            $fwd_data1 = json_decode($ldata['forward'], true);
                            $fwd_add_data1 = json_decode($ldata['forward_additional'], true);
                            $addwt1 = $wt - $ldata['min_weight'];
                            
                            $times1 = $addwt1/$rwt1; 
                            
                            dd($times1);
                            $k = explode('.',$times1);
                            echo $k[0].','.$k[1];die;
                            if($k[0]>0){
                                $n1=1;
                            }
                            else{
                                $n1=0;
                            }
                            if($k[1]>0){
                                $n2=1;
                            }
                            else{
                                $n2=0;
                            }
                            $round_no = ceil($times1);
                            if($round_no == 0){
                                $no1 = 1;
                            }
                            else{
                                $no1 =$round_no;
                            }
                            echo $addwt1.','.$times1;
                            echo "<br>";
                          echo $no1.','.$fwd_data1['forward'][$zone->zone_code].','.$fwd_add_data1['forward_additional'][$zone->zone_code];die;
                            $tot_price1 = $fwd_data1['forward'][$zone->zone_code] + (($fwd_add_data1['forward_additional'][$zone->zone_code])*$no1);
                           # dd($tot_price1);
                           if($cod=="yes") {
                                $cod_val = (float)$ldata->cod;
                            }
                            else{
                                $cod_val = 0;
                            }
                            $price = $tot_price1;
                            #echo 'if'.$price;die;
                            $dspData[$key]['courier_name'] = $ldata->courier;
                            $dspData[$key]['courier_rate'] =$price;
                            $dspData[$key]['weight'] = $ldata->min_weight;
                            $dspData[$key]['cod'] = $cod_val;
                            $dspData[$key]['shipment_mode'] = $ldata->shipment_mode;
                           
                        }
                        
                     
                       #echo $tot_price1.','.$tot_price2;die;
                        
                        // if($cod=="yes") {
                        //     $cod_val = (float)$ldata->cod;
                        // }
                        // else{
                        //     $cod_val = 0;
                        // }
                       
                        // if($tot_price1 > $tot_price2){
                           
                        //     $price = $tot_price1;
                        //     #echo 'if'.$price;die;
                        //     $dspData[$key]['courier_name'] = $ldata->courier;
                        //     $dspData[$key]['courier_rate'] =$price;
                        //     $dspData[$key]['weight'] = $ldata->min_weight;
                        //     $dspData[$key]['cod'] = $cod_val;
                        //     $dspData[$key]['shipment_mode'] = $ldata->shipment_mode;
                        // }
                        // else{
                           
                        //     $price = $tot_price1;
                        //   # echo "else".$price;die;
                        //     $dspData[$key]['courier_name'] = $ldata->courier;
                        //     $dspData[$key]['courier_rate'] =$price;
                        //     $dspData[$key]['weight'] = $ldata->min_weight;
                        //     $dspData[$key]['cod'] = $cod_val;
                        //     $dspData[$key]['shipment_mode'] = $ldata->shipment_mode;
                        // }
                        
                    }
                    $zones = Zone::all();
                    $rates = Rate::orderby('courier')
                            ->orderBy('aggregator')
                            ->orderBy('min_weight')
                            ->get();
                            
                    $requestData = $request->all();
                    #dd($dspData);
                    if(!empty($dspData)){
                    $courier_data = $dspData;
                    return view('client-app.rate-calculator-card', compact('rates','zones','courier_data','requestData'));
                    }
                    else{
                        $courier_data = [];
                        return view('client-app.rate-calculator-card', compact('rates','zones','courier_data','requestData'));
                    }
                }
                else{
                    $listData = Rate::select('forward', 'cod', 'cod_percent', 'min_weight', 'courier', 'shipment_mode','forward_additional')
                                ->where('min_weight', $rcd1[0]['min_weight'])
                                ->get();
                  
                    $dspData=[];
                    $tot_price1=0;
                    $drt = $rcd1[0]['min_weight'];
                    foreach($listData as $key=>$ldata)
                    {
                        if($drt!=$ldata->min_weight){
                            
                           
                            $rwt1 = $drt;
                          
                            $fwd_data2 = json_decode($ldata['forward'], true);
                            $tot_price2 = $fwd_data2['forward'][$zone->zone_code];
                            if($cod=="yes") {
                                $cod_val = (float)$ldata->cod;
                            }
                            else{
                                $cod_val = 0;
                            }
                            $price = $tot_price2;
                            #echo 'if'.$price;die;
                            $dspData[$key]['courier_name'] = $ldata->courier;
                            $dspData[$key]['courier_rate'] =$price;
                            $dspData[$key]['weight'] = $ldata->min_weight;
                            $dspData[$key]['cod'] = $cod_val;
                            $dspData[$key]['shipment_mode'] = $ldata->shipment_mode;
                        }
                        else{
                            #echo "else";
                            $rwt1 = $drt;
                            #$rwt2 =$rcd[0]['min_weight'];
                            $fwd_data1 = json_decode($ldata['forward'], true);
                            $fwd_add_data1 = json_decode($ldata['forward_additional'], true);
                            $addwt1 = $wt - $ldata['min_weight'];
                            $times1 =($addwt1/$rwt1); 
                            $round_no = ceil($times1);
                            if($round_no == 0){
                                $no1 = 1;
                            }
                            else{
                                $no1 =$round_no;
                            }
                            // echo $addwt1.','.$times1;
                            // echo "<br>";
                           # echo $no1.','.$fwd_data1['forward'][$zone->zone_code].','.$fwd_add_data1['forward_additional'][$zone->zone_code];die;
                            $tot_price1 = $fwd_data1['forward'][$zone->zone_code] + (($fwd_add_data1['forward_additional'][$zone->zone_code])*$no1);
                           # dd($tot_price1);
                           if($cod=="yes") {
                                $cod_val = (float)$ldata->cod;
                            }
                            else{
                                $cod_val = 0;
                            }
                            $price = $tot_price1;
                            #echo 'if'.$price;die;
                            $dspData[$key]['courier_name'] = $ldata->courier;
                            $dspData[$key]['courier_rate'] =$price;
                            $dspData[$key]['weight'] = $ldata->min_weight;
                            $dspData[$key]['cod'] = $cod_val;
                            $dspData[$key]['shipment_mode'] = $ldata->shipment_mode;
                           
                        }
                        
                     
                       #echo $tot_price1.','.$tot_price2;die;
                        
                        // if($cod=="yes") {
                        //     $cod_val = (float)$ldata->cod;
                        // }
                        // else{
                        //     $cod_val = 0;
                        // }
                       
                        // if($tot_price1 > $tot_price2){
                           
                        //     $price = $tot_price1;
                        //     #echo 'if'.$price;die;
                        //     $dspData[$key]['courier_name'] = $ldata->courier;
                        //     $dspData[$key]['courier_rate'] =$price;
                        //     $dspData[$key]['weight'] = $ldata->min_weight;
                        //     $dspData[$key]['cod'] = $cod_val;
                        //     $dspData[$key]['shipment_mode'] = $ldata->shipment_mode;
                        // }
                        // else{
                           
                        //     $price = $tot_price1;
                        //   # echo "else".$price;die;
                        //     $dspData[$key]['courier_name'] = $ldata->courier;
                        //     $dspData[$key]['courier_rate'] =$price;
                        //     $dspData[$key]['weight'] = $ldata->min_weight;
                        //     $dspData[$key]['cod'] = $cod_val;
                        //     $dspData[$key]['shipment_mode'] = $ldata->shipment_mode;
                        // }
                        
                    }
                    $zones = Zone::all();
                    $rates = Rate::orderby('courier')
                            ->orderBy('aggregator')
                            ->orderBy('min_weight')
                            ->get();
                            
                    $requestData = $request->all();
                    #dd($dspData);
                    if(!empty($dspData)){
                    $courier_data = $dspData;
                    return view('client-app.rate-calculator-card', compact('rates','zones','courier_data','requestData'));
                    }
                    else{
                        $courier_data = [];
                        return view('client-app.rate-calculator-card', compact('rates','zones','courier_data','requestData'));
                    }
                }
                 
                    
                }
            } 
            else {
              
                dd('No zone found');
            }
        } else {
            dd('no state availabe on this delivery pincode');  
        }
       
        
    }
    public function b2c_calculator_bkp(Request $request)
    {
      $validate = $request->validate([
            'origin' => 'required|digits:6',
            'destination' => 'required|digits:6',
            'weight' => 'required|numeric|gt:0',
            'length' => 'required|numeric|gt:0',
            'height' => 'required|numeric|gt:0',
            'breadth' => 'required|numeric|gt:0',
            'cod' => 'required|string'
            
        ]); 
        $origin = $request->origin;
        $delivery = $request->destination;
        $weight = $request->weight;
        $vol_weight = ($request->length*$request->breadth*$request->height)/5000;
        if($vol_weight>$weight)
        {
            $wt = $vol_weight;
        }
        else{
            $wt = $weight;
        }
        $cod = $request->cod;
        $origin_state = Pincode::select('state')->where('pincode',$origin)->first();
        $delivery_state = Pincode::where('pincode', $delivery)->value('state');
        if ($delivery_state) {
            $user_type='';
            $clientID = 0;
            $companyId = 0;
            if(Auth::user()->user_type == 'isCompany')
            {
               if(Session::has('client'))
                { 
                    $clientID = session('client.id');
                    $companyId = Auth::user()->company->id;
                    $zone_type="isClient";
                }
                else
                {
                    $companyId = Auth::user()->company->id;
                    $clientID = Auth::user()->client->id;
                    $zone_type = "isCompany";
                }
            }
            else
            {
                $clientID = Auth::user()->client->id;
                $companyId = Auth::user()->company->id;
                $zone_type="isClient";
            }
            $zone = Zone::whereRaw("FIND_IN_SET(?, zone_mapping) > 0", [$delivery_state])->where('zone_type',$zone_type)->where('company_id',$companyId)->where('client_id',$clientID)->first();
            $zones = Zone::where('zone_type',$zone_type)->where('company_id',$companyId)->where('client_id',$clientID)->get();
            if (!empty($zone)) {
                $list = Rate::select('forward', 'cod', 'cod_percent','min_weight','courier','shipment_mode')->where('min_weight', $wt)->get();
                if(count($list)>0){
                    foreach($list as $key=>$ldata)
                    {
                        $dspData = [];
                        foreach($list as $key=>$ldata)
                        {
                            if($cod=="yes") {
                                $cod_val = $ldata->cod;
                            }
                            else{
                                $cod_val = 0;
                            }
                            $data = json_decode($ldata->forward, true);
                            if (isset($data['forward'][$zone->zone_code])) 
                            {
                                $zoneValue = $data['forward'][$zone->zone_code];
                                $dspData[$key]['courier_name'] = $ldata->courier;
                                $dspData[$key]['courier_rate'] = $zoneValue;
                                $dspData[$key]['weight'] = $ldata->min_weight;
                                $dspData[$key]['cod'] = $cod_val;
                                $dspData[$key]['shipment_mode'] = $ldata->shipment_mode;
                            } 
                            
                        }
                        
                        $rates = Rate::orderby('courier')
                                ->orderBy('aggregator')
                                ->orderBy('min_weight')
                                ->get();
                        $requestData = $request->all();
                        if(!empty($dspData)){
                            $courier_data = $dspData;
                            return view('client-app.rate-calculator-card', compact('rates','zones','courier_data','requestData'));
                        }
                        else{
                            $courier_data = [];
                            return view('client-app.rate-calculator-card', compact('rates','zones','courier_data','requestData'));
                        }
                    }
                }
                else{
                    $rcd = Rate::select('min_weight')
                                    ->where('min_weight','>',$wt)
                                    ->limit(1)
                                    ->get();
                 
                    $rcd1 = Rate::select('min_weight')
                                    ->where('min_weight','<',$wt)
                                    ->orderBy('min_weight','desc')
                                    ->limit(1)
                                    ->get();
                  
                    if(count($rcd)>0 && count($rcd1)>0){
                        
                    $listData = Rate::select('forward', 'cod', 'cod_percent', 'min_weight', 'courier', 'shipment_mode','forward_additional','additional_weight')
                                ->where('min_weight', $rcd[0]['min_weight'])
                                ->orWhere('min_weight', $rcd1[0]['min_weight'])
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
                        else{
                            $drt = $rcd[0]['min_weight'];
                        }
                        if($drt!=$ldata->min_weight)
                        {
                            
                            $rwt1 = $drt;
                            $fwd_data2 = json_decode($ldata['forward'], true);
                            $tot_price2 = $fwd_data2['forward'][$zone->zone_code];
                            if($cod=="yes") {
                                $cod_val = (float)$ldata->cod;
                            }
                            else{
                                $cod_val = 0;
                            }
                            
                            $dspData[$key]['courier_name'] = $ldata->courier;
                            $dspData[$key]['courier_rate'] = $tot_price2;
                            $dspData[$key]['weight'] = $ldata->min_weight;
                            $dspData[$key]['cod'] = $cod_val;
                            $dspData[$key]['shipment_mode'] = $ldata->shipment_mode;
                        }
                        else{
                           
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
                            if($cod=="yes") {
                                $cod_val = (float)$ldata->cod;
                            }
                            else{
                                $cod_val = 0;
                            }
                            $dspData[$key]['courier_name'] = $ldata->courier;
                            $dspData[$key]['courier_rate'] =$tot_price1;
                            $dspData[$key]['weight'] = $ldata->min_weight;
                            $dspData[$key]['cod'] = $cod_val;
                            $dspData[$key]['shipment_mode'] = $ldata->shipment_mode;
                           
                        }
                    }
                    
                    $rates = Rate::orderby('courier')
                            ->orderBy('aggregator')
                            ->orderBy('min_weight')
                            ->get();
                            
                    $requestData = $request->all();
                    #dd($dspData);
                    if(!empty($dspData)){
                        $courier_data = $dspData;
                        return view('client-app.rate-calculator-card', compact('rates','zones','courier_data','requestData'));
                    }
                    else{
                        $courier_data = [];
                        return view('client-app.rate-calculator-card', compact('rates','zones','courier_data','requestData'));
                    }
                }
                else if(count($rcd)>0 && count($rcd1) == 0){
                    $list1 = Rate::select('forward', 'cod', 'cod_percent','min_weight','courier','shipment_mode','additional_weight')->where('min_weight', $rcd[0]['min_weight'])->get();
                    $dspData = [];
                   
                    foreach($list1 as $key=>$ldata)
                    {
                        if($cod=="yes") {
                            $cod_val = $ldata->cod;
                        }
                        else{
                            $cod_val = 0;
                        }
                        $data = json_decode($ldata->forward, true);
                       
                        if (isset($data['forward'][$zone->zone_code])) 
                        {
                            $zoneValue = $data['forward'][$zone->zone_code];
                            $dspData[$key]['courier_name'] = $ldata->courier;
                            $dspData[$key]['courier_rate'] = $zoneValue;
                            $dspData[$key]['weight'] = $ldata->min_weight;
                            $dspData[$key]['cod'] = $cod_val;
                            $dspData[$key]['shipment_mode'] = $ldata->shipment_mode;
                        } 
                        
                    }
                    
                    $rates = Rate::orderby('courier')
                            ->orderBy('aggregator')
                            ->orderBy('min_weight')
                            ->get();
                    $requestData = $request->all();
                    if(!empty($dspData)){
                        $courier_data = $dspData;
                        return view('client-app.rate-calculator-card', compact('rates','zones','courier_data','requestData'));
                    }
                    else{
                        $courier_data = [];
                        return view('client-app.rate-calculator-card', compact('rates','zones','courier_data','requestData'));
                    }
                }
                
                else{
                    
                    $listData = Rate::select('forward', 'cod', 'cod_percent', 'min_weight', 'courier', 'shipment_mode','forward_additional','additional_weight')
                                ->where('min_weight', $rcd1[0]['min_weight'])
                                ->get();
                    $dspData=[];
                    $tot_price1=0;
                    $drt = $rcd1[0]['min_weight'];
                    foreach($listData as $key=>$ldata)
                    {
                        if($drt!=$ldata->min_weight){
                            $rwt1 = $drt;
                            $fwd_data2 = json_decode($ldata['forward'], true);
                            $tot_price2 = $fwd_data2['forward'][$zone->zone_code];
                            if($cod=="yes") {
                                $cod_val = (float)$ldata->cod;
                            }
                            else{
                                $cod_val = 0;
                            }
                            $price = $tot_price2;
                            #echo 'if'.$price;die;
                            $dspData[$key]['courier_name'] = $ldata->courier;
                            $dspData[$key]['courier_rate'] =$price;
                            $dspData[$key]['weight'] = $ldata->min_weight;
                            $dspData[$key]['cod'] = $cod_val;
                            $dspData[$key]['shipment_mode'] = $ldata->shipment_mode;
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
                            #echo $addwt1.' , price '.$price.' ,count '.$count.',tot_price1 '.$tot_price1;die;
                            if($cod=="yes") {
                                $cod_val = (float)$ldata->cod;
                            }
                            else{
                                $cod_val = 0;
                            }
                            $dspData[$key]['courier_name'] = $ldata->courier;
                            $dspData[$key]['courier_rate'] =$tot_price1;
                            $dspData[$key]['weight'] = $ldata->min_weight;
                            $dspData[$key]['cod'] = $cod_val;
                            $dspData[$key]['shipment_mode'] = $ldata->shipment_mode;
                           
                        }
 
                    }
                    
                    $rates = Rate::orderby('courier')
                            ->orderBy('aggregator')
                            ->orderBy('min_weight')
                            ->get();
                            
                    $requestData = $request->all();
                    #dd($dspData);
                    if(!empty($dspData)){
                        $courier_data = $dspData;
                        return view('client-app.rate-calculator-card', compact('rates','zones','courier_data','requestData'));
                    }
                    else{
                        $courier_data = [];
                        return view('client-app.rate-calculator-card', compact('rates','zones','courier_data','requestData'));
                    }
                }
            }
        } 
        else 
        {
          
           
            return redirect()->back()->with(['error' => 'No zone found']);
        }
    } 
    else 
    {
        
        return redirect()->back()->with(['error' => 'no state availabe on this delivery pincode']);
    }
       
        
    }
    public function b2c_calculator(Request $request)
    {
      $validate = $request->validate([
            'origin' => 'required|digits:6',
            'destination' => 'required|digits:6',
            'weight' => 'required|numeric|gt:0',
            'length' => 'required|numeric|gt:0',
            'height' => 'required|numeric|gt:0',
            'breadth' => 'required|numeric|gt:0',
            'cod' => 'required|string'
            
        ]); 
        $origin = $request->origin;
        $delivery = $request->destination;
        $weight = $request->weight;
        $vol_weight = ($request->length*$request->breadth*$request->height)/5000;
        if($vol_weight>$weight)
        {
            $wt = $vol_weight;
        }
        else{
            $wt = $weight;
        }
        $cod = $request->cod;
        $origin_state = Pincode::select('state')->where('pincode',$origin)->first();
        $delivery_state = Pincode::where('pincode', $delivery)->value('state');
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
            $dspData = [];
            $zone = Zone::whereRaw("FIND_IN_SET(?, zone_mapping) > 0", [$delivery_state])
                        ->where('zone_type',$zone_type)
                        ->where('company_id',$companyId)
                        ->where('client_id',$clientID)
                        ->first();
            $zones = Zone::where('zone_type',$zone_type)->where('company_id',$companyId)->where('client_id',$clientID)->get();
            $rates = Rate::orderby('courier')
                                ->where('contract_type','company_client')
                                ->where('company_id',$companyId)
                                ->where('client_id',$clientID)
                                ->orderBy('aggregator')
                                ->orderBy('min_weight')
                                ->get();                                
            $requestData = $request->all();
            if (!empty($zone)) 
            {
                $list = Rate::select('forward', 'cod', 'cod_percent','min_weight','courier','shipment_mode')
                            ->where('contract_type','company_client')
                            ->where('company_id',$companyId)
                            ->where('client_id',$clientID)
                            ->where('min_weight', $wt)
                            ->get();
                if(count($list)>0)
                {
                    foreach($list as $key=>$ldata)
                    {
                        foreach($list as $key=>$ldata)
                        {
                            if($cod=="yes") 
                            {
                                $cod_val = $ldata->cod;
                            }
                            else
                            {
                                $cod_val = 0;
                            }
                            $data = json_decode($ldata->forward, true);
                            if (isset($data['forward'][$zone->zone_code])) 
                            {
                                $zoneValue = $data['forward'][$zone->zone_code];
                                $dspData[$key]['courier_name'] = $ldata->courier;
                                $dspData[$key]['courier_rate'] = $zoneValue;
                                $dspData[$key]['weight'] = $ldata->min_weight;
                                $dspData[$key]['cod'] = $cod_val;
                                $dspData[$key]['shipment_mode'] = $ldata->shipment_mode;
                            } 
                            
                        }
                       
                        if(!empty($dspData))
                        {
                            $courier_data = $dspData;
                            return view('client-app.rate-calculator-card', compact('rates','zones','courier_data','requestData'));
                        }
                        else{
                            $courier_data = [];
                            return view('client-app.rate-calculator-card', compact('rates','zones','courier_data','requestData'));
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
                                    ->orderBy('min_weight','desc')
                                    ->where('contract_type','company_client')
                                    ->where('company_id',$companyId)
                                    ->where('client_id',$clientID)
                                    ->limit(1)
                                    ->get();
                  
                    if(count($rcd)>0 && count($rcd1)>0)
                    {                        
                        $listData = Rate::select('forward', 'cod', 'cod_percent', 'min_weight', 'courier', 'shipment_mode','forward_additional','additional_weight')
                                    ->where(function ($query) use ($rcd, $rcd1) {
                                            $query->where('min_weight', $rcd[0]['min_weight'])
                                                ->orWhere('min_weight', $rcd1[0]['min_weight']);
                                        })
                                    ->where('contract_type','company_client')
                                    ->where('company_id',$companyId)
                                    ->where('client_id',$clientID)
                                    ->get();
                        if(!$listData)
                        {
                            return redirect()->back()->with(['error' => 'Data not found on this range'.$rcd[0]['min_weight'].'-'.$rcd[0]['min_weight']]);
                        }   
                        
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
                                $tot_price2 = $fwd_data2['forward'][$zone->zone_code];
                                if($cod=="yes") 
                                {
                                    $cod_val = (float)$ldata->cod;
                                }
                                else
                                {
                                    $cod_val = 0;
                                }
                                
                                $dspData[$key]['courier_name'] = $ldata->courier;
                                $dspData[$key]['courier_rate'] = $tot_price2;
                                $dspData[$key]['weight'] = $ldata->min_weight;
                                $dspData[$key]['cod'] = $cod_val;
                                $dspData[$key]['shipment_mode'] = $ldata->shipment_mode;
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
                                if($cod=="yes") 
                                {
                                    $cod_val = (float)$ldata->cod;
                                }
                                else
                                {
                                    $cod_val = 0;
                                }
                                $dspData[$key]['courier_name'] = $ldata->courier;
                                $dspData[$key]['courier_rate'] =$tot_price1;
                                $dspData[$key]['weight'] = $ldata->min_weight;
                                $dspData[$key]['cod'] = $cod_val;
                                $dspData[$key]['shipment_mode'] = $ldata->shipment_mode;                           
                            }
                        }
                       
                        if(!empty($dspData))
                        {
                            $courier_data = $dspData;
                            return view('client-app.rate-calculator-card', compact('rates','zones','courier_data','requestData'));
                        }
                        else
                        {
                            $courier_data = [];
                            return view('client-app.rate-calculator-card', compact('rates','zones','courier_data','requestData'));
                        }
                    }
                    else if(count($rcd) > 0 && count($rcd1) == 0)
                    {
                        $list1 = Rate::select('forward', 'cod', 'cod_percent','min_weight','courier','shipment_mode','additional_weight')
                                        ->where('contract_type','company_client')
                                        ->where('company_id',$companyId)
                                        ->where('client_id',$clientID)
                                        ->where('min_weight', $rcd[0]['min_weight'])
                                        ->get();
                        if(!$list1)
                        {
                            return redirect()->back()->with(['error' => 'Data not found on'.$rcd[0]['min_weight']]);
                        }   
                        foreach($list1 as $key=>$ldata)
                        {
                            if($cod=="yes") 
                            {
                                $cod_val = $ldata->cod;
                            }
                            else
                            {
                                $cod_val = 0;
                            }
                            $data = json_decode($ldata->forward, true);
                        
                            if (isset($data['forward'][$zone->zone_code])) 
                            {
                                $zoneValue = $data['forward'][$zone->zone_code];
                                $dspData[$key]['courier_name'] = $ldata->courier;
                                $dspData[$key]['courier_rate'] = $zoneValue;
                                $dspData[$key]['weight'] = $ldata->min_weight;
                                $dspData[$key]['cod'] = $cod_val;
                                $dspData[$key]['shipment_mode'] = $ldata->shipment_mode;
                            } 
                        }
                        if(!empty($dspData))
                        {
                            $courier_data = $dspData;
                            return view('client-app.rate-calculator-card', compact('rates','zones','courier_data','requestData'));
                        }
                        else
                        {
                            $courier_data = [];
                            return view('client-app.rate-calculator-card', compact('rates','zones','courier_data','requestData'));
                        }
                    }
                    else if(count($rcd) == 0 && count($rcd1) > 0)
                    {
                        
                        $listData = Rate::select('forward', 'cod', 'cod_percent', 'min_weight', 'courier', 'shipment_mode','forward_additional','additional_weight')
                                    ->where('min_weight', $rcd1[0]['min_weight'])
                                    ->where('contract_type','company_client')
                                    ->where('company_id',$companyId)
                                    ->where('client_id',$clientID)
                                    ->get();
                        if(!$rates)
                        {
                            return redirect()->back()->with(['error' => 'Data not found on'.$rcd1[0]['min_weight']]);
                        }   
                       
                        $tot_price1=0;
                        $drt = $rcd1[0]['min_weight'];
                        foreach($listData as $key=>$ldata)
                        {
                            if($drt!=$ldata->min_weight)
                            {
                                $rwt1 = $drt;
                                $fwd_data2 = json_decode($ldata['forward'], true);
                                $tot_price2 = $fwd_data2['forward'][$zone->zone_code];
                                if($cod=="yes") 
                                {
                                    $cod_val = (float)$ldata->cod;
                                }
                                else
                                {
                                    $cod_val = 0;
                                }
                                $price = $tot_price2;
                                #echo 'if'.$price;die;
                                $dspData[$key]['courier_name'] = $ldata->courier;
                                $dspData[$key]['courier_rate'] =$price;
                                $dspData[$key]['weight'] = $ldata->min_weight;
                                $dspData[$key]['cod'] = $cod_val;
                                $dspData[$key]['shipment_mode'] = $ldata->shipment_mode;
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
                                if($cod=="yes") 
                                {
                                    $cod_val = (float)$ldata->cod;
                                }
                                else{
                                    $cod_val = 0;
                                }
                                $dspData[$key]['courier_name'] = $ldata->courier;
                                $dspData[$key]['courier_rate'] =$tot_price1;
                                $dspData[$key]['weight'] = $ldata->min_weight;
                                $dspData[$key]['cod'] = $cod_val;
                                $dspData[$key]['shipment_mode'] = $ldata->shipment_mode;
                            
                            }
    
                        }
                      
                        if(!empty($dspData))
                        {
                            $courier_data = $dspData;
                            return view('client-app.rate-calculator-card', compact('rates','zones','courier_data','requestData'));
                        }
                        else
                        {
                            $courier_data = [];
                            return view('client-app.rate-calculator-card', compact('rates','zones','courier_data','requestData'));
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
                return redirect()->back()->with(['error' => 'No zone found']);
            }
        } 
        else 
        {           
            return redirect()->back()->with(['error' => 'no state availabe on this delivery pincode']);
        }   
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
    public function destroy($id)
    {
        //
    }
}
