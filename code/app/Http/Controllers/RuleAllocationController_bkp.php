<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppLogistics;
use App\Models\RuleAllocation;
use App\Models\ShipmentType;
use App\Models\Weight;
use App\Models\Zone;
use App\Models\LogisticsMapping;
use Auth,Redirect,DB;

class RuleAllocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ruleList = RuleAllocation::where('client_id',Auth::user()->client_id)->get();
        if(!$ruleList){
            $ruleList = [];
        }
        $userP = DB::table('user_permissions as u')
                ->join('pages as p', function ($join) {
                    $join->on('u.page_id', '=', 'p.id')
                        ->where('u.role_id', '=', Auth::user()->role_id)
                        ->where('u.user_id', '=', Auth::user()->id)
                        ->where('p.pagename', '=', 'rule');
                })
                ->select('p.pagename', 'u.role_id', 'u.user_id', 'u.page_id', 'u.read', 'u.write', 'u.update', 'u.delete', 'u.print', 'u.download')
                ->first(); // Use first instead of get
                   
        // Check if a permission record exists
        if((!empty($userP)) && ($userP->read ==1))
	    {
            return view('client-app.rule-allocation-list',compact('ruleList','userP'));
	    }
	    else{
	        return \Redirect::back()->with(['error' => 'No Permission!!!']);
	    }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        $logistics = LogisticsMapping::join('app_logistics', 'logistics_mappings.partner_id', '=', 'app_logistics.id')
							->where('logistics_mappings.client_id', Auth::user()->client_id)
							
							->where('logistics_mappings.status', 'Active')
							->get();	
         #dd($logistics);               
        $shipment_modes = ShipmentType::all();
        
        $weights = Weight::all();
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
        $ruleList = RuleAllocation::where('client_id',Auth::user()->client_id)->get();
        if(!$ruleList){
            $ruleList = [];
        }
        $data = [];
        #dd($rules);
        $userP = DB::table('user_permissions as u')
                ->join('pages as p', function ($join) {
                    $join->on('u.page_id', '=', 'p.id')
                        ->where('u.role_id', '=', Auth::user()->role_id)
                        ->where('u.user_id', '=', Auth::user()->id)
                        ->where('p.pagename', '=', 'rule');
                })
                ->select('p.pagename', 'u.role_id', 'u.user_id', 'u.page_id', 'u.read', 'u.write', 'u.update', 'u.delete', 'u.print', 'u.download')
                ->first(); // Use first instead of get
                   
        // Check if a permission record exists
        if((!empty($userP)) && ($userP->read ==1))
	    {
            return view('client-app.rule-allocation-card',compact('ruleList','logistics','shipment_modes','weights','zones','data','userP'));
	    }
	    else{
	        return \Redirect::back()->with(['error' => 'No Permission!!!']);
	    }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'rule_name' => 'required|string|unique:rule_allocations',
            'rule_priority' => 'required|numeric|unique:rule_allocations',   
            'order_type' => 'required',   
            'shipment_mode' => 'required',  
            'payment_mode' => 'required',
            'weight' => 'required',
            'zone' => 'required',
            
        ]);
        $status = 'Rule Added Successfully';
        $rule = RuleAllocation::where('order_type',$request->order_type)
                        ->where('order_type',$request->order_type)
                        ->where('shipment_mode',$request->shipment_mode)
                        ->where('payment_mode',$request->payment_mode)
                        ->where('weight',$request->weight)
                        ->where('zone',$request->zone)->count();
        if($rule>0){
            return Redirect::back()->with(['error' => 'Rule already exists!!!']);
        }
        try{    
            $data = new RuleAllocation;
            $data->client_id = Auth::user()->client_id;
            $data->order_type = $request->order_type;
            $data->shipment_mode = $request->shipment_mode;
            $data->payment_mode = $request->payment_mode;
            $data->weight = $request->weight;
            $data->zone = $request->zone;
            $data->rule_name = $request->rule_name;
            $data->rule_priority = $request->rule_priority;
            $courier_priorities = $request->courier_priority;
            $priorityData = [];
            $i = 1;
            foreach ($courier_priorities as $key => $courier_priority) {
                $priorityData[] = [
                    'Priority '.$i => $courier_priority,
                ];
                $i++;
            }
            $data->courier_priority = json_encode(['courier_priority' => $priorityData]);
            $data->created_by = Auth::user()->id;
            #dd($data);
            $data->save();           
            return redirect()->route('rule-allocation.index')->with(['status' => $status]);
        }
        catch(Exception $e) { 
            return Redirect::back()->with(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = RuleAllocation::find($id);
        if($data->rule_status == '1'){
            $data->rule_status = '0';
        }
        else {
            $data->rule_status = '1';
        }
        
        try {
            $data->save();
            return Redirect::back()->with(['status' => 'Rule status updated!!!']);
        } catch(Exception $e) {
            return Redirect::back()->with(['error' => $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $logistics = LogisticsMapping::join('app_logistics', 'logistics_mappings.partner_id', '=', 'app_logistics.id')
							->where('logistics_mappings.client_id', Auth::user()->client_id)
						
							->where('logistics_mappings.status', 'Active')
							->get();	
        $shipment_modes = ShipmentType::all();
        $weights = Weight::all();
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
        $ruleList = RuleAllocation::where('client_id',Auth::user()->client_id)->get();
        if(!$ruleList){
            $ruleList = [];
        }
        $data = RuleAllocation::find($id);
        if($data){       
            return view('client-app.rule-allocation-card', compact('data','logistics','shipment_modes','ruleList','zones','weights'));
        }
        else{
            $data = [];
            return view('client-app.rule-allocation-card', compact('data','logistics','shipment_modes','ruleList','zones','weights'));
        }
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
        $request->validate([
            'rule_name' => 'required|string|unique:rule_allocations,rule_name,'.$id,
            'rule_priority' => 'required|numeric',   
            'order_type' => 'required',   
            'shipment_mode' => 'required',  
            'payment_mode' => 'required',
            'weight' => 'required',
            'zone' => 'required',
            
        ]);
        
        $data = RuleAllocation::find($id);
        if(!$data){       
            return Redirect::back()->with(['error' => 'data not Found!!!']);
        }
        $rule = RuleAllocation::where('order_type',$request->order_type)
                        ->where('order_type',$request->order_type)
                        ->where('shipment_mode',$request->shipment_mode)
                        ->where('payment_mode',$request->payment_mode)
                        ->where('weight',$request->weight)
                        ->where('zone',$request->zone)
                        ->where('id','!=',$id)
                        ->count();
        if($rule>0){
            return Redirect::back()->with(['error' => 'Rule already exists!!!']);
        }
        $status = 'Rule updated Successfully';
        try{    
            $data->order_type = $request->order_type;
            $data->shipment_mode = $request->shipment_mode;
            $data->payment_mode = $request->payment_mode;
            $data->weight = $request->weight;
            $data->zone = $request->zone;
            $data->rule_name = $request->rule_name;
            $data->rule_priority = $request->rule_priority;
            $courier_priorities = $request->courier_priority;
            $priorityData = [];
            
            $hasDuplicates = count($courier_priorities) > count(array_unique($courier_priorities)); 
            #dd($hasDuplicates);
            if($hasDuplicates == true){
                return Redirect::back()->with(['error' => 'Courier priority can not have duplicate value']);
            }
            $i = 1;
            foreach ($courier_priorities as $key => $courier_priority) {
                if (!empty($courier_priority)) {
                    $priorityData[] = [
                        'Priority '.$i => $courier_priority,
                    ];
                    $i++;
                }
            }
            $data->courier_priority = json_encode(['courier_priority' => $priorityData]);
            $data->updated_by = Auth::user()->id;
            #dd($data);
            $data->save();           
            return redirect()->route('rule-allocation.index')->with(['status' => $status]);
        }
        catch(Exception $e) {
            
            return Redirect::back()->with(['error' => $e->getMessage()]);
        }
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
