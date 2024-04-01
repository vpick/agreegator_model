<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Region;
use App\Models\Pincode;
use Auth,Crypt,Redirect;

class regionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $regions = Region::paginate(10);
       
        #dd($states);
        return view('admin-app.admin-tab.admin-tab-region', compact('regions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [];
        $regionMappings = Region::pluck('destinations')->toArray();
        $regionArray = []; 
        foreach ($regionMappings as $regionMapping) {
            $regionArray = array_merge($regionArray, explode(',', $regionMapping));
        }
        $stateData = Pincode::distinct()->pluck('state')->toArray(); // Retrieve state names
        $resultData = array_diff($stateData, $regionArray); // Find the difference
        $states = array_values($resultData);
        if(empty($states)){
            return Redirect::back()->with(['warning' => 'All states are occupied!!']);
        }
        return view('admin-app.admin-card.admin-region-card',compact('data','states'));
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
            'region' => 'required|string|unique:regions',    
            'state' => 'required',    
        ]);
        $status="Added successfully";
        try{            
            $data = new Region;
            $data->region = $request->region;
          
            $stateArray = $request->state;
            $states = implode(',',$stateArray);
            $data->destinations = $states;
            $data->save();
            return redirect()->route('region.index')->with(['status' => $status]);
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
        $data = Region::find($id);
        $regionMappings = Region::where('id','!=',$data->id)->pluck('destinations')->toArray();
        $regionArray = []; 
        foreach ($regionMappings as $regionMapping) {
            $regionArray = array_merge($regionArray, explode(',', $regionMapping));
        }
        $stateData = Pincode::distinct()->pluck('state')->toArray(); // Retrieve state names
        $resultData = array_diff($stateData, $regionArray);
        $states = array_values($resultData);
        if($data){       
           return view('admin-app.admin-card.admin-region-card',compact('data','states'));
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
        $data = Region::find($id);
        if(!$data){       
            return Redirect::back()->with(['error' => 'Data not found']);
        }        
        $status="Updated successfully";
        try{     
            $data->region = $request->region;
           
            $stateArray = $request->state;
            $states = implode(',',$stateArray);
            $data->destinations = $states;
            $data->save();
            return redirect()->route('region.index')->with(['status' => $status]);
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
