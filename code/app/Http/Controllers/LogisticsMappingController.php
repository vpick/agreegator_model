<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LogisticsMapping;
use App\Models\AppLogistics;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Session;
class LogisticsMappingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		
		$user = Auth::user();
		$field = '';
		$fieldValue = '';
		$field1 = '';
		$fieldValue1 = '';
		if($user->user_type =='isCompany')
		{
		    if(!Session::has('company')){
			    $field = 'company_id';
		        $fieldValue = $user->company_id;
    		}
    		else{
    		    $field = 'company_id';
    			$fieldValue = session('company.id');
    		}
    		if(!Session::has('client')){
			    $field1 = 'client_id';
		        $fieldValue1 = $user->client_id;
    		}
    		else{
    		     $field1 = 'client_id';
    			 $fieldValue1 = session('client.id');
    		}
		}
		else
		{
		    
		    $field = 'company_id';
		    $fieldValue = $user->company_id;
		    if(!Session::has('client'))
		    {
			    $field1 = 'client_id';
		        $fieldValue1 = $user->client_id;
    		}
    		else
    		{
    		     $field1 = 'client_id';
    			 $fieldValue1 = session('client.id');
    		}
		}
		$record = LogisticsMapping::where(''.$field,$fieldValue)->where(''.$field1,$fieldValue1)
                   ->where('partner_id', $request->partner_id)
                   ->firstOrNew();
        $partner = AppLogistics::select('logistics_name','add_fields')->find($request->partner_id);
		$keys = array_keys($request->all());
		if (!$record->exists) 
		{
    		    if(!Session::has('client'))
    		    {
    			    $status = Auth::user()->user_type;
        		}
        		else
        		{
        			 $status = 'isClient';
        		}
        
				$PartnerMapping = new LogisticsMapping;
				$PartnerMapping->user_name = $request->user_id?$request->user_id:'';
				$PartnerMapping->password = $request->password?$request->password:'';
				$PartnerMapping->auth_key = $request->auth_key?$request->auth_key:'';
				$PartnerMapping->auth_secret = $request->secret_key?$request->secret_key:'';
				$PartnerMapping->business_acc = $request->business_acc?$request->business_acc:'';
				$PartnerMapping->base_of = $request->status ? $request->status :$status;
				// $PartnerMapping->company_id = $user->company_id;
				// $PartnerMapping->client_id = $user->client_id?$user->client_id:0;
				$PartnerMapping->company_id = $fieldValue;
				$PartnerMapping->client_id = $fieldValue1 ? $fieldValue1 :0;
				$PartnerMapping->status = 'Active';
				$PartnerMapping->partner_id = $request->partner_id?$request->partner_id:0;
				$PartnerMapping->partner_name = $partner->logistics_name.'App';
				$fields = $partner->add_fields;
			
				$fieldArray = explode(',',$fields);
				
				foreach($fieldArray as $list)
				{
					if (count(array_intersect([$list], $keys)) > 0) {
					
						$PartnerMapping->$list = $request->input($list);
					}
				}
				
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
			
			
		    if(!Session::has('client')){
    			    $status = Auth::user()->user_type;
        		}
        		else{
        			 $status = 'isClient';
        			
        		}
			$PartnerMapping = LogisticsMapping::findOrFail($record->id);
			
			#dd($PartnerMapping);
			$PartnerMapping->user_name = $request->user_id?$request->user_id:'';
			$PartnerMapping->password = $request->password?$request->password:'';
			$PartnerMapping->auth_key = $request->auth_key?$request->auth_key:'';
			$PartnerMapping->auth_secret = $request->secret_key?$request->secret_key:'';
			$PartnerMapping->business_acc = $request->business_acc?$request->business_acc:'';
			$PartnerMapping->base_of = $request->status ? $request->status :$status;
// 			$PartnerMapping->company_id = $user->company_id;
// 			$PartnerMapping->client_id = $user->client_id?$user->client_id:0;
            $PartnerMapping->company_id = $fieldValue;
			$PartnerMapping->client_id = $fieldValue1 ? $fieldValue1 :0;
			$PartnerMapping->status = 'Active';
			$PartnerMapping->partner_id = $request->partner_id?$request->partner_id:0;
			$fields = $partner->add_fields;
			
			$fieldArray = explode(',',$fields);
			
			foreach($fieldArray as $list)
			{
				if (count(array_intersect([$list], $keys)) > 0) {
				 
					$PartnerMapping->$list = $request->input($list);
				}
			}
			
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
		return redirect('our-aggrigators')->with('status', ''.$message);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PartnerMapping  $partnerMapping
     * @return \Illuminate\Http\Response
     */
    public function show(PartnerMapping $partnerMapping)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PartnerMapping  $partnerMapping
     * @return \Illuminate\Http\Response
     */
    public function edit(PartnerMapping $partnerMapping)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PartnerMapping  $partnerMapping
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PartnerMapping $partnerMapping)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PartnerMapping  $partnerMapping
     * @return \Illuminate\Http\Response
     */
    public function destroy(PartnerMapping $partnerMapping)
    {
        //
    }
	public function get_mapping_partner($partnerId)
    {
		$user = Auth::user();
		
		$field = '';
		$fieldValue = '';
		$field1 = '';
		$fieldValue1 = '';
		if($user->user_type =='isCompany')
		{
    		if(!Session::has('client'))
    		{
			    $field1 = 'client_id';
		        $fieldValue1 = $user->client_id;
		        $field = 'company_id';
		        $fieldValue = $user->company_id;
    		}
    		else
    		{
    		     $field1 = 'client_id';
    			 $fieldValue1 = session('client.id');
    			 $field = 'company_id';
		         $fieldValue = $user->company_id;
    		}
		}
		else
		{
		    $field = 'company_id';
		    $fieldValue = $user->company_id;
		    $field1 = 'client_id';
		    $fieldValue1 = $user->client_id;
		}
		$addColumn = AppLogistics::select('add_fields')->where('id',$partnerId)->first();
		$columns=[];
	    if($addColumn->add_fields!='' || $addColumn->add_fields !=null)
	    {
	        $columns =explode(',',$addColumn->add_fields);
	    }
	    $data['columns'] =$columns;
		$data['mapping'] = LogisticsMapping::where('partner_id', '=', $partnerId)
		                            ->where(''.$field, '=',$fieldValue)->where(''.$field1, '=',$fieldValue1)
		                            ->join('app_logistics', 'app_logistics.id', '=', 'logistics_mappings.partner_id')
                                    ->get();
        
		return response()->json($data);	
    }
    public function get_mapping_partner_onbhalf_company($partnerId)
    {
        $auth = Auth::user();
        if(!Session::has('company')){
		        $user = $auth->company_id;
		}
		else{
		     
			 $user = session('company.id');
		}
	
		$mapping = LogisticsMapping::where('partner_id', '=', $partnerId)
		->where('company_id', '=',$user)->where('base_of','isCompany')
		->join('app_logistics', 'app_logistics.id', '=', 'logistics_mappings.partner_id')
            ->get();
		
		return response()->json($mapping->all());	
    }
    public function get_mapping_partner_onbhalf_client($partnerId)
    {
        $auth = Auth::user();
        if(!Session::has('client')){
		     $user = $auth->client_id;
		}
		else{
		     
			 $user = session('client.id');
		}
		
		$mapping = LogisticsMapping::where('partner_id', '=', $partnerId)
		->where('client_id', '=',$user)->where('base_of','isClient')
		->join('app_logistics', 'app_logistics.id', '=', 'logistics_mappings.partner_id')
            ->get();
		#dd($user);
		return response()->json($mapping->all());	
    }
}
