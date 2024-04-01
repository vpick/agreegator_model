<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ApiUser;
use App\Models\Client;
use Auth,Str,Hash,DB,Session;

class ApiUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        if(Session::has('client')){
            $clientId = session('client.id');
             $clientName = session('client.name');
        }
        else{
            $clientId = Auth::user()->client->id;
            $clientName = Auth::user()->client->name;
        }
        $enableUser = ApiUser::where('client_id',$clientId)->first();
        if(Auth::user()->user_type!= 'isCompany'){
            $userP = DB::table('user_permissions as u')
                    ->join('pages as p', function ($join) {
                        $join->on('u.page_id', '=', 'p.id')
                            ->where('u.role_id', '=', Auth::user()->role_id)
                            ->where('u.user_id', '=', Auth::user()->id)
                            ->where('p.pagename', '=', 'api');
                    })
                    ->select('p.pagename', 'u.role_id', 'u.user_id', 'u.page_id', 'u.read', 'u.write', 'u.update', 'u.delete', 'u.print', 'u.download')
                    ->first(); // Use first instead of get
             
            if((!empty($userP)) && ($userP->read ==1))
            {
                return view('client-app.api-user',compact('enableUser','userP'));
            }
            else{
                return \Redirect::back()->with(['error' => 'No Permission!!!']);
            }
        }
        else{
            return view('client-app.api-user',compact('enableUser'));
        }
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $id = \Crypt::decrypt($id);
        $data = Client::find($id);
        if(!$data){
            return \Redirect::back()->with(['error' => 'Data not found!!.'], 404);
        }
        try{
            $apiUser = New ApiUser;
            $name = $data->client_code;          
            $rand = Str::random(2);
            $apiUser->client_id = $data->id;
            $apiUser->username = $name.$rand;
            $pass = Str::random(10);
            $apiUser->password = Hash::make($pass);
            $apiUser->show_password = $pass;
            $apiUser->access_token = Str::random(60);
            $apiUser->created_by = Auth::user()->id;                        
            $apiUser->save();  
            return \Redirect::back()->with(['status' => 'generated successfully!!']);
        } catch(Exception $e) {
            return \Redirect::back()->with(['error' => $e->getMessages()]);
        } 
        return \Redirect::back()->with(['error' => 'data not Found!!!']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        
        $user = ApiUSer::find($id);
      
        if(!$user){
            return \Redirect::back()->with(['error' => 'Data not found!!.'], 404);
        }
        $data = Client::find($user->client_id);
        
        if(!$data){
            return \Redirect::back()->with(['error' => 'Client not found!!.'], 404);
        }
        try{
            $name = $data->client_code;          
            $rand = Str::random(2);
            $user->client_id = $data->id;
            $user->username = $name.$rand;
            $pass = Str::random(10);
            $user->password = Hash::make($pass);
            $user->show_password = $pass;   
            $user->access_token = Str::random(60);
            $user->save();  
            
            return \Redirect::back()->with(['status' => 'updated successfully!!']);
        } catch(Exception $e) {
            return \Redirect::back()->with(['error' => $e->getMessages()]);
        } 
        return \Redirect::back()->with(['error' => 'data not Found!!!']);
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
        //
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
    public function change_key($id){
      
        $data = ApiUser::find($id);
        if($data->is_active == '1'){
            $data->is_active = '0';
        }
        else {
            $data->is_active = '1';
        }
        
        try {
            $data->save();
            return \Redirect::back()->with(['status' => ' status updated!!!']);
        } catch(\Exception $e) {
            return \Redirect::back()->with(['error' => $e->getMessages()]);
        } 

        return \Redirect::back()->with(['error' => 'data not Found!!!']);
    }
    
   
}
