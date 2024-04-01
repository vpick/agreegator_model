<?php

namespace App\Http\Controllers;
use App\Models\Label;
use App\Models\LabelPrint;
use Illuminate\Http\Request;
use Auth,Crypt,DB,Session,Redirect;
use App\Models\ProductDetails;
use App\Models\Order;
use App\Models\InvoiceSetting;

class PrintController extends Controller
{
    public function create(){
        
        if(Session::has('client')){
            $clientId = session('client.id');
             $clientName = session('client.name');
        }
        else{
            $clientId = Auth::user()->client->id;
            $clientName = Auth::user()->client->name;
        }
        if(Session::has('warehouse')){
            $warehouseId = session('warehouse.id');
             $warehouseName = session('warehouse.warehouse_name');
        }
        else{
            $warehouseId = Auth::user()->warehouse->id;
            $warehouseName = Auth::user()->warehouse->warehouse_name;
        }
        $labels = Label::all();
        $data = LabelPrint::where('company_id',Auth::user()->company_id)
                            ->where('client_id',$clientId)
                            ->where('warehouse_id',$warehouseId)->first();
        if(Auth::user()->user_type!='isCompany'){
            $userP = DB::table('user_permissions as u')
                ->join('pages as p', function ($join) {
                    $join->on('u.page_id', '=', 'p.id')
                        ->where('u.role_id', '=', Auth::user()->role_id)
                        ->where('u.user_id', '=', Auth::user()->id)
                        ->where('p.pagename', '=', 'label');
                })
                ->select('p.pagename', 'u.role_id', 'u.user_id', 'u.page_id', 'u.read', 'u.write', 'u.update', 'u.delete', 'u.print', 'u.download')
                ->first(); // Use first instead of get
        
            if((!empty($userP)) && ($userP->read ==1))
            {
                return view('common-app.card.label-print-card',compact('labels','data','userP'));
            }
            else{
                return \Redirect::back()->with(['error' => 'No Permission!!!']);
            }
        }
        else{
            return view('common-app.card.label-print-card',compact('labels','data'));
        }
    }
    public function store(Request $request){
        $labels = Label::all();
        if(Session::has('client')){
            $clientId = session('client.id');
             $clientName = session('client.name');
        }
        else{
            $clientId = Auth::user()->client->id;
            $clientName = Auth::user()->client->name;
        }
        if(Session::has('warehouse')){
            $warehouseId = session('warehouse.id');
             $warehouseName = session('warehouse.warehouse_name');
        }
        else{
            $warehouseId = Auth::user()->warehouse->id;
            $warehouseName = Auth::user()->warehouse->warehouse_name;
        }
        $data = LabelPrint::where('company_id',Auth::user()->company_id)
                             ->where('client_id',$clientId)
                            ->where('warehouse_id',$warehouseId)->first();
        if($data){
            $labelPrint = LabelPrint::find($data->id);             
        }
        else{
            $labelPrint = New LabelPrint;
        }            
        $labelPrint->company_id = Auth::user()->company_id;
        $labelPrint->client_id = $clientId;
        $labelPrint->warehouse_id = $warehouseId;
        $labelPrint->label_id = $request->label_id; 
        try{
            $labelPrint->save();
            return redirect()->route('label.print', compact('labels', 'data'))->with(['status' => 'Label set successfully!']);

        }
        catch(Exception $e) {
            return Redirect::back()->with(['error' => $e->getMessage()]);
        }
        
    }
    public function invoicePrint($invoice_no){
        $invoice_no = Crypt::decrypt($invoice_no);
        $data = DB::table('orders')
        ->leftJoin('warehouses', 'orders.warehouse_code', '=', 'warehouses.warehouse_code')
        ->leftJoin('companies', 'warehouses.company_id', '=', 'companies.id')
        ->where('orders.invoice_no',$invoice_no)
        ->get();
        if(!$data){
            return Redirect::back()->with(['error' => 'no data found'],404);
        }
        $products = ProductDetails::where('order_id',$data[0]->id)->get();
        if(!Session::has('client')){
			$client_id = Auth::user()->client->id;
		}
		else{
			$client_id = session('client.id');
		}
        $setting = InvoiceSetting::where('client_id',$client_id)->first();
        if(!$setting){
            return Redirect::back()->with(['error' => 'Check invoice setting']);
        }
        return view('common-app.card.invoice-print-card',compact('data','products','setting'));
    }
}
