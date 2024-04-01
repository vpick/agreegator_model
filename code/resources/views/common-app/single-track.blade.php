@extends('common-app/master')
@section('title', 'Shipment Tracking')
@section('content')
<!--<section class="py-5"></section>-->
<!-- Header Section-->
<style>
   .status-delivery-heading {
    background: #288b46;
    color: #fff;
    padding: 20px;
}
.status-delivery-box {
    background: #fff;
    padding: 20px;
    color: #12263f;
}
.track {
    position: relative;
    /* background-color: #ddd; */
    height: 7px;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    margin-bottom: 40px;
    margin-top: 35px;
}
.status-delivery-box .ready_to_go {
    color: #17a2b8;
    font-size: 25px;
    margin-top: 20px;
}
.track_con {
    border-bottom: 1px solid #000;
    padding-bottom: 30px;
}
.track_details {
    margin: 0;
    padding: 0;
    margin-top: 30px;
    margin-bottom: 60px;
}
.track_details li {
    list-style: none;
    font-size: 14px;
    margin-bottom: 8px;
}
.shipment_progress {
    border-bottom: 1px solid #000;
    padding-top: 40px;
}
.scrollbar {
    height: 258px;
    width: 100%;
    background: #fff;
    overflow-y: scroll;
    margin-bottom: 0;
}
.step-progress {
    position: relative;
    padding-left: 45px;
    list-style: none;
    margin-top: 30px;
}
.track .step {
    -webkit-box-flex: 1;
    -ms-flex-positive: 1;
    flex-grow: 1;
    width: 25%;
    margin-top: -12px;
    text-align: center;
    position: relative;
}
.track .step.active .icon {
    background: #f58220;
    color: #fff;
}
.track .icon {
    display: inline-block;
    width: 30px;
    height: 30px;
    line-height: 30px;
    position: relative;
    border-radius: 100%;
    background: #ddd;
    font-size: 12px;
    z-index: 9;
}
.track .step.active .text {
    color: #000;
}

.track .text {
    display: block;
    margin-top: 7px;
    font-size: 14px;
    font-weight: 600;
}
.track .step.active:before {
    background: #f58220;
    left: 50%;
    position: absolute;
}

.track .step::before {
    height: 7px;
    position: absolute;
    content: "";
    width: 100%;
    left: 50%;
    top: 12px;
    /* background: #ddd; */
}

.track .step {
    -webkit-box-flex: 1;
    -ms-flex-positive: 1;
    flex-grow: 1;
    width: 25%;
    margin-top: -12px;
    text-align: center;
    position: relative;
}
.shipment_progress {
    border-bottom: 1px solid #000;
    padding-top: 40px;
}

.mt-4, .my-4 {
    margin-top: 1.5rem!important;
}
.scrollbar {
    height: 258px;
    width: 100%;
    background: #fff;
    overflow-y: scroll;
    margin-bottom: 0;
}

.step-progress {
    position: relative;
    padding-left: 45px;
    list-style: none;
    margin-top: 30px;
}
.step-progress::before {
    display: inline-block;
    content: '';
    position: absolute;
    top: 0;
    left: 24px;
    width: 10px;
    height: 100%;
    border-left: 2px solid #ccc;
}
.step-progress-item:not(:last-child) {
    padding-bottom: 20px;
}

.step-progress-item {
    position: relative;
    counter-increment: list;
    padding-top: 0px;
    padding-left: 10px;
}
.step-progress-item.is-done::before {
    border-left: 2px solid #36b37e;
}

