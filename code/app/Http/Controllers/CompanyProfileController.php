<?php

namespace App\Http\Controllers;

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

class CompanyProfileController extends Controller
{
    public function company_profile(){
        $states = State::all();
        $data = Company::find(Auth::user()->company_id);
        if(!$data){
            return Redirect::back()->with(['error' => 'no data found'],404);
        }
        return view('company-app.card.company-profile',compact('data','states'));
    }
    public function com_profile_update(Request $request, $id){
        $data = Company::find(Auth::user()->company_id);
        if(!$data){
            return Redirect::back()->with(['error' => 'no data found'],404);
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
            $data->pan_no = $request->pan_no;
            $data->gst_no = $request->gst_no;           
            $data->address = $request->address1;
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
            $data->cancelled_cheque = $request->cancelled_cheque ?? $data->cancelled_cheque;
            $data->company_logo = $request->company_logo ?? $data->company_logo;
            $data->updated_by = Auth::user()->id;  
            try{
                $data->save();
                return redirect()->route('company.profile')->with(['status' => $status]);
            }
            catch(Exception $e) {
                return Redirect::back()->with(['error' => $e->getMessage()]);
            } 
    }
}
