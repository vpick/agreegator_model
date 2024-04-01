<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Company;
use App\Models\State;
use App\Models\User;
use App\Helpers\Helper;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use DB,Session;
use DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ClientsExport;
use App\Imports\ClientsImport;

class AppClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   
    public function index(Request $request)
    {
        if(Session::has('company')){
            $companyId = session('company.id');
        }
        else{
            $companyId = Auth::user()->company->id;
        }
        $clients = Client::with('state:id,state_name','company:id,name','user:id,username')->where('company_id',$companyId)->orderby('id', 'desc');
        $client_name = $request->input('client_name');
        if($client_name){
            $clients = $clients->where('name',$client_name);
        }
        $client_code = $request->input('client_code');
        if($client_code){
            $clients = $clients->where('client_code',$client_code);
        }
        $company = $request->input('company');
        if($company){
            $clients = $clients->where('company_id',$company);
        }
        $clients = $clients->paginate(10);
        $companies = Company::get();
        //dd($clients);
        return view('admin-app.admin-tab.admin-tab-client',compact('clients','companies','companyId'));
    }
    public function getAppClients(Request $request)
    {       
        $query = Client::with('state:id,state_name','company:id,name','user:id,username');
        if (!empty($request->client_code)) {
            $query->where('client_code',$request->client_code);
        }
        $clients = $query->where('status','1')->orderby('id', 'desc')->get();        
        //dd($clients);
        return DataTables::of($clients)
        ->addColumn('action', function ($client) {
            $encryptedId = \Crypt::encrypt($client->id);
            $editUrl = route('app-client.edit', $encryptedId);
            return '<a href="' . $editUrl . '" class="btn btn-outline-primary">View</a>';
        })
        ->make(true);    
    }
    // public function importViewClient(Request $request){
    //     return view('importFile');
    // }
    public function exportAppClient(Request $request) 
    {
        
        return Excel::download(new ClientsExport, 'clients.xlsx');
    }

    public function importAppClient(Request $request){
        Excel::import(new ClientsImport, $request->file('file')->store('public'));
        
        return redirect()->back();
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
        if(Session::has('company')){
            
              $companies = Company::where('status','1')->where('id',$companyId)->get();
        }
        else{
            
             $companies = Company::where('status','1')->get();
        }
       
       
        return view('admin-app.admin-card.admin-client-card', compact('states','companies','data','companyId'));
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
            'name' => 'required|string|min:4|unique:clients',
            'client_code' => 'required|unique:clients',
            'company_id' => 'required',
            'phone' => 'required|numeric|digits:10',
            'email' => 'required|email|unique:clients',
            'billing_address' => 'required|string',
            'address2' => 'string',
            'country' => 'required|string',
            'state_id' => 'required|numeric',
            'city' => 'required|string',
            'pincode' => 'required|numeric|digits:6', 
            
        ]);
        $status="Client created successfully";
        try{  
            DB::beginTransaction();
            $data = new Client;
            $data->name = $request->name;
            $data->client_code = $request->client_code;
            $data->company_id =$request->company_id;
            $data->phone = $request->phone;
            $data->email = $request->email; 
            $data->billing_address = $request->billing_address;         
            $data->address2 = $request->address2;
            $data->country = $request->country;
            $data->state_id = $request->state_id;
            $data->city = $request->city;
            $data->pincode = $request->pincode;    
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
                    'client_map' => $user->client_map.','.$data->id
                ]);    
            }         
            DB::commit();
            return redirect()->route('app-client.index')->with(['status' => $status]);    
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
       
        $data = Client::find($id);
        if($data->status == '1'){
            $data->status = '0';
        }
        else {
            $data->status = '1';
        }
        
        try {
            $data->save();
            return \Redirect::back()->with(['status' => 'Client status updated!!!']);
        } catch(\Exception $e) {
            return \Redirect::back()->with(['error' => $e->getMessages()]);
        } 

        return \Redirect::back()->with(['error' => 'Service not Found!!!']);
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
        //dd($id);
        $data = Client::find($id);
        $states = State::all();
        $companies = Company::where('status','1')->get();
        if($data){       
            return view('admin-app.admin-card.admin-client-card', compact('states','data','companies'));
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
        $data = Client::find($id);
        if(!$data){
            return Redirect::back()->with(['error' => 'no data found'],404);
        }
        $validate = $request->validate([
            'name' => 'string|min:4|unique:clients,name,'.$id,
            'phone' => 'numeric|digits:10',
            'email' => 'email|unique:clients,email,'.$id,
            'billing_address' => 'string',
            'address2' => 'string',
            'country' => 'string',
            'state_id' => 'numeric',
            'city' => 'string',
            'pincode' => 'numeric|digits:6', 
            
        ]);
        $status="Client updated successfully";
        try{  
            $data->name = $request->name;
            // $data->client_code = $this->client_code;
            $data->phone = $request->phone;
            $data->email = $request->email; 
            $data->billing_address = $request->billing_address;         
            $data->address2 = $request->address2;
            $data->country = $request->country;
            $data->state_id = $request->state_id;
            $data->city = $request->city;
            $data->pincode = $request->pincode;    
            $data->updated_by = Auth::user()->id;                        
            $data->save();         
            return redirect()->route('app-client.index')->with(['status' => $status]);    
        }
        catch(Exception $e) {
            DB::rollBack();
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
