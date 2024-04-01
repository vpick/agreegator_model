<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvoiceSetting;
use Auth,DB,Session;

class InvoiceSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
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
        $data = InvoiceSetting::where('client_id',$clientId)->first();
        if(!$data){
            $data = [];
        }
        if(Auth::user()->user_type != 'isCompany')
        {
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
                return view('client-app.invoice-settings-card',compact('data','userP'));
            }
            else{
                return \Redirect::back()->with(['error' => 'No Permission!!!']);
            }
        }
        else
        {
            return view('client-app.invoice-settings-card',compact('data'));
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
        if(Session::has('client')){
            $clientId = session('client.id');
             $clientName = session('client.name');
        }
        else{
            $clientId = Auth::user()->client->id;
            $clientName = Auth::user()->client->name;
        }
        
        try{         
            $inv =  InvoiceSetting::where('client_id',$clientId)->first();         
            if(!$inv){
                $status="Added successfully";
                $data = new InvoiceSetting;
                $data->client_id = $clientId;
                $data->created_by = Auth::user()->id; 
            } 
            else{
                $status="Updated successfully";
                $data = InvoiceSetting::find($inv->id);                 
                $data->updated_by = Auth::user()->id; 
            }
            
            $data->company_name_toggle = $request->company_name ?? $data->company_name_toggle;           
            $data->invoice_prefix = $request->invoice_prefix ?? $data->invoice_prefix;
            $data->logo = $request->company_logo ?? $data->logo;
            $data->logo_toggle = $request->logo_toggle ?? $data->logo_toggle;
            $data->signature = $request->signature ?? $data->signature;
            $data->signature_toggle = $request->signature_toggle ?? $data->signature_toggle;
            $columnNames = $request->column_name ?? $columnNames;
            $columnValues = $request->column_value ?? $columnValues;
            $formData = [];
            foreach ($columnNames as $index => $columnName) {
                $formData[] = [
                    'column_name' => $columnName,
                    'column_value' => $columnValues[$index],
                ];
            }
            $data->customize_field = json_encode(['customize_fields' => $formData]);
            $data->page_size = $request->page_size ?? $data->page_size;     
            $data->save();           
            return redirect()->route('invoice-settings.create')->with(['status' => $status]);
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
}
