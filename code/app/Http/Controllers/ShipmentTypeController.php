<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
#use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\ShipmentType;
use Illuminate\Support\MessageBag;
use Auth,Crypt;

class ShipmentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index(Request $request)
    {
        
		$shipment = ShipmentType::orderByDesc('id');
		if(!empty($request->input('shipment_type'))){
			$shipment = $shipment->where('shipment_type',$request->input('shipment_type'));
		}
		$shipmentTypes = $shipment->paginate(10);
		
		return view('admin-app.admin-tab.admin-tab-shipment-type', compact('shipmentTypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [];
        return view('admin-app.admin-card.admin-shipment-type-card',compact('data'));
    }
    public function store(Request $request){
        $validate = $request->validate([
            'shipment_type' => 'required|string|min:4',
            
            
        ]);
        $status="Added successfully";
        try{  
           
            $data = new ShipmentType;
            $data->shipment_type = $request->shipment_type;
            $data->created_by = Auth::user()->id;                         
            $data->save();
            return redirect('/shipmentType')->with(['status' => $status]);    
        }
        catch(Exception $e) {
            
            return Redirect::back()->with(['error' => $e->getMessage()]);
        }
    }
    public function edit($id)
    {
        $id = \Crypt::decrypt($id);
        //dd($id);
        $data = ShipmentType::find($id);
       
        if($data){       
           return view('admin-app.admin-card.admin-shipment-type-card',compact('data'));
        }
        else{
            return Redirect::back()->with(['error' => 'Data not found']);
        }
    }
    public function update(Request $request){
        try{  
        $encryptedId = $request->input('type');
		$id = Crypt::decrypt($encryptedId);
		// Check if the decrypted ID is valid
		if (!is_numeric($id)) 
		{
			throw new \Exception('Invalid ID');
		}
        //dd($id);
        $data = ShipmentType::find($id);
       
        if(!$data){       
           return Redirect::back()->with(['error' => 'Data not found']);
        }
        
        $status="Updated successfully";
        
            $data->shipment_type = $request->shipment_type;
            $data->updated_by = Auth::user()->id;                         
            $data->save();
            return redirect('/shipmentType')->with(['status' => $status]);    
        }
        catch(Exception $e) {
            
            return Redirect::back()->with(['error' => $e->getMessage()]);
        }
    }
    public function downloadSample()
    {
        #$csvData = "Pincode,District,City,State\nJohn,john@example.com\nJane,jane@example.com";
        $csvData = "shipment_type\n";
        return Response::make($csvData, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=shipment-type-sample.csv',
        ]);
    }
    	public function import(Request $request)
    {
	    if($request->hasFile('importFile')) 
		{
            $file = $request->file('importFile');
            $filePath = $file->getRealPath();

            $csvData = array_map('str_getcsv', file($filePath));
            $csvHeader = array_shift($csvData); // Assuming the first row contains column names
            
			$rules = 
			[
				'shipment_type' => 'required|string|min:4',
				
			];
			$failedRows = [];

			foreach ($csvData as $row) 
			{
				$validator = Validator::make(array_combine($csvHeader, $row), $rules);

				if ($validator->fails()) {
					$failedRows[] = [
						'data' => $row,
						'errors' => $validator->errors()->all(),
					];
				}
			
				if (!empty($failedRows)) 
				{
					$failedRowsMessages = [];
					foreach ($failedRows as $failedRow) {
						$rowErrors = implode('<br>', $failedRow['errors']);
						$failedRowsMessages[] = "Row: " . implode(', ', $failedRow['data']) . "<br>" . $rowErrors;
					}
					return back()->withErrors(implode('<br>', $failedRowsMessages));
				} 
				else 
				{
					$data = array_combine($csvHeader, $row);
					try 
					{
						ShipmentType::create($data);
						$returnMsg = 'Shpment Types uploaded successfully..!';
					} 
					catch (\Illuminate\Database\Eloquent\MassAssignmentException $e) {
						
						$errorCode = $e->errorInfo[1];
						if ($errorCode == 1062) 
						{ // Duplicate entry error code
							preg_match("/for key '([^']+)'/", $e->errorInfo[2], $matches);
							
							if(isset($matches[1])) 
							{
								$duplicateKeyName = $matches[1];
								$errorMessage = "Duplicate entry for $duplicateKeyName. This value is already in use.";
							} 
							else 
							{
								$errorMessage = "Duplicate entry. This value is already in use.";
							}
							return back()->withErrors([$errorMessage]);
						} 
						else 
						{
							Log::error('MassAssignmentException: ' . $e->getMessage());
							return back()->withErrors([$e->errorInfo]);
						}
					}
				}
			}
		}
		else
		{
			#return redirect()->back()->with('status', 'Please check file should be csv');
			$dbErrors[] = 'Please check file should be csv';
			return back()->withErrors(implode('<br>', $dbErrors));
		}
		return redirect()->back()->with('status', ''.$returnMsg);
    }

}
