<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Weight;
use Auth,Crypt;


class WeightController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $weights = Weight::paginate(10);
        return view('admin-app.admin-tab.admin-tab-weight', compact('weights'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [];
        return view('admin-app.admin-card.admin-weight-card',compact('data'));
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
            'min_weight' => 'required|numeric',  
            'operator' => 'required|string',
            'max_weight' => 'numeric|nullable',  
            'description' => 'required|string',  
        ]);
        $status="Added successfully";
        try{  
           
            $data = new Weight;
            $data->min = $request->min_weight;
            $data->operator = $request->operator;
            $data->max = $request->max_weight ?? '100000';
            if($data->operator == 'between'){
                $weight_range = $data->min.'-'.$data->max;
            }
            else  if($data->operator == 'above'){
                $weight_range = 'above'.$data->min;
            }
            $data->weight_range =  $weight_range;
            $data->description = $request->description;
            $data->created_by = Auth::user()->id;                         
            $data->save();
            return redirect()->route('weight-range.index')->with(['status' => $status]);
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
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = Crypt::decrypt($id);
        //dd($id);
        $data = Weight::find($id);
        if($data){       
           return view('admin-app.admin-card.admin-weight-card',compact('data'));
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
        try{  
            $data = Weight::find($id);
            if(!$data){       
               return Redirect::back()->with(['error' => 'Data not found']);
            } 
            $status="Updated successfully";           
            $data->min = $request->min_weight;
            $data->operator = $request->operator;
            $data->max = $request->max_weight ?? '100000';
            if($data->operator == 'between'){
                $weight_range = $data->min.'-'.$data->max;
            }
            else if($data->operator == 'above'){
                $weight_range = 'above'.$data->min;
            }
            $data->weight_range =  $weight_range;
            $data->description = $request->description;
            $data->updated_by = Auth::user()->id;                         
            $data->save();
            return redirect()->route('weight-range.index')->with(['status' => $status]);
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