.step-progress-item::before {
    display: inline-block;
    content: '';
    position: absolute;
    left: -21px;
    height: 100%;
    width: 10px;
}
.step-progress-item.is-done::after {
    background: url(../img/tick.jpg) no-repeat #36b37e;
    border: 2px solid #36b37e;
    background-size: 10px 9px;
    background-repeat: no-repeat;
    background-position: 6px 7px;
    width: 25px;
    height: 25px;
    top: 0;
    left: -33px;
    font-size: 14px;
    text-align: center;
    color: #36b37e;
    border: 2px solid #36b37e;
    background-color: #34b37c;
}
.step-progress-item::after {
    content: '';
    display: inline-block;
    position: absolute;
    top: 0;
    left: -38px;
    width: 32px;
    height: 32px;
    border: 2px solid #ccc;
    border-radius: 50%;
    background-color: #fff;
}
.track .step.current .icon {
    background: #f58220;
    color: #fff;
}
.track .icon {
    display: inline-block;
    width: 30px;
    height: 30px;
    line-height: 30px;
    position: relative;
    border-radius: 100%;
    background: #ddd;
    font-size: 12px;
    z-index: 9;
}
.cancel{
    color:white!important;background:red!important;
}
.dismis{
    color:white!important;background:#ddd!important;
}
.activeMode{
    background: #f58220!important;
    color: #fff!important;
}
.track .step.active1::before {
    height: 7px;
    position: absolute;
    content: "";
    width: 100%;
    left: 50%;
    top: 12px;
     background: #ddd; 
}
</style>
<section class="py-3"></section>
<section class="bg-white">
  <div class="status-delivery mb-3">
    <div class="container">
      <div class="row">
          <div class="col-md-12 col-lg-4 d-flex m-b-20 mb-lg-0">
            <div style="background: #fff; width: 100%;">
              <div class="status-delivery-heading"> Order Details</div>
                <div class="status-delivery-box">           
                  <div class="ready_to_go">{{ ucfirst($orderDetail->order_status) }}</div>
                    <div class="track_con mt-2"></div>           
                      <ul class="track_details">
                        @php
                          $originalDateTime = $orderDetail->created_at;
                          $timestamp = strtotime($originalDateTime);
                          $formattedDateTime = date("M d, Y H:i", $timestamp);
                        @endphp
                        <li><span>ORDER PLACED ON :</span> {{ $formattedDateTime }} </li>
                        <li><span>PARTNER :</span>  {{ $orderDetail->request_partner }}</li>
                        <li><span>COURIER :</span>  {{ $orderDetail->courier_name }}</li>
                        <li><span>TRACKING ID :</span>  {{ $orderDetail->awb_no }}</li>
                        <li><span>ORDER ID :</span>  {{ $orderDetail->order_no }}</li>
                        <li><span>REMARK :</span>  {{ $orderDetail->remarks }}</li>
                      </ul>
                    </div>
                  </div>
                </div>          
                <div class="col-md-12 col-lg-8">
                  <div class="status-delivery-heading">Status Delivery</div>
                    <div class="status-delivery-box">
                      <h3>{{ ucfirst($orderDetail->order_status) }}</h3>                        
                      <div class="track">
                        @if($orderDetail->order_status == 'Delivered' ||  $orderDetail->order_status == 'Booked'  || $orderDetail->order_status == 'Pending Pickup' || $orderDetail->order_status == 'In Transit' || $orderDetail->order_status == 'Out For Delivery')
                            <div class="step active">
                                <span class="icon activeMode" >
                                    <i class="fa fa-check"></i>
                                </span>
                                <span class="text">Booked</span>
                            </div>
                            @elseif($orderDetail->order_status == 'Cancelled')
                                <div class="step active1" >
                                  <span class="icon cancel">  
                                    X
                                  </span>
                                  <span class="text">Cancelled</span>
                                </div>
                            @else
                                <div class="step active1">
                                    <span class="icon dismis">X</span>
                                    <span class="text">Booked</span>
                                </div>
                            @endif
                            @if($orderDetail->order_status == 'Delivered' || $orderDetail->order_status == 'Pending Pickup' || $orderDetail->order_status == 'In Transit' || $orderDetail->order_status == 'Out For Delivery')
                                <div class="step active">
                                    <span class="icon activeMode">  
                                    <i class="fa fa-check"></i>
                                    </span>
                                    <span class="text">Pending Pickup</span>
                                </div>
                            @else
                             <div class="step active1">
                                <span class="icon dismis">X</span>
                                <span class="text">Pending Pickup</span>
                             </div>
                            @endif
                          
                            @if($orderDetail->order_status == 'Delivered' || $orderDetail->order_status == 'In Transit' || $orderDetail->order_status == 'Out For Delivery')
                                <div class="step active">
                                    <span class="icon activeMode">  
                                    <i class="fa fa-check"></i></span>
                                    <span class="text">In Transit</span>
                                </div>
                            @else
                                <div class="step active1">
                                    <span class="icon dismis">X</span>
                                    <span class="text">In Transit</span>
                                </div>
                            @endif
                            @if($orderDetail->order_status == 'Delivered' || $orderDetail->order_status == 'Out For Delivery')
                                <div class="step active">
                                    <span class="icon activeMode">  <i class="fa fa-check"></i></span>
                                    <span class="text">Out For Delivery</span>
                                </div>
                            @else
                                <div class="step active1">
                                    <span class="icon dismis">X</span>
                                    <span class="text">Out For Delivery</span>
                                </div>
                            @endif
                            @if($orderDetail->order_status == 'Delivered')
                                <div class="step current last">
                                    <span class="icon activeMode"> <i class="fa fa-check"></i></span>
                                    <span class="text">Delivered</span>
                                </div>
                            @else
                                <div class="step current last">
                                     <span class="icon dismis"> X</span>
                                     <span class="text">Delivered</span>
                                </div>
                            @endif
                      </div>                      
                      <div class="shipment_progress mt-4">
                        <h4>Shipment Progress</h4>
                      </div>
                      <ul class="step-progress scrollbar" id="style-4">
                    @if(isset($dataArray['history']))
                      @foreach($dataArray['history'] as $event)
                        <li class="step-progress-item is-done">
                          <div class="progress_content">
                            @php
                            $originalDateTime = $event['event_time'];
                            $timestamp = strtotime($originalDateTime);
                            $formattedDateTime = date("M d, Y H:i", $timestamp);
                            @endphp
                            <p>{{ $formattedDateTime ?? ''}}</p>
                            <p><strong>{{ $event['message'] ?? '' }}</strong></p>
                            <p>{{ $event['location'] ?? '' }} <small>{{ $event['message'] ?? '' }}</small></p>
                          </div>
                        </li>
                        @endforeach    
                        @endif
                      </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
@endsection
