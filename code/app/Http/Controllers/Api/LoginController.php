<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Models\ApiUser;


class LoginController extends Controller
{
    public function get_client(){
       
        // $user = Auth::guard('client')->user();
        // return response()->json(['user' => $user], 200); 
    }
   
   
    public function api_login(Request $request)
        { 
            $validator = Validator::make($request->all(), [
                'username' => 'required',
                'password' => 'required',
            ]);
              
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 401);
            }
            
            if (Auth::guard('client')->attempt(['username' => request('username'), 'password' => request('password'),'is_active'=>'1'])) {
                $user = Auth::guard('client')->user();
                $token = $user->createToken('MyApp')->accessToken;
                if($token){
                    try{
                        $userApi = Auth::guard('client')->user();
                        $userApi->access_token = $token;
                        $userApi->save();
                    }
                    catch(Exception $e) {
                        return Response::json(['error' => $e->getMessage()],  500);
                    }
                }
                $message ='login successfully';
                return response()->json(['access_token' => $user->access_token], 200);
            } else {
                return response()->json(['error' => 'Email or password incorrect'], 401);
            }
        }
    
}
