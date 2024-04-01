<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Company;
use App\Models\State;
use App\Models\Warehouse;
use App\Models\User;
use Crypt,Auth;
use Illuminate\Support\Facades\Session;


class AppCommonController extends Controller
{
    public function company_list()
    {
        $companies = Company::all();
        if(!empty($companies)){
             
            return view('admin-app.admin-company-list',compact('companies'));
        }
        return \Redirect::back()->with(['error' => 'Data not Found!!!']);
    }
    public function companyclient_list($id)
    {
      
        $id = \Crypt::decrypt($id);
        $clients = Client::where('company_id',$id)->get();
        // $clist = [];
        // foreach($client as $client){
        //     array_push($clist, $client->id);
        // }
        // $lst = implode(',',$clist);
        // $check = User::whereIn('client_map',$lst)->get();
        #dd($check);
        if(!empty( $clients)){
             
            return view('admin-app.admin-client-list',compact('clients'));
        }
        return \Redirect::back()->with(['error' => 'Data not Found!!!']);
    }
    
    public function clientwarehouse_list($id)
    {
        $id = \Crypt::decrypt($id);
        $warehouses = Warehouse::where('client_id',$id)->get();
        if(!empty( $warehouses)){
             
            return view('admin-app.admin-warehouse-list',compact('warehouses'));
        }
        return \Redirect::back()->with(['error' => 'Data not Found!!!']);
    }
    public function get_client_list($id)
    {
        $id = \Crypt::decrypt($id);
        $clients = Client::where('company_id',$id)->get();
        if(!empty($clients)){
             
            return view('company-app.client-list',compact('clients'));
        }
        return \Redirect::back()->with(['error' => 'Data not Found!!!']);
    }
    
    public function get_warehouse_list($id)
    {
      
        $id = \Crypt::decrypt($id);
        if(Auth::user()->user_type=='isClient'){
            $userId = Auth::user()->id;
            $user = User::select('warehouse_map')->where('id',$userId)->first();  
            $arr = explode(',',$user->warehouse_map);
            $warehouses = Warehouse::whereIn('id',$arr)->get();
        }
        else if(Auth::user()->user_type=='isCompany'){
            $warehouses = Warehouse::where('client_id',$id)->get();
        }
        if(!empty( $warehouses)){
             
             if(Auth::user()->user_type == 'isCompany')
            {
                return view('common-app.list.warehouse-list',compact('warehouses'));
            }
            return view('client-app/client-warehouse-list',compact('warehouses'));
        }
        return \Redirect::back()->with(['error' => 'Data not Found!!!']);
    }
    public function set_company_session($id){
     
		$id = Crypt::decrypt($id);
        #dd($id);	
        if($id != session('company.id')){
            Session::forget('company');
            Session::forget('client');
            Session::forget('warehouse');
        }
		$companyData = Company::find($id);
        Session::put('company', $companyData);
		$encryptedClientId = Crypt::encrypt(session('company.id'));
        return redirect()->away('/client-list/' . $encryptedClientId);
        
    }
    public function set_client_session($id){
     
		$id = Crypt::decrypt($id);
        #dd($id);	
        if($id != session('client.id')){
            Session::forget('client');
            Session::forget('warehouse');
        }
		$clientData = Client::find($id);
        Session::put('client', $clientData);
		$encryptedClientId = Crypt::encrypt(session('client.id'));
        if(Auth::user()->user_type == 'isSystem'){
            
            return redirect()->away('/warehouse-list/' . $encryptedClientId);
        }
        return redirect()->away('/get-warehouses/' . $encryptedClientId);
        
    }
    public function set_session($id){
     
     
		$id = Crypt::decrypt($id);
        #dd($id);	
        if($id != session('warehouse.id')){
            Session::forget('warehouse');
        }
		$warehouseData = Warehouse::find($id);
		$encryptedWarehouseId = Crypt::encrypt(session('warehouse.id'));
        Session::put('warehouse', $warehouseData);
		#dd(session('warehouse.warehouse_name'));
        if(Auth::user()->user_type == 'isSystem'){
            return redirect()->route('warehouse.view',$encryptedWarehouseId);
        }
        return redirect()->away('/orders');
        
    }

}
