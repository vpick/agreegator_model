<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppChanel;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Auth;
class AppChanelController extends Controller
{
	public function index()
    {
    	$chanels = AppChanel::paginate(5);
        return view('admin-app.admin-tab.admin-tab-chanel',compact('chanels'));
    }
    public function add_chanel()
    {
        return view('admin-app.admin-card.adnin-channel-card');
        
    }
	public function store(Request $request)
    {
		$app_chanel = new AppChanel;
		$validated = $request->validate([
			'chanel_name' => 'required|string|max:50',
			'chanel_logo' => 'required',
		]);
		$app_chanel->chanel_name = $validated['chanel_name'];
		$app_chanel->chanel_logo = $validated['chanel_logo'];
		#If use save then use below code
		$app_chanel->save();
		return redirect('app-chanels')->with('status', 'Chanel added successfully!');
		
		#If use Create then use below code
		#$app_chanel = AppChanel::create($validated);
	}
	public function chanel_list()
    {
        $chanels = AppChanel::all();
        if(Auth::user()->user_type!='isCompany'){
            $userP = DB::table('user_permissions as u')
                ->join('pages as p', function ($join) {
                    $join->on('u.page_id', '=', 'p.id')
                        ->where('u.role_id', '=', Auth::user()->role_id)
                        ->where('u.user_id', '=', Auth::user()->id)
                        ->where('p.pagename', '=', 'channel');
                })
                ->select('p.pagename', 'u.role_id', 'u.user_id', 'u.page_id', 'u.read', 'u.write', 'u.update', 'u.delete', 'u.print', 'u.download')
                ->first(); // Use first instead of get
        
            if((!empty($userP)) && ($userP->read ==1))
            {
                return view('common-app.list.channel',compact('chanels','userP'));
            }
            else{
                return \Redirect::back()->with(['error' => 'No Permission!!!']);
            }
        }
        else{
            return view('common-app.list.channel',compact('chanels'));
        }
    }
	public function show(Request $request)
    {
		try 
		{
			$encryptedId = $request->input('ord');
			$chanelsId = Crypt::decrypt($encryptedId);

			// Check if the decrypted ID is valid
			if (!is_numeric($chanelsId)) 
			{
				throw new \Exception('Invalid order ID');
			}
            $chanels = AppChanel::find($chanelsId);
            return view('admin-app.admin-card.chanels-edit-view-card',compact('chanels'));
		} 
		catch (\Exception $e) 
		{
			// Handle any exceptions or errors
			// For example, display an error message or redirect the user back with an error
			return back()->withErrors($e->getMessage());
		}
    }
	public function edit(Request $request)
    {
		try 
		{
			$encryptedId = $request->input('ord');
			$chanelsId = Crypt::decrypt($encryptedId);
			// Check if the decrypted ID is valid
			if (!is_numeric($chanelsId)) 
			{
				throw new \Exception('Invalid order ID');
			}
            $chanelsVarify = AppChanel::findOrFail($chanelsId);		 
			if(!empty($chanelsVarify))
			{
			    $rules = 
				[
					'chanel_name' => 'required|string|max:15',
					'chanel_logo' => 'required|string',
				];
				$validator = Validator::make($request->all(),$rules);
				if ($validator->fails()) 
				{
					// Validation failed
					$errors = $validator->errors()->all();
					// Handle validation errors appropriately
					#$response['error_message'] = $errors;
					#dd($errors);
					return back()->withErrors($errors);
					#return redirect('app-chanels')->with('status', ''.$errors);
				} 
				$validated = $validator->validated();
				$chanelsVarify->chanel_name = $validated['chanel_name'];
				$chanelsVarify->chanel_logo = $validated['chanel_logo'];
				#dd($chanelsVarify);
				#If use save then use below code
				DB::beginTransaction();
				try
				{
					$chanelsVarify->save();
					DB::commit();
					return redirect('app-chanels')->with('status', 'Chanel updated successfully!');
				}
				catch (\Exception $e) 
				{
					// Something went wrong, rollback the transaction
					DB::rollback();
					// Optionally, you can handle the exception or log it
					// Log::error($e->getMessage());
					#echo $e->getMessage();dd();
					return back()->withErrors($e->getMessage());
				}	
			}	
            else
			{
				return redirect('app-chanels')->with('status', 'Error code 404: Partners not exist');
			}				
        } 
		catch (\Exception $e) 
		{
			// Handle any exceptions or errors
			// For example, display an error message or redirect the user back with an error
			return back()->withErrors($e->getMessage());
		}
    }
}
