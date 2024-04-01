<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Warehouse;
use App\Models\Company;
use App\Models\User;
use DB,Auth,Session;
use App\Models\State;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

class AppWarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     
    public function index()
    {
         $clientId = session('client.id') ?? Auth::user()->client->id;
        $warehouses = Warehouse::with('state','client','company')->where('client_id',$clientId)->orderby('id', 'desc')->paginate(10);
       
        return view('admin-app.admin-tab.admin-tab-warehouse',compact('warehouses'));
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
        $companyId = session('company.id') ?? Auth::user()->company->id;
        $clientId = session('client.id') ?? Auth::user()->client->id;
        
        if(Session::has('company')){
            $companies = Company::where('status','1')->where('id',$companyId)->get();
            $clients = Client::where('status','1')->where('company_id',$companyId)->get();
            #dd($clients);
        }
        else{
            $companies = Company::where('status','1')->get();
            $clients = [];
        }
       
       
        return view('admin-app.admin-card.admin-warehouse-card', compact('states','companies','data','companyId','clientId','clients'));
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
            $data->company_id =$request->company_id;
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
            return redirect()->route('app-warehouse.index')->with(['status' => $status]);
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
        #dd($id);
        $data = Warehouse::find($id);
        $states = State::all();
       
        $companyId = session('company.id') ?? Auth::user()->company->id;
        $clientId = session('client.id') ?? Auth::user()->client->id;
        $companies = Company::where('status','1')->where('id',$companyId)->get();
        $clients = Client::where('status','1')->where('id',$clientId)->get();
       
        if($data){       
            return view('admin-app.admin-card.admin-warehouse-card', compact('states','data','companies','clients','data','companyId','clientId'));
        }
        else{
            return Redirect::back()->with(['error' => 'no data found'],404);
        }
    }
    public function view($id)
    {
        $id = \Crypt::decrypt($id);
        #dd($id);
        $data = Warehouse::find($id);
        $states = State::all();
        $clients = Client::where('status','1')->get();
        $companies = Company::where('status','1')->get();
        if($data){       
            return view('admin-app.admin-card.admin-warehouse-detail', compact('states','data','companies','clients','data'));
        }
        else{
            return \Redirect::back()->with(['warning' => 'Switch Warehouse!!!']);
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
            return redirect()->route('app-warehouse.index')->with(['status' => $status]);
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
