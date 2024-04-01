<?php
namespace App\Exports;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use DB;
class OrderExport implements FromCollection, WithHeadings ,WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $fromDate;
    protected $orderId;
    protected $awbNo;
    protected $customerName;
    protected $paymentMode;
    protected $orderStatus;
     protected $status;
    public function __construct($fromDate, $orderId, $awbNo, $customerName, $paymentMode, $orderStatus, $status)
    {
        $this->fromDate = $fromDate;
        $this->orderId = $orderId;
        $this->awbNo = $awbNo;
        $this->customerName = $customerName;
        $this->paymentMode = $paymentMode;
        $this->orderStatus = $orderStatus;
        $this->status = $status;
    }

    public function collection()
    {
        if(!Session::has('warehouse')){
            
             $warehouse_code = Auth::user()->warehouse->warehouse_code;
         }
		else{
		     
		    $warehouse_code =session('warehouse.warehouse_code');
		   
		}
	
		$order = Order::orderByDesc('id');
		
		if(!empty($this->orderId)){
			$orderId =$this->orderId;
			$oid = explode(',',$orderId);
			$order = $order->whereIn('order_no',$oid);
		}
			
		if(!empty($this->awbNo)){
			$awbNo =$this->awbNo;
			$awb_num = explode(',',$awbNo);
			
			$order = $order->whereIn('awb_no',$awb_num);
		}
		if(!empty($this->paymentMode)){
			$order = $order->where('payment_mode',$this->paymentMode);
		}
		
		if(!empty($this->customerName)){
			$order = $order->where('shipping_first_name',$this->customerName);
		}
		
		if(!empty($this->fromDate)){
			$fromdate = $this->fromDate;
		    $date = explode('-',$fromdate);
		    $from_date = date('Y-m-d', strtotime($date[0]));
		    $to_date = date('Y-m-d', strtotime($date[1]));
		  
			$order = $order->whereBetween(DB::raw('DATE(created_at)'),[$from_date,$to_date]);
		}
	
		$ordStatus = $this->orderStatus;
			
		if(!empty($ordStatus))
		{
		  
			$status = $ordStatus;
		
			$order->where('warehouse_code',$warehouse_code)->whereIn('order_status', $status);
			
		}
		if(!empty($this->status))
		{
			$order->where('warehouse_code',$warehouse_code)->where('order_status', $this->status);
				
		}
		    
			$order->where('warehouse_code',$warehouse_code);
		
		$order = $order->get();
	#dd($order);
        return $order;
    }
	public function headings(): array
    {
        return ['Order ID', 'Order Date', 'Payment','Pay Mode','Ship to First Name','Ship to Last Name','Shipping Phone No.','Shipping Alternate Phone No.','Shipping Email Id','Shipping Address1','Shipping Address2',
        'Shipping City', 'Shipping State','Shipping Pincode','Bill to First Name','Bill to Last Name','Billing Company Name','Billing Phone No.','Billing Alternate Phone No.','Billing Email Id','Billing Address1','Billing Address2',
        'Billing City', 'Billing State','Billing Pincode','Partners','Last Mile Partner','Warehouse','Weight','AWB No.','Shipment Status','Length','Breadth','Height','Volumetric Weight']; // Empty array to exclude headers
    }
    public function map($row): array
    {
        #dd($row);
        // Map data to desired format
        return 
        [
            $row->order_no,
            $row->created_at,
            $row->total_amount,
            $row->payment_mode,
            $row->shipping_first_name,
            $row->shipping_last_name,
            $row->shipping_phone_number,
            $row->shipping_alternate_phone,
            $row->shipping_email,
            $row->shipping_address1,
            $row->shipping_address2,
            $row->shipping_city,
            $row->shipping_state,
            $row->shipping_pincode,
            $row->billing_first_name,
            $row->billing_last_name,
            $row->billing_company_name,
            $row->billing_phone_number,
            $row->billing_alternate_phone,
            $row->billing_email,
            $row->billing_address_1,
            $row->billing_address_2,
            $row->billing_city,
            $row->billing_state,
            $row->billing_pincode,
            $row->request_partner,
            $row->courier_name,
            $row->warehouse_code.'-'.$row->warehouse_city,
            $row->total_weight/1000,
            $row->awb_no,
            $row->order_status,
            $row->length,
            $row->breadth,
            $row->height,
            $row->volumetric_weight,
            
            
        ];
    }
}
