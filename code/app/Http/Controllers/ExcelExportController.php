<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Exports\OrderExport;
use App\Exports\ReverseOrderExport;
use App\Exports\PincodeExport;
use App\Exports\ShipmentTypeExport;
use App\Exports\ZonecodeExport;
use App\Exports\UserExport;
use Maatwebsite\Excel\Facades\Excel;

class ExcelExportController extends Controller
{
    public function exportOrder(Request $request)
	{
	    $status= $request->input('ord');
	    $fromDate = $request->input('from_date');
        $orderId = $request->input('order_id');
        $awbNo = $request->input('awb_no');
        $customerName = $request->input('customer_name');
        $paymentMode = $request->input('payment_mode');
        $orderStatus = $request->input('shipment_status');
        return Excel::download(
            new OrderExport($fromDate, $orderId, $awbNo, $customerName, $paymentMode, $orderStatus, $status),
            'orders.xlsx'
        );
	}
	public function exportReverseOrder(Request $request)
	{
	    $status= $request->input('ord');
	    $fromDate = $request->input('from_date');
        $orderId = $request->input('order_id');
        $awbNo = $request->input('awb_no');
        $customerName = $request->input('customer_name');
        $paymentMode = $request->input('payment_mode');
        $orderStatus = $request->input('shipment_status');
        return Excel::download(
            new ReverseOrderExport($fromDate, $orderId, $awbNo, $customerName, $paymentMode, $orderStatus, $status),
            'rorders.xlsx'
        );
	}
	public function exportPincode(Request $request)
	{
	    $pincode = $request->input('pincode');
        $district = $request->input('district');
        $city = $request->input('city');
        $state = $request->input('state');
        return Excel::download(
            new PincodeExport($pincode,$district,$city,$state),
            'pincode.xlsx'
        );
	}
	public function exportZonecode(Request $request)
	{
	    $zonecode = $request->input('zonecode');
		$pincode = $request->input('pincode');
		$courier = $request->input('courier');
        $hubname = $request->input('hubname');
		$city = $request->input('city');
        $state = $request->input('state');
		return Excel::download(
            new ZonecodeExport($zonecode,$pincode,$courier,$hubname,$city,$state),
            'zonecode.xlsx'
        );
	}
	public function exportShipmentType(Request $request)
	{
	    $shipment_type = $request->input('shipment_type');
        
        return Excel::download(
            new ShipmentTypeExport($shipment_type),
            'shipment_type.xlsx'
        );
	}
	public function exportUser(Request $request)
	{
	    
	    $user_code = $request->input('user_code');
		$user_type = $request->input('user_type');
		$status = $request->input('status');
        
		return Excel::download(
            new UserExport($user_code,$user_type,$status),
            'users.xlsx'
        );
	}
}