<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash,Auth,DB;
use App\Models\User;
use App\Models\KycDetail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class KycController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->user_type == 'isCompany'){
            $data = KycDetail::with('company:id,name')->where('company_id',Auth::user()->company_id)->first();
        }
        else{
            $data = KycDetail::with('client:id,name')->where('client_id',Auth::user()->client_id)->first();
        }
        if(!$data){
            $data = [];
        }
        if(Auth::user()->user_type!='isCompany'){
            $userP = DB::table('user_permissions as u')
                ->join('pages as p', function ($join) {
                    $join->on('u.page_id', '=', 'p.id')
                        ->where('u.role_id', '=', Auth::user()->role_id)
                        ->where('u.user_id', '=', Auth::user()->id)
                        ->where('p.pagename', '=', 'invoice');
                })
                ->select('p.pagename', 'u.role_id', 'u.user_id', 'u.page_id', 'u.read', 'u.write', 'u.update', 'u.delete', 'u.print', 'u.download')
                ->first(); // Use first instead of get
        
            if((!empty($userP)) && ($userP->read ==1))
            {
                return view('common-app.card.kyc-card',compact('data','userP'));
            }
            else{
                return \Redirect::back()->with(['error' => 'No Permission!!!']);
            }
        }
        else{
            return view('common-app.card.kyc-card',compact('data'));
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
           $status="Kyc Detail updated successfully";
           if(Auth::user()->user_type == 'isCompany'){
                $kyc = KycDetail::where('company_id',Auth::user()->company_id)->first();
           }
           else{
            $kyc = KycDetail::where('client_id',Auth::user()->client_id)->first();
           }
            if(!$kyc){
                $validate = $request->validate([
                    'kyc_type' => 'required',
                    'shipment_type' => 'required',
                    'document_type1' => 'required',
                    'document_id1' => 'required',
                    'name_on_doc1' => 'required|string',
                    'doc_photo1' => 'required',
                    'document_type2' => 'required',
                    'document_id2' => 'required', 
                    'name_on_doc2' => 'required|string',
                    'doc_photo2' => 'required', 
                ]);
                $data = new KycDetail;
                if(Auth::user()->user_type == 'isCompany'){
                    $data->company_id = Auth::user()->company_id;
                }
                else{
                    $data->client_id = Auth::user()->client_id;
                }
                
                $data->created_by = Auth::user()->id;  
            }
            else{
                $data = KycDetail::find($kyc->id);
                $data->updated_by = Auth::user()->id;  
            }
         
            $data->kyc_type = $request->kyc_type;
            $data->shipment_type = $request->shipment_type;   
            $data->iec_code = $request->iec_code;
            $data->iec_branch_code = $request->iec_branch_code;
            $data->iec_photo = $request->iec_photo;
            $data->gst_certificate = $request->gst_certificate;
            $data->document_type1 = $request->document_type1;           
            $data->document_id1 = $request->document_id1;
            $data->name_on_doc1 = $request->name_on_doc1;
            $data->doc_photo1 = $request->doc_photo1;
            $data->document_type2 = $request->document_type2; 
            $data->document_id2 = $request->document_id2; 
            $data->name_on_doc2 = $request->name_on_doc2;
            $data->doc_photo2 = $request->doc_photo2;
           
            //dd($data);
            try{
                $data->save();
                return redirect()->route('kyc.index')->with(['status' => $status]);
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
        //
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

    public function update_profile(Request $request)
    {
        $data = User::find(Auth::user()->id);
        if(!$data){
            return Redirect::back()->with(['error' => 'no data found'],404);
        }
        $validators = $request->validate([
             'mobile' => 'numeric|digits:10',
             'email' => 'email|unique:users,email,'.$data->id,
        //      'password' => Password::defaults(),
        //      'confirm_password' => 'confirmed:password'
        ]);
        
            $status="User updated successfully";
            $data->mobile = $request->mobile;
            $data->email = $request->email; 
            //$data->password = Hash::make($request->password);
            $data->updated_by = Auth::user()->id;  
            try{              
                $data->save();
                return redirect()->route('kyc.index')->with(['status' => $status]);
            }
            catch(Exception $e) {
                return Redirect::back()->with(['error' => $e->getMessage()]);
            }
    }
}
