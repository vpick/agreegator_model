<?php

namespace App\Http\Controllers;

use App\Models\Webhook;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Interfaces\AppOrderProcessInterface;
use Auth,DB;
class WebHookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userP = DB::table('user_permissions as u')
                ->join('pages as p', function ($join) {
                    $join->on('u.page_id', '=', 'p.id')
                        ->where('u.role_id', '=', Auth::user()->role_id)
                        ->where('u.user_id', '=', Auth::user()->id)
                        ->where('p.pagename', '=', 'webhook');
                })
                ->select('p.pagename', 'u.role_id', 'u.user_id', 'u.page_id', 'u.read', 'u.write', 'u.update', 'u.delete', 'u.print', 'u.download')
                ->first(); // Use first instead of get
                 
                // Check if a permission record exists
                if((!empty($userP)) && ($userP->read ==1))
        	    {
                    $webhooks = WebHook::where('client_id',Auth::user()->client_id)->paginate(10);
            		return view('client-app.webhook-list', compact('webhooks','userP'));
        	    }
        	    else{
        	         return \Redirect::back()->with(['error' => 'No Permission!!!']);
        	    }
    }
    public function webhook_response($orderType)
    {
		// Resolve the appropriate service based on the order type
        $myService = app()->makeWith(AppOrderProcessInterface::class, ['ordersendTo' => $orderType]);
        // Now you can use $myService based on the order type.
        // For example, if $orderType is 'type1', the corresponding ServiceOne will be injected.
        if (!$myService instanceof AppOrderProcessInterface) {
			//throw new \RuntimeException("Service resolution failed for order type: $orderType");
			echo 'You try to call invalid service class';dd();
		}
		return $myService->webhook_response();
    } 
	public function order_track(Request $request)
	{
		$ordersendTo = $request['param']?$request['param']:'NimbusWebHook';
        $finalResponse = app(WebHookController::class)->webhook_response($ordersendTo);
		if($finalResponse)
		{
			return response()->json(['response' => 'Data received'], 201);
		}
		else
		{
			return response()->json(['response' => 'Unable to received data'], 201);
		}
	}
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         $userP = DB::table('user_permissions as u')
                ->join('pages as p', function ($join) {
                    $join->on('u.page_id', '=', 'p.id')
                        ->where('u.role_id', '=', Auth::user()->role_id)
                        ->where('u.user_id', '=', Auth::user()->id)
                        ->where('p.pagename', '=', 'webhook');
                })
                ->select('p.pagename', 'u.role_id', 'u.user_id', 'u.page_id', 'u.read', 'u.write', 'u.update', 'u.delete', 'u.print', 'u.download')
                ->first(); // Use first instead of get
                   
                // Check if a permission record exists
                if((!empty($userP)) && ($userP->read ==1))
        	    {
                    $data = [];
                    return view('client-app.webhook-card',compact('data','userP'));
        	    }
        	    else{
        	        return \Redirect::back()->with(['error' => 'No Permission!!!']);
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
        
        $validate = $request->validate([
            'webhook_name' => 'required|unique:webhooks',
            'webhook_url' => 'required',
            'webhook_secret' => 'required',
            'webhook_status' => 'required'    
        ]);
        $status="Added successfully";
        try{           
            $data = new WebHook;
            $data->client_id = Auth::user()->client_id;
            $data->webhook_name = $request->webhook_name;
            $data->webhook_url = $request->webhook_url;
            $data->webhook_secret = $request->webhook_secret;
            $data->webhook_status = $request->webhook_status;
            $data->created_by = Auth::user()->id;                         
            $data->save();
            return redirect()->route('webhook.index')->with(['status' => $status]);
        }
        catch(Exception $e) {
            
            return Redirect::back()->with(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Webhook  $webhook
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = WebHook::find($id);      
        try {
            $data->delete();
            return \Redirect::back()->with(['status' => 'Deleted!!!']);
        } catch(\Exception $e) {
            return \Redirect::back()->with(['error' => $e->getMessages()]);
        } 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Webhook  $webhook
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = \Crypt::decrypt($id);
        //dd($id);
        $data = WebHook::find($id);
        if($data){       
            return view('client-app.webhook-card', compact('data'));
        }
        else{
            return Redirect::back()->with(['error' => 'Data not found']);
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Webhook  $webhook
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
       
        $data = WebHook::find($id);
        if(!$data){
            return Redirect::back()->with(['error' => 'no data found'],404);
        }
        $status="updated successfully";
        try{           
            $data->webhook_name = $request->webhook_name ?? $data->webhook_name;
            $data->webhook_url = $request->webhook_url ?? $data->webhook_url;
            $data->webhook_secret = $request->webhook_secret ?? $data->webhook_secret;
            $data->webhook_status = $request->webhook_status ?? $data->webhook_status;
            $data->updated_by = Auth::user()->id;                         
            $data->save();
            return redirect()->route('webhook.index')->with(['status' => $status]);
        }
        catch(Exception $e) {
            
            return Redirect::back()->with(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Webhook  $webhook
     * @return \Illuminate\Http\Response
     */
    public function destroy(Webhook $webhook)
    {
        //
    }
}
