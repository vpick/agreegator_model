<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Erp;
use App\Models\ErpMapping;
use Response,Auth,DB;


class AppErpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $erps = Erp::orderBy('id','desc')->paginate(5);
        if(!$erps)
        {
            $erp = [];
        }
        /*if(Auth::user()->user_type!='isCompany')
        {
            $userP = DB::table('user_permissions as u')
                ->join('pages as p', function ($join) {
                    $join->on('u.page_id', '=', 'p.id')
                        ->where('u.role_id', '=', Auth::user()->role_id)
                        ->where('u.user_id', '=', Auth::user()->id)
                        ->where('p.pagename', '=', 'erp');
                })
                ->select('p.pagename', 'u.role_id', 'u.user_id', 'u.page_id', 'u.read', 'u.write', 'u.update', 'u.delete', 'u.print', 'u.download')
                ->first(); // Use first instead of get
        
            if((!empty($userP)) && ($userP->read ==1))
            {*/
                return view('admin-app.admin-tab.admin-tab-erp',compact('erps'));
            /*}
            else{
                return \Redirect::back()->with(['error' => 'No Permission!!!']);
            }
        }
        else
        {
            return view('admin-app.admin-tab.admin-tab-erp',compact('erps'));
        }*/
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [];
        return view('admin-app.admin-card.admin-erp-card',compact('data'));
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
            'erp_name' => 'required|string|unique:erps',
            'erp_type' => 'required',
            // 'auth_key' => 'string',
            // 'auth_name' => 'string',
            // 'auth_password' => 'string',
            // 'auth_secret' => 'string',
            'logo' => 'required',
            'status' => 'required',
            
        ]);
        $status="Record creates successfully";
        try{  
            
            $data = new Erp;
            $data->erp_name = $request->erp_name;
            $data->erp_type = $request->erp_type;
            $data->erp_auth_key = $request->auth_key;
            $data->erp_auth_name =$request->auth_name;
            $data->erp_auth_password = $request->uth_password;
            $data->erp_auth_secret = $request->auth_secret; 
            $data->erp_logo = $request->logo; 
            $data->status = $request->status;         
            $data->created_by = Auth::user()->id;  
            $data->save();        
            return redirect()->route('app-erp.index')->with(['status' => $status]);
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
        
        $data = Erp::find($id);
        if($data->status == '1'){
            $data->status = '0';
        }
        else {
            $data->status = '1';
        }
        
        try {
            $data->save();
            return \Redirect::back()->with(['status' => 'status updated!!!']);
        } catch(\Exception $e) {
            return \Redirect::back()->with(['error' => $e->getMessages()]);
        } 

        return \Redirect::back()->with(['error' => 'Record not Found!!!']);
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
        $data = Erp::find($id);
        if($data){       
            return view('admin-app.admin-card.admin-erp-card', compact('data'));
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
        $data = Erp::find($id);
        if(!$data){
            return Redirect::back()->with(['error' => 'no data found'],404);
        }
        $validate = $request->validate([
            'erp_name' => 'string|unique:erps,erp_name,'.$data->id,
        ]);
        $status="Record updated successfully";
        try{  
            $data->erp_name = $request->erp_name;
            $data->erp_type = $request->erp_type;
            $data->erp_auth_key = $request->auth_key;
            $data->erp_auth_name =$request->auth_name;
            $data->erp_auth_password = $request->uth_password;
            $data->erp_auth_secret = $request->auth_secret; 
            $data->erp_logo = $request->logo; 
            $data->status = $request->status;         
            $data->created_by = Auth::user()->id;  
            $data->save();        
            return redirect()->route('app-erp.index')->with(['status' => $status]);
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
    public function get()
    {
      
        $wms = Erp::where('erp_type','WMS')->orderBy('id','desc')->get();
        $erps = Erp::where('erp_type','ERP')->orderBy('id','desc')->get();
        $coms = Erp::where('erp_type','E-commerce')->orderBy('id','desc')->get();
        if(!$erps || !$coms || !$wms){
            $erps = [];
            $coms = [];
            $wms = [];
        }
        return view('common-app.list.erp-list',compact('wms','erps','coms'));
    }
    public function fetch($id)
    {
          $user = Auth::user();
        if($user->user_type =='isCompany'){
           
            $data = ErpMapping::where('erp_id',$id)->where('base_of','isCompany')->first();
        }
        else{
             
            $data = ErpMapping::where('erp_id',$id)->where('base_of','isClient')->first();
        }
        if($data){
            return Response::json(['data' => $data]);
        }
        return Response::json(['data' => 'no']);
    }
    public function map(Request $request){
        $user = Auth::user();
		$field = '';
		$fieldValue = '';
		if($user->user_type =='isCompany')
		{
		    $field = 'company_id';
		    $fieldValue = $user->company_id;
		}
		else
		{
		    $field = 'client_id';
		    $fieldValue = $user->client_id;
		}
		$record = ErpMapping::where(''.$field,$fieldValue)
                   ->where('erp_id', $request->partner_id)
                   ->firstOrNew();
		if (!$record->exists) 
		{
		    
				$PartnerMapping = new ErpMapping;
				$PartnerMapping->user_name = $request->user_id?$request->user_id:'';
				$PartnerMapping->password = $request->password?$request->password:'';
				$PartnerMapping->auth_key = $request->auth_key?$request->auth_key:'';
				$PartnerMapping->auth_secret = $request->secret_key?$request->secret_key:'';
				$PartnerMapping->business_acc = $request->business_acc?$request->business_acc:'';
				$PartnerMapping->base_of = $user->user_type;
				$PartnerMapping->company_id = $user->company_id;
				$PartnerMapping->client_id = $user->client_id;
				$PartnerMapping->status = '1';
				$PartnerMapping->erp_id = $request->partner_id?$request->partner_id:0;
				
				try
				{
					$PartnerMapping->save();
					$message = 'Mapping done successfully!';
				}
				catch (\Exception $e) 
				{
					// Handle any exceptions or errors
					// For example, display an error message or redirect the user back with an error
					
					$errorCode = $e->errorInfo[1];
					if($errorCode == '1062')
					{
						$returnMessage = 'Duplicate Entry';
					}
					else
					{
						$returnMessage = $e->errorInfo[2];
					}
					return back()->withErrors($returnMessage);
				}
		}
		else
		{
			$PartnerMapping = ErpMapping::findOrFail($record->id);
			#dd($PartnerMapping);
			$PartnerMapping->user_name = $request->user_id?$request->user_id:'';
			$PartnerMapping->password = $request->password?$request->password:'';
			$PartnerMapping->auth_key = $request->auth_key?$request->auth_key:'';
			$PartnerMapping->auth_secret = $request->secret_key?$request->secret_key:'';
			$PartnerMapping->business_acc = $request->business_acc?$request->business_acc:'';
			$PartnerMapping->base_of = $user->user_type;
			$PartnerMapping->company_id = $user->company_id;
			$PartnerMapping->client_id = $user->client_id;
			$PartnerMapping->status = '1';
			$PartnerMapping->erp_id = $request->partner_id?$request->partner_id:0;
			#dd($PartnerMapping);
			try
			{
				$PartnerMapping->save();
			    $message = 'Records updated successfully!';
			}
			catch (\Exception $e) 
			{
				// Handle any exceptions or errors
				// For example, display an error message or redirect the user back with an error
				
				$errorCode = $e->errorInfo[1];
				if($errorCode == '1062')
				{
					$returnMessage = 'Duplicate Entry';
				}
				else
				{
					$returnMessage = $e->errorInfo[2];
				}
				return back()->withErrors($returnMessage);
			}
			
		}
		return redirect()->route('erp.get')->with('status', ''.$message);
    }
}
