<?php

namespace App\Interfaces;

interface AppOrderProcessInterface
{
    public function app_login($mapArray);
    public function processOrder($shipment,$mapArray);
    public function processShipOrder($shipment,$mapArray);
    public function reprocessOrder($shipment,$mapArray);
	public function cancelledShipment($shipment,$mapArray);
    public function serviceability($shipment,$mapArray);
    public function serviceabilitylist($mapArray);
    public function courier($mapArray);
    public function trackShipment($awbNo,$mapArray);
    public function trackSingleShipment($awbNo,$mapArray);
    public function ndr_shipment($mapArray);
	public function ndr_processed($ndrdata,$mapArray);
	public function manifest($manifest,$mapArray);
	public function processWarehouse($warehouse,$mapArray);
	public function webhook_response();
}