<?php

namespace App\Http\Controllers\Admin;

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

class ClientController extends Controller
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
        $clients = Client::with('state','company')->orderby('id', 'desc')->where('company_id',Auth::user()->company->id)->where('id',$clientId);
        if(!empty($request->input('client_code'))){
            $clients = $clients->where('client_code',$request->input('client_code'));
        }
        $clients=$clients->paginate(10);
        return view('company-app.table-list.client',compact('clients'));
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
        $companies = Company::where('status','1')->where('id',Auth::user()->company->id)->get();
        return view('company-app.card.client-card', compact('states','companies','data'));
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
            'name' => 'required|string|min:4',
            'client_code' => 'required|unique:clients',
            'company_id' => 'required',
            'phone' => 'required|numeric|digits:10',
            'email' => 'required|email',
            'billing_address' => 'required|string',
            'address2' => 'string',
            'country' => 'required|string',
            'state_id' => 'required|numeric',
            'city' => 'required|string',
            'pincode' => 'required|numeric|digits:6', 
            
        ]);
        $status="Client creates successfully";
        try{  
            DB::beginTransaction();
            $data = new Client;
            $data->name = $request->name;
            $data->client_code = $request->client_code;
            $data->company_id = Auth::user()->company_id;
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
            return redirect()->route('admin.client.index')->with(['status' => $status]);    
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
      //  $id = \Crypt::decrypt($id);
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

        return \Redirect::back()->with(['error' => 'data not Found!!!']);
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
        $data = Client::find($id);
        $states = State::all();
        $companies = Company::where('status','1')->get();
        if($data){       
            return view('company-app.card.client-card', compact('states','data','companies'));
        }
        else{
            return Redirect::back()->with(['error' => 'data not Found!!!']);
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
            'name' => 'string|min:4',
            'phone' => 'numeric|digits:10',
            'email' => 'email',
            'billing_address' => 'string',
            'address2' => 'string',
            'country' => 'string',
            'state_id' => 'numeric',
            'city' => 'string',
            'pincode' => 'numeric|digits:6', 
            
        ]);
        $status="Client creates successfully";
        try{  
            $data->name = $request->name;
       
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
            return redirect()->route('admin.client.index')->with(['status' => $status]);    
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
    public function client_profile(){
        $states = State::all();
        $data = Client::find(Auth::user()->client_id);
        if(!$data){
            return Redirect::back()->with(['error' => 'no data found'],404);
        }
        return view('client-app.client-profile',compact('data','states'));
    }
    public function client_profile_update(Request $request, $id){
        $data = Client::find($id);
        if(!$data){
            return Redirect::back()->with(['error' => 'no data found'],404);
        }
        $validate = $request->validate([
            'name' => 'string|min:4',
            'phone' => 'numeric|digits:10',
            'email' => 'email',
            'billing_address' => 'string',
            'address2' => 'string',
            'country' => 'string',
            'state_id' => 'numeric',
            'city' => 'string',
            'pincode' => 'numeric|digits:6', 
            
        ]);
        $status="Client update successfully";
        try{  
            $data->name = $request->name;
       
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
            return redirect()->route('client.profile')->with(['status' => $status]);    
        }
        catch(Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(['error' => $e->getMessage()]);
        } 
    }
}
