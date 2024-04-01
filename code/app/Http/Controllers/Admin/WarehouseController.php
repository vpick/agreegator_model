<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Warehouse;
use App\Models\Company;
use App\Models\User;
use App\Models\LogisticsMapping;
use DB, Auth,Session;
use App\Models\State;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use App\Interfaces\AppOrderProcessInterface;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(Session::has('client')){
            $clientId = session('client.id');
        }
        else{
            $clientId = Auth::user()->client->id;
        }
        if(Session::has('warehouse')){
            $warehouseId = session('warehouse.id');
        }
        else{
            $warehouseId = Auth::user()->warehouse->id;
        }
        
        if(Auth::user()->user_type == 'isCompany')
        {
            $warehouses = Warehouse::with('state','client','company')->orderby('id', 'desc')->where('company_id',Auth::user()->company->id)->where('client_id',$clientId)->where('id',$warehouseId);
        }
        else
        {
          $warehouses = Warehouse::with('state','client','company')->orderby('id', 'desc')->where('client_id',$clientId)->where('id',$warehouseId);
        }
        if(!empty($request->input('warehouse_code'))){
            	$warehouses = $warehouses->where('warehouse_code',$request->input('warehouse_code'));
        }
        $warehouses= $warehouses->paginate(10);
        if(Auth::user()->user_type!='isCompany'){
            $userP = DB::table('user_permissions as u')
                ->join('pages as p', function ($join) {
                    $join->on('u.page_id', '=', 'p.id')
                        ->where('u.role_id', '=', Auth::user()->role_id)
                        ->where('u.user_id', '=', Auth::user()->id)
                        ->where('p.pagename', '=', 'warehouse');
                })
                ->select('p.pagename', 'u.role_id', 'u.user_id', 'u.page_id', 'u.read', 'u.write', 'u.update', 'u.delete', 'u.print', 'u.download')
                ->first(); // Use first instead of get
                   
                // Check if a permission record exists
                if((!empty($userP)) && ($userP->read ==1))
        	    {
        	        
                    return view('company-app.table-list.warehouse',compact('warehouses','userP'));
        	    }
        	    else{
        	        return \Redirect::back()->with(['error' => 'No Permission!!!']);
        	    }
        }
        else{
            return view('company-app.table-list.warehouse',compact('warehouses'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [];
        $states = State::all();
        
        if(Session::has('client')){
            $clientId = session('client.id');
             $clientName = session('client.name');
        }
        else{
            $clientId = Auth::user()->client->id;
            $clientName = Auth::user()->client->name;
        }
       
        if(Session::has('warehouse')){
             $clients = Client::where('status','1')->where('id',$clientId)->get();
        }
        else{
            $clients = Client::where('status','1')->where('company_id',Auth::user()->company->id)->get();
        }
        if(Auth::user()->user_type!='isCompany'){
            $userP = DB::table('user_permissions as u')
                ->join('pages as p', function ($join) {
                    $join->on('u.page_id', '=', 'p.id')
                        ->where('u.role_id', '=', Auth::user()->role_id)
                        ->where('u.user_id', '=', Auth::user()->id)
                        ->where('p.pagename', '=', 'warehouse');
                })
                ->select('p.pagename', 'u.role_id', 'u.user_id', 'u.page_id', 'u.read', 'u.write', 'u.update', 'u.delete', 'u.print', 'u.download')
                ->first(); // Use first instead of get
                   
                // Check if a permission record exists
                if((!empty($userP)) && ($userP->read ==1))
        	    {
                    return view('company-app.card.warehouse-card', compact('states','clients','data','clientId','clientName','userP'));
        	    }
        	    else{
        	        return \Redirect::back()->with(['error' => 'No Permission!!!']);
        	    }
            }
        else{
            return view('company-app.card.warehouse-card', compact('states','clients','data','clientId','clientName'));
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
        $validate = $request->validate([
            'warehouse_name' => 'required|string|min:4',
            'warehouse_code' => 'required|unique:warehouses',
            'contact_name' => 'required|string',
            'client_id' => 'required',
            'support_phone' => 'required|numeric|digits:10',
            'support_email' => 'required|email',
            'address1' => 'required|string',
            'address2' => 'string',
            'phone' => 'required|numeric|digits:10',
            'state_id' => 'required|numeric',
            'city' => 'required|string',
            'pincode' => 'required|numeric|digits:6', 
            'gst_no' => 'required|max:15|unique:warehouses',
            
        ]);
        $status="Warehouse creates successfully";
        try{  
            DB::beginTransaction();
            $data = new Warehouse;
            $data->warehouse_name = $request->warehouse_name;
            $data->contact_name = $request->contact_name;
            $data->warehouse_code = $request->warehouse_code;
            $data->company_id =Auth::user()->company_id;;
            $data->client_id =$request->client_id;
            $data->support_phone = $request->support_phone;
            $data->support_email = $request->support_email; 
            $data->address1 = $request->address1;         
            $data->address2 = $request->address2;
            $data->phone = $request->phone;
            $data->state_id = $request->state_id;
            $data->city = $request->city;
            $data->pincode = $request->pincode;  
            $data->gst_no = $request->gst_no;   
            $data->created_by = Auth::user()->id;  
            $data->save();
            $users = User::where('company_id', '=', '2')
                            ->where(function ($query) {
                                $query->where('user_type', '=', 'isSystem')
                                    ->orWhere('user_type', '=', 'isCompany');
                            })
                        ->get();
            foreach($users as $user){
                $user_id = $user->id;  
                $query = User::where('id', $user_id) // IDs of the rows you want to update
                ->update([
                    'warehouse_map' => $user->warehouse_map.','.$data->id
                ]);    
            }    
            DB::commit();          
            return redirect()->route('warehouse.index')->with(['status' => $status]);
        }
        catch(Exception $e) {
            DB::rollBack();
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
        
        $data = Warehouse::find($id);
        if($data->status == '1'){
            $data->status = '0';
        }
        else {
            $data->status = '1';
        }
        
        try {
            $data->save();
            return \Redirect::back()->with(['status' => 'Warehouse status updated!!!']);
        } catch(\Exception $e) {
            return \Redirect::back()->with(['error' => $e->getMessages()]);
        } 

        return \Redirect::back()->with(['error' => 'Warehouse not Found!!!']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = \Crypt::decrypt($id);
        $data = Warehouse::find($id);
        $states = State::all();
        if(Session::has('client')){
            $clientId = session('client.id');
             $clientName = session('client.name');
        }
        else{
            $clientId = Auth::user()->client->id;
            $clientName = Auth::user()->client->name;
        }
       
        if(Session::has('warehouse')){
             $clients = Client::where('status','1')->where('id',$clientId)->get();
        }
        else{
            $clients = Client::where('status','1')->where('company_id',Auth::user()->company->id)->get();
        }
        $companies = Company::where('status','1')->get();
        if($data){       
            return view('company-app.card.warehouse-card', compact('states','data','companies','clients','clientId','clientName'));
        }
        else{
            return Redirect::back()->with(['error' => 'Warehouse not Found!!!']);
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
        $data = Warehouse::find($id);
        if(!$data){
            return Redirect::back()->with(['error' => 'no data found'],404);
        }
        $validate = $request->validate([
            'warehouse_name' => 'string|min:4',
            'contact_name' => 'string',
            'support_phone' => 'numeric|digits:10',
            'support_email' => 'email',
            'address1' => 'string',
            'address2' => 'string',
            'phone' => 'numeric|digits:10',
            'state_id' => 'numeric',
            'city' => 'string',
            'pincode' => 'numeric|digits:6', 
            'gst_no' => 'max:15',
            
        ]);
        $status="Warehouse updated successfully";
        try{  

            $data->warehouse_name = $request->warehouse_name;
            $data->contact_name = $request->contact_name;
            $data->support_phone = $request->support_phone;
            $data->support_email = $request->support_email; 
            $data->address1 = $request->address1;         
            $data->address2 = $request->address2;
            $data->phone = $request->phone;
            $data->state_id = $request->state_id;
            $data->city = $request->city;
            $data->pincode = $request->pincode; 
            $data->updated_by = Auth::user()->id;    
            $data->save();         
            return redirect()->route('warehouse.index')->with(['status' => $status]);
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
    public function warehouse_dsp_create()
    {
        if(Session::has('client'))
        {
            $clientId = session('client.id');
        }
        else
        {
            $clientId = Auth::user()->client->id;
        }
        if(Session::has('warehouse'))
        {
            $warehouseId = session('warehouse.id');
        }
        else
        {
            $warehouseId = Auth::user()->warehouse->id;
        }
        $logistics = LogisticsMapping::select('app_logistics.logistics_name','logistics_mappings.partner_id')
                            ->join('app_logistics','logistics_mappings.partner_id', '=','app_logistics.id')
							->where('logistics_mappings.client_id', $clientId)
							->where('logistics_mappings.status', 'Active')
							->get();
			
		if($logistics)	
		{
            $warehouse = Warehouse::with('client')->where('id',$warehouseId)->first();
            if($warehouse)
            {
                if(Auth::user()->user_type!='isCompany')
                {
                    $userP = DB::table('user_permissions as u')
                        ->join('pages as p', function ($join) {
                            $join->on('u.page_id', '=', 'p.id')
                                ->where('u.role_id', '=', Auth::user()->role_id)
                                ->where('u.user_id', '=', Auth::user()->id)
                                ->where('p.pagename', '=', 'warehouse');
                        })
                        ->select('p.pagename', 'u.role_id', 'u.user_id', 'u.page_id', 'u.read', 'u.write', 'u.update', 'u.delete', 'u.print', 'u.download')
                        ->first();
                    // Check if a permission record exists
                    if((!empty($userP)) && ($userP->read ==1))
            	    {
                        return view('company-app.card.warehouse-dsp-card',  compact('warehouse','logistics','userP'));
            	    }
            	    else
            	    {
            	        return \Redirect::back()->with(['error' => 'No Permission!!!']);
            	    }
                 }
                else
                {
                    return view('company-app.card.warehouse-dsp-card', compact('warehouse','userP'));
                }
            }   
            else
            {
                return \Redirect::back()->with(['error' => 'warehouse detail not found !']);
            }
		}
		else
		{
		    return \Redirect::back()->with(['error' => 'No dsp mapped !']);
		}
    }
    public function processWarehouse($request,$mapArray,$partner)
    {
       
		// Resolve the appropriate service based on the order type
        $myService = app()->makeWith(AppOrderProcessInterface::class, ['ordersendTo' => $partner]);
        
        // Now you can use $myService based on the order type.
        // For example, if $orderType is 'type1', the corresponding ServiceOne will be injected.
        
        if (!$myService instanceof AppOrderProcessInterface) {
			//throw new \RuntimeException("Service resolution failed for order type: $orderType");
			echo 'You try to call invalid service class';dd();
		}
        
        return $myService->processWarehouse($request,$mapArray);
    }
    public function warehouse_dsp_send(Request $request)
    {
        $validate = $request->validate([
            'warehouse_name' => 'required',
            'warehouse_code' => 'required',
            'partner' => 'required',
            'warehouse_address1' => 'required',
            'warehouse_address2' => 'string',
            'warehouse_phone' => 'required',
            'warehouse_city' => 'required',
            'warehouse_pincode' => 'required', 
            'warehouse_gst' => 'required',
            'warehouse_email' => 'required',
        ]);
			$mapVarify = LogisticsMapping::where('client_id', $request->client_id)->where('partner_id',$request->partner)->first();
		
			if(!empty($mapVarify))
			{
    			$ordersendTo = $mapVarify['partner_name'];
    			$warehouseDetail['warehouse'] = $request->all();
    			$resultData = json_encode($warehouseDetail);
    		    
    		    $finalResponse = app(WarehouseController::class)->processWarehouse($resultData,$mapVarify,$ordersendTo);
    		    if(!empty($finalResponse))
        		{
        		    if($finalResponse['status'] =='success')
					{
					    $response['message'] = $finalResponse['message']?$finalResponse['message']:'Warehouse created succesfully';
						$response['status'] = true;
					}
					else
					{
					    $response['message'] = $finalResponse['message']?$finalResponse['message']:'Warehouse creation Failed';
						$response['status'] = false;
					}
        
        		}
        		else
        		{
        			$response['message'] = 'Unble to create warehouse';
        			$response['status'] = false;
        		}
    	    }
            else
    		{
    		   	$response['message'] = 'Configuration issue occurred';
    			$response['status'] = false;
    		}
		
		
		return response()->json(['response' => $response], 201);
    }
}
