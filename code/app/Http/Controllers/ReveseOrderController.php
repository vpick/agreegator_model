<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Warehouse;
use App\Models\ProductDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use RealRashid\SweetAlert\Facades\Alert;
use Auth;
use App\Models\AppLogistics;
use App\Models\Rate;
use App\Models\ShipmentType;
use App\Models\Weight;
use DateTime;
use DateInterval;
class ReveseOrderController extends Controller
{
    public function index(Request $request)
    {
        $couriers = AppLogistics::where('logistics_type','Currior')->get();
        if(!Session::has('warehouse'))
        {
            $warehouse_code = Auth::user()->warehouse->warehouse_code;
        }
		else
		{
		    $warehouse_code =session('warehouse.warehouse_code');
		}
	    if(!Session::has('client'))
	    {
			$client_code = Auth::user()->client->client_code;
		}
		else
		{
			$client_code = session('client.client_code');
		}
		$order = Order::orderByDesc('id');
		if(!empty($request->input('order_id')))
		{
			$orderId =$request->input('order_id');
			$oid = explode(',',$orderId);
			$order = $order->whereIn('order_no',$oid);
		}
		if(!empty($request->input('awb_no')))
		{
			$awbNo =$request->input('awb_no');
			$awb_num = explode(',',$awbNo);
			$order = $order->whereIn('awb_no',$awb_num);
		}
		if(!empty($request->input('payment_mode')))
		{
			$order = $order->where('payment_mode',$request->input('payment_mode'));
		}
		
		if(!empty($request->input('shipment_status')))
		{
			$order = $order->whereIn('order_status',$request->input('shipment_status'));
		}
		if(!empty($request->input('customer_name')))
		{
			$order = $order->where('shipping_first_name',$request->input('customer_name'));
		}
		if(!empty($request->input('from_date')))
		{
			$fromdate =$request->input('from_date');
		    $date = explode('-',$fromdate);
		    $from_date = date('Y-m-d', strtotime($date[0]));
		    $to_date = date('Y-m-d', strtotime($date[1]));
			$order = $order->whereBetween(DB::raw('DATE(created_at)'),[$from_date,$to_date]);
		}
		else
		{
	        $to_date = date("Y-m-d");
            $from_date = (new DateTime($to_date))->sub(new DateInterval('P30D'))->format("Y-m-d");
            #dd($from_date);
	    	$order = $order->whereBetween(DB::raw('DATE(created_at)'),[$from_date,$to_date]);
		}
		$partner = $request->input('partner');
		if($partner)
		{
			$order = $order->where('request_partner',$partner);
		}
		$courier_name = $request->input('courier_name');
	    if($courier_name)
	    {
			$order = $order->where('courier_name',$courier_name);
		}
		$orderStatus = $request->input('ord');
		if($orderStatus)
		{
			$status = [''.$orderStatus];
			$total_shipment = $order->where('warehouse_code',$warehouse_code)->where('consignment_type','Reverse')->count();
			$data['orders'] = $order->where('warehouse_code',$warehouse_code)->whereIn('order_status', $status)->where('consignment_type','Reverse')->paginate(10);
		}
		else
		{
		    $total_shipment = $order->where('warehouse_code',$warehouse_code)->where('consignment_type','Reverse')->count();
			$data['orders'] = $order->where('warehouse_code',$warehouse_code)->where('consignment_type','Reverse')->paginate(10);
		}
        if(Auth::user()->user_type == 'isSystem')
        {
			return view('admin-app.admin-tab.admin-tab-orders',compact('data','couriers'));
		}
		$ship = Order::where('warehouse_code',$warehouse_code)
                       ->where('order_status', 'ship')
                       ->where('consignment_type','Reverse')
                       ->whereBetween(DB::raw('DATE(created_at)'),[$from_date,$to_date])
                       ->count();
		$book = Order::where('warehouse_code', $warehouse_code)
                        ->where(function ($query) {
                            $query->where('order_status', 'ship')
                                  ->orWhere('order_status', 'Booked');
                        })
                        ->whereBetween(DB::raw('DATE(created_at)'),[$from_date,$to_date])
                        ->where('consignment_type','Reverse')
                        ->count();
		$cancelled = Order::where('warehouse_code',$warehouse_code)
		                  ->where('order_status', 'Cancelled')
		                  ->where('consignment_type','Reverse')
		                  ->whereBetween(DB::raw('DATE(created_at)'),[$from_date,$to_date])
		                  ->count();
		$rto = Order::where('warehouse_code',$warehouse_code)
	                ->where('order_status', 'RTO')
	                ->where('consignment_type','Reverse')
	                ->whereBetween(DB::raw('DATE(created_at)'),[$from_date,$to_date])
	                ->count();
		$pick = Order::where('warehouse_code',$warehouse_code)->where('order_status', 'Pending Pickup')
	                    ->where('consignment_type','Reverse')
	                    ->whereBetween(DB::raw('DATE(created_at)'),[$from_date,$to_date])
	                    ->count();
		$transit = Order::where('warehouse_code',$warehouse_code)
		                ->where('order_status', 'In Transit')
		                ->where('consignment_type','Reverse')
		                ->whereBetween(DB::raw('DATE(created_at)'),[$from_date,$to_date])
		                ->count();
		$delivered = Order::where('warehouse_code',$warehouse_code)
		                    ->where('order_status', 'Delivered')
		                    ->where('consignment_type','Reverse')
		                    ->count();
		$out_for_delivery = Order::where('warehouse_code',$warehouse_code)
		                    ->where('order_status', 'out for delivery')
		                    ->where('consignment_type','Reverse')
		                    ->count();
		$statuses = Order::select('order_status')
		                    ->where('consignment_type','Reverse')
		                    ->distinct()
		                    ->get();
	    $aggrigators = Order::select('request_partner')
	                        ->where('warehouse_code',$warehouse_code)
	                        ->where('courier_name','!=','')
	                        ->where('consignment_type','Reverse')
	                        ->distinct()
	                        ->get();
		$partners = Order::select('courier_name')
		                    ->where('warehouse_code',$warehouse_code)
		                    ->where('courier_name','!=','')
		                    ->where('consignment_type','Reverse')
		                    ->distinct()->get();
		#dd($partners);
		$warehouses = Warehouse::where('client_id',$client_code)->get();
	    $logistics = AppLogistics::where('logistics_status','Active')->get();
	    $rates = Rate::orderby('courier')
                    ->orderBy('aggregator')
                    ->orderBy('min_weight')
                    ->get();
	    $shipmentTypes = ShipmentType::all();
	    $weights = Weight::all();
	    if(Auth::user()->user_type!='isCompany')
	    {
        	$userP = DB::table('user_permissions as u')
            ->join('pages as p', function ($join) {
                $join->on('u.page_id', '=', 'p.id')
                    ->where('u.role_id', '=', Auth::user()->role_id)
                    ->where('u.user_id', '=', Auth::user()->id)
                    ->where('p.pagename', '=', 'order');
            })
            ->select('p.pagename', 'u.role_id', 'u.user_id', 'u.page_id', 'u.read', 'u.write', 'u.update', 'u.delete', 'u.print', 'u.download')
            ->first(); 
            if((!empty($userP)) && ($userP->read ==1))
    	    {
                return view('common-app.list.rorders',compact('data','couriers','ship','book','cancelled','rto','pick','transit','delivered','statuses','aggrigators','partners','warehouses','logistics','shipmentTypes','weights','rates','userP','total_shipment','out_for_delivery'));
    	    }
    	    else
    	    {
    	        return \Redirect::back()->with(['error' => 'No Permission!!!']);
    	    }
	    }
	    else
	    {
	        return view('common-app.list.rorders',compact('data','couriers','ship','book','cancelled','rto','pick','transit','delivered','statuses','aggrigators','partners','warehouses','logistics','shipmentTypes','weights','rates','total_shipment','out_for_delivery'));
	    }
    }
    public function create()
    {
	  if(Auth::user()->user_type!='isCompany')
        {
            $userP = DB::table('user_permissions as u')
                ->join('pages as p', function ($join) {
                    $join->on('u.page_id', '=', 'p.id')
                        ->where('u.role_id', '=', Auth::user()->role_id)
                        ->where('u.user_id', '=', Auth::user()->id)
                        ->where('p.pagename', '=', 'order');
                })
                ->select('p.pagename', 'u.role_id', 'u.user_id', 'u.page_id', 'u.read', 'u.write', 'u.update', 'u.delete', 'u.print', 'u.download')
                ->first(); 
            if((!empty($userP)) && ($userP->read ==1))
    	    {
		        return view('common-app.card.rorder-card',compact('userP'));
    	    }
    	    else
    	    {
    	        return \Redirect::back()->with(['error' => 'No Permission!!!']);
    	    }
        }
	    else
	    {
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
		$orderCount = Order::where('order_no',$request->order_id)->count();
		if($orderCount>0)
		{
		    return back()->with('error','Order already exists');
		}
		$duplicateSkus = [];

        // Initialize an empty array to track seen product_sku values
        $seenSkus = [];
        
        foreach ($request->products as $product) {
            $sku = $product['product_sku'];
        
            // Check if the product_sku has been seen before
            if (in_array($sku, $seenSkus)) {
                // This is a duplicate product_sku
                $duplicateSkus[] = $sku;
            } else {
                // Add the product_sku to the seen array
                $seenSkus[] = $sku;
            }
        }
        $duplicateEntries = array_filter($request->products, function ($product) use ($duplicateSkus) {
            return in_array($product['product_sku'], $duplicateSkus);
        });
    	if(count($duplicateEntries)>0){
    	    return back()->with('error','Duplicate product exists in order');  
    	}
		
		
		$app_orders = new Order;
		$rules = 
		[
			'order_id' => ['required','string','max:25','regex:/^[a-zA-Z0-9\/+\\-]*$/'],
			'payment_mode' => 'required|string|max:10',
			'order_type' => 'required|string|max:10',
			'shipping_charges' => 'required|numeric',
			'tax_amount' => ['required', 'regex:/(?:[1-9]\d+|\d)(?:,\d\d)?$/'],
			'discount' => ['required', 'regex:/(?:[1-9]\d+|\d)(?:,\d\d)?$/'],
			'cod_charges' => ['required', 'regex:/(?:[1-9]\d+|\d)(?:,\d\d)?$/'],
			'weight' => ['required', 'regex:/(?:[1-9]\d+|\d)(?:,\d\d)?$/','gt:0'],
			'length' => 'required|numeric|min:1',
			'breadth' => 'required|numeric|min:1',
			'height' => 'required|numeric|min:1',
			'shipping_first_name' => 'required|string|max:25',
			'shipping_address_1' => 'required|string|max:50',
			'shipping_pincode' => 'required|numeric|min:100000|max:999999',
			'shipping_city' => 'required|string',
			'shipping_state' => 'required|string',
			'shipping_country' => 'required|string',
			'shipping_phone_number' => 'required|numeric|digits:10',
			
			'billing_first_name' => 'required|string|max:25',
			'billing_address_1' => 'required|string|max:50',
			'billing_pincode' => 'required|numeric|min:100000|max:999999',
			'billing_city' => 'required|string',
			'billing_state' => 'required|string',
			'billing_country' => 'required|string',
			'billing_phone_number' => 'required|numeric|digits:10',
			
			'products.*.product_sku' => 'required|string',
			'products.*.product_name' => 'required|string',
			'products.*.product_qty' => 'required|numeric|gt:0',
			'products.*.product_price' => 'required|numeric|gt:0',
		];
		$validator = Validator::make($request->all(),$rules);
		if ($validator->fails()) 
		{
			$errors = $validator->errors()->all();
            return redirect()->back()->withErrors($validator)->with('error', implode('<br>', $errors));
		} 
		
		$validated = $validator->validated();
		$productPrices = array_column($request->products, "product_price");
		$productQuantities = array_column($request->products, "product_qty");
        $totalPrice = array_sum($productPrices);
        $totalQty = array_sum($productQuantities);
		$app_orders->order_no = $validated['order_id'];
		$app_orders->shipping_city = $validated['shipping_city'];
		$app_orders->payment_mode = $validated['payment_mode'];
		$app_orders->shipping_first_name = $validated['shipping_first_name'];
		$app_orders->shipping_address_1 = $validated['shipping_address_1'];
		$app_orders->shipping_pincode = $validated['shipping_pincode'];
		$app_orders->shipping_state = $validated['shipping_state'];
		$app_orders->shipping_country = $validated['shipping_country'];
		$app_orders->shipping_phone_number = $validated['shipping_phone_number'];
		$app_orders->shipping_charges = $validated['shipping_charges'];
		$app_orders->cod_amount = $validated['cod_charges'];
		$app_orders->tax_amount = $validated['tax_amount'];
		$app_orders->discount_amount = $validated['discount'];
		$app_orders->total_amount = $totalPrice;
		$invoice_amount = $totalPrice + $app_orders->shipping_charges + $app_orders->cod_amount + $app_orders->tax_amount - $app_orders->discount_amount; 
		$app_orders->shipping_address_2 = $request->shipping_address_2?$request->shipping_address_2:'';
		$app_orders->shipping_last_name = $request->shipping_last_name?$request->shipping_last_name:'';
		$app_orders->shipping_email = $request->shipping_email?$request->shipping_email:'';
		$app_orders->shipping_alternate_phone = $request->shipping_alternate_phone?$request->shipping_alternate_phone:'0';
		$app_orders->shipping_company_name = $request->shipping_company_name?$request->shipping_company_name:'';
		
		
		$app_orders->billing_first_name = $request->billing_first_name?$request->billing_first_name:'';
		$app_orders->billing_last_name = $request->billing_last_name?$request->billing_last_name:'';
		$app_orders->billing_company_name = $request->billing_company_name?$request->billing_company_name:'';
		$app_orders->billing_address_1 = $request->billing_address_1?$request->billing_address_1:'';
		$app_orders->billing_address_2 = $request->billing_address_2?$request->billing_address_2:'';
		$app_orders->billing_pincode = $request->billing_pincode?$request->billing_pincode:'0';
		$app_orders->billing_email = $request->billing_email?$request->billing_email:'';
		
		$app_orders->billing_city = $request->billing_city?$request->billing_city:'';
		$app_orders->billing_state = $request->billing_state?$request->billing_state:'';
		$app_orders->billing_country = $request->billing_country?$request->billing_country:'';
		$app_orders->billing_phone_number = $request->billing_phone_number?$request->billing_phone_number:'0';
		$app_orders->billing_alternate_phone = $request->billing_alternate_phone?$request->billing_alternate_phone:'0';
		$app_orders->gst_no = $request->gst_no?$request->gst_no:'';
		
		$app_orders->weight_unit = 'grams';
		$app_orders->dimension_unit = 'cm';
		$app_orders->total_weight = $request->weight?$request->weight:'0.00';
		$app_orders->volumetric_weight = $request->volumetric_weight?$request->volumetric_weight:'0.00';
		$app_orders->vol_weight = $request->vol_weight?$request->vol_weight:'0.00';
		$app_orders->length = $request->length?$request->length:'1';
		$app_orders->breadth = $request->breadth?$request->breadth:'1';
		$app_orders->height = $request->height?$request->height:'1';
		$app_orders->latitude = $request->latitude?$request->latitude:'';
		$app_orders->longitude = $request->longitude?$request->longitude:'';
		$app_orders->hyperlocal_address = $request->hyperlocal_address?$request->hyperlocal_address:'';
		$app_orders->postal_code = $request->postal_code?$request->postal_code:'0';
		$app_orders->request_partner = '';
		$app_orders->order_request = '';
		
		$app_orders->order_type = $request->postal_code?$request->postal_code:'B2C';
	    if(!Session::has('warehouse'))
	    {      
			$app_orders->business_account = Auth::user()->warehouse->warehouse_code;     
            $app_orders->warehouse_code = Auth::user()->warehouse->warehouse_code;
			$app_orders->warehouse_name = Auth::user()->warehouse->warehouse_name;
        }
		else
		{
			$app_orders->business_account = session('warehouse.warehouse_code');
			$app_orders->warehouse_code = session('warehouse.warehouse_code');
			$app_orders->warehouse_name = session('warehouse.warehouse_name');
		}
		
		if(Auth::user()->user_type == "isClient")
		{
			$app_orders->client_code = Auth::user()->client->client_code;
		}
		else
		{
			$app_orders->client_code = session('client.client_code');
		}
		$app_orders->currency_code = 'INR';
		$app_orders->consignment_type = 'Reverse';
		$app_orders->shipping_label = '';
		$app_orders->manifest_url = '';
		$app_orders->invoice_url = '';
		$app_orders->total_quantity = $totalQty;
		$app_orders->invoice_no = $validated['order_id'];
		$app_orders->invoice_amount = $invoice_amount;
		$app_orders->no_of_invoice = '1';
		$app_orders->invoice_date = date('Y-m-d H:i:s');
		$app_orders->no_of_box = count($request->products);
		$app_orders->awb_no = '';
		$app_orders->courier_name = '';
		$app_orders->courrier_id = '0';
		$app_orders->remarks = '';
		$app_orders->tracking_history = '';
		$app_orders->order_status = 'Booked';
		$app_orders->omnee_order = random_int(100,9999);
		
		#If use save then use below code
		DB::beginTransaction();
		try
		{
			$app_orders->save();
			$insertdOrderid = $app_orders->id;
			if($insertdOrderid > 0)
			{
			    foreach ($request->products as $product) 
				{
				    $ProductDetails = new ProductDetails;
					$ProductDetails->order_id = $insertdOrderid;
					$ProductDetails->product_code = $product['product_sku']?$product['product_sku']:'';
					$ProductDetails->product_hsn_code = '';
					$ProductDetails->product_description = $product['product_name']?$product['product_name']:'';
					$ProductDetails->product_quantity = $product['product_qty']?$product['product_qty']:0;
					$ProductDetails->product_price = $product['product_price']?$product['product_price']:0.00;
					
					
					$ProductDetails->no_of_box = 1;
					$ProductDetails->product_weight_unit = 'grams';
					$ProductDetails->product_weight = $request->weight?$request->weight:'0.00';
					$ProductDetails->product_lbh_unit = 'cm';
					$ProductDetails->product_breadth = $request->breadth?$request->breadth:'1';
					$ProductDetails->product_height = $request->height?$request->height:'1';
					$ProductDetails->product_length = $request->length?$request->length:'1';
					
					
					$ProductDetails->save();
				}
				$returnMsg = 'Order created successfully!';
				DB::commit();
				#echo "yes";dd();
			}
		}
		catch (\Exception $e) 
		{
			// Something went wrong, rollback the transaction
			DB::rollback();
			// Optionally, you can handle the exception or log it
			// Log::error($e->getMessage());
			$returnMsg = ''.$e->getMessage();
		}
		return redirect('rorders')->with('status', ''.$returnMsg);
    }
    public function show(Request $request)
    {
		try 
		{
			if($request->input('ordClone'))
			{
				$oId = $request->input('ordClone');				
				$orderClone = Crypt::decrypt($oId);				
				$orderdata = Order::where('order_no',$orderClone)->first();	
				$orderId = $orderdata->id;
			}
			else
			{
				$encryptedId = $request->input('ord');
				$orderId = Crypt::decrypt($encryptedId);
				$orderClone = '';
			}
			if (!is_numeric($orderId)) 
			{
				throw new \Exception('Invalid order ID');
			}
            $order = Order::find($orderId);
            $products = ProductDetails::where('order_id', $orderId)->get();
			return view('common-app.card.rorder-edit-view-card', compact('order', 'products','orderClone'));
		} 
		catch (\Exception $e) 
		{
			return \Redirect::back()->with(['error' =>$e->getMessage()]);
		}
    }
    public function view(Request $request)
	{
	   
		try 
		{
			$encryptedId = $request->input('ord');
			$orderId = Crypt::decrypt($encryptedId);
			if (!is_numeric($orderId)) 
			{
				throw new \Exception('Invalid order ID');
			}
            $order = Order::find($orderId);
            $products = ProductDetails::where('order_id', $orderId)->get();
			return view('common-app.card.rorder-view-card', compact('order', 'products'));
		} 
		catch (\Exception $e) 
		{
			return \Redirect::back()->with(['error' =>$e->getMessage()]);
		}
	}
	public function edit(Request $request)
    {
		try 
		{
			$encryptedId = $request->input('ord');
			$orderId = Crypt::decrypt($encryptedId);
			if (!is_numeric($orderId)) 
			{
				throw new \Exception('Invalid order ID');
			}
			$duplicateSkus = [];
        // Initialize an empty array to track seen product_sku values
            $seenSkus = []; 
            foreach ($request->products as $product) {
                $sku = $product['product_sku'];
            
                // Check if the product_sku has been seen before
                if (in_array($sku, $seenSkus)) {
                    // This is a duplicate product_sku
                    $duplicateSkus[] = $sku;
                } else {
                    // Add the product_sku to the seen array
                    $seenSkus[] = $sku;
                }
            }
            $duplicateEntries = array_filter($request->products, function ($product) use ($duplicateSkus) {
                return in_array($product['product_sku'], $duplicateSkus);
            });
        	if(count($duplicateEntries)>0){
        	    return back()->with('error','Duplicate product exists in order');  
        	}
        	
            $orderVarify = Order::findOrFail($orderId);		 
            
			if(!empty($orderVarify))
			{
			    $rules = 
				[
					'order_id' => 'required|string|max:25',
					'order_type' => 'string|max:25',
					'payment_mode' => 'required|string|max:10',
					'shipping_charges' => ['required', 'regex:/(?:[1-9]\d+|\d)(?:,\d\d)?$/'],
					'tax_amount' => ['required', 'regex:/(?:[1-9]\d+|\d)(?:,\d\d)?$/'],
					'discount' => ['required', 'regex:/(?:[1-9]\d+|\d)(?:,\d\d)?$/'],
					'cod_charges' => ['required', 'regex:/(?:[1-9]\d+|\d)(?:,\d\d)?$/'],
					'weight' => ['required', 'regex:/(?:[1-9]\d+|\d)(?:,\d\d)?$/','gt:0'],
					'length' => 'required|integer|min:1',
					'breadth' => 'required|integer|min:1',
					'height' => 'required|integer|min:1',
					'shipping_first_name' => 'required|string|max:25',
					'shipping_address_1' => 'required|string|max:50',
					'shipping_pincode' => 'required|numeric|min:100000|max:999999',
					'shipping_city' => 'required|string',
					'shipping_state' => 'required|string',
					'shipping_country' => 'required|string',
					'shipping_phone_number' => 'required|numeric|digits:10',
					
        			'billing_first_name' => 'required|string|max:25',
        			'billing_address_1' => 'required|string|max:50',
        			'billing_pincode' => 'required|numeric|min:100000|max:999999',
        			'billing_city' => 'required|string',
        			'billing_state' => 'required|string',
        			'billing_country' => 'required|string',
        			'billing_phone_number' => 'required|numeric|digits:10',
			
					'products.*.product_sku' => 'required|string',
					'products.*.product_name' => 'required|string',
					'products.*.product_qty' => 'required|numeric|gt:0',
					'products.*.product_price' => 'required|numeric|gt:0',
				];
				$validator = Validator::make($request->all(),$rules);
				if ($validator->fails()) 
				{
					$errors = $validator->errors()->all();
                    return redirect()->back()->withErrors($validator)->with('error', implode('<br>', $errors));
				} 
				$validated = $validator->validated();
			
				$productPrices = array_column($request->products, "product_price");
        		$productQuantities = array_column($request->products, "product_qty");
                $totalPrice = array_sum($productPrices);
                $totalQty = array_sum($productQuantities);
				$orderVarify->order_no = $validated['order_id'];
				$orderVarify->shipping_city = $validated['shipping_city'];
				$orderVarify->payment_mode = $validated['payment_mode'];
				$orderVarify->shipping_first_name = $validated['shipping_first_name'];
				$orderVarify->shipping_address_1 = $validated['shipping_address_1'];
				$orderVarify->shipping_pincode = $validated['shipping_pincode'];
				$orderVarify->shipping_state = $validated['shipping_state'];
				$orderVarify->shipping_country = $validated['shipping_country'];
				$orderVarify->shipping_phone_number = $validated['shipping_phone_number'];
				$orderVarify->shipping_charges = $validated['shipping_charges'];
				$orderVarify->cod_amount = $validated['cod_charges'];
        		
				$orderVarify->tax_amount = $validated['tax_amount'];
				$orderVarify->discount_amount = $validated['discount'];
				$orderVarify->total_amount = $totalPrice;
		        $invoice_amount = $totalPrice + $orderVarify->shipping_charges + $orderVarify->cod_amount + $orderVarify->tax_amount - $orderVarify->discount_amount; 
				$orderVarify->shipping_address_2 = $request->shipping_address_2?$request->shipping_address_2:'';
				$orderVarify->shipping_last_name = $request->shipping_last_name?$request->shipping_last_name:'';
				$orderVarify->shipping_email = $request->shipping_email?$request->shipping_email:'';
				$orderVarify->shipping_alternate_phone = $request->shipping_alternate_phone?$request->shipping_alternate_phone:'0';
				$orderVarify->shipping_company_name = $request->shipping_company_name?$request->shipping_company_name:'';
				$orderVarify->billing_first_name = $request->billing_first_name?$request->billing_first_name:'';
				$orderVarify->billing_last_name = $request->billing_last_name?$request->billing_last_name:'';
				$orderVarify->billing_company_name = $request->billing_company_name?$request->billing_company_name:'';
				$orderVarify->billing_address_1 = $request->billing_address_1?$request->billing_address_1:'';
				$orderVarify->billing_address_2 = $request->billing_address_2?$request->billing_address_2:'';
				$orderVarify->billing_pincode = $request->billing_pincode?$request->billing_pincode:'0';
				$orderVarify->billing_email = $request->billing_email?$request->billing_email:'';
				$orderVarify->billing_city = $request->billing_city?$request->billing_city:'';
				$orderVarify->billing_state = $request->billing_state?$request->billing_state:'';
				$orderVarify->billing_country = $request->billing_country?$request->billing_country:'';
				$orderVarify->billing_phone_number = $request->billing_phone_number?$request->billing_phone_number:'0';
				$orderVarify->billing_alternate_phone = $request->billing_alternate_phone?$request->billing_alternate_phone:'0';
				$orderVarify->gst_no = $request->gst_no?$request->gst_no:'';
				$orderVarify->weight_unit = 'grams';
				$orderVarify->dimension_unit = 'cm';
				$orderVarify->total_weight = $request->weight?$request->weight:'0.00';
				$orderVarify->volumetric_weight = $request->volumetric_weight?$request->volumetric_weight:'0.00';
				$orderVarify->vol_weight = $request->vol_weight?$request->vol_weight:'0.00';
				$orderVarify->length = $request->length?$request->length:'1';
				$orderVarify->breadth = $request->breadth?$request->breadth:'1';
				$orderVarify->height = $request->height?$request->height:'1';
				$orderVarify->latitude = $request->latitude?$request->latitude:'';
				$orderVarify->longitude = $request->longitude?$request->longitude:'';
				$orderVarify->hyperlocal_address = $request->hyperlocal_address?$request->hyperlocal_address:'';
				$orderVarify->postal_code = $request->postal_code?$request->postal_code:'0';
				$orderVarify->order_type = $request->order_type;
				$orderVarify->business_account = Auth::user()->warehouse->warehouse_code;
				$orderVarify->warehouse_code = Auth::user()->warehouse->warehouse_code;
				$orderVarify->warehouse_name = Auth::user()->warehouse->warehouse_name;
				$orderVarify->client_code = Auth::user()->client->client_code;
				$orderVarify->currency_code = 'INR';
				$orderVarify->consignment_type = 'Reverse';
				$orderVarify->shipping_label = '';
				$orderVarify->manifest_url = '';
				$orderVarify->invoice_url = '';
				$orderVarify->total_quantity = $totalQty;
				$orderVarify->invoice_no = $validated['order_id'];
				$orderVarify->invoice_amount = $invoice_amount;
				$orderVarify->no_of_invoice = '1';
				$orderVarify->invoice_date = date('Y-m-d H:i:s');
				$orderVarify->no_of_box = count($request->products);
				$orderVarify->awb_no = '';
				$orderVarify->courier_name = '';
				$orderVarify->courrier_id = 0;
				$orderVarify->remarks = '';
				$orderVarify->tracking_history = '';
				$orderVarify->order_status = 'Booked';
			
				DB::beginTransaction();
				try
				{
					$orderVarify->save();
					if($orderVarify->id)
					{
						foreach ($request->products as $product) 
						{
							$ProductDetails = new ProductDetails;
							if($product['product_id'] > 0)
							{
								$productVarify = ProductDetails::findOrFail($product['product_id']);
								if($productVarify)
								{
								    $productVarify->order_id = $orderVarify->id;
									$productVarify->product_code = $product['product_name']?$product['product_name']:'';
									$productVarify->product_hsn_code = '';
									$productVarify->product_description = $product['product_sku']?$product['product_sku']:'';
									$productVarify->product_quantity = $product['product_qty']?$product['product_qty']:0;
									$productVarify->product_price = $product['product_price']?$product['product_price']:0.00;
									$productVarify->save();
								}
							}
							else
							{
								$ProductDetails->order_id = $orderVarify->id;
								$ProductDetails->product_code = $product['product_name']?$product['product_name']:'';
								$ProductDetails->product_hsn_code = '';
								$ProductDetails->product_description = $product['product_sku']?$product['product_sku']:'';
								$ProductDetails->product_quantity = $product['product_qty']?$product['product_qty']:0;
								$ProductDetails->product_price = $product['product_price']?$product['product_price']:0.00;
								$ProductDetails->save();
							}
							
						}
						
						$returnMsg = 'Order updated successfully!';
						DB::commit();
					}
					return redirect('/rorders')->with('status', ''.$returnMsg);
				}
				catch (\Exception $e) 
				{
					DB::rollback();
					return Redirect::back()->with('error',$e->getMessage());
				}	
			}	
            else
			{
				return redirect('rorders')->with('status', 'Error code 404: Order not exist');
			}				
        } 
		catch (\Exception $e) 
		{
			return back()->with('error',$e->getMessage());
		}
    }
	public function downloadSample()
    {
        $csvData = "order_no*,order_type*,shipment_type,gst_no,consignment_type*,payment_mode*,shipping_charges*,cod_amount*,discount_amount*,tax_amount*,total_weight*,length*,breadth*,height*,billing_first_name*,billing_last_name,billing_company_name,billing_address_1*,billing_address_2,billing_phone_number*,billing_alternate_phone,billing_email*,billing_city*,billing_state*,billing_country*,billing_pincode*,shipping_first_name*,shipping_last_name,shipping_company_name,shipping_address_1*,shipping_address_2,shipping_phone_number*,shipping_alternate_phone,shipping_email,shipping_city*,shipping_state*,shipping_country*,shipping_pincode*,product_code1*,product_hsn_code1,product_description1*,product_quantity1*,product_price1*,product_weight1*";
        return Response::make($csvData, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=order-sample.csv',
        ]);
    }
    public function import(Request $request)
    {
	    if($request->hasFile('importFile')) 
		{ 
		    try {
		        DB::beginTransaction();
                $csvDetail = "order_no*,order_type*,shipment_type,gst_no,consignment_type*,payment_mode*,shipping_charges*,cod_amount*,discount_amount*,tax_amount*,total_weight*,length*,breadth*,height*,billing_first_name*,billing_last_name,billing_company_name,billing_address_1*,billing_address_2,billing_phone_number*,billing_alternate_phone,billing_email*,billing_city*,billing_state*,billing_country*,billing_pincode*,shipping_first_name*,shipping_last_name,shipping_company_name,shipping_address_1*,shipping_address_2,shipping_phone_number*,shipping_alternate_phone,shipping_email,shipping_city*,shipping_state*,shipping_country*,shipping_pincode*,product_code1*,product_hsn_code1,product_description1*,product_quantity1*,product_price1*,product_weight1*";
                $csvColumns = explode(',', $csvDetail);
                $file = $request->file('importFile');
                $filePath = $file->getRealPath();
                $csvData = array_map('str_getcsv', file($filePath));
                $headers = $csvData[0]; // Get the headers from the first row
                #dd($headers);
                
                $missingColumns = array_diff($csvColumns, $headers);

                if (count($missingColumns) > 0) {
                    return redirect()->back()->with('error','Missing columns: '.implode(',',$missingColumns));
                    
                }
                
                // Mandatory field validation for blank
                $filteredOrders = [];
                // Loop through the rows starting from the second row (index 1)
                // Initialize errorMessages array before the loop
                 
            // Initialize errorMessages array before the loop
            $errorMessages = [];
            for ($i = 0; $i < count($csvData); $i++) {
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

                foreach ($manData as $key => $value) {
                    // If the current key is "product_weight1*", set the flag to true
                    if ($key === "product_weight1*") {
                        $removeAsterisk = true;
                        continue; // Skip "product_weight1*" and move to the next iteration
                    }
                
                    // If the flag is true, remove asterisk from the current key
                    if ($removeAsterisk) {
                        $newKey = str_replace("*", "", $key);
                        $manData[$newKey] = $value;
                        unset($manData[$key]);
                    }
                }
             
                // Output the modified array
                foreach ($manData as $orderKey => $orderValue) {
                     
                    // Check if the key ends with '*'
                    if (substr($orderKey, -1) === '*' && ($orderValue === '' || $orderValue === null )) {
                        // Add an error message to the array
                        $errorMessages[] = 'Error: ' . $orderKey . ' is a mandatory field and cannot be blank.';
                        
                    }
                    
                }
                
                foreach ($rowData as $orderKey => $orderValue) {
                    if (strpos($orderKey, 'total_weight') !== false && $orderValue === '0') {
                        // Add an error message to the array
                        $errorMessages[] = 'Error: Total weight cannot be zero.';
                    }
                    // Check if the key contains "product_quantity" and the value is 0
                    if (strpos($orderKey, 'product_quantity') !== false && $orderValue === '0') {
                        // Add an error message to the array
                        $errorMessages[] = 'Error: product quantity cannot be zero.';
                    }
                
                    // Check if the key contains "product_price" and the value is 0
                    if (strpos($orderKey, 'product_price') !== false && $orderValue === '0') {
                        // Add an error message to the array
                        $errorMessages[] = 'Error: product price cannot be zero.';
                    }
                    if (strpos($orderKey, 'product_weight') !== false && $orderValue === '0') {
                        // Add an error message to the array
                        $errorMessages[] = 'Error: product weight cannot be zero.';
                    }
                }
                
                // If there are any error messages, redirect back with the messages
                if (!empty($errorMessages)) {
                    return redirect()->back()->withErrors($errorMessages);
                }
           #dd($manData);
                // Check if product code is empty or null
                $hasEmptyProductCode = false;
            
                // Iterate through product keys
                for ($j = 1; isset($rowData["product_code$j"]); $j++) {
                    $productCodeKey = "product_code$j";
                    $productDescriptionKey = "product_description$j";
                    $productQuantityKey = "product_quantity$j";
                    $productPriceKey = "product_price$j";
                    $productWeightKey = "product_weight$j";
            
                    // Check if both product code and other product details are null
                    if (
                        (is_null($rowData[$productCodeKey]) || $rowData[$productCodeKey] === '') &&
                        (is_null($rowData[$productDescriptionKey]) || $rowData[$productDescriptionKey] === '') &&
                        (is_null($rowData[$productQuantityKey]) || $rowData[$productQuantityKey] === '') &&
                        (is_null($rowData[$productPriceKey]) || $rowData[$productPriceKey] === '') &&
                        (is_null($rowData[$productWeightKey]) || $rowData[$productWeightKey] === '')
                    ) {
                        continue; // Continue to the next iteration if both product code and other product details are null
                    }
            
                    // Check if product code is empty or null
                    if (is_null($rowData[$productCodeKey]) || $rowData[$productCodeKey] === '') {
                        $errorMessages[] = 'Error in row ' . ($i + 1) . ': Product code is empty or null.';
                        $hasEmptyProductCode = true;
                        break; // Break out of the loop if an empty or null product code is found
                    } else {
                        // Check if other product details are missing
                        if (
                            empty($rowData[$productDescriptionKey]) ||
                            empty($rowData[$productQuantityKey]) ||
                            empty($rowData[$productPriceKey]) ||
                            empty($rowData[$productWeightKey])
                        ) {
                            $errorMessages[] = 'Error in row ' . ($i + 1) . ': Product details are incomplete for ' . $rowData[$productCodeKey];
                            $hasEmptyProductCode = true;
                            break; // Break out of the loop if incomplete product details are found
                        }
                    }
                }
            
                // If product code is not empty and other product details are available, add the order to the filtered orders
                if (!$hasEmptyProductCode) {
                    $filteredOrders[] = $rowData;
                }
            }
            
            // If there are any error messages, return them
            if (!empty($errorMessages)) {
                return redirect()->back()->withErrors($errorMessages);
            }
          
            if (!empty($filteredOrders)) {
                // Now $elementsAtIndex0 contains the elements at index 0 from each sub-array
                
    			$rules = 
    			[
    				'order_no' => 'string|max:25',
    				'order_type' => 'string|max:25',
    				'consignment_type' => 'string|max:10',
    				'payment_mode' => 'string|max:10',
    				'shipping_charges' => ['regex:/(?:[1-9]\d+|\d)(?:,\d\d)?$/'],
    				'tax_amount' => ['regex:/(?:[1-9]\d+|\d)(?:,\d\d)?$/'],
    				'discount_amount' => ['regex:/(?:[1-9]\d+|\d)(?:,\d\d)?$/'],
    				'cod_amount' => ['regex:/(?:[1-9]\d+|\d)(?:,\d\d)?$/'],
    				'total_weight' => ['regex:/(?:[1-9]\d+|\d)(?:,\d\d)?$/'],
    				'length' => 'integer|min:1',
    				'breadth' => 'integer|min:1',
    				'height' => 'integer|min:1',
    				'shipping_first_name' => 'string|max:25',
    				'shipping_address_1' => 'string|max:50',
    				'shipping_pincode' => 'integer|min:100000|max:999999',
    				'shipping_city' => 'string',
    				'shipping_state' => 'string',
    				'shipping_country' => 'string',
    				'shipping_phone_number' => ['regex:/^[0-9]{10}$/'],	
    				'billing_first_name' => 'string|max:25',
    				'billing_address_1' => 'string|max:50',
    				'billing_pincode' => 'integer|min:100000|max:999999',
    				'billing_city' => 'string',
    				'billing_state' => 'string',
    				'billing_country' => 'string',
    				'billing_phone_number' => ['regex:/^[0-9]{10}$/'],
    				
    			];
    			$csvHeaderData = array_shift($csvData); // Assuming the first row contains column names
                $csvHeader = str_replace('*', '', $csvHeaderData);
                $validator = Validator::make($csvData, $rules);
                // Check if validation fails
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
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
    					foreach ($failedRows as $failedRow) 
    					{
    						$rowErrors = implode('<br>', $failedRow['errors']);
    						$failedRowsMessages[] = "Row: " . implode(', ', $failedRow['data']) . "<br>" . $rowErrors;
    					}
    					return \Redirect::back()->with(['error' => implode('<br>', $failedRowsMessages)]);
    				} 
    				else 
    				{
    				    
    					$data = array_combine($csvHeader, $row);
    					 #dd($data);
    					try 
    					{
    					    if(Auth::user()->user_type == "isClient")
    						{
    							$client_code = Auth::user()->client->client_code;
    						}
    						else
    						{
    							$client_code = session('client.client_code');
    						}
    						
    						if(!Session::has('warehouse'))
    						{      
    							
    							$business_account = Auth::user()->warehouse->warehouse_code;     
    							$warehouse_code = Auth::user()->warehouse->warehouse_code;
    							$warehouse_name = Auth::user()->warehouse->warehouse_name;
    							$warehouse_address = Auth::user()->warehouse->address1;
    							$warehouse_address_2 = Auth::user()->warehouse->address2;
    							$warehouse_city = Auth::user()->warehouse->city;
    							$warehouse_state = Auth::user()->warehouse->state->state_name;
    							$warehouse_pincode = Auth::user()->warehouse->pincode;
    							$warehouse_phone_number = Auth::user()->warehouse->phone;
    						
    						}
    						else
    						{
    						
    							$business_account = session('warehouse.warehouse_code');
    							$warehouse_code = session('warehouse.warehouse_code');
    							$warehouse_name = session('warehouse.warehouse_name');
    							$warehouse_address =  session('warehouse.address1');
    							$warehouse_address_2 =  session('warehouse.address2');
    							$warehouse_city =  session('warehouse.city');
    							$warehouse_state =  session('warehouse.state.state_name');
    							$warehouse_pincode =  session('warehouse.pincode');
    							$warehouse_phone_number =  session('warehouse.phone');
    						}
    						
    					    $order =Order::where('order_no',$data['order_no'])->where('consignment_type','Reverse')->where('client_code',$client_code)->where('warehouse_code',$warehouse_code)->first();
    					    #dd($order);
    					    if($order){
    					        return redirect()->back()->with('error','Order already Exists');
    					    }
    						$app_orders = new Order;
    						$totalProductPrice = 0;
    						$totalProductQuantities=0;
                            $count = 0;
                            // Assuming the array keys are structured as "product_priceX"
                            for ($i = 1; isset($data["product_price$i"]); $i++) {
                                $totalProductPrice += floatval($data["product_price$i"]);
                                $totalProductQuantities += floatval($data["product_quantity$i"]);
                                $count = $i;
                            }
                    	   
    						$app_orders->order_no = $data['order_no'];
    						$app_orders->shipping_city = $data['shipping_city'];
    						$app_orders->payment_mode = $data['payment_mode'];
    						$app_orders->shipping_first_name = $data['shipping_first_name'];
    						$app_orders->shipping_address_1 = $data['shipping_address_1'];
    						$app_orders->shipping_pincode = $data['shipping_pincode'];
    						$app_orders->shipping_state = $data['shipping_state'];
    						$app_orders->shipping_country = $data['shipping_country'];
    						$app_orders->shipping_phone_number = $data['shipping_phone_number'];
    						$app_orders->total_amount = $totalProductPrice;
    						$app_orders->shipping_charges = $data['shipping_charges']?$data['shipping_charges']:0;
    						$app_orders->cod_amount = $data['cod_amount']?$data['cod_amount']:0;
    						$app_orders->tax_amount = $data['tax_amount'] ?$data['tax_amount'] :0;
    						$invoice_amount = $app_orders->total_amount + $app_orders->shipping_charges + $app_orders->cod_amount + $app_orders->tax_amount - $app_orders->discount_amount;
    						$app_orders->shipping_address_2 = $data['shipping_address_2']?$data['shipping_address_2']:'';
    						$app_orders->shipping_last_name = $data['shipping_last_name']?$data['shipping_last_name']:'';
    						$app_orders->shipping_email = $data['shipping_email']?$data['shipping_email']:'';
    						$app_orders->shipping_alternate_phone = $data['shipping_alternate_phone']?$data['shipping_alternate_phone']:'0';
    						$app_orders->shipping_company_name = $data['shipping_company_name']?$data['shipping_company_name']:'';
    						
    						
    						$app_orders->billing_first_name = $data['billing_first_name']?$data['billing_first_name']:'';
    						$app_orders->billing_last_name = $data['billing_last_name']?$data['billing_last_name']:'';
    						$app_orders->billing_company_name = $data['billing_company_name']?$data['billing_company_name']:'';
    						$app_orders->billing_address_1 = $data['billing_address_1']?$data['billing_address_1']:'';
    						$app_orders->billing_address_2 = $data['billing_address_2']?$data['billing_address_2']:'';
    						$app_orders->billing_pincode = $data['billing_pincode']?$data['billing_pincode']:'0';
    						$app_orders->billing_email = $data['billing_email']?$data['billing_email']:'';
    						
    						$app_orders->billing_city = $data['billing_city']?$data['billing_city']:'';
    						$app_orders->billing_state = $data['billing_state']?$data['billing_state']:'';
    						$app_orders->billing_country = $data['billing_country']?$data['billing_country']:'';
    						$app_orders->billing_phone_number = $data['billing_phone_number']?$data['billing_phone_number']:'0';
    						$app_orders->billing_alternate_phone = $data['billing_alternate_phone']?$data['billing_alternate_phone']:'0';
    						$app_orders->gst_no = $data['gst_no']?$data['gst_no']:'';
    						
    						$app_orders->weight_unit = 'grams';
    						$app_orders->dimension_unit = 'cm';
    						$app_orders->total_weight = $data['total_weight']?$data['total_weight']:'0.00';
    					
    						$app_orders->length = $data['length']?$data['length']:'1';
    						$app_orders->breadth = $data['breadth']?$data['breadth']:'1';
    						$app_orders->height = $data['height']?$data['height']:'1';
    						$sum = $app_orders->length * $app_orders->breadth * $app_orders->height;
                            $totalsum = $sum / 5000;
                            $volweight = ($totalsum * 1000);
    						$app_orders->volumetric_weight = $volweight ?? '0.00';
    						$app_orders->vol_weight = $volweight ??'0.00';
    						$app_orders->latitude ='';
    						$app_orders->longitude = '';
    						$app_orders->hyperlocal_address = '';
    						$app_orders->postal_code = '0';
    						$app_orders->request_partner = '';
    						$app_orders->order_type = $data['order_type'];
							$app_orders->business_account = $business_account;     
							$app_orders->warehouse_code = $warehouse_code;
							$app_orders->warehouse_name = $warehouse_name;
							$app_orders->warehouse_address = $warehouse_address;
							$app_orders->warehouse_address_2 = $warehouse_address_2;
							$app_orders->warehouse_city = $warehouse_city;
							$app_orders->warehouse_state = $warehouse_state;
							$app_orders->warehouse_pincode = $warehouse_pincode;
							$app_orders->warehouse_phone_number = $warehouse_phone_number;
    						$app_orders->client_code = $client_code;
    						$app_orders->currency_code = 'INR';
    						$app_orders->consignment_type = 'Reverse';
    						$app_orders->shipping_label = '';
    						$app_orders->manifest_url = '';
    						$app_orders->invoice_url = '';
    						$app_orders->total_quantity = $totalProductQuantities;
    						$app_orders->invoice_no = $data['order_no'];
    						$app_orders->invoice_amount = $invoice_amount;
    						$app_orders->no_of_invoice = '1';
    						$app_orders->invoice_date = date('Y-m-d H:i:s');
    						$app_orders->no_of_box = $count;
    						$app_orders->awb_no = '';
    						$app_orders->courier_name = '';
    						$app_orders->courrier_id = '0';
    						$app_orders->remarks = '';
    						$app_orders->tracking_history = '';
    						$app_orders->order_status = 'Booked';
    						$app_orders->omnee_order = random_int(100,9999);
    						#dd($app_orders);
    						try{
    							$check = Order::where('order_no',$app_orders->order_no)->get();
    							if(count($check) > 0){
    								$insertdOrderid = $check[0]->id;
    							}
    							else{
    							    
    								$app_orders->save();
    								$insertdOrderid = $app_orders->id;
    							}
    							
    							if($insertdOrderid > 0)
    							{	
    							    
    							    for ($x = 1; !empty($data["product_code$x"]) && !empty($data["product_price$x"]) && !empty($data["product_quantity$x"]) && !empty($data["product_weight$x"]) && !empty($data["product_description$x"]); $x++) {
    							        $product = ProductDetails::where('product_code',$data["product_code$x"])->where('order_id',$insertdOrderid)->first();
    							        if($product){
    							            return \Redirect::back()->with(['error' =>'Duplicate product on same order no,Try again']);
    							        }
                                        $ProductDetails = new ProductDetails;
                                        $ProductDetails->order_id = $insertdOrderid;
                                        $ProductDetails->product_code = $data["product_code$x"] ?? '';
                                        $ProductDetails->product_hsn_code = '';
                                        $ProductDetails->product_description = $data["product_description$x"] ?? '';
                                        $ProductDetails->product_quantity = $data["product_quantity$x"] ?? 0;
                                        $ProductDetails->product_price = $data["product_price$x"] ?? 0.00;
                                        $ProductDetails->no_of_box = 1;
                                        $ProductDetails->product_weight_unit = 'grams';
                                        $ProductDetails->product_weight = $data["product_weight$x"] ?? '0.00';
                                        $ProductDetails->product_lbh_unit = 'cm';
                                        $ProductDetails->product_breadth = $data['breadth'] ?? '1';
                                        $ProductDetails->product_height = $data['height'] ?? '1';
                                        $ProductDetails->product_length = $data['length'] ?? '1';
                                        $ProductDetails->save();
                                    }

    							}
    							
						    $returnMsg = 'Order uploaded successfully..!';
    							
    						}catch (\Exception  $e) {
    							return \Redirect::back()->with(['error' =>$e->getMessage()]);
    						}
    					} 
    					catch (\PDOException  $e) 
    					{
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
    							return \Redirect::back()->with(['error' =>$errorMessage]);
    						} 
    						else 
    						{
    							$errorMessage = "An error occurred while processing the data.";
    							return \Redirect::back()->with(['error' =>$errorMessage]);
    						}
    					}
    				}
    			}
            DB::commit();
            return redirect()->back()->with('status', $returnMsg);
            	 
		    } 
		    else {
                $errorMessages[] = 'No orders with non-empty product codes and other product details found.';
                return redirect()->back()->withErrors($errorMessages);
            }
		}
	    catch (\Exception $e) {
            // Rollback the transaction in case of any exception
            DB::rollback();
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
	}
		else
		{
			#return redirect()->back()->with('status', 'Please check file should be csv');
			$dbErrors[] = 'Please check file should be csv';
			return \Redirect::back()->with(['error' =>implode('<br>', $dbErrors)]);
		}
    }
   
}
