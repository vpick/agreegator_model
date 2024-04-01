<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rate;
use App\Models\AppLogistics;
use App\Models\ShipmentType;
use App\Models\Zone;
use App\Models\Region;
use App\Models\RateB2b;
use App\Models\LogisticsMapping;
use Illuminate\Support\Facades\Validator;
use Auth,Redirect,Crypt,Response,Session;
class RateCardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    
        $user_type='';
        $clientID = 0;
        $companyId = 0;
       
       if(Auth::user()->user_type == 'isCompany')
       {
           if(Session::has('client'))
            { 
                $clientID = session('client.id');
                $companyId = Auth::user()->company->id;
                $zone_type="isClient";
            }
            else
            {
                $companyId = Auth::user()->company->id;
                $clientID = Auth::user()->client->id;
                $zone_type = "isCompany";
            }
        }
        else
        {
            
            $clientID = Auth::user()->client->id;
            $companyId = Auth::user()->company->id;
            $zone_type="isClient";
        }
        $zones = Zone::where('zone_type',$zone_type)->where('company_id',$companyId)->where('client_id',$clientID)->get();
       
        $rates = Rate::orderby('courier')
                    ->orderBy('aggregator')
                    ->orderBy('min_weight')
                    ->paginate(5);
          
	    
        $types = AppLogistics::select('logistics_type')->distinct()->get();
        $courierList = LogisticsMapping::with('courier')->where('base_of', 'isClient')
                    ->where('client_id', $clientID)
                    ->get();
        
        $shipmentTypes = ShipmentType::all();
        if(!$rates)
        {
            return Redirect::back()->with(['error' => 'Data not found']);
        }
        if(Auth::user()->user_type == 'isSystem')
        {
            return view('admin-app.admin-tab.admin-tab-rate-card', compact('rates','zones','types','shipmentTypes','courierList'));
        }
        else
        {
            return view('company-app.table-list.company-tab-rate-list', compact('rates','zones','types','shipmentTypes','courierList'));
        }
    
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $zone_type='';
        $clientId =0;
        $companyId=0;
        if(Auth::user()->user_type == 'isCompany')
        {
            if(Session::has('client'))
            {
                $clientId = session('client.id');
                $zone_type="isClient";
                $companyId = Auth::user()->company->id;
            }
            else
            {
                $clientId = Auth::user()->client->id;
                $companyId = Auth::user()->company->id;
                $zone_type="isCompany";
            }
        }
        else
        {
            $clientId = Auth::user()->client->id;
            $companyId = Auth::user()->company->id;
            $zone_type="isClient";
        }
        $zones = Zone::where('zone_type',$zone_type)->where('company_id',$companyId)->where('client_id',$clientId)->get();
        $types = AppLogistics::select('logistics_type')->distinct()->get();
        $shipmentTypes = ShipmentType::all();
        
        $data = [];
        if(Auth::user()->user_type == 'isSystem')
        {
            return view('admin-app.admin-card.admin-rate-card', compact('data','types','shipmentTypes','zones'));
        }
        else{
            return view('company-app.card.company-rate-card', compact('data','types','shipmentTypes','zones'));
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
        $zone_type='';
        $clientId =0;
        $companyId=0;
        if(Auth::user()->user_type == 'isCompany')
        {
            if(Session::has('client'))
            {
                $clientId = session('client.id');
                $zone_type="isClient";
                $companyId = Auth::user()->company->id;
            }
            else
            {
                $clientId = Auth::user()->client->id;
                $companyId = Auth::user()->company->id;
                $zone_type="isCompany";
            }
        }
        else
        {
            $clientId = Auth::user()->client->id;
            $companyId = Auth::user()->company->id;
            $zone_type="isClient";
        }
       #dd($request->all());
        $validate = $request->validate([
            
            'logistics_partner' => 'required_if:logistics_type,aggrigator|nullable',
            'courier_id' => 'required',
            'courier_name' => 'required|unique:rates',
            'shipment_type' => 'required',
            'min_weight' => 'required|numeric',
            'additional_weight' => 'required|numeric',
            'cod_charge' => 'required|numeric',
            'cod_percent' => 'required|numeric',
        ]);        
        $rateCount = Rate::where('logistics_type',$request->logistics_type)
                        ->where('aggregator',$request->logistics_partner)
                        ->where('courier',$request->courier_id)
                        ->where('shipment_mode',$request->shipment_type)
                        ->where('min_weight',$request->min_weight)->count();
        if($rateCount>0){
            return redirect()->route('rate-card.create')->with(['warning' => 'Record already exist on this courier and shipment mode']);
        }
        $status="Added successfully";
        try{  
            $data = new Rate;
            $data->client_id = $clientId;
            $data->logistics_type = $request->logistics_type;
            $data->aggregator = $request->logistics_partner;
            #dd($data->aggregator);
            $data->courier = $request->courier_id;       
            $data->courier_name = $request->courier_name; 
            $data->shipment_mode = $request->shipment_type;
            $data->min_weight = $request->min_weight;
            $data->additional_weight = $request->additional_weight;
            $data->cod = $request->cod_charge;
            $data->cod_percent = $request->cod_percent;
            $data->contract_type="company-client";
            $forward_rates = $request->forward_rate;    
            $forward_additional_rates = $request->forward_additional_rate;      
            $reverse_rates = $request->reverse_rate;      
            $dto_rates = $request->dto_rate;      
            $zones = Zone::select('zone_code')->where('zone_type',$zone_type)->where('company_id',$companyId)->where('client_id',$clientId)->get();
            // forward data
            $forwardData = ['forward' => []];
            $forward_additionalData = ['forward_additional' => []];
            $reverseData = ['reverse' => []]; 
            $dtoData = ['dto' => []]; 
            foreach ($zones as $key => $zone) {
                // Check if the zone code exists in the request
                $forwardData['forward'][$zone->zone_code] = $forward_rates[$key] ?? '0.00';
                $forward_additionalData['forward_additional'][$zone->zone_code] = $forward_additional_rates[$key] ?? '0.00';
                if($data->aggregator == 'NimbusPost')
                { 
                    $reverseData['reverse'][$zone->zone_code] = ($forward_rates[$key] *0.80) ?? '0.00';
                    $dtoData['dto'][$zone->zone_code] = ($forward_rates[$key] *1.5) ?? '0.00';
                }
                else
                {
                    $reverseData['reverse'][$zone->zone_code] = $reverse_rates[$key] ?? '0.00';
                    $dtoData['dto'][$zone->zone_code] = $dto_rates[$key] ?? '0.00';
                }
                
            } 
              
            // Encode JSON once after the loop
            $data->forward = json_encode($forwardData);
            $data->forward_additional = json_encode($forward_additionalData);
            $data->reverse = json_encode($reverseData);
            $data->dto = json_encode($dtoData);
            $data->created_by = Auth::user()->id; 
                 
            $data->save();
           
            return redirect()->route('rate-card.index')->with(['status' => $status]);  
        
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
    public function b2CRateContractDownloadSample($dsp)
    {
        if(Auth::user()->user_type == 'isCompany')
        {
            if(Session::has('client'))
            {
                $clientId = session('client.id');
                $zone_type="isClient";
                $companyId = Auth::user()->company->id;
            }
            else
            {
                $clientId = Auth::user()->client->id;
                $companyId = Auth::user()->company->id;
                $zone_type="isCompany";
            }
        }
        else
        {
            $clientId = Auth::user()->client->id;
            $companyId = Auth::user()->company->id;
            $zone_type="isClient";
        }
        $zones = Zone::select('zone_code')->where('zone_type',$zone_type)->where('company_id',$companyId)->where('client_id',$clientId)->get();
        $zoneArray = $zones->pluck('zone_code')->toArray();
        $zoneData = array_map(function ($zone) {
                    return $zone.'*';
                }, $zoneArray);

        $zoneString = implode(',', $zoneData);
        
        $csvData = "logistic_type*,aggregator,courier*,courier_name*,shipment_mode*,min_weight*,additional_weight*,cod_charge*,cod_percent*,consignment_type*,$zoneString";
        return Response::make($csvData, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=ratecontract-sample.csv',
        ]);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function edit($id)
    // {
      
    //     $id = \Crypt::decrypt($id);      
    //     $data = Rate::find($id);
    //     $couriers = AppLogistics::where('logistics_status','Active')->get();
    //     $shipmentTypes = ShipmentType::all();
    //     $zones = Zone::all();
    //     if($data){       
    //        return view('admin-app.admin-card.admin-rate-card',compact('data','couriers','shipmentTypes','zones'));
    //     }
    //     else{
    //         return Redirect::back()->with(['error' => 'Data not found']);
    //     }
    // }
    
    public function edit_card($id)
    {
      
        $zone_type='';
        $clientId =0;
        $companyId=0;
        if(Auth::user()->user_type == 'isCompany')
        {
            if(Session::has('client'))
            {
                $clientId = session('client.id');
                $zone_type="isClient";
                $companyId = Auth::user()->company->id;
            }
            else
            {
                $clientId = Auth::user()->client->id;
                $companyId = Auth::user()->company->id;
                $zone_type="isCompany";
            }
        }
        else
        {
            $clientId = Auth::user()->client->id;
            $companyId = Auth::user()->company->id;
            $zone_type="isClient";
        }
        $zones = Zone::where('zone_type',$zone_type)->where('company_id',$companyId)->where('client_id',$clientId)->get();
        $id = \Crypt::decrypt($id);
        #$mode = \Crypt::decrypt($mode);
        $data = Rate::find($id);
        $aggregators = AppLogistics::select('logistics_name')->where('logistics_type', 'Aggrigator')->where('logistics_status','Active')->get();
        $couriers = AppLogistics::select('logistics_name')->where('logistics_type','Currior')->where('logistics_status','Active')->get();  
        $types = AppLogistics::select('logistics_type')->distinct()->get();
        $shipmentTypes = ShipmentType::all();
        if($data)
        {       
            if(Auth::user()->user_type="isSystem")
            {
                return view('admin-app.admin-card.admin-rate-card',compact('data','types','shipmentTypes','zones','aggregators','couriers'));
            }
           else
           {
                return view('company-app.card.company-rate-card',compact('data','types','shipmentTypes','zones','aggregators','couriers'));
           }
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
        $zone_type='';
        $clientId =0;
        $companyId=0;
        if(Auth::user()->user_type == 'isCompany')
        {
            if(Session::has('client'))
            {
                $clientId = session('client.id');
                $zone_type="isClient";
                $companyId = Auth::user()->company->id;
            }
            else
            {
                $clientId = Auth::user()->client->id;
                $companyId = Auth::user()->company->id;
                $zone_type="isCompany";
            }
        }
        else
        {
            $clientId = Auth::user()->client->id;
            $companyId = Auth::user()->company->id;
            $zone_type="isClient";
        }
        $validate = $request->validate([
            'logistics_type' => 'required',
            'logistics_partner' => 'required_if:logistics_type,aggrigator|nullable',
            'courier_id' => 'required',
            'courier_name' => 'required|unique:rates,courier_name,'.$id,
            'shipment_type' => 'required',
            'min_weight' => 'required|numeric',
            'additional_weight' => 'required|numeric',
            'cod_charge' => 'required|numeric',
            'cod_percent' => 'required|numeric',
        ]);       
        $data = Rate::find($id);
        $rateCount = Rate::where('logistics_type',$request->logistics_type)
                        ->where('aggregator',$request->logistics_partner)
                        ->where('courier',$request->courier_id)
                        ->where('shipment_mode',$request->shipment_type)
                        ->where('min_weight',$request->min_weight)
                        ->where('id','!=',$id)->count();
        if($rateCount>0){
            return redirect()->route('rate-card.create')->with(['warning' => 'Record already exist on this courier and shipment mode']);
        }
        $status="Updated successfully";
        try{  
           
            $data->logistics_type = $request->logistics_type;
            if($data->logistics_type == 'Aggrigator'){
                $data->aggregator = $request->logistics_partner;
            }
            else{
                $data->aggregator = '';
            }
            #dd($data->aggregator);
            $data->courier = $request->courier_id;   
            $data->courier_name = $request->courier_name; 
            $data->shipment_mode = $request->shipment_type;
            $data->min_weight = $request->min_weight;
            $data->additional_weight = $request->additional_weight;
            $data->cod = $request->cod_charge;
            $data->cod_percent = $request->cod_percent;
            $forward_rates = $request->forward_rate;    
            $forward_additional_rates = $request->forward_additional_rate;      
            $reverse_rates = $request->reverse_rate;      
            $dto_rates = $request->dto_rate;      
            $zones = Zone::select('zone_code')->where('zone_type',$zone_type)->where('company_id',$companyId)->where('client_id',$clientId)->get();
            // forward data
            $forwardData = ['forward' => []];
            $forward_additionalData = ['forward_additional' => []];
            $reverseData = ['reverse' => []]; 
            $dtoData = ['dto' => []]; 
            foreach ($zones as $key => $zone) {
                // Check if the zone code exists in the request
                $forwardData['forward'][$zone->zone_code] = $forward_rates[$key] ?? '0.00';
                $forward_additionalData['forward_additional'][$zone->zone_code] = $forward_additional_rates[$key] ?? '0.00';
                if($data->aggregator == 'NimbusPost'){ 
                    $reverseData['reverse'][$zone->zone_code] = ($forward_rates[$key] *0.80) ?? '0.00';
                    $dtoData['dto'][$zone->zone_code] = ($forward_rates[$key] *1.5) ?? '0.00';
                }
                else{
                    $reverseData['reverse'][$zone->zone_code] = $reverse_rates[$key] ?? '0.00';
                    $dtoData['dto'][$zone->zone_code] = $dto_rates[$key] ?? '0.00';
                }
                
            } 
              
            // Encode JSON once after the loop
            $data->forward = json_encode($forwardData);
            $data->forward_additional = json_encode($forward_additionalData);
            $data->reverse = json_encode($reverseData);
            $data->dto = json_encode($dtoData);
            $data->created_by = Auth::user()->id; 
             #dd($data);    
            $data->save();
           
            return redirect()->route('rate-card.index')->with(['status' => $status]);  
        
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
     public function import_b2c_new(Request $request)
     {
        if($request->hasFile('importFile')) 
		{ 
		    try
            {
                $file = $request->file('importFile');
                $filePath = $file->getRealPath();
                $csvData = array_map('str_getcsv', file($filePath));
                $header = $csvData[0];
                $rules = 
    			[
    				'logistics_type' => 'required_if:logistics_type,aggrigator|nullable',
                    'courier' => 'required|string',
                    'courier_name' => 'required|string',
                    'shipment_type' => 'required|string',
                    'min_weight' => 'required|numeric|gt:0',
                    'additional_weight' => 'required|numeric|gt:0',
                    'cod_charge' => 'required|numeric',
                    'cod_percent' => 'required|numeric',
                    'consignment_type' => 'required|string',
    				
    			];
    			$csvHeaderData = array_shift($csvData); // Assuming the first row contains column names
                $csvHeader = str_replace('*', '', $csvHeaderData);
                $validator = Validator::make($csvData, $rules);
                // Check if validation fails
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                
                
                $forwardData = ['forward' => []];
                $forward_additionalData = ['forward_additional' => []];
                $reverseData = ['reverse' => []]; 
                $dtoData = ['dto' => []]; 
                for ($i = 1; $i < count($csvData); $i++) {
                    $arr= $csvData[$i];
                    $row=array_combine($header,$arr);
                    $filterCriteria = [
                        'logistic_type' => $row['logistics_type'],
                        'aggregator' => $row['aggregator'],
                        'courier' => $row['courier'],             // Replace with your desired courier
                        'courier_name' => $row['courier_name'],   // Replace with your desired courier name
                        'shipment_mode' => $row['shipment_mode'],    // Replace with your desired shipment mode
                        'min_weight' => $row['min_weight'],             // Replace with your desired min weight
                    ];
                    
                    // Check if the row matches the filter criteria
                    if (
                        $row['courier'] == $filterCriteria['courier'] &&
                        $row['courier_name'] == $filterCriteria['courier_name'] &&
                        $row['shipment_mode'] == $filterCriteria['shipment_mode'] &&
                        $row['min_weight'] == $filterCriteria['min_weight']
                    )
                    {
                        $data = new Rate;
                        $data->client_id = Auth::user()->id;
                        $data->logistics_type = $row['logistic_type'];
                        $data->aggregator = $row['aggregator'];
                        #dd($data->aggregator);
                        $data->courier = $row['courier'];       
                        $data->courier_name = $row['courier_name']; 
                        $data->shipment_mode = $row['shipment_mode'];
                        $data->min_weight = $row['min_weight'];
                        $data->additional_weight = $row['additional_weight'];
                        $data->cod = $row['cod_charge'];
                        $data->cod_percent = $row['cod_percent'];
                        $zones = Zone::select('zone_code')->where('dsp','3')->get();
                        // forward data
                        
                        if ($row['consignment_type'] == 'forward') {
                            foreach ($zones as $key => $zone) {
                                $forwardData['forward'][$zone->zone_code] = $row[$zone->zone_code] ?? '0.00';
                            }
                        }
                    
                        if ($row['consignment_type'] == 'forward_additional') {
                            foreach ($zones as $key => $zone) {
                                $forward_additionalData['forward_additional'][$zone->zone_code] = $row[$zone->zone_code] ?? '0.00';
                            }
                        }
                    }
                    $data->forward = json_encode($forwardData);
                    $data->forward_additional = json_encode($forward_additionalData);
                    $data->reverse = json_encode($reverseData);
                    $data->dto = json_encode($dtoData);
                    $data->created_by = Auth::user()->id;
                }
                $filteredData[] = $data;
               
                $data->save();
                return redirect()->route('rate-card.index')->with(['status' => 'uploaded successfully !']);
            }
            catch(Exception $e) {
                return Redirect::back()->with(['error' => $e->getMessage()]);
            } 
		}
		else{
		    #return redirect()->back()->with('status', 'Please check file should be csv');
			$dbErrors[] = 'Please check file should be csv';
			return \Redirect::back()->with(['error' =>implode('<br>', $dbErrors)]);
		}
     }
     public function import_b2c(Request $request)
     {
         if(Auth::user()->user_type == 'isCompany')
        {
            if(Session::has('client'))
            {
                $clientId = session('client.id');
                $zone_type="isClient";
                $companyId = Auth::user()->company->id;
            }
            else
            {
                $clientId = Auth::user()->client->id;
                $companyId = Auth::user()->company->id;
                $zone_type="isCompany";
            }
        }
        else
        {
            $clientId = Auth::user()->client->id;
            $companyId = Auth::user()->company->id;
            $zone_type="isClient";
        }
        if($request->hasFile('importFile')) 
		{ 
		    try
            {
                $file = $request->file('importFile');
                $filePath = $file->getRealPath();
                $csvData = array_map('str_getcsv', file($filePath));
               
                $headers = $csvData[0];
                $dsp_name = $csvData[1][2];
                // $zones = Zone::select('zone_code')
                //     ->where('dsp', function ($query) use ($dsp_name) {
                //         $query->select('id')
                //             ->from('app_logistics')
                //             ->where('logistics_name', $dsp_name)
                //             ->limit(1); // Assuming you want only one result
                //     })
                //     ->where('zone_type',$zone_type)->where('company_id',$companyId)->where('client_id',$clientId)
                //     ->get();
                $zones = Zone::select('zone_code')
    
                ->where('zone_type', $zone_type)
                ->where('company_id', $companyId)
                ->where('client_id', $clientId)
                ->get();

#dd($zones);

                // if(count($zones)== 0)
                // {
                //     return Redirect::back()->with(['error' => 'No Zone found on this DSP']);
                // }
                $zoneCodes = $zones->pluck('zone_code')->toArray();
                for ($i = 0; $i < count($csvData); $i++) 
                {
                    $row = $csvData[$i];
                    $rowData = array();
                    $manData = array();
                    // Loop through the headers and create an associative array
                    foreach ($headers as $index => $header) {
                        // Remove asterisk (*) from the header
                        $cleanHeader = rtrim($header, '*');
                        $rowData[$cleanHeader] = $row[$index];
                        $manData[$header] = $row[$index];
                    }
                    $removeAsterisk = false;
                    // Output the modified array
                    foreach ($manData as $rateKey => $rateValue) {
                         
                        // Check if the key ends with '*'
                        if (substr($rateKey, -1) === '*' && ($rateValue === '' || $rateValue === null )) {
                            // Add an error message to the array
                            $errorMessages[] = 'Error: ' . $rateKey . ' is a mandatory field and cannot be blank.';
                            
                        }
                    
                    }
                
                    foreach ($rowData as $rateKey => $rateValue) {
                        if (strpos($rateKey, 'min_weight') !== false && $rateValue === '0') {
                            // Add an error message to the array
                            $errorMessages[] = 'Error: Min weight cannot be zero.';
                        }
                        // Check if the key contains "product_quantity" and the value is 0
                        if (strpos($rateKey, 'additional_weight') !== false && $rateValue === '0') {
                            // Add an error message to the array
                            $errorMessages[] = 'Error: Additional Weight cannot be zero.';
                        }
                        if (strpos($rateKey, 'logistic_type') !== false && $rateValue === 'Aggregator' ) {
                            // Add an error message to the array
                            $errorMessages[] = 'Error: Aggregator column cannot be blank.';
                        }
                        foreach ($zoneCodes as $zoneCode) {
                            
                            if (strpos($rateKey, $zoneCode) !== false && $rateValue === '0') {
                                // Add an error message to the array
                                $errorMessages[] = 'Error: Zone Value cannot be zero.';
                            }
                        }
                        
                    }
                    
                    // If there are any error messages, redirect back with the messages
                    if (!empty($errorMessages)) {
                        return redirect()->back()->withErrors($errorMessages);
                    }
           
            }
            
            // If there are any error messages, return them
            if (!empty($errorMessages)) {
                return redirect()->back()->withErrors($errorMessages);
            }
                $rules = [
                        'logistic_type' => 'required_if:logistics_type,aggregator|nullable|string',
                        'courier' => 'string',
                        'courier_name' => 'string',
                        'shipment_mode' => 'string',
                        'min_weight' => 'numeric|gt:0',
                        'additional_weight' => 'numeric|gt:0',
                        'cod_charge' => 'numeric',
                        'cod_percent' => 'numeric',
                        'consignment_type' => 'string',
                    ];

                    // Dynamically add rules for each zone code
                    foreach ($zoneCodes as $zoneCode) {
                        $rules[$zoneCode] = 'numeric';
                    }
                    $csvHeaderData = array_shift($csvData); // Assuming the first row contains column names
                    $csvHeader = str_replace('*', '', $csvHeaderData);
                    
                    // Check if all required columns are present
                    $missingColumns = array_diff(array_keys($rules), $csvHeader);
                    if (!empty($missingColumns)) {
                        $missingColumnsMessage = 'Missing columns: ' . implode(', ', $missingColumns);
                        return redirect()->back()->withErrors([$missingColumnsMessage])->withInput();
                    }
                    
                    // Validate the CSV header against the rules
                    $validator = Validator::make($csvHeader, $rules);
                    
                    // Check if validation fails
                    if ($validator->fails()) {
                        return redirect()->back()->withErrors($validator)->withInput();
                    }
                $failedRows = [];
    			foreach ($csvData as $row) 
    			{	
    			    $com = array_combine($csvHeader, $row);
    			    $rateCount = Rate::where('courier',$com['courier'])
                        ->where('shipment_mode',$com['shipment_mode'])
                        ->where('min_weight',$com['min_weight'])
                        ->count();
                    if($rateCount>0){
                        return redirect()->back()->with(['warning' => 'Record already exist on this courier and shipment mode and min weight']);
                    }
    				$validator = Validator::make($com, $rules);
    				if ($validator->fails()) {
    					$failedRows[] = [
    						'data' => $row,
    						'errors' => $validator->errors()->all(),
    					];
    				}
    				if (!empty($failedRows)) 
    				{
    					$failedRowsMessages = [];
    					foreach ($failedRows as $failedRow) 
    					{
    						$rowErrors = implode('<br>', $failedRow['errors']);
    						$failedRowsMessages[] = "Row: " . implode(', ', $failedRow['data']) . "<br>" . $rowErrors;
    					}
    					return \Redirect::back()->with(['error' => implode('<br>', $failedRowsMessages)]);
    				} 
    				else 
    				{
                        $forwardData = ['forward' => []];
                        $forward_additionalData = ['forward_additional' => []];
                        $reverseData = ['reverse' => []]; 
                        $dtoData = ['dto' => []]; 
                        for ($i = 0; $i < count($csvData); $i++) 
                        {   
                            $arr= $csvData[$i];
                            $row=array_combine($csvHeader,$arr);
                            $data = new Rate;
                            $data->client_id = $clientId; //Auth::user()->id;
                            $data->logistics_type = $row['logistic_type'];
                            $data->aggregator = $row['aggregator'];
                            $data->courier = $row['courier'];       
                            $data->courier_name = $row['courier_name']; 
                            $data->shipment_mode = $row['shipment_mode'];
                            $data->min_weight = $row['min_weight'];
                            $data->additional_weight = $row['additional_weight'];
                            $data->cod = $row['cod_charge'];
                            $data->cod_percent = $row['cod_percent'];
                            $data->contract_type="company-client";
                       
                            if($row['consignment_type'] == 'forward') {
                                foreach ($zones as $key => $zone) {
                                    $forwardData['forward'][$zone->zone_code] = $row[$zone->zone_code] ?? '0.00';
                                }
                            }
                            
                            $data->forward =json_encode($forwardData);
                            $data->forward_additional =0;
                            $data->reverse =0;
                            $data->dto =0;
                            // forward data
                            
                            $rateData = Rate::where('courier_name',$data['courier_name'])
                                          ->where('shipment_mode',$data['shipment_mode'])
                                          ->where('min_weight',$data['min_weight'])
                                          ->first();
                            
                            if($rateData)
                            {
                                $rate =Rate::find($rateData->id);
                                if ($row['consignment_type'] == 'forward') {
                                    foreach ($zones as $key => $zone) {
                                        $forwardData['forward'][$zone->zone_code] = $row[$zone->zone_code] ?? '0.00';
                                    }
                                }
                            
                                if ($row['consignment_type'] == 'forward_additional') {
                                    foreach ($zones as $key => $zone) {
                                        $forward_additionalData['forward_additional'][$zone->zone_code] = $row[$zone->zone_code] ?? '0.00';
                                    }
                                }
                                if ($row['consignment_type'] == 'reverse') {
                                    foreach ($zones as $key => $zone) {
                                        $reverseData['reverse'][$zone->zone_code] = $row[$zone->zone_code] ?? '0.00';
                                    }
                                }
                                if ($row['consignment_type'] == 'dto') {
                                    foreach ($zones as $key => $zone) {
                                        $dtoData['dto'][$zone->zone_code] = $row[$zone->zone_code] ?? '0.00';
                                    }
                                }
                            
                                $rate->forward = json_encode($forwardData);
                                $rate->forward_additional = json_encode($forward_additionalData);
                                $rate->reverse = json_encode($reverseData);
                                $rate->dto = json_encode($dtoData);
                                $rate->created_by = Auth::user()->id;
                                $rate->save();
                            }
                            else
                            {
                              
                                $data->save();
                            }
                        }
                        return redirect()->route('rate-card.index')->with(['status' => 'uploaded successfully !']);
    				}
    			}
            }
            catch(Exception $e) {
                return Redirect::back()->with(['error' => $e->getMessage()]);
            } 
		}
		else{
		    #return redirect()->back()->with('status', 'Please check file should be csv');
			$dbErrors[] = 'Please check file should be csv';
			return \Redirect::back()->with(['error' =>implode('<br>', $dbErrors)]);
		}
     }
     public function import_b2c_bkp(Request $request)
     {
        if($request->hasFile('importFile')) 
		{ 
		   
            $file = $request->file('importFile');
            $filePath = $file->getRealPath();
            $csvData = array_map('str_getcsv', file($filePath));
           
            $header = $csvData[0];
            $rowDataArray =[];
            $filterArray = [];
            $forwardData = ['forward' => []];
            $forward_additionalData = ['forward_additional' => []];
            $reverseData = ['reverse' => []]; 
            $dtoData = ['dto' => []]; 
            $zones = Zone::select('zone_code')->where('dsp','3')->get();
            for ($i = 1; $i < count($csvData); $i++) {
                
                $rowData = $csvData[$i];
                
                $row=array_combine($header,$rowData);
                $rowDataArray[] = $row;
                // Loop through the headers and create an associative array
                #$filterArray = filterArray($row, $filterCriteria);
            }
           
            $newDataArray = [];
          
            foreach ($rowDataArray as $row) {
                
                $consignmentTypeIndex = array_search("consignment_type", array_keys($row));
            
                // Use array_slice to create a new array starting from "consignment_type"
                $newArray = array_slice($row, 0, $consignmentTypeIndex + 1);
            
                // Remove elements from the original array that are present in the new array
                $remainingArray = array_diff_key($row, $newArray);
                
                // Append the remaining array to the new array
                $newArray[$row['consignment_type']] = $remainingArray;
            
                // Append the new array to the result array
                $newDataArray[] = $newArray;
                
            }
            
             print_r($newDataArray);die;
            $newArr = [];
            
            foreach ($rowDataArray as $row) {
                // Find the index of "consignment_type"
                $consignmentTypeIndex = array_search("consignment_type", array_keys($row));
            
                if ($consignmentTypeIndex !== false) {
                    // Use array_slice to create a new array before "consignment_type"
                    $commonArray = array_slice($row, 0, $consignmentTypeIndex);
            
                    // Create a new array with common elements before "consignment_type"
                    $newArray = $commonArray;
            
                    // Append the "consignment_type" and its value to the new array
                    $newArray['consignment_type'] = $row['consignment_type'];
            
                    // Append the new array to the result array
                    $newArr[] = $newArray;
                } else {
                    // If "consignment_type" is not found, simply append the original row
                    $newArr[] = $row;
                }
            }

           # dd($newArr[0]);

            //zone Data
            $newdiffArray = [];
           

            foreach ($rowDataArray as $row) {
               $consignmentIndex = array_search("consignment_type", array_keys($row));
            
                // Create a new array with elements after "consignment_type"
                $newArray = array_slice($row, $consignmentIndex + 1, null, true);
            
                // Display the new array
                $newdiffArray[$row['consignment_type']] =$newArray;
            }



            $mergeArr = array_merge($newArr[0],$newdiffArray);
              #dd($mergeArr);
              
            $filteredData = [];
            $dataToInsert = [];
            foreach ($newDataArray as $row) 
            {
                
                    DB::beginTransaction();
                    $data = new Rate;
                    $data->client_id = Auth::user()->id;
                    $data->logistics_type= $row['logistic_type'];
                    $data->aggregator = $row['aggregator'];
                    $data->courier = $row['courier'];
                    $data->courier_name = $row['courier_name'];
                    $data->shipment_mode = $row['shipment_mode'];
                    $data->min_weight = $row['min_weight'];
                    $data->additional_weight = $row['additional_weight'];
                    $data->cod = $row['cod_charge'];
                    $data->cod_percent = $row['cod_percent'];
                    $data->forward = '0';
                    $data->forward_additional =  '0';
                    $data->reverse = '0';
                    $data->dto = '0';
                    
                    $check = Rate::where('logistics_type', $data->logistics_type)
                                        ->where('aggregator', $data->aggregator)
                                        ->where('courier', $data->courier)
                                        ->where('min_weight', $data->min_weight)
                                        ->where('additional_weight', $data->additional_weight)
                                        ->where('cod', $data->cod)
                                        ->where('cod_percent', $data->cod_percent)->first();
            		if($check)
            		{
            		    $rate = Rate::find($check->id);
                        // Adjust the following lines based on your data structure
                        if(isset($row['forward']))
                        {
                            $rate->forward = json_encode($row['forward']);
                        }
                       
                        if(isset($row['forward_additional']))
                        {
                            $rate->forward_additional = json_encode($row['forward_additional']) ?json_encode($row['forward_additional']) : '';
                        }
                        if(isset($row['reverse']))
                        {
                            $rate->reverse = json_encode($row['reverse']) ?json_encode($row['reverse']): '';
                        }
                        if(isset($row['dto'])){
                            $rate->dto = json_encode($row['dto']) ?json_encode($row['dto']): '';
                        }
                        
                       $rate->save();
                    }
            		else
            		{
            		    $data->save();
            		}     
                
                
            
            DB::commit();
        return redirect()->route('rate-card.index')->with(['status' => 'uploaded successfully !']);
        }
         
            
     }
     
		else{
		    #return redirect()->back()->with('status', 'Please check file should be csv');
			$dbErrors[] = 'Please check file should be csv';
			return \Redirect::back()->with(['error' =>implode('<br>', $dbErrors)]);
		}
     }
    public function b2b_list()
    {
        $couriers = AppLogistics::where('logistics_status','Active')->get();
        $rates = RateB2b::where('user_id',Auth::user()->id)->paginate(10);
        if(!$rates){
            return Redirect::back()->with(['error' => 'Data not found']);
        }
        return view('admin-app.admin-tab.admin-tab-rate-card-b2b',compact('couriers','rates'));
    }
    public function add_b2b(){ 
        $regions = Region::all();
        $couriers = AppLogistics::where('logistics_status','Active')->get();
        $data = [];
        return view('admin-app.admin-card.admin-rate-card-b2b', compact('regions','data','couriers'));
    }
    public function store_b2b(Request $request)
    { 
        $validate = $request->validate([
            'origin' => 'required',
            'region' => 'required',
            'destination' => 'required',
            'courier' => 'required',
            'courier_charge' => 'required|numeric',
            'docket_charge' => 'required|numeric',
            'fuel_surcharge' => 'required|numeric',
            'fov_min_charge' => 'required|numeric',
            'fov_percent' => 'required|numeric',
            'min_chargable_weight' => 'required|numeric',
            'min_chargable_amount' => 'required|numeric',
            'volumetric_weight' => 'required|numeric'
        ]); 
        $status = 'Record added successfully';
        try{
            $data = new RateB2b;
            $data->user_id = Auth::user()->id;
            $data->user_type = Auth::user()->user_type;
            $data->origin = $request->origin;
            $data->region = $request->region;
            $data->destinations = $request->destination;
            $data->courier = $request->courier;
            $data->courier_charge = $request->courier_charge ?? '0.00';
            $data->docket_charge = $request->docket_charge ?? '0.00';
            $data->fuel_surcharge = $request->fuel_surcharge ?? '0.00';
            $fov[] =array(
                'fov_min_charge' => $request->fov_min_charge ?? '0.00',
                'fov_percent' => $request->fov_percent ?? '0.00'
            );
            $data->fov_owner_risk = json_encode(['fov_charge'=>$fov]);
            $data->min_chargable_weight = $request->min_chargable_weight ?? '0.00';
            $data->min_chargable_amount = $request->min_chargable_amount ?? '0.00';
            $data->volumetric_weight = $request->volumetric_weight ?? '0.00';
            #dd($data);
            $data->save();
            return redirect()->route('b2b_list')->with(['status' => $status]);  
        }
        catch(Exception $e) {            
            return Redirect::back()->with(['error' => $e->getMessage()]);
        }
    }

    public function edit_b2b($id){
        $id = \Crypt::decrypt($id);       
        $data = RateB2b::find($id);
        #dd($data);
        $couriers = AppLogistics::where('logistics_status','Active')->get();
        $regions = Region::all();
        if(!$data){       
            return Redirect::back()->with(['error' => 'Data not found']);
        }  
        return view('admin-app.admin-card.admin-rate-card-b2b',compact('data','couriers','regions'));
        
    }

    public function update_b2b(Request $request, $id){
        $data = RateB2b::find($id);
        if(!$data){       
            return Redirect::back()->with(['error' => 'Data not found']);
        } 
        $validate = $request->validate([
           
            'courier_charge' => 'numeric',
            'docket_charge' => 'numeric',
            'fuel_surcharge' => 'numeric',
            'fov_min_charge' => 'numeric',
            'fov_percent' => 'numeric',
            'min_chargable_weight' => 'numeric',
            'min_chargable_amount' => 'numeric',
            'volumetric_weight' => 'numeric'
        ]); 
        $status = 'Record added successfully';
        try{
           
            $data->user_id = Auth::user()->id;
            $data->user_type = Auth::user()->user_type;
            $data->origin = $request->origin;
            $data->region = $request->region;
            $data->destinations = $request->destination;
            $data->courier = $request->courier;
            $data->courier_charge = $request->courier_charge ?? '0.00';
            $data->docket_charge = $request->docket_charge ?? '0.00';
            $data->fuel_surcharge = $request->fuel_surcharge ?? '0.00';
            $fov[] =array(
                'fov_min_charge' => $request->fov_min_charge ?? '0.00',
                'fov_percent' => $request->fov_percent ?? '0.00'
            );
            $data->fov_owner_risk = json_encode(['fov_charge'=>$fov]);
            $data->min_chargable_weight = $request->min_chargable_weight ?? '0.00';
            $data->min_chargable_amount = $request->min_chargable_amount ?? '0.00';
            $data->volumetric_weight = $request->volumetric_weight ?? '0.00';
            #dd($data);
            $data->save();
            return redirect()->route('b2b_list')->with(['status' => $status]);  
        }
        catch(Exception $e) {            
            return Redirect::back()->with(['error' => $e->getMessage()]);
        }
    }
}
