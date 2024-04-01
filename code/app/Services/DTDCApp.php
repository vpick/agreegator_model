<?php

namespace App\Services;

use App\Interfaces\AppOrderProcessInterface;
use App\Models\Order;
class DTDCApp implements AppOrderProcessInterface
{
    public function app_login($mapArray)
	{
	}
    public function processOrder($shipment,$mapArray)
    {
	    #echo "DTDCApp";die;
	    $result['message'] = 'No configuration on DTDCApp';
		$result['status'] = false;
		return $result;
		
	}
	 public function reprocessOrder($shipment,$mapArray)
    {
        #echo "DTDCApp";die;
        $result['message'] = 'No configuration on DTDCApp';
		$result['status'] = false;
		return $result;
    }
    public function processShipOrder($shipmentDetail,$mapArray){}
	
    public function trackShipment($awbNo,$mapArray)
    {
            
    }
    public function trackSingleShipment($awbNo,$mapArray)
    {
		
        
    }
    public function cancelledShipment($shipment,$mapArray)
    {
       
    }
    public function serviceability($shipment,$mapArray)
    {
           
    }
    public function serviceabilitylist($mapArray)
    {
            
    }
    public function courier($mapArray)
    {
           
    }
    public function ndr_shipment($mapArray)
    {
           
    }
	public function ndr_processed($ndrdata,$mapArray)
    {
		    
    }
    public function manifest($awbNo,$mapArray)
    {
		
    }
    function is_JSON($string)
	{
		
	}
	function webhook_response()
	{
		
	}
}