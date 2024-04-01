<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Zone;
use App\Models\State;
use App\Models\Pincode;
use App\Models\AppLogistics;
use App\Models\LogisticsMapping;
use Auth,Crypt,Redirect,Session;

class ZoneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $zone = Zone::with('courier')->where('zone_type','isSystem')->orderBy('dsp')
            ->when($request->input('dsp'), function ($query) use ($request) {
                $query->whereIn('dsp', $request->input('dsp'));
            })
            ->when($request->input('zone_code'), function ($query) use ($request) {
                $query->whereIn('zone_code', $request->input('zone_code'));
            })
            ->when($request->input('description'), function ($query) use ($request) {
                $query->whereIn('description', $request->input('description'));
            });
        
        $couriers = AppLogistics::all();
        $zone_codes = Zone::select('zone_code')->get();
        $zones = $zone->paginate(10);
      
        return view('admin-app.admin-tab.admin-tab-zone', compact('zones', 'couriers', 'zone_codes'));
    }
    
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [];
        $couriers = AppLogistics::all();
        $zoneMappings = Zone::pluck('zone_mapping')->toArray();
        $zoneArray = []; 
        foreach ($zoneMappings as $zoneMapping) 
        {
            $zoneArray = array_merge($zoneArray, explode(',', $zoneMapping));
        }
       
        #$states = Pincode::distinct()->pluck('state')->toArray(); // Retrieve state names
        #dd($stateData);
        
        // $resultData = array_diff($stateData, $zoneArray); // Find the difference
        // $states = array_values($resultData);
        
        // if(empty($states)){
        //     return Redirect::back()->with(['warning' => 'All states are occupied!!']);
        // }
        
        return view('admin-app.admin-card.admin-zone-card',compact('data','couriers'));
        
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
        $validate = $request->validate([  
            'dsp' => 'required',   
            'zone_code' => 'required|string',  
            'description' => 'required|string',
            'state' => 'required',    
        ]);
        $status="Added successfully";
        $zone =Zone::where('zone_code',$request->zone_code)->where('dsp',$request->dsp)->first();
        if($zone)
        {
            return Redirect::back()->with(['error' => 'Zone already exists on this dsp']);
        }
        try{            
            $data = new Zone;
            $data->zone_type = 'isSystem';
            $data->dsp = $request->dsp;
            $data->zone_code = $request->zone_code;
            $data->description = $request->description;
            $stateArray = $request->state;
            $states = implode(',',$stateArray);
            $data->zone_mapping = $states;
            $data->created_by = Auth::user()->id;              
            $data->save();
            return redirect()->route('zone.index')->with(['status' => $status]);
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
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = Crypt::decrypt($id);
        $data = Zone::find($id);
        $couriers = AppLogistics::all();
        $zoneMappings = Zone::where('id','!=',$data->id)->where('zone_type','isSystem')->where('dsp',$data->dsp)->pluck('zone_mapping')->toArray();
        $zoneArray = []; 
        foreach ($zoneMappings as $zoneMapping) {
            $zoneArray = array_merge($zoneArray, explode(',', $zoneMapping));
        }
        $stateData = Pincode::distinct()->pluck('state')->toArray(); // Retrieve state names
        $resultData = array_diff($stateData, $zoneArray);
        $states = array_values($resultData);
        if($data){       
           return view('admin-app.admin-card.admin-zone-card',compact('data','states','couriers'));
        }
        else{
            return Redirect::back()->with(['error' => 'Data not found']);
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
        $data = Zone::find($id);
        if(!$data){       
            return Redirect::back()->with(['error' => 'Data not found']);
        }        
        $status="Updated successfully";
        try{     
            $data->zone_code = $request->zone_code;
            $data->description = $request->description;
            $stateArray = $request->state;
            $states = implode(',',$stateArray);
            $data->zone_mapping = $states;
            $data->updated_by = Auth::user()->id;                         
            $data->save();
            return redirect()->route('zone.index')->with(['status' => $status]);
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
    public function zone_list(Request $request)
    {        
        $zone_codes='';
        $zone_type='';
        $clientId =0;
        $companyId=0;
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
        $zone_codes = Zone::select('zone_code')
                            ->where('client_id',$clientId)
                            ->where('company_id',$companyId)
                            ->get();
        $zone = Zone::with('courier')->when($request->input('zone_code'), function ($query) use ($request) {
                $query->whereIn('zone_code', $request->input('zone_code'));
            })
            ->when($request->input('description'), function ($query) use ($request) {
                $query->whereIn('description', $request->input('description'));
            });
        $zone->where('client_id',$clientId)->where('company_id',$companyId);
        if(Auth::user()->user_type == 'isCompany')
        {
            $zones = $zone->where('zone_type','!=','client_dsp')->paginate(10);
        }
        else
        {
            $zones = $zone->where('zone_type','client_dsp')->paginate(10);
        }     
       
        return view('common-app.list.zone-list', compact('zones', 'zone_codes'));
    }
    public function view()
    {
        $zone_type='';
        $clientId = 0;
        $companyId = 0;
        $zoneMappings ='';
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
        
        // $zoneMappings = Zone::where('client_id',$clientId)
        //                     ->where('company_id',$companyId)
        //                     ->pluck('zone_mapping')
        //                     ->toArray();
        
        $data = [];
        $dsps = LogisticsMapping::with('courier')
                    ->where('client_id', $clientId)
                    ->get(); 
        // $zoneArray = []; 
        // foreach ($zoneMappings as $zoneMapping) 
        // {
        //     $zoneArray = array_merge($zoneArray, explode(',', $zoneMapping));
        // }
       
        $states = Pincode::distinct()->pluck('state')->toArray(); // Retrieve state names
        #dd($stateData);
        
        // $resultData = array_diff($stateData, $zoneArray); // Find the difference
        // $states = array_values($resultData);
        
        // if(empty($states)){
        //     return Redirect::back()->with(['warning' => 'All states are occupied!!']);
        // }
        return view('common-app.card.add-zone',compact('data','dsps','states'));
        
    }
    public function save(Request $request)
    {
        
        $zone='';
        $zone_type='';
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
        
        $zone_type=$request->zone_type;
        if($zone_type !== 'company_client'){
            $dsp = $request->dsp;
        }
        else{
            $dsp = 0; 
        }
        $zone = Zone::where('zone_code',$request->zone_code)
                    ->where('zone_type',$zone_type)
                    ->where('company_id',$companyId)
                    ->where('client_id',$clientId)
                    ->where('dsp',$dsp)
                    ->first();
       
        $validate = $request->validate([  
            'zone_type' => 'required|string',
            'zone_code' => 'required|string',  
            'description' => 'required|string',
            'state' => 'required',    
        ]);
        $status="Added successfully";
        
        if($zone)
        {
            return Redirect::back()->with(['error' => 'Zone already exists']);
        }
        try{            
            $data = new Zone;
            $data->zone_type = $zone_type;
            $data->company_id = $companyId ?? 0;
            $data->client_id = $clientId ?? 0;
            $data->dsp = $request->dsp ?? 0;
            $data->zone_code = $request->zone_code;
            $data->description = $request->description;
            $stateArray = $request->state;
            $states = implode(',',$stateArray);
            $data->zone_mapping = $states;
            $data->created_by = Auth::user()->id;              
            $data->save();
            return redirect()->route('zone.get')->with(['status' => $status]);
        }
        catch(Exception $e) {
            
            return Redirect::back()->with(['error' => $e->getMessage()]);
        }
    }
    public function fetch($id)
    {
        $zone_type='';
        $clientId = 0;
        $companyId = 0;
        $zoneMappings ='';
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
        $id = Crypt::decrypt($id);
        $data = Zone::find($id);
        $zone_type=$data->zone_type;
        $zoneMappings = Zone::where('id','!=',$data->id)
                            ->where('zone_type',$zone_type)
                            ->where('company_id',$companyId)
                            ->where('client_id',$clientId)
                            ->pluck('zone_mapping')->toArray();
       
        $zoneArray = []; 
        foreach ($zoneMappings as $zoneMapping) {
            $zoneArray = array_merge($zoneArray, explode(',', $zoneMapping));
        }
        $stateData = Pincode::distinct()->pluck('state')->toArray(); // Retrieve state names
        $resultData = array_diff($stateData, $zoneArray);
        $states = array_values($resultData);
        $dsps = LogisticsMapping::with('courier')
                    ->where('client_id', $clientId)
                    ->get(); 
        if($data){       
           return view('common-app.card.add-zone',compact('data','states','dsps'));
        }
        else{
            return Redirect::back()->with(['error' => 'Data not found']);
        }
        
    }
    public function modified(Request $request, $id)
    {
       
        $data = Zone::find($id);
        if(!$data){       
            return Redirect::back()->with(['error' => 'Data not found']);
        }        
        $status="Updated successfully";
        try{     
            $data->zone_code = $request->zone_code;
            $data->description = $request->description;
            $stateArray = $request->state;
            $states = implode(',',$stateArray);
            $data->zone_mapping = $states;
            $data->updated_by = Auth::user()->id;                         
            $data->save();
            return redirect()->route('zone.get')->with(['status' => $status]);
        }
        catch(Exception $e) {
            
            return Redirect::back()->with(['error' => $e->getMessage()]);
        }
    }
}
