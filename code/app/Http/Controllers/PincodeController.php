<?php

namespace App\Http\Controllers;

use App\Models\Pincode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
#use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
class PincodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		$pincodeQuery = Pincode::orderBy('state')->orderBy('city');

		if ($request->input('pincode')) {
			$pincodeQuery->whereIn('pincode', explode(',', $request->input('pincode')));
		}

		if ($request->input('district')) {
			$pincodeQuery->whereIn('district', explode(',', $request->input('district')));
		}

		if ($request->input('city')) {
			$pincodeQuery->whereIn('city', explode(',', $request->input('city')));
		}

		if ($request->input('state')) {
			$pincodeQuery->whereIn('state', explode(',', $request->input('state')));
		}

		$pincode = $pincodeQuery->paginate(10);

		return view('admin-app.admin-tab.admin-tab-pincode-master', compact('pincode'));
    }
    public function pincode_list(Request $request)
    {
		$pincodeQuery = Pincode::orderBy('state')->orderBy('city');

		if ($request->input('pincode')) {
			$pincodeQuery->whereIn('pincode', explode(',', $request->input('pincode')));
		}

		if ($request->input('district')) {
			$pincodeQuery->whereIn('district', explode(',', $request->input('district')));
		}

		if ($request->input('city')) {
			$pincodeQuery->whereIn('city', explode(',', $request->input('city')));
		}

		if ($request->input('state')) {
			$pincodeQuery->whereIn('state', explode(',', $request->input('state')));
		}

		$pincode = $pincodeQuery->paginate(10);

		return view('common-app.list.pincode-list', compact('pincode'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pincode  $pincode
     * @return \Illuminate\Http\Response
     */
    public function show(Pincode $pincode)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pincode  $pincode
     * @return \Illuminate\Http\Response
     */
    public function edit(Pincode $pincode)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pincode  $pincode
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pincode $pincode)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pincode  $pincode
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pincode $pincode)
    {
        //
    }
	public function downloadSample()
    {
        #$csvData = "Pincode,District,City,State\nJohn,john@example.com\nJane,jane@example.com";
        $csvData = "Pincode,District,City,State\n";
        return Response::make($csvData, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=pincode-sample.csv',
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
				'Pincode' => 'required|integer|min:100000|max:999999',
				'City' => 'required|string',
				'State' => 'required|string',
				'District' => 'required|string',
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
						Pincode::create($data);
						$returnMsg = 'Pincode uploaded successfully..!';
					} 
					catch (\Exception $e) {
						
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
							$errorMessage = "An error occurred while processing the data.";
							return back()->withErrors([$errorMessage]);
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