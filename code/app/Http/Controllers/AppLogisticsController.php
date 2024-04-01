<?php

namespace App\Http\Controllers;

use App\Models\AppLogistics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\LogisticsMapping;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;


class AppLogisticsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	protected $table_fields;
    public function __construct()
    {
			$table_fields = ['id', 'created_at','updated_at','status','company_id','client_id','partner_id','partner_name','base_of','user_name','password','auth_key','auth_secret','business_acc'];
			$this->pre_fields = $table_fields;
    }
    public function index(Request $request)
    {
        
		try
		{
		    $logistics = AppLogistics::where('logistics_type', 'Currior')->paginate(10);
		}
		catch (\Exception $e) 
		{
			// Something went wrong, rollback the transaction
			// Optionally, you can handle the exception or log it
			// Log::error($e->getMessage());
			echo $e->getMessage();
		}
		// 		$userP = DB::table('user_permissions as u')
		//                 ->join('pages as p', function ($join) {
		//                     $join->on('u.page_id', '=', 'p.id')
		//                         ->where('u.role_id', '=', Auth::user()->role_id)
		//                         ->where('u.user_id', '=', Auth::user()->id)
		//                         ->where('p.pagename', '=', 'logistic');
		//                 })
		//                 ->select('p.pagename', 'u.role_id', 'u.user_id', 'u.page_id', 'u.read', 'u.write', 'u.update', 'u.delete', 'u.print', 'u.download')
		//                 ->first(); // Use first instead of get
				
		//         if((!empty($userP)) && ($userP->read ==1))
		//         {
		    return view('admin-app.admin-tab.admin-tab-logistics',compact('logistics'));
        // }
        // else{
        //     return \Redirect::back()->with(['error' => 'No Permission!!!']);
        // }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add_logistics()
    {
        return view('admin-app.admin-card.adnin-logistics-card');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$app_logistics = new AppLogistics;
		$validated = $request->validate([
			'logistics_type' => 'required|string|max:20',
			'logistics_name' => 'required|string|max:50',
			'logistics_business_acc' => 'required|string|max:50',
			'logistics_status' => 'required|string|max:20',
			'logistics_logo' => 'required'
		]);
		$app_logistics->logistics_type = $validated['logistics_type'];
		$app_logistics->logistics_name = $validated['logistics_name'];
		$app_logistics->logistics_business_acc = $validated['logistics_business_acc'];
		$app_logistics->logistics_status = $validated['logistics_status'];
		$app_logistics->logistics_logo = $validated['logistics_logo'];
		
		$app_logistics->logistics_auth_key = $request->logistics_auth_key?$request->logistics_auth_key:'';
		$app_logistics->logistics_auth_name = $request->logistics_auth_name?$request->logistics_auth_name:'';
		$app_logistics->logistics_auth_password = $request->logistics_auth_password?$request->logistics_auth_password:'';
		$app_logistics->logistics_auth_secret = $request->logistics_auth_secret?$request->logistics_auth_secret:'';
		$app_logistics->logistics_currior_id = $request->logistics_currior_id?$request->logistics_currior_id:'';
		
		
		#If use save then use below code
		$app_logistics->save();
		return redirect('app-partners')->with('status', 'Chanel added successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AppLogistics  $appLogistics
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
		try 
		{
			$encryptedId = $request->input('ord');
			$partnersId = Crypt::decrypt($encryptedId);

			// Check if the decrypted ID is valid
			if (!is_numeric($partnersId)) 
			{
				throw new \Exception('Invalid order ID');
			}
            $partners = AppLogistics::find($partnersId);
            return view('admin-app.admin-card.partners-edit-view-card',compact('partners'));
		} 
		catch (\Exception $e) 
		{
			// Handle any exceptions or errors
			// For example, display an error message or redirect the user back with an error
			return back()->withErrors($e->getMessage());
		}
    }
    public function logistics_list()
	{
	    
	    $user = Auth::user();
		try
		{
			$logistics = AppLogistics::where('logistics_type', '=', 'Currior')
            ->get();
		}
		catch (\Exception $e) 
		{
			// Something went wrong, rollback the transaction
			// Optionally, you can handle the exception or log it
			// Log::error($e->getMessage());
			echo $e->getMessage();
		}
	    if(Auth::user()->user_type!='isCompany'){
    		$userP = DB::table('user_permissions as u')
                    ->join('pages as p', function ($join) {
                        $join->on('u.page_id', '=', 'p.id')
                            ->where('u.role_id', '=', Auth::user()->role_id)
                            ->where('u.user_id', '=', Auth::user()->id)
                            ->where('p.pagename', '=', 'logistic');
                    })
                    ->select('p.pagename', 'u.role_id', 'u.user_id', 'u.page_id', 'u.read', 'u.write', 'u.update', 'u.delete', 'u.print', 'u.download')
                    ->first(); // Use first instead of get
            
            if((!empty($userP)) && ($userP->read ==1))
            {
    		    return view('common-app.list.logistics',compact('logistics','user','userP'));
            }
            else{
                return \Redirect::back()->with(['error' => 'No Permission!!!']);
            }
	    }
	    else{
	        return view('common-app.list.logistics',compact('logistics','user'));
	    }
	}
	
	public function aggrigators_list()
	{
		
	    $user = Auth::user();
		try
		{
			$logistics = AppLogistics::where('logistics_type', '=', 'Aggrigator')->get();
		}
		catch (\Exception $e) 
		{
			echo $e->getMessage();
		}
		if(Auth::user()->user_type!='isCompany'){
			$userP = DB::table('user_permissions as u')
                ->join('pages as p', function ($join) {
                    $join->on('u.page_id', '=', 'p.id')
                        ->where('u.role_id', '=', Auth::user()->role_id)
                        ->where('u.user_id', '=', Auth::user()->id)
                        ->where('p.pagename', '=', 'aggrigator');
                })
                ->select('p.pagename', 'u.role_id', 'u.user_id', 'u.page_id', 'u.read', 'u.write', 'u.update', 'u.delete', 'u.print', 'u.download')
                ->first(); // Use first instead of get
        
            if((!empty($userP)) && ($userP->read ==1))
            {
    		    return view('common-app.list.logistics',compact('logistics','user','userP'));
            }
            else
			{
                return \Redirect::back()->with(['error' => 'No Permission!!!']);
            }
		}
		else{
		    return view('common-app.list.logistics',compact('logistics','user'));
		}
	}
	public function get_aggrigators()
	{
		try
		{
			$logistics = AppLogistics::where('logistics_type', '=', 'Aggrigator')
           ->paginate(10);
		}
		catch (\Exception $e) 
		{
			// Something went wrong, rollback the transaction
			// Optionally, you can handle the exception or log it
			// Log::error($e->getMessage());
			echo $e->getMessage();
		}
		return view('admin-app.admin-aggrigators',compact('logistics'));
	}
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AppLogistics  $appLogistics
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
		try 
		{
			$encryptedId = $request->input('ord');
			$partnersId = Crypt::decrypt($encryptedId);
			// Check if the decrypted ID is valid
			if (!is_numeric($partnersId)) 
			{
				throw new \Exception('Invalid order ID');
			}
            $partnersVarify = AppLogistics::findOrFail($partnersId);		 
			if(!empty($partnersVarify))
			{
			    $rules = 
				[
					'logistics_type' => 'required|string|max:15',
					'logistics_name' => 'required|string|max:25',
					'logistics_business_acc' => 'required|string|max:25',
					'logistics_status' => 'required|string|max:10',
				];
				$validator = Validator::make($request->all(),$rules);
				if ($validator->fails()) 
				{
					// Validation failed
					$errors = $validator->errors()->all();
					// Handle validation errors appropriately
					#$response['error_message'] = $errors;
					#dd($errors);
					return back()->withErrors($errors);
					#return redirect('app-partners')->with('status', ''.$errors);
				} 
				$validated = $validator->validated();
				$partnersVarify->logistics_type = $validated['logistics_type'];
				$partnersVarify->logistics_name = $validated['logistics_name'];
				$partnersVarify->logistics_business_acc = $validated['logistics_business_acc'];
				$partnersVarify->logistics_status = $validated['logistics_status'];
				
				$partnersVarify->logistics_auth_key = $request->logistics_auth_key?$request->logistics_auth_key:'';
				$partnersVarify->logistics_auth_name = $request->logistics_auth_name?$request->logistics_auth_name:'';
				$partnersVarify->logistics_auth_password = $request->logistics_auth_password?$request->logistics_auth_password:'';
				$partnersVarify->logistics_auth_secret = $request->logistics_auth_secret?$request->logistics_auth_secret:'';
				$partnersVarify->logistics_currior_id = $request->logistics_currior_id?$request->logistics_currior_id:'';
				$partnersVarify->logistics_status = $request->logistics_status?$request->logistics_status:'';
				$partnersVarify->logistics_logo = $request->logistics_logo?$request->logistics_logo:'';
				
				#dd($partnersVarify);
				#If use save then use below code
				DB::beginTransaction();
				try
				{
					$partnersVarify->save();
					DB::commit();
					return redirect('app-partners')->with('status', 'Partners updated Successfully!');
				}
				catch (\Exception $e) 
				{
					// Something went wrong, rollback the transaction
					DB::rollback();
					// Optionally, you can handle the exception or log it
					// Log::error($e->getMessage());
					#echo $e->getMessage();dd();
					return back()->withErrors($e->getMessage());
				}	
			}	
            else
			{
				return redirect('app-partners')->with('status', 'Error code 404: Partners not exist');
			}				
        } 
		catch (\Exception $e) 
		{
			// Handle any exceptions or errors
			// For example, display an error message or redirect the user back with an error
			return back()->withErrors($e->getMessage());
		}
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AppLogistics  $appLogistics
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AppLogistics $appLogistics)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AppLogistics  $appLogistics
     * @return \Illuminate\Http\Response
     */
    public function destroy(AppLogistics $appLogistics)
    {
        //
    }
	public function add_field($partnerId)
	{
		$logistic = AppLogistics::select('id','logistics_name','add_fields')->where('id', $partnerId)->first();
		$columns = \DB::connection()->getSchemaBuilder()->getColumnListing('logistics_mappings');
		$pre_table_colomn = $this->pre_fields;
		return view('common-app.card.card-add-field',compact('logistic','columns','pre_table_colomn'));
	}
	public function store_field(Request $request)
	{
		try {
			$columnData = trim($request->field_name);
			$columnName = str_replace(' ', '_', $columnData);
			$fieldName = strtolower($columnName);
			$tableName = 'logistics_mappings';
			// Check if the column already exists in the table
			$columnExists = DB::select("SHOW COLUMNS FROM $tableName LIKE '$fieldName'");
			if (empty($columnExists)) {
				// If the column does not exist, add it
				DB::statement("ALTER TABLE $tableName ADD $fieldName VARCHAR(255) AFTER business_acc");
				return back()->with('status', 'Field Added Successfully!');
			} else {
				// If the column already exists, handle accordingly
				return back()->with('status', 'Field Already Exists!');
			}
		} catch (\Exception $e) {
			// Handle any exceptions or errors
			// For example, display an error message or redirect the user back with an error
			return back()->withErrors($e->getMessage());
		}
	}
	

public function field_mapping(Request $request)
{
    $partnerId = $request->partner_id;
    
    try {
        if (!$partnerId) {
            throw new \Exception('Empty partner id');
        }

        $partnersVerify = AppLogistics::findOrFail($partnerId);
        $partnersVerify->add_fields = implode(',',$request->field_name);
        $partnersVerify->save();

        return back()->with('status', 'Field Mapped Successfully!');
    } catch (\Exception $e) {
        // Handle any exceptions or errors
        // For example, display an error message or redirect the user back with an error
        return back()->withErrors($e->getMessage());
    }
}

}

