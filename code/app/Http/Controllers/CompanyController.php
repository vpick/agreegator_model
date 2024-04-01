<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Helpers\Helper;
use Illuminate\Support\Facades\Redirect;
use Response,Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Models\Client;
use App\Models\User;
use App\Models\Company;
use App\Models\State;
use App\Models\Warehouse;

class CompanyController extends Controller
{
    protected $company_code;
    public function __construct()
    {
        //$this->company_code = Helper::numSeries('company');
         $data = Company::orderBy('id','desc')->first();
            if(!empty($data)){            
                $companyCode =$data->company_code;
                $str = explode('_',$companyCode);
                $num = $str[1]+1;
                $numSeries = Str::of($str[0])->append('_'.$num);
                $this->company_code = $numSeries;
            }
            else{
                $prefix ='com_';
                $start = '100';
                $numSeries = $prefix.$start;
                $this->company_code = $numSeries;
            }
        //dd($this->company_code);
    } 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        
        $companies = Company::orderby('id', 'desc');
        $company_name = $request->input('company_name');
        if($company_name){
            $companies = $companies->where('name',$company_name);
        }
        $company_code = $request->input('company_code');
        if($company_code){
            $companies = $companies->where('company_code',$company_code);
        }
        $companies = $companies->paginate(10);
        if(!$companies){
            $companies = [];
        }
        return view('admin-app.admin-tab.admin-tab-company', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $states = State::all();
        $data = [];
        return view('admin-app.admin-card.admin-company-card', compact('states','data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
        $request->validate([
            'name' => 'required|string|min:4|unique:companies',
            'phone' => 'required|numeric|digits:10',
            'email' => 'required|email|unique:companies',
            'pan_no' => 'required|max:10|unique:companies',
            'gst_no' => 'max:15|unique:companies',
            'address' => 'required|string',
            'state_id' => 'required|numeric',
            'city' => 'required|string',
            'district' => 'required|string',
            'pincode' => 'required|numeric|digits:6', 
            'account_name' => 'required|string',
            'account_no' => 'required|numeric',
            'cancelled_cheque' =>'required',
            'company_logo' =>'required',
            'bank_name' => 'required|string',
            'bank_branch' => 'required|string',
            'account_type' => 'required|string',
            'ifsc_code' => 'required|string',
        ]);

        $status="Company created successfully";
            $data = new Company;
            $data->name = $request->name;
            $data->company_code = $this->company_code;   
            $data->url = $request->url;
            $data->phone = $request->phone;
            $data->email = $request->email;
            $data->pan_no = strtoupper($request->pan_no);
            $data->gst_no = strtoupper($request->gst_no);           
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
            $data->ifsc_code = strtoupper($request->ifsc_code);                 
            $data->cancelled_cheque =$request->cancelled_cheque;
            $data->company_logo =$request->company_logo;
            $data->created_by = Auth::user()->id;  
            try{
                $data->save();
                return redirect()->route('company.index')->with(['status' => $status]);
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
       
        $data = Company::find($id);
        if($data->status == '1'){
            $data->status = '0';
        }
        else {
            $data->status = '1';
        }
        
        try {
            $data->save();
            return \Redirect::back()->with(['status' => 'Company status updated!!!']);
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
        $data = Company::find($id);
        $states = State::all();
        if($data){       
            return view('admin-app.admin-card.admin-company-card', compact('states','data'));
        }
        else{
            return Redirect::back()->with(['error' => $e->getMessage()]);
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
            return Redirect::back()->with(['error' => 'no data found'],404);
        }
       
        $validation = Validator::make($request->all(), [
            'name' => 'string|min:4|unique:companies,name,'.$id,
            'url' => 'string',
            'phone' => 'numeric|digits:10',
            'email' => 'email|unique:companies,email,'.$id,
            'pan_no' => 'max:10|unique:companies,pan_no,'.$id,
            'gst_no' => 'max:15|unique:companies,gst_no,'.$id,
            'address' => 'string',
            'state_id' => 'numeric',
            'city' => 'string',
            'district' => 'string',
            'pincode' => 'numeric|digits:6', 
            'account_name' => 'string',
            'account_no' => 'numeric',
            'bank_name' => 'string',
            'bank_branch' => 'string',
            'account_type' => 'string',
            'ifsc_code' => 'string',
            
        ]);

        $status="Company updated successfully";     
            $data->name = $request->name;           
            $data->url = $request->url;
            $data->phone = $request->phone;
            $data->email = $request->email;
            $data->pan_no = strtoupper($request->pan_no);
            $data->gst_no = strtoupper($request->gst_no);           
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
            $data->ifsc_code = strtoupper($request->ifsc_code);
            $data->cancelled_cheque = $request->cancelled_cheque ?? $data->cancelled_cheque;
            $data->company_logo = $request->company_logo ?? $data->company_logo;
            $data->updated_by = Auth::user()->id;  
            try{
                $data->save();
                return redirect()->route('company.index')->with(['status' => $status]);
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
