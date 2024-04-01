<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use App\Models\Company;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\Role;
use App\Models\Client;
use App\Models\State;
use App\Helpers\Helper;
use Hash,Auth;
use Illuminate\Support\Str;

class AppUserController extends Controller
{
    protected $user_code;
    public function __construct()
    {
       // $this->user_code = Helper::numSeries('user');
       $data = User::orderBy('id','desc')->first();
        if(!empty($data)){            
            $userCode =$data->user_code;
            $str = explode('_',$userCode);
            $num = $str[1]+1;
            $numSeries = Str::of($str[0])->append('_'.$num);
            $this->user_code = $numSeries;
        }
        else{
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
    public function index()
    {
        
      
        $companyId = session('company.id') ?? Auth::user()->company->id;
        $clientId = session('client.id') ?? Auth::user()->client->id;
        $warehouseId = session('warehouse.id') ?? Auth::user()->warehouse->id;
       
        $users = User::with('company','client','warehouse','role')
                    ->where('user_type','!=','isSystem')
                    ->where('id','!=',Auth::user()->id)
                    ->where('company_id',$companyId)
                    ->where('client_id',$clientId)
                    ->where('warehouse_id',$warehouseId)
                    ->orderby('id', 'desc')
                    ->paginate(10);
        return view('admin-app.admin-tab.admin-tab-users',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        $roles = Role::all();
        $data = [];
        $companyId = session('company.id') ?? Auth::user()->company->id;
        $clientId = session('client.id') ?? Auth::user()->client->id;
        $warehouseId = session('warehouse.id') ?? Auth::user()->warehouse->id;
        $companies = Company::where('status','1')->where('id',$companyId)->get();
        $clients = Client::where('status','1')->where('id',$clientId)->get();
        $warehouses = Warehouse::where('status','1')->where('id',$warehouseId)->get();
        return view('admin-app.admin-card.admin-users-card',compact('companies','roles','data','clients','warehouses','companyId','clientId','warehouseId'));
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
            $data->user_code = $this->user_code;
            $data->password = Hash::make($request->password);
            $data->user_code = $this->user_code;
            $data->company_id =$request->company_id;
            $data->client_id =$request->client_id;
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
            
            try{              
                $data->save();
                return redirect()->route('app-user.index')->with(['status' => $status]);
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
        $companyId = session('company.id') ?? Auth::user()->company->id;
        $clientId = session('client.id') ?? Auth::user()->client->id;
        $warehouseId = session('warehouse.id') ?? Auth::user()->warehouse->id;
        $companies = Company::where('status','1')->where('id',$companyId)->get();
        $clients = Client::where('status','1')->where('id',$clientId)->get();
        $warehouses = Warehouse::where('status','1')->where('id',$warehouseId)->get();
        if($data){       
            return view('admin-app.admin-card.admin-users-card', compact('roles','data','companies','clients','warehouses','companyId','clientId','warehouseId'));
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
    public function app_user_profile(){
        
        $data = User::find(Auth::user()->id);
        $states = State::all();
        return view('admin-app.admin-card.admin-profile-card',compact('data','states'));
    }
    public function app_update_profile(Request $request)
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
                return redirect()->route('app.user.profile')->with(['status' => $status]);
            }
            catch(Exception $e) {
                return Redirect::back()->with(['error' => $e->getMessage()]);
            }
    }
    public function app_change_password(){
        return view('admin-app.admin-card.admin-change-password-card');
    }
    public function app_update_password (Request $request)
    {
        #dd(Hash::make('root'));
        $data = User::find(Auth::user()->id);
        
        if(!$data){
            return Redirect::back()->with(['error' => 'no data found'],404);
        }
        $validators = $request->validate([
              'password' => Password::defaults(),
               'confirm_password' => 'required' 
        ]);
        
        if($validators['password']!=$validators['confirm_password']){
            return redirect()->route('app.user.password')->with(['warning' => 'Confirm password does not match with password']);
        }
        
        $status="Password changed successfully";
        $data->password = Hash::make($request->password);
        $data->updated_by = Auth::user()->id;  
        try{              
            $data->save();
            return redirect()->route('app.user.password')->with(['status' => $status]);
        }
        catch(Exception $e) {
            return Redirect::back()->with(['error' => $e->getMessage()]);
        }
    }
}
