<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\Company;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Helper;

class CompanyController extends Controller
{
    protected $company_code;
    public function __construct()
    {
        $this->company_code = Helper::numSeries('company');
    } 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Company::with('state:id,state_name')->get();
        if($data){
            return Response::json(['company' => $data], 200);
        }
        else{
            return Response::json(['error' => 'Data not Found'], 404);
        }
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
        $validation = Validator::make($request->all(), [
            'name' => 'required|min:4',
            'url' => 'string',
            'phone' => 'required|numeric|digits:10',
            'email' => 'required|email',
            'pan_no' => 'required|max:10|unique:companies',
            'gst_no' => 'max:15|unique:companies',
            'address' => 'required|string',
            'state_id' => 'required|numeric',
            'city' => 'required|string',
            'district' => 'required|string',
            'pincode' => 'required|numeric|digits:6', 
            'account_name' => 'required|string',
            'account_no' => 'required|numeric',
            'cancelled_cheque' =>'required|image|mimes:jpeg,png,jpg',
            'bank_name' => 'required|string',
            'bank_branch' => 'required|string',
            'account_type' => 'required|string',
            'ifsc_code' => 'required|string',
        ]);
        if($validation->fails()) {
            return Response::json(['error' => $validation->errors()->first()], 422);
        }
        $company_code = Helper::numSeries('company');
        try{
            $data = new Company;
            $data->name = $request->name;
            $data->company_code = $company_code;
            $data->url = $request->url;
            $data->phone = $request->phone;
            $data->email = $request->email;
            $data->pan_no = $request->pan_no;
            $data->gst_no = $request->gst_no;           
            $data->address = $request->address;
            $data->state_id = $request->state_id;
            $data->city = $request->city;
            $data->district = $request->district; 
            $data->pincode = $request->pincode; 
            $data->account_name = $request->account_name;
            $data->account_no = $request->account_no;
            $data->bank_name = $request->bank_name;
            $data->bank_branch = $request->bank_branch;
            $data->account_type = $request->account_type;
            $data->ifsc_code = $request->ifsc_code;
            
            $data->cancelled_cheque = Helper::convertTobase64($request->cancelled_cheque);
            $data->save();
            return response()->json(['success' => 'Data Added Successfully!!!'], 200);

        }  catch (\Exception $e) {
            return Response::json(['error' => $e->getMessage()],  500);
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Company::find($id);
        if($data){
            return Response::json(['company' => $data], 200);
        }
        else{
            return Response::json(['error' => 'Data not Found'], 404);
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
        
        $data = Company::find($id);
        if(!$data){
            return Response::json(['error' => 'no data found'],404);
        }
       
        $validation = Validator::make($request->all(), [
            'name' => 'string|min:4',
            'url' => 'string',
            'phone' => 'numeric|digits:10',
            'email' => 'email',
            'pan_no' => 'max:10|unique:companies',
            'gst_no' => 'max:15|unique:companies',
            'address' => 'string',
            'state_id' => 'numeric',
            'city' => 'string',
            'district' => 'string',
            'pincode' => 'numeric|digits:6', 
            'account_name' => 'string',
            'account_no' => 'numeric',
            'cancelled_cheque' =>'image|mimes:jpeg,png,jpg',
            'bank_name' => 'string',
            'bank_branch' => 'string',
            'account_type' => 'string',
            'ifsc_code' => 'string',
            
        ]);

        if($validation->fails()) {
            return Response::json(['error' => $validation->errors()->first()], 422);
        }      
        try{            
            $data->name = $request->name ?? $data->name;           
            $data->url = $request->url ?? $data->url;
            $data->phone = $request->phone ?? $data->phone;
            $data->email = $request->email ?? $data->email;
            $data->pan_no = $request->pan_no ?? $data->pan_no;
            $data->gst_no = $request->gst_no ?? $data->gst_no;           
            $data->address = $request->address ?? $data->address;
            $data->state_id = $request->state_id ?? $data->state_id;
            $data->city = $request->city ?? $data->city;
            $data->district = $request->district ?? $data->district; 
            $data->pincode = $request->pincode ?? $data->pincode; 
            $data->account_name = $request->account_name ?? $data->account_name;
            $data->account_no = $request->account_no ?? $data->account_no;
            $data->bank_name = $request->bank_name ?? $data->bank_name;
            $data->bank_branch = $request->bank_branch ?? $data->bank_branch;
            $data->account_type = $request->account_type ?? $data->account_type;
            $data->ifsc_code = $request->ifsc_code ?? $data->ifsc_code;
            if($request->hasFile($request->cancelled_cheque)){
                $data->cancelled_cheque = Helper::convertTobase64($request->cancelled_cheque); 
             }
            
            $data->save();
            return response()->json(['success' => 'Data Updated Successfully!!!'], 200);

        }  catch (\Exception $e) {
            return Response::json(['error' => $e->getMessage()],  500);
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
