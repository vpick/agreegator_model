<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use App\Models\Company;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\Role;
use App\Models\Client;
use App\Helpers\Helper;
use App\Models\State;
use Hash,Auth,DB,Session;
use Illuminate\Support\Str;

class UserController extends Controller
{
    protected $user_code;
    public function __construct()
    {
       
       // $this->user_code = Helper::numSeries('user');
       $data = User::orderBy('id','desc')->first();
        if(!empty($data))
        {            
            $userCode =$data->user_code;
            $str = explode('_',$userCode);
            $num = $str[1]+1;
            $numSeries = Str::of($str[0])->append('_'.$num);
            $this->user_code = $numSeries;
        }
        else
        {
            $prefix ='user_';
            $start = '100';
            $numSeries = $prefix.$start;
            $this->user_code = $numSeries;
        }
    
    } 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    { 
        if(Session::has('client'))
        {
            $clientId = session('client.id');
            $clientName = session('client.name');
        }
        else
        {
            $clientId = Auth::user()->client->id;
            $clientName = Auth::user()->client->name;
        }
        if(Session::has('warehouse'))
        {
            $warehouseId = session('warehouse.id');
             $warehouseName = session('warehouse.warehouse_name');
        }
        else{
            $warehouseId = Auth::user()->warehouse->id;
            $warehouseName = Auth::user()->warehouse->warehouse_name;
        }
       if(Auth::user()->user_type == 'isCompany')
       {
            if(Session::has('client'))
            {
               $user = User::with('company','client','warehouse','role')
                        ->where('user_type','!=','isSystem')
                        ->where('user_type','!=','isCompany')
                        ->where('user_type','!=','isClient')
                        ->where('client_id',$clientId)
                        ->where('warehouse_id',$warehouseId)
                        ->where('id','!=',Auth::user()->id);
            }
            else
            {
                $user = User::with('company','client','warehouse','role')
                        ->where('user_type','!=','isSystem')
                        ->where('user_type','!=','isCompany')
                       
                        // ->where('client_id',$clientId)
                        // ->where('warehouse_id',$warehouseId)
                        ->where('id','!=',Auth::user()->id);
            }
        }
        else
        {
                $user = User::with('company','client','warehouse','role')
                        ->where('user_type','!=','isSystem')
                        ->where('user_type','!=','isCompany')
                       ->where('user_type','!=','isClient')
                        ->where('client_id',$clientId)
                        ->where('warehouse_id',$warehouseId)
                        ->where('id','!=',Auth::user()->id);
            }            
            if(!empty($request->input('user_code'))){
            	$user = $user->where('user_code',$request->input('user_code'));
            }
            if(!empty($request->input('user_type'))){
            	$order = $user->where('user_type',$request->input('user_type'));
            }
            if(!empty($request->input('status'))){
            	$user = $user->where('status',$request->input('status'));
            }
        	$users = $user->paginate(10);
            if(Auth::user()->user_type!='isCompany'){
                $userP = DB::table('user_permissions as u')->Join('pages as p', function ($join) {
                   $join->on('u.page_id', '=','p.id' )->where('u.role_id', Auth::user()->role_id)
                  ->where('p.pagename','user'); })
                  ->select('u.role_id','u.user_id','u.page_id','u.read','u.write','u.update','u.delete','u.download','u.print')
                  ->first();
                
            if((!empty($userP)) && ($userP->read ==1))
            {
                return view('common-app.list.users',compact('users','userP'));
            }
            else{
                return \Redirect::back()->with(['error' => 'No Permission!!!']);
            }
        }
        else{
            return view('common-app.list.users',compact('users'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Session::has('client')){
            $clientId = session('client.id');
             $clientName = session('client.name');
        }
        else{
            $clientId = Auth::user()->client->id;
            $clientName = Auth::user()->client->name;
        }
        if(Session::has('warehouse')){
            $warehouseId = session('warehouse.id');
             $warehouseName = session('warehouse.warehouse_name');
        }
        else{
            $warehouseId = Auth::user()->warehouse->id;
            $warehouseName = Auth::user()->warehouse->warehouse_name;
        }
        if(Auth::user()->user_type == 'isCompany'){
            $roles = Role::where('id','>',2)->get();
        }
        else {
            $roles = Role::where('id','>',3)->get();
        }
        $clients = Client::where('company_id',Auth::user()->company_id)->where('id',$clientId)->where('status','1')->get();
        $data = [];
        $warehouses = Warehouse::where('status','1')->where('client_id',$clientId)->where('id',$warehouseId)->get();
        if(Auth::user()->user_type!='isCompany'){
            $userP = DB::table('user_permissions as u')->Join('pages as p', function ($join) {
               $join->on('u.page_id', '=','p.id' )->where('u.role_id', Auth::user()->role_id)
              ->where('p.pagename','user'); })
              ->select('u.role_id','u.user_id','u.page_id','u.read','u.write','u.update','u.delete','u.download','u.print')
              ->first();
            
            if((!empty($userP)) && ($userP->read ==1))
            {
                
                return view('common-app.card.users-card',compact('clients','roles','warehouses','data','clientId','clientName','warehouseId','warehouseName','userP'));
            }
            else{
                return \Redirect::back()->with(['error' => 'No Permission!!!']);
            }
        }
        else{
            return view('common-app.card.users-card',compact('clients','roles','warehouses','data','clientId','clientName','warehouseId','warehouseName'));
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
       
       $validators = $request->validate([
            'username' => 'required|string|min:4|unique:users',
             'password' => Password::defaults(),
             'company_id' => 'required',
             'client_id' => 'required',
              'warehouse_id' => 'required',  
             'phone' => 'required|numeric|digits:10',
             'email' => 'required|email|unique:users',
             'role_id' => 'required',
              'user_type' => 'required',
             'multi_client' => 'required',
             'multi_location' => 'required',           
        ]);
        $role = Role::where('role',$request->role_id)->first();
        if(!$role){
             return \Redirect::back()->with(['error' => 'Role not Found!!!']);
        }
       $status="User create successfully";
            $data = new User;
            $data->username = $request->username;
            $data->password = Hash::make($request->password);
            $data->user_code = $this->user_code;
            $data->company_id =Auth::user()->company_id;
            $data->client_id = $request->client_id;
            
            $data->warehouse_id =$request->warehouse_id;
            $data->mobile = $request->phone;
            $data->email = $request->email; 
            $data->role_id = $role->id;  
            $data->user_type = $request->user_type;  
            $data->multi_client = $request->multi_client;
            $data->multi_location = $request->multi_location;
            $data->client_map = $request->client_id;
            $data->warehouse_map = $request->warehouse_id;
            $data->created_by = Auth::user()->id;  
            //dd($request->all());
            try{              
                $data->save();
                return redirect()->route('user.index')->with(['status' => $status]);
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
        
        $data = User::find($id);
        if($data->status == '1'){
            $data->status = '0';
        }
        else {
            $data->status = '1';
        }
        
        try {
            $data->save();
            return \Redirect::back()->with(['status' => 'User status updated!!!']);
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
        //dd($id);
        $data = User::find($id);
        $roles = Role::all();
        if(Session::has('client')){
            $clientId = session('client.id');
             $clientName = session('client.name');
        }
        else{
            $clientId = Auth::user()->client->id;
            $clientName = Auth::user()->client->name;
        }
        if(Session::has('warehouse')){
            $warehouseId = session('warehouse.id');
             $warehouseName = session('warehouse.warehouse_name');
        }
        else{
            $warehouseId = Auth::user()->warehouse->id;
            $warehouseName = Auth::user()->warehouse->name;
        }
        $companies = Company::where('status','1')->get();
        $clients = Client::where('status','1')->get();
        $warehouses = Warehouse::where('status','1')->get();
        if($data){       
            return view('common-app.card.users-card', compact('roles','data','companies','clients','warehouses','clientId','clientName','warehouseId','warehouseName'));
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
        $data = User::find($id);
        if(!$data){
            return Redirect::back()->with(['error' => 'no data found'],404);
        }
        $validators = $request->validate([
             'phone' => 'numeric|digits:10',
             'email' => 'email|unique:users,email,'.$id
                       
        ]);
       // dd($validators);
            $status="User updated successfully";
            $data->mobile = $request->phone;
            $data->email = $request->email; 
            $data->updated_by = Auth::user()->id;  
            #dd($data);
            try{              
                $data->save();
                return redirect()->route('user.index')->with(['status' => $status]);
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
     public function user_profile(){
        if(Auth::user()->user_type == 'isCompany'){
            $data = Company::find(Auth::user()->company_id);
            $data['type'] = 'Company';
        }
        elseif(Auth::user()->user_type == 'isClient'){
            $data = Client::find(Auth::user()->client_id);
            $data['type'] = 'Client';
        }
        elseif(Auth::user()->user_type == 'isUser'){
            $data = Warehouse::find(Auth::user()->warehouse_id);
            $data['type'] = 'Warehouse';
        }
        else{

        }
        $states = State::all();
        return view('common-app.card.profile-card',compact('data','states'));
    }
    public function update_profile(Request $request)
    {
        $data = User::find(Auth::user()->id);
        if(!$data){
            return Redirect::back()->with(['error' => 'no data found'],404);
        }
        $validators = $request->validate([
             'mobile' => 'numeric|digits:10',
             'email' => 'email|unique:users,email,'.$data->id,
        ]);
        
            $status="User updated successfully";
            $data->mobile = $request->mobile;
            $data->email = $request->email; 
            $data->updated_by = Auth::user()->id;  
            try{              
                $data->save();
                return redirect()->route('user.profile')->with(['status' => $status]);
            }
            catch(Exception $e) {
                return Redirect::back()->with(['error' => $e->getMessage()]);
            }
    }
    public function change_password(){
        return view('common-app.card.change-password-card');
    }
    public function update_password (Request $request)
    {

        $data = User::find(Auth::user()->id);
        if(!$data){
            return Redirect::back()->with(['error' => 'no data found'],404);
        }
        $validators = $request->validate([
              'password' => Password::defaults(),
               'confirm_password' => 'required' 
        ]);
        if($validators['password']!=$validators['confirm_password']){
            return redirect()->route('user.password')->with(['warning' => 'Confirm password does not match with password']);
        }
        $status="Password changed successfully";
        $data->password = Hash::make($request->password);
        $data->updated_by = Auth::user()->id;  
        try{              
            $data->save();
            return redirect()->route('user.password')->with(['status' => $status]);
        }
        catch(Exception $e) {
            return Redirect::back()->with(['error' => $e->getMessage()]);
        }
    }
    
    
}
