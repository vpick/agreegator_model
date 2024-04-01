<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Company;
use App\Models\Client;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class MappingController extends Controller
{
    public function get($id)
    {   
        $id = \Crypt::decrypt($id);
        $user = User::with('company')->find($id);
        if(empty($user)){
            return \Redirect::back()->withInput(['error' => 'User not Found!!!']);
        }
        if ($user->multi_client == '0' && $user->multi_location == '0'){
            return Redirect::back()->withInput(['error' => 'You do not have multiple access.']);
        }
        else if ($user->multi_client == '0' && $user->multi_location == '1'){
            $clients = Client::with('warehouse')->where('id',$user->client_id)->get();
           
        }
        else{
           
            $clients = Client::with('warehouse')->where('id',$user->client_id)->get();
          
            
        }
        
        return view('common-app.card.mapping-card',compact('clients','user'));
        
    }
   
}
