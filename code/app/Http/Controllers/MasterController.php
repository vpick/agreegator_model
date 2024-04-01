<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Models\Client;
use App\Models\Warehouse;
use App\Models\Order;

use Auth,DB,Session,DateTime;

class MasterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        if(Auth::user()->user_type =='isCompany'){
            return view('company-app.company-dashboard');
        }
        else if(Auth::user()->user_type =='isClient'){
            return view('client-app.client-dashboard');
        }
        else
        {
            return view('warehouse-app.warehouse-dashboard');
        }
    }
    public function companyDashboard_bkp(Request $request){ 
        if ($request->input('from_date')) {
            $dateRange = $request->input('from_date');
            $range = explode(' - ', $dateRange);
            $from_date = date("Y-m-d", strtotime($range[0]));
            $to_date = date("Y-m-d", strtotime($range[1]));
        } else {
            $to_date = date("Y-m-d");
            $from_date = date('Y-m-d', strtotime('-30 days', strtotime($to_date)));
        }
        $endDate = date('Y-m-d');
        $startDate = date('Y-m-d', strtotime('-6 months'));
        
        $items = DB::table('orders')
        ->join('clients', 'clients.client_code', '=', 'orders.client_code')
        ->leftJoin('companies', 'companies.id', '=', 'clients.company_id')
        ->select(
            DB::raw("COUNT(orders.id) as total_order"),
            DB::raw("SUM(CASE WHEN orders.order_status = 'Delivered' THEN 1 ELSE 0 END) as total_delivered"),
            DB::raw("MONTHNAME(orders.created_at) as month_name"),
            DB::raw("MONTH(orders.created_at) as month_no"),
            DB::raw("YEAR(orders.created_at) as year")
        )
        ->whereBetween(DB::raw('DATE(orders.created_at)'), [$startDate, $endDate])
        ->whereYear('orders.created_at', date('Y'))
        ->groupBy(DB::raw("MONTHNAME(orders.created_at)"), DB::raw("MONTH(orders.created_at)"), DB::raw("YEAR(orders.created_at)"))
        ->orderBy('year', 'ASC')
        ->orderBy('month_no', 'ASC')
        ->get()
        ->toArray();
        $order = DB::table('orders')
            ->Join('clients', 'clients.client_code', '=', 'orders.client_code')
            ->leftJoin('companies', 'companies.id', '=', 'clients.company_id')
            ->whereBetween(DB::raw('DATE(orders.created_at)'), [$from_date, $to_date])
            ->get();

        $data['order'] = $order->count();  
        // You can simplify the following block using an array of statuses
        $statuses = ['ship', 'booked', 'cancelled', 'rto', 'pending pickup', 'in transit', 'Delivered'];
        foreach ($statuses as $status) {
            $data[strtolower($status)] = $order->filter(function ($item) use ($status) {
                return strcasecmp($item->order_status, $status) === 0;
            })->count();
        }   
        $data['revenue'] = $order->where('order_status', 'Delivered')->sum('invoice_amount');        
        $data['cod'] = $order->where('payment_mode', 'COD')->count();    
        $data['prepaid'] = $order->where('payment_mode', 'prepaid')->count();
        $shippingCities = $order->pluck('shipping_city')->filter(function ($value) {
            return is_string($value);
        })->toArray();    
        $cityCounts = array_count_values($shippingCities);
        arsort($cityCounts);
        $topCities = array_slice($cityCounts, 0, 10);
        $data['cities'] = [];        
        foreach ($topCities as $city => $count) {
            $data['cities'][] = [
                'city' => $city,
                'count' => $count,
            ];
        }   
        $data['dateRange'] = $dateRange;
        $data['monthlyReport'] = $items;
        return response()->json(['data' => $data]);
    }
    public function companyDashboard(Request $request)
    { 
        $to_date = '0000-00-00';
        $from_date ='0000-00-00';
        if ($request->input('from_date')) 
        {
            $dateRange = $request->input('from_date');
            $range = explode(' - ', $dateRange);
            $from_date = date("Y-m-d", strtotime($range[0]));
            $to_date = date("Y-m-d", strtotime($range[1]));
        } 
        else 
        {
            $to_date = date("Y-m-d");
            $from_date = date('Y-m-d', strtotime('-30 days', strtotime($to_date)));
        }
        $diff = abs(strtotime($from_date) - strtotime($to_date));
        $years = floor($diff / (365*60*60*24));
        $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
        $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
        $interval = (int)$days;
        
        $data['client_data'] = Client::select(           
                                    DB::raw("count(*) as total_client"),
                                    DB::raw("SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as active_client"),
                                    DB::raw("SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as inactive_client")
                                )
                                ->where('company_id',Auth::user()->company_id)
                                ->get();
        $data['active_clients'] = Client::where('company_id',Auth::user()->company_id)
                                ->orderBy('client_code')   
                                ->where('status',1)                                       
                                ->pluck('client_code')
                                ->toArray();
        $data['inactive_clients'] = Client::where('company_id',Auth::user()->company_id)
                                ->orderBy('client_code')  
                                ->where('status',0)                                          
                                ->pluck('client_code')
                                ->toArray();
        $data['clients'] = Client::where('company_id',Auth::user()->company_id)
                                ->orderBy('client_code')                                          
                                ->select('client_code')
                                ->get()->toArray();
                            
        $data['averageShipment'] = Order::join('clients', 'clients.client_code', '=', 'orders.client_code')
                                ->leftJoin('companies', 'companies.id', '=', 'clients.company_id')
                                ->select(
                                    DB::raw("COUNT(orders.id)/$interval as total_order"),
                                    DB::raw("SUM(orders.total_weight)/$interval as total_weight"),
                                    DB::raw("SUM(orders.total_amount)/$interval as total_amount")
                                )
                                
                                ->get()->toArray();
                                
        $data['gmvData'] = Order::join('clients', 'clients.client_code', '=', 'orders.client_code')
                                ->leftJoin('companies', 'companies.id', '=', 'clients.company_id')
                                ->select( DB::raw("(SUM(orders.total_amount) / COUNT(orders.id)) as gmv"))
                                ->whereBetween(DB::raw('DATE(orders.created_at)'), [$from_date, $to_date])
                                ->get()->toArray();
                                
        $data['topClients'] = DB::table('orders as ord')
                                ->select('ord.client_code', DB::raw('COUNT(ord.id) as total_shipment'), 
                                         DB::raw('SUM(ord.total_weight)/1000 as total_weight'), 
                                         DB::raw('SUM(ord.total_amount) as total_amount'), 
                                         DB::raw('(SUM(ord.total_amount)/COUNT(ord.id)) as gmv'))
                                ->leftJoin('clients as cl', 'cl.client_code', '=', 'ord.client_code')
                                ->leftJoin('companies as cm', 'cm.id', '=', 'cl.company_id')
                                ->where('cm.id', '=', Auth::user()->company_id)
                                ->whereBetween(DB::raw('DATE(ord.created_at)'), [$from_date, $to_date])
                                ->groupBy('ord.client_code')
                                ->orderByDesc('total_shipment')
                                ->limit(10)
                                ->get();
        $data['topCities'] = DB::table('orders as ord')
                                ->select('ord.shipping_city', DB::raw('COUNT(ord.id) as total_shipment'), 
                                         DB::raw('SUM(ord.total_weight)/1000 as total_weight'), 
                                         DB::raw('SUM(ord.total_amount) as total_amount'), 
                                         DB::raw('(SUM(ord.total_amount)/COUNT(ord.id)) as gmv'))
                                ->leftJoin('clients as cl', 'cl.client_code', '=', 'ord.client_code')
                                ->leftJoin('companies as cm', 'cm.id', '=', 'cl.company_id')
                                ->where('cm.id', '=', Auth::user()->company_id)
                                ->whereBetween(DB::raw('DATE(ord.created_at)'), [$from_date, $to_date])
                                ->groupBy('ord.shipping_city')
                                ->orderByDesc('total_shipment')
                                ->limit(10)
                                ->get();
        $data['topDsps'] = DB::table('orders as ord')
                                ->select('ord.request_partner', DB::raw('COUNT(ord.id) as total_shipment'), 
                                         DB::raw('SUM(ord.total_weight)/1000 as total_weight'), 
                                         DB::raw('SUM(ord.total_amount) as total_amount'), 
                                         DB::raw('(SUM(ord.total_amount)/COUNT(ord.id)) as gmv'))
                                ->leftJoin('clients as cl', 'cl.client_code', '=', 'ord.client_code')
                                ->leftJoin('companies as cm', 'cm.id', '=', 'cl.company_id')
                                ->where('cm.id', '=', Auth::user()->company_id)
                                ->where('ord.request_partner', '!=', '')
                                ->whereBetween(DB::raw('DATE(ord.created_at)'), [$from_date, $to_date])
                                ->groupBy('ord.request_partner')
                                ->orderByDesc('total_shipment')
                                ->limit(10)
                                ->get();  
        return $data;
    }
    public function clientDashboard(Request $request)
    { 
        
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
		$to_date = '0000-00-00';
        $from_date ='0000-00-00';
        if ($request->input('from_date')) 
        {
            $dateRange = $request->input('from_date');
            $range = explode(' - ', $dateRange);
            $from_date = date("Y-m-d", strtotime($range[0]));
            $to_date = date("Y-m-d", strtotime($range[1]));
        } 
        else 
        {
            $to_date = date("Y-m-d");
            $from_date = date('Y-m-d', strtotime('-30 days', strtotime($to_date)));
        }
        $previousStartDateFormatted = '0000-00-00';
        $previousEndDateFormatted  = '0000-00-00';
        if($from_date == $to_date){
          
            $startDate = new DateTime($from_date);
            $endDate = new DateTime($to_date);
            // Get the previous day for the start date
            $previousStartDate = clone $startDate;
            $previousStartDate->modify('-1 day');
            
            // Get the previous day for the end date
            $previousEndDate = clone $endDate;
            $previousEndDate->modify('-1 day');
            
            // Format the results
            $previousStartDateFormatted = $previousStartDate->format('Y-m-d');
            $previousEndDateFormatted = $previousEndDate->format('Y-m-d');
        }
        else
        {
            
            //previous time interval
            $startDate = new DateTime($from_date);
            $endDate = new DateTime($to_date);
            
            // Calculate the interval
            $interval = $startDate->diff($endDate);
            
            // Get the previous time period
            $previousStartDate = clone $startDate;
            $previousEndDate = clone $endDate;
            
            $previousStartDate->sub($interval);
            $previousEndDate->sub($interval);
            
            $previousStartDateFormatted = $previousStartDate->format('Y-m-d');
            $previousEndDateFormatted = $previousEndDate->format('Y-m-d');
        }
       
        $items = Order::select(
                        DB::raw("COUNT('*') as total_order"), 
                        DB::raw("SUM(CASE WHEN order_status = 'Delivered' THEN 1 ELSE 0 END) as total_delivered"),
                        DB::raw("DATE_FORMAT(created_at, '%d %b %Y') as formatted_date")
                )
                ->where('client_code',$client_code)
                ->where('warehouse_code',$warehouse_code)
                ->where('consignment_type','Forward')
                ->whereBetween(DB::raw('DATE(created_at)'), [$from_date, $to_date])
                ->groupBy('formatted_date')
                ->orderBy('formatted_date', 'Desc')
                ->get()
                ->toArray();
              usort($items, function ($a, $b) {
                $dateA = strtotime($a["formatted_date"]);
                $dateB = strtotime($b["formatted_date"]);
                return $dateA - $dateB;
            });
        
        $order = Order::where('client_code',$client_code)->where('warehouse_code',$warehouse_code)->where('consignment_type','Forward')->whereBetween(DB::raw('DATE(created_at)'), [$from_date, $to_date])->get();        
        
        $data['order'] = $order->count();
        
        $data['ship'] = $order->filter(function ($item) {
            return strcasecmp($item->order_status, 'ship') === 0;
        })->count();
		$data['book'] = $order->filter(function ($item) {
            return strcasecmp($item->order_status, 'booked') === 0;
        })->count();
        
        $data['ship'] = $order->filter(function ($item) {
            return strcasecmp($item->order_status, 'ship') === 0;
        })->count();
		$data['cancelled'] = $order->filter(function ($item) {
            return strcasecmp($item->order_status, 'cancelled') === 0;
        })->count();
        $data['rto'] = $order->filter(function ($item) {
            return strcasecmp($item->order_status, 'rto') === 0;
        })->count();
		$data['pending_pick'] = $order->filter(function ($item) {
            return strcasecmp($item->order_status, 'pending pickup') === 0;
        })->count();
        $data['out_for_delivery'] = $order->filter(function ($item) {
            return strcasecmp($item->order_status, 'out for delivery') === 0;
        })->count();
        $data['delay_shipment'] = $order->filter(function ($item) {
            return strcasecmp($item->order_status, 'delay shipment') === 0;
        })->count();
        $data['transit'] = $order->filter(function ($item) {
            return strcasecmp($item->order_status, 'in transit') === 0;
        })->count();
		$data['delivered'] = $order->filter(function ($item) {
            return strcasecmp($item->order_status, 'delivered') === 0;
        })->count();
        $data['revenue'] = $order->filter(function ($item) {
            return strcasecmp($item->order_status, 'delivered') === 0;
        })->sum('invoice_amount');
        $data['cod'] = $order->where('payment_mode', 'cod')->count();
        $data['prepaid'] = $order->where('payment_mode', 'prepaid')->count();
        
        //top city
        
        
        $data['dateRange'] = $dateRange;
        $data['monthlyReport'] = $items;   
        $couriers = Order::select('request_partner', DB::raw('SUM(CASE WHEN order_status = "delivered" THEN 1 ELSE 0 END) as deliveredDspOrder'), DB::raw('COUNT(id) as totalDspOrder'))
                ->where('client_code', '=', $client_code)
                ->where('warehouse_code', '=', $warehouse_code)
                ->where('consignment_type', '=', 'Forward')
                ->where('request_partner', '!=', '')
                ->whereBetween(DB::raw('DATE(created_at)'), [$from_date, $to_date])
                ->groupBy('request_partner')
                ->get();
            
            $data['dsps'] = [];
            foreach ($couriers as $courier) {
                $data['dsps'][] = [
                    'dsp' => $courier->request_partner,
                    'dspCount' => $courier->totalDspOrder, // Access using the alias
                    'deliveredDspCount' => $courier->deliveredDspOrder, // Access using the alias
                ];
            }



        $data['outDeliveryOrder'] = Order::where('client_code',$client_code)->where('warehouse_code',$warehouse_code)->where('consignment_type','Forward')->whereBetween(DB::raw('DATE(created_at)'), [$from_date, $to_date])->where('order_status', 'out for delivery')->get();
       
        
        $data['previousData'] = Order::select(
                DB::raw('COUNT(*) as totalOrderCount'),
                DB::raw('SUM(CASE WHEN order_status = "delivered" THEN 1 ELSE 0 END) as lastDeliveredCount'),
                DB::raw('SUM(CASE WHEN order_status = "ship" THEN 1 ELSE 0 END) as lastShipCount'),
                DB::raw('SUM(CASE WHEN order_status = "booked" THEN 1 ELSE 0 END) as lastBookedCount'),
                DB::raw('SUM(CASE WHEN order_status = "rto" THEN 1 ELSE 0 END) as lastRtoCount'),
                DB::raw('SUM(CASE WHEN order_status = "pending pickup" THEN 1 ELSE 0 END) as lastPendingpickCount'),
                DB::raw('SUM(CASE WHEN order_status = "cancelled" THEN 1 ELSE 0 END) as lastCancelledCount'),
                DB::raw('SUM(CASE WHEN order_status = "out for delivery" THEN 1 ELSE 0 END) as lastOutDeliveredCount'),
                DB::raw('SUM(CASE WHEN order_status = "in transit" THEN 1 ELSE 0 END) as lastIntransitCount')
            )
            ->where('client_code', '=', $client_code)
            ->where('warehouse_code', '=', $warehouse_code)
            ->where('consignment_type', '=', 'Forward')
            ->whereBetween(DB::raw('created_at'), [$previousStartDateFormatted, $previousEndDateFormatted])
            ->get();
        $shippingCities = Order::where('client_code', '=', $client_code)
                        ->where('warehouse_code', '=', $warehouse_code)
                        ->where('consignment_type', '=', 'Forward')
                        ->whereBetween(DB::raw('created_at'), [$from_date, $to_date])
                        ->pluck('shipping_city')
                        ->toArray();
                    
        $cityCounts = array_count_values($shippingCities);
        arsort($cityCounts);
        
        $rankedCities = [];
        $rank = 1;
        
        foreach ($cityCounts as $city => $count) {
            $rankedCities[] = [
                'rank' => $rank++,
                'city' => $city,
                'count' => $count,
            ];
        }
        
        $lastDataCities = Order::where('client_code', '=', $client_code)
            ->where('warehouse_code', '=', $warehouse_code)
            ->where('consignment_type', '=', 'Forward')
            ->whereBetween(DB::raw('created_at'), [$previousStartDateFormatted, $previousEndDateFormatted])
            ->pluck('shipping_city')
            ->toArray();

        $lastDataCityCounts = array_count_values($lastDataCities);
        arsort($lastDataCityCounts);
        
        $lastRankedCities = [];
        $lastrank = 1;
        
        foreach ($lastDataCityCounts as $city => $count) {
            $lastRankedCities[] = [
                'last_rank' => $lastrank++,
            ];
        }
        
        foreach ($rankedCities as $index => $cityData) {
            $lastRank = isset($lastRankedCities[$index]['last_rank']) ? $lastRankedCities[$index]['last_rank'] : 0;
            $rankedCities[$index]['last_rank'] = $lastRank;
        }
        
        $data['cities'] = $rankedCities;
        return response()->json(['data' => $data]);
    }
    public function warehouseDashboard(Request $request){ 
        if ($request->input('from_date')) {
            $dateRange = $request->input('from_date');
            $range = explode(' - ', $dateRange);
            $from_date = date("Y-m-d", strtotime($range[0]));
            $to_date = date("Y-m-d", strtotime($range[1]));
        } else {
            $to_date = date("Y-m-d");
            $from_date = date('Y-m-d', strtotime('-30 days', strtotime($to_date)));
        }
        $dateRange =$request->input('from_date');
        $endDate = date('Y-m-d'); 
        $startDate = date('Y-m-d', strtotime('-6 months'));
        
        $items = Order::select(
            DB::raw("COUNT('*') as total_order"), 
            DB::raw("SUM(CASE WHEN order_status = 'Delivered' THEN 1 ELSE 0 END) as total_delivered"),
            DB::raw("MONTHNAME(created_at) as month_name"),
            DB::raw("MONTH(created_at) as month_no"),
            DB::raw("YEAR(created_at) as year")
        )
        ->where('warehouse_code', Auth::user()->warehouse->warehouse_code)
        ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
        ->whereYear('created_at', date('Y'))
        ->groupBy(DB::raw("MONTHNAME(created_at)"), DB::raw("MONTH(created_at)"), DB::raw("YEAR(created_at)"))
        ->orderBy('year', 'ASC')
        ->orderBy('month_no', 'ASC')
        ->get()
        ->toArray();
        
        $order = Order::where('warehouse_code',Auth::user()->warehouse->warehouse_code)->whereBetween(DB::raw('DATE(created_at)'), [$from_date, $to_date])->get();        
        $data['order'] = $order->count();
        $data['ship'] = $order->filter(function ($item) {
            return strcasecmp($item->order_status, 'ship') === 0;
        })->count();
		$data['book'] = $order->filter(function ($item) {
            return strcasecmp($item->order_status, 'booked') === 0;
        })->count();
		$data['cancelled'] = $order->filter(function ($item) {
            return strcasecmp($item->order_status, 'cancelled') === 0;
        })->count();
        $data['rto'] = $order->filter(function ($item) {
            return strcasecmp($item->order_status, 'rto') === 0;
        })->count();
		$data['pending_pick'] = $order->filter(function ($item) {
            return strcasecmp($item->order_status, 'pending pickup') === 0;
        })->count();
        
        $data['transit'] = $order->filter(function ($item) {
            return strcasecmp($item->order_status, 'in transit') === 0;
        })->count();
        
       
		$data['delivered'] = $order->filter(function ($item) {
            return strcasecmp($item->order_status, 'delivered') === 0;
        })->count();
        $data['revenue'] = $order->filter(function ($item) {
            return strcasecmp($item->order_status, 'delivered') === 0;
        })->sum('invoice_amount');
        $data['cod'] = $order->where('payment_mode', 'cod')->count();
        $data['prepaid'] = $order->where('payment_mode', 'prepaid')->count();
        $shippingCities = $order->pluck('shipping_city')->toArray();
        $cityCounts = array_count_values($shippingCities);
        arsort($cityCounts);
        $topCities = array_slice($cityCounts, 0, 10);
        $data['cities'] = [];
        foreach ($topCities as $city => $count) {
            $data['cities'][] = [
                'city' => $city,
                'count' => $count,
            ];
        }
        $data['dateRange'] = $dateRange;
        $data['monthlyReport'] = $items;      
        return response()->json(['data' => $data]);
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }
}
