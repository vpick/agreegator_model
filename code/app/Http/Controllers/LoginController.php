<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;
use Hash;

class LoginController extends Controller
{
    public function index()
    {   
       //dd(Hash::make('user'));
        return view('common-app.login');
    }
    public function login(Request $request)
    {       
        
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);     
        try{
            $username = $request->username;
            $password = $request->password;
            //$remember = $request->has('rememberme') ? true : false; 
            if (Auth::attempt(['username' => $username, 'password' => $password,'status'=>'1'])) {
                 if(Auth::user()->user_type == 'isSystem'){
                    return redirect()->route('master-app.index');    
                }
                
                return redirect()->route('master.index'); 
            }
           
            else{
                return Redirect::back()->with(['error' => 'Invalid login credentials!!!']);
            }    
        }
        catch(\Exception $e) {
            return Redirect::back()->with(['error' => $e->getMessage()]);
        }
    }
}
