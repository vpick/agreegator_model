<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderStatus;
use Auth;

class OrderStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = OrderStatus::all();
        return view('admin-app.admin-tab.admin-tab-order-status',compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [];
        return view('admin-app.admin-card.admin-order-status-card',compact('data'));
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
            'order_status' => 'required|string',
            
        ]);
        $status="Added successfully";
        $data = New OrderStatus;
        $data->order_status = $request->order_status;
        $data->created_by = Auth::user()->id; 
        try{
            $data->save();
            return redirect()->route('order-status.index')->with(['status' => $status]);
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
        $data = OrderStatus::find($id);
       
        try {
            $data->delete();
            return \Redirect::back()->with(['status' => 'Deleted!!!']);
        } catch(\Exception $e) {
            return \Redirect::back()->with(['error' => $e->getMessages()]);
        } 

        return \Redirect::back()->with(['error' => 'Order Status not Found!!!']);
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
        //dd($id);
        $data = OrderStatus::find($id);
        if($data){       
            return view('admin-app.admin-card.admin-order-status-card', compact('data'));
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
        $data = OrderStatus::find($id);
        if(!$data){
            return Redirect::back()->with(['error' => 'no data found'],404);
        }
        $status="Status updated successfully";
        try{  
            $data->order_status = $request->order_status;
            $data->save();         
            return redirect()->route('order-status.index')->with(['status' => $status]);    
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
       
        
        
    }
}
