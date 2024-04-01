@extends('common-app.master')
@section('title', 'Client Dashboard')
@section('content')
<style>
    .card {
    --bs-card-spacer-y: 1.5rem;
    --bs-card-spacer-x: 1.5rem;
    --bs-card-title-spacer-y: 0.875rem;
    --bs-card-title-color: #566a7f;
    --bs-card-subtitle-color: ;
    --bs-card-border-width: 0;
    --bs-card-border-color: #d9dee3;
    --bs-card-border-radius: 0.5rem;
    --bs-card-box-shadow: 0 2px 6px 0 rgba(67, 89, 113, 0.12);
    --bs-card-inner-border-radius: 0.5rem;
    --bs-card-cap-padding-y: 1.5rem;
    --bs-card-cap-padding-x: 1.5rem;
    --bs-card-cap-bg: transparent;
    --bs-card-cap-color: ;
    --bs-card-height: ;
    --bs-card-color: ;
    --bs-card-bg: #fff;
    --bs-card-img-overlay-padding: 1.5rem;
    --bs-card-group-margin: 0.8125rem;
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    height: var(--bs-card-height);
    color: var(--bs-body-color);
    /* word-wrap: break-word; */
    background-color: var(--bs-card-bg);
    /* background-clip: border-box; */
    /* border: var(--bs-card-border-width) solid var(--bs-card-border-color); */
    border-radius: var(--bs-card-border-radius);
}
.w-25
{
    width: 25%;
}
.text-white{
    color:white;
}
.theading{
    padding: 23px!important;
}
.f-14{
    font-size:14px;
}
::-webkit-scrollbar {
  width: 5px;
  height: auto;
}

::-webkit-scrollbar-track {
  -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
  -webkit-border-radius: 10px;
  border-radius: 10px;
}

::-webkit-scrollbar-thumb {
  -webkit-border-radius: 10px;
  border-radius: 10px;
  background: rgba(255, 255, 255, 0.3);
  -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.5);
}

::-webkit-scrollbar-thumb:window-inactive {
  background: rgba(255, 255, 255, 0.3);
}
.cardHeight{
    height: 470px;
}
.cardHeight1{
    height: 400px
}
.cardBodyStyle{
    overflow : hidden;
    overflow-y: scroll;
}
.pb-1 {
    padding-bottom: 0.1rem !important;
}
</style>
<section class="py-3">
  <div class="row d-flex align-items-md-stretch ms-2 pb-2">
    <div class="col-lg-3 col-md-6">
      <label class="p-b-10" for="reportrange">From Date:</label>
      <div class="input-group">     
          <input type="hidden" id="range" value="">
        <input class="form-control" id='reportrange' name="from_date" type="text" placeholder="From Date" >
      </div>
    </div>
  </div>
</section>
<!--
<section class="" style='padding-left:10px; padding-right:10px;'>
  <div class="row d-flex align-items-md-stretch">
    <div class="col-sm-6 col-lg-3 mb-4">
    <div class="card card-border-shadow-primary">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-primary"><i class='fas fa-truck'></i></span>
          </div>
          <h4 class="ms-1 mb-0">42</h4>
        </div>
        <p class="mb-1">Total Shipment</p>
        <p class="mb-0">
          <span class="fw-medium me-1">+18.2%</span>
          <small class="text-muted">than last week</small>
        </p>
      </div>
    </div>
    </div>
    <div class="col-sm-6 col-lg-3 mb-4">
    <div class="card card-border-shadow-primary">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-primary"><i class='fas fa-truck'></i></span>
          </div>
          <h4 class="ms-1 mb-0">42</h4>
        </div>
        <p class="mb-1">Delivered Shipment</p>
        <p class="mb-0">
          <span class="fw-medium me-1">+18.2%</span>
          <small class="text-muted">than last week</small>
        </p>
      </div>
    </div>
    </div>
    <div class="col-sm-6 col-lg-3 mb-4">
    <div class="card card-border-shadow-primary">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-primary"><i class='fas fa-truck'></i></span>
          </div>
          <h4 class="ms-1 mb-0">42</h4>
        </div>
        <p class="mb-1">Intransit Shipment</p>
        <p class="mb-0">
          <span class="fw-medium me-1">+18.2%</span>
          <small class="text-muted">than last week</small>
        </p>
      </div>
    </div>
   </div>
   <div class="col-sm-6 col-lg-3 mb-4">
    <div class="card card-border-shadow-primary">
    <div class="row">
      <div class="col-6">
        <div class="card-body">
          <div class="d-flex align-items-center mb-2 pb-1">
            <div class="avatar me-2">
              <span class="avatar-initial rounded bg-label-primary"><i class='fas fa-truck'></i></span>
            </div>
            <h4 class="ms-1 mb-0">42</h4>
          </div>
          <p class="mb-1" style="font-size:12px">Delay Intransit</p>
          <p class="mb-0">
            <span class="fw-medium me-1">+18.2%</span>
            <small class="text-muted">than last week</small>
          </p>
        </div>
      </div>
      <div class="col-6" style="border-left: 1px solid #d9dee3">
        <div class="card-body">
          <div class="d-flex align-items-center mb-2 pb-1">
            <div class="avatar me-2">
              <span class="avatar-initial rounded bg-label-primary"><i class='fas fa-truck'></i></span>
            </div>
            <h4 class="ms-1 mb-0">42</h4>
          </div>
          <p class="mb-1" style="font-size:12px">Delay Delivered</p>
          <p class="mb-0">
            <span class="fw-medium me-1">+18.2%</span>
            <small class="text-muted">than last week</small>
          </p>
        </div>
      </div>
    </div>
  </div>
 </div>
 </div>
</section>-->
<section class="" style='padding-left:10px; padding-right:10px;'>
  <div class="row d-flex align-items-md-stretch">
    <div class="col-sm-6 col-lg-2">
    <div class="card card-border-shadow-primary">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-primary"><i class='fas fa-truck'></i></span>
          </div>
          <h4 class="ms-1 mb-0" id="countTotalShipment">0</h4>
        </div>
        <p class="mb-1">Total Shipment</p>
        <p class="mb-0">
          <span class="fw-medium me-1" id="totalShipmentProgress">0%</span>
          <small class="text-muted">than last time period</small>
        </p>
      </div>
    </div>
    </div>
    <div class="col-sm-6 col-lg-2">
    <div class="card card-border-shadow-primary">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-primary"><i class='fas fa-truck'></i></span>
          </div>
          <h4 class="ms-1 mb-0" id="countDelivered">0</h4>
        </div>
        <p class="mb-1">Delivered Shipment</p>
        <p class="mb-0">
          <span class="fw-medium me-1" id="totalDeliveredProgress">0%</span>
          <small class="text-muted">than last time period</small>
        </p>
      </div>
    </div>
    </div>
    <div class="col-sm-6 col-lg-2">
    <div class="card card-border-shadow-primary">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-primary"><i class='fas fa-truck'></i></span>
          </div>
          <h4 class="ms-1 mb-0" id="countIntransit">0</h4>
        </div>
        <p class="mb-1">Intransit Shipment</p>
        <p class="mb-0">
          <span class="fw-medium me-1" id="totalIntransitProgress">0%</span>
          <small class="text-muted">than last time period</small>
        </p>
      </div>
    </div>
   </div>
   <div class="col-sm-6 col-lg-2">
    <div class="card card-border-shadow-primary">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-primary"><i class='fas fa-truck'></i></span>
          </div>
          <h4 class="ms-1 mb-0" id="countPendingpick">0</h4>
        </div>
        <p class="mb-1">Pickup Pending</p>
        <p class="mb-0">
          <span class="fw-medium me-1" id="totalPendingpickProgress">0%</span>
          <small class="text-muted">than last time period</small>
        </p>
      </div>
    </div>
   </div>
    <div class="col-sm-6 col-lg-2">
    <div class="card card-border-shadow-primary">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-primary"><i class='fas fa-truck'></i></span>
          </div>
          <h4 class="ms-1 mb-0" id="countDelay">0</h4>
        </div>
        <p class="mb-1">Delayed Shipment</p>
        <p class="mb-0">
          <span class="fw-medium me-1" id="totalDelayProgress">0%</span>
          <small class="text-muted">than last time period</small>
        </p>
      </div>
    </div>
   </div>
    <div class="col-sm-6 col-lg-2">
    <div class="card card-border-shadow-primary">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-primary"><i class='fas fa-truck'></i></span>
          </div>
          <h4 class="ms-1 mb-0" id="countCancelled">0</h4>
        </div>
        <p class="mb-1">Cancelled Shipment</p>
        <p class="mb-0">
          <span class="fw-medium me-1" id="totalCancelledProgress">0%</span>
          <small class="text-muted">than last time period</small>
        </p>
      </div>
    </div>
   </div>
    
 </div>
</section>
<section class="" style='padding-left:10px; padding-right:10px;'>
    <div class="row d-flex align-items-md-stretch">
    <div class="col-sm-6 col-lg-6 mb-6">
    <div class="card cardHeight1">
      <div class="card-header">
        <div class="card-title mb-0">
          <h5 class="m-0">Shipment overview</h5>
        </div>
      </div>
      <div class="card-body cardBodyStyle">
        <div class="table-responsive">
          <table class="table card-table">
             <thead>
                 <tr>
                     <th class="fs-big fw-medium text-white bg-gray-900 px-1 px-lg-3 shadow-none theading f-14">DSP</th>
                     <th class="fs-big fw-medium text-center text-white bg-gray-900 px-1 px-lg-3 shadow-none theading f-14" colspan="2">Total Shipment </th>
                     <th class="fs-big fw-medium text-center text-white bg-gray-900 px-1 px-lg-3 shadow-none f-14" colspan="2">Delivery Performance<br><small>(Improvment Last Month)</small></th>
                     <th class="fs-big fw-medium text-center text-white bg-gray-900 px-1 px-lg-3 shadow-none f-14">Delayed Shipment<br><small>(TAT)</small></th>
                 </tr>
                
             </thead>
            <tbody class="table-border-bottom-0" id="dspData">
            </tbody>
          </table>
        </div>
      </div>
    </div>
    </div>
    <div class="col-sm-6 col-lg-6 mb-6">
      <div class="card">
          <div id="chart"></div>
      </div>
  </div>
  </div>
</section>
<section class="" style='padding-left:10px; padding-right:10px;'>
    <div class="row d-flex align-items-md-stretch">
        <div class="col-sm-6 col-lg-4">
            <div class="card cardHeight">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="card-title mb-0">
                        <h5 class="m-0 me-2">Shipment by Cities</h5>
                        <small class="text-muted">62 deliveries in progress</small>
                    </div>
                </div>
                <div class="card-body cardBodyStyle">
                    <ul class="p-0 m-0" id="cities">
                    
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-4">
            <div class="card cardHeight">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="card-title mb-0">
                        <h5 class="m-0 me-2">Reasons for delayed exceptions</h5>
                        <small class="text-muted">12% increase in this month</small>
                    </div>
                </div>
                <div class="card-body py-5" style="margin-bottom:6px">
                    <div id="pieChart"></div>
                </div>
                <div class="card-body"></div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-4">
            <div class="card cardHeight">
                <div class="card-header d-flex align-items-center justify-content-between">
                <div class="card-title mb-0">
                    <h5 class="m-0 me-2">Delivery Performance</h5>
                    <small class="text-muted">12% increase in this month</small>
                </div>
            </div>
            <div class="card-body cardBodyStyle" >
                <ul class="p-0 m-0">
                    <li class="d-flex mb-4 pb-1">
                        <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-primary"><i class="fa fa-cube"></i></span>
                        </div>
                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                                <h6 class="mb-1 fw-normal">Packages in transit</h6>
                                <small class="text-success fw-normal d-block">
                                    <i class="bx bx-chevron-up"></i>
                                    25.8%
                                </small>
                            </div>
                            <div class="user-progress">
                                <h6 class="mb-0">10k</h6>
                            </div>
                        </div>
                    </li>
                    <li class="d-flex mb-4 pb-1">
                        <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-info"><i class="fas fa-truck"></i></span>
                        </div>
                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                                <h6 class="mb-1 fw-normal">Packages out for delivery</h6>
                                <small class="text-success fw-normal d-block">
                                    <i class="bx bx-chevron-up"></i>
                                    4.3%
                                </small>
                            </div>
                            <div class="user-progress">
                                <h6 class="mb-0">5k</h6>
                            </div>
                        </div>
                    </li>
                  <li class="d-flex mb-4 pb-1">
                    <div class="avatar flex-shrink-0 me-3">
                      <span class="avatar-initial rounded bg-label-success"><i class="fa fa-check-circle"></i></span>
                    </div>
                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                      <div class="me-2">
                        <h6 class="mb-1 fw-normal">Packages delivered</h6>
                        <small class="text-danger fw-normal d-block">
                          <i class="bx bx-chevron-down"></i>
                          12.5
                        </small>
                      </div>
                      <div class="user-progress">
                        <h6 class="mb-0">15k</h6>
                      </div>
                    </div>
                  </li>
                  <li class="d-flex mb-4 pb-1">
                    <div class="avatar flex-shrink-0 me-3">
                      <span class="avatar-initial rounded bg-label-warning"><i class="fa fa-percent"></i></span>
                    </div>
                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                      <div class="me-2">
                        <h6 class="mb-1 fw-normal">Delivery success rate</h6>
                        <small class="text-success fw-normal d-block">
                          <i class="bx bx-chevron-up"></i>
                          35.6%
                        </small>
                      </div>
                      <div class="user-progress">
                        <h6 class="mb-0">95%</h6>
                      </div>
                    </div>
                  </li>
              <li class="d-flex mb-4 pb-1">
                <div class="avatar flex-shrink-0 me-3">
                  <span class="avatar-initial rounded bg-label-secondary"><i class="fa fa-history"></i></span>
                </div>
                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                  <div class="me-2">
                    <h6 class="mb-1 fw-normal">Average delivery time</h6>
                    <small class="text-danger fw-normal d-block">
                      <i class="bx bx-chevron-down"></i>
                      2.15
                    </small>
                  </div>
                  <div class="user-progress">
                    <h6 class="mb-0">2.5 Days</h6>
                  </div>
                </div>
              </li>
              <li class="d-flex">
                <div class="avatar flex-shrink-0 me-3">
                  <span class="avatar-initial rounded bg-label-danger"><i class="fa fa-user"></i></span>
                </div>
                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                  <div class="me-2">
                    <h6 class="mb-1 fw-normal">Customer satisfaction</h6>
                    <small class="text-success fw-normal d-block">
                      <i class="bx bx-chevron-up"></i>
                      5.7%
                    </small>
                  </div>
                  <div class="user-progress">
                    <h6 class="mb-0">4.5/5</h6>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </div>
    </div>
    </div>
</section>
<section class="" style='padding-left:10px; padding-right:10px;'>
    <div class="row d-flex align-items-md-stretch">
        <div class="col-12 order-5">
            <div class="card">
                <table class="table align-middle mb-0 bg-white" >
	                <thead class="bg-light">
            		   <tr>
            		      <th class="text-center">#</th>
            			  <th class="text-center">Order ID</th>
            			  <th class="text-center">Order Date</th>
            			  <th class="text-center">Shipment Value</th>
            			  <th>Pay Mode</th>
            			  <th>Recipient</th>
            			  <th>Aggregator/Partner</th>
            			  <th>LMP/LSP</th>
            			  <th>Weight</th>
            			  <th>AWB No.</th>
            			  <th>Shipment Status</th>
            			</tr>
                    </thead>
		            <tbody id="outDeliveryTable">
            		  
	                </tbody>
		        </table>
            </div>
        </div>
  </div>
</section>
<script src="{{  url('js/apexcharts.js') }}"></script>
<script>
     var donutData = [];
      var monthlyData = [];
      var monthName = [];
      var monthTotalCount = [];
      var monthDeliveredCount = [];
      var rtoData = 0;
      var deliveredData = 0;
      var pendingPick =0;
      var intransit = 0
      var out_for_delivery = 0;
      var ship = 0;
      var book = 0;
      var cancelled = 0;
      
    $(document).ready(function () {
        
        $('#reportrange').on('change', function () {
            var  dateRange= $(this).val();
            $.ajax({
              url: 'clientDashboard',
              type: "GET",
              data: {from_date:dateRange},
              success: function(data) {
               
                var data = data.data;
                book = data ? data.book : 0;
                ship = data ? data.ship : 0;
                out_for_delivery = data ? data.out_for_delivery:0;
                intransit = (data ? data.transit : 0)+ out_for_delivery
                pendingPick = (data ? data.pending_pick : 0) + book +ship;
                rtoData = data ? data.rto : 0;
                cancelled = data ? data.cancelled :0;
                deliveredData =data ? data.delivered:0;
                var totalShipment = data ? data.order :0;
                $('#countTotalShipment').text(totalShipment);
                $('#total_order').text(totalShipment);
                $('#total_delivered').text(deliveredData);
                $('#countCancelled').text(cancelled);
                $('#countDelivered').text(deliveredData);
                $('#countRto').text(rtoData);
                $('#countDelay').text((data?data.delay_shipment:0));
                $('#countPendingpick').text(pendingPick);
                $('#countIntransit').text(intransit);
                
                $('#revenue').text('₹ '+((data ? data.revenue :0)).toFixed(2));
                
                //last time period progress
                var lastTotalShipment = data ? data.previousData[0].totalOrderCount :0;
                var totalShipmentProgressData = ((totalShipment - lastTotalShipment)*100)/totalShipment;
                var totalShipmentProgress = (totalShipmentProgressData>0) ? totalShipmentProgressData : 0;
                var sym = (totalShipmentProgress > 0) ? '+' : '';
                $("#totalShipmentProgress").text(sym + ' ' + totalShipmentProgress.toFixed(1) + '%');
                
                var lastTotalDelivered = data ? data.previousData[0].totalDeliveredCount : 0;
                var totalDeliveredProgressData = ((deliveredData - lastTotalDelivered)*100)/deliveredData;
                var totalDeliveredProgress = (totalDeliveredProgressData>0) ? totalDeliveredProgressData : 0;
                var sym1 = (totalDeliveredProgress > 0) ? '+' : '';
                $("#totalDeliveredProgress").text(sym1 + ' ' + totalDeliveredProgress.toFixed(1) + '%'); 
                
                var lastTotalIntransit = data ? data.previousData[0].totalIntransitCount :0;
                var totalIntransitProgressData = ((intransit - lastTotalIntransit)*100)/intransit;
                var totalIntransitProgress = (totalIntransitProgressData>0) ? totalIntransitProgressData : 0;
                var sym3 = (totalIntransitProgress > 0) ? '+' : '';
                $("#totalIntransitProgress").text(sym1 + ' ' + totalIntransitProgress.toFixed(1) + '%'); 
                
                
                var lastTotalPendingpick = data ? data.previousData[0].totalPendingpickCount :0;
                var totalPendingpickProgressData = ((pendingPick - lastTotalPendingpick)*100)/pendingPick;
                var totalPendingpickProgress = (totalPendingpickProgressData>0) ? totalPendingpickProgressData : 0;
                var sym4 = (totalPendingpickProgress > 0) ? '+' : '';
                $("#totalPendingpickProgress").text(sym1 + ' ' + totalPendingpickProgress.toFixed(1) + '%'); 
                
                
                var lastTotalCancelled = data ? data.previousData[0].lastCancelledCount :0;
                var totalCancelledProgressData = ((cancelled - lastTotalCancelled)*100)/cancelled;
                var totalCancelledProgress = (totalCancelledProgressData>0) ? totalCancelledProgressData : 0;
                var sym5 = (totalCancelledProgress > 0) ? '+' : '';
                $("#totalCancelledProgress").text(sym1 + ' ' + totalCancelledProgress.toFixed(1) + '%'); 
                
                
                var dsps = data ? data.dsps : [];
                var ship = data ? data.ship : 0;
                var total_order = totalShipment;
                var cities = data ? data.cities: [];  
                var record_months = data ? data.monthlyReport : []; 
                var cod = data ? data.cod :0;
                var outDeliveryData = data ? data.outDeliveryOrder: [];
                var prepaid = data ? data.prepaid :0;   
                $('#total_shipment').text(totalShipment);     
                $('#cod').text(cod);        
                $('#prepaid').text(prepaid); 
                
                var cod_per = (cod/total_order)*100;
                var prepaid_per = (prepaid/total_order)*100;
                //console.log(cod_per,prepaid_per);
                var shipMode='<div class="progress-bar progress-bar bg-primary" role="progressbar" style="width: ' + prepaid_per + '%" aria-valuenow="'+prepaid+'" aria-valuemin="0" aria-valuemax="100"></div>';
                $('#progress').append(shipMode); 
                $('#cities').empty();
                var contentCity = '<li class="d-flex mb-4 pb-1"><div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2"><div class="me-2"><h6 class="mb-1">City Name</h6></div><div class="user-progress"><h6 class="mb-0">Total Shipment</h6></div><div class="user-progress"><h6 class="mb-0">Current Rank</h6></div><div class="user-progress"><h6 class="mb-0">Last Rank</h6></div></div></li>';
                       
                if((cities.length)>0)
                {
                    cities.forEach(cityElement => {
                      var cityName = cityElement.city;
                      var cityCount = cityElement.count;
                       var cityRank = cityElement.rank;
                       var lastCityRank = cityElement.last_rank;
                      //html ='<div class="d-flex"><a class="btn btn-xs btn-dark py-1 m-1" href="#!"><i class="fas fa-thumbs-up me-1"></i>'+cityCount+'</a><a class="btn btn-xs btn-dark py-1 m-1" href="#!"><i class="fas fa-heart me-1"></i>'+cityName+'</a></div> '
                      contentCity += '<li class="d-flex mb-4 pb-1"><div class="avatar flex-shrink-0 me-3"><span class="avatar-initial rounded bg-label-primary"><i class="fa fa-building"></i></span></div><div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2"><div class="me-2"><h6 class="mb-1 fw-normal">'+ cityName+'</h6><small class="text-success fw-normal d-block"><i class="bx bx-chevron-up"></i></small></div><div class="user-progress"><h6 class="mb-0">'+cityCount+'</h6></div><div class="user-progress"><h6 class="mb-0">'+cityRank+'</h6></div><div class="user-progress"><h6 class="mb-0">'+lastCityRank+'</h6></div></div></li>';
                  
                        
                    });   
                    $('#cities').append(contentCity);
                }
                else
                {                   
                  $('#cities').append(contentCity);
                } 
                
                //outdeliverydata
                    var outdeliveryContent = '';
                    $('#outDeliveryTable').empty();
                    if((outDeliveryData.length)>0){
                        $.each(outDeliveryData, function (index, val) {
                            var originalDate = new Date(val['created_at']);
                            // Get year, month, and day components
                            var year = originalDate.getFullYear();
                            var month = String(originalDate.getMonth() + 1).padStart(2, '0'); // Adding 1 because months are zero-based
                            var day = String(originalDate.getDate()).padStart(2, '0');
                            
                            // Form the new date string in "Y-m-d" format
                            var formattedDate = year + '-' + month + '-' + day;
                            var orderStatus = val['order_status'];
    
                            // Convert each first letter to uppercase
                            var formattedOrderStatus = orderStatus.replace(/\b\w/g, function (match) {
                                return match.toUpperCase();
                            });
                            outdeliveryContent += `
                                <tr style='border-radius: 5px 5px 4px 5px;box-shadow: 1px 1px 1px 0px aqua;'>
                                    <td class="text-center">
                                        ${index+1}
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center">
                                            <div class="ms-3">
                                                <p class="fw-bold mb-1">${val['source'] == 'Manual' ? '<span class="h6 m-0" style="color:var(--bs-link-color);padding-right:10px">MW </span>' : ''}MW ${val['order_no']}</p>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="text-center">
                                        <p class="fw-normal mb-1">${formattedDate}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="fw-normal mb-1">₹ ${val['total_amount']}</p>
                                    </td>
                                    <td>
                                        <p class="fw-normal mb-1">${val['payment_mode']}</p>
                                    </td>
                                    <td>
                                        <p class="fw-normal mb-1" title="${val['shipping_first_name']} ${val['shipping_last_name']}">${val['shipping_first_name']}</p>
                                    </td>
                                    <td>
                                        <p class="fw-normal mb-1">${val['request_partner']}</p>
                                    </td>
                                    <td>
                                        <p class="fw-normal mb-1">${val['courier_name'] || 'XXXXXXXXX'}</p>
                                    </td>
                                    <td>
                                        <p class="fw-normal mb-1">${val['total_weight']/1000} Kg</p>
                                    </td>
                                    <td>
                                        <p class="fw-normal mb-1">${val['awb_no'] || 'XXXXXXXXX'}</p>
                                    </td>
                                    <td>
                                        <p class="fw-normal mb-1"><span class="btn btn-sm btn-primary"  style="width: 90%!important;">${formattedOrderStatus}</span></p>
                                    </td>
                                </tr>`;
                        });
                        
                        // Append the new content
                        $('#outDeliveryTable').append(outdeliveryContent);
                    }
                    else
                    {                   
                      $('#outDeliveryTable').empty();
                    } 
                
                //dsp Data
                var dspContent = `
                        <tr>
                            <td class="w-25 ps-0">
                                <div class="d-flex justify-content-start align-items-center">
                                    <div class="me-2"><i class="bx bxs-truck"></i></div>
                                    <h6 class="mb-0">Total Given Shipment</h6>
                                </div>
                            </td>
                            <td class="pe-0 text-nowrap" >
                                <span class="mb-0 fw-medium">${totalShipment}</span>
                            </td>
                            <td class="text-center pe-0">
                                <span class="fw-medium"></span>
                            </td>
                            <td class="w-25 ps-0">
                                <div class="d-flex justify-content-start align-items-center">
                                    <div class="me-2"><i class="bx bxs-truck"></i></div>
                                    <h6 class="mb-0">Total Delivered Shipment</h6>
                                </div>
                            </td>
                            <td class="pe-0 text-nowrap" >
                                <span class="mb-0 fw-medium">${deliveredData}</span>
                            </td>
                          <td class="text-center pe-0">
                                <span class="fw-medium"></span>
                            </td>
                            
                        </tr>`;
                
                $('#dspData').empty();
                if((dsps.length)>0)
                {
                    dsps.forEach(dspElement => {
                        var dspData = dspElement.dsp;
                        var dspCountData = dspElement.dspCount;
                        var splitName = dspData.split('App');
                        var dspName = splitName[0];
                        var dspCountPercent = ((dspCountData*100)/totalShipment).toFixed(2);
                        var dspDelivereCountData = dspElement.deliveredDspCount;
                        var deliveredCountPercent = 0;
                        if(deliveredData>0){
                            deliveredCountPercent = ((dspDelivereCountData*100)/deliveredData).toFixed(2);
                        }
                        else{
                            deliveredCountPercent = 0;
                        }
                        dspContent += '<tr><td class="w-25 ps-0"><div class="d-flex justify-content-start align-items-center"><div class="me-2"><i class="bx bxs-truck"></i></div><h6 class="mb-0">' + dspName + '</h6></div></td><td class="pe-0 text-nowrap"><span class=" mb-0 fw-medium" >' + dspCountData + '</span></td><td><span style="margin-left:10px" class=" mb-0 fw-medium" >' + dspCountPercent + '%</span></td><td class="pe-0 text-center"><span class="fw-medium">'+dspDelivereCountData+'</span></td><td class="pe-0 text-center"><span class="fw-medium">'+deliveredCountPercent+'%</span></td><td class="text-center pe-0"><span class="fw-medium">0%</span></td></tr>';
                    
                        
                    });
                    $('#dspData').append(dspContent);
                }
                else{
                    $('#dspData').empty();
                }
                monthDate = [];
                monthTotalCount = [];
                monthDeliveredCount = [];
                record_months.forEach(recordElement => {
                    monthDate.push(recordElement.formatted_date);
                    monthTotalCount.push(recordElement.total_order);
                    monthDeliveredCount.push(recordElement.total_delivered)
                });
                 // Extract and parse values
                var delivered = deliveredData;
                var rto = rtoData;
                var pending_pick = pendingPick;
                // Update donutData array
                
                donutData = [total_order, rto, delivered]; 
                monthlyData = [total_order,delivered]   
                onDataUpdate();                   
              },
              error: function (error) {
                  console.log("AJAX error:", error);
              }
            });                  
          });
      });
      
    function onDataUpdate() {
      
        //donut chart
        var categories = ["Shipment", "RTO", "Delivered"];
        var donutChartData = {
          series: donutData,
          chart: {
            width: 380,
            type: 'donut',
          },
          
          plotOptions: {
            pie: {
              startAngle: -90,
              endAngle: 270
            }
          },
          dataLabels: {
            enabled: false
          },
          fill: {
            type: 'gradient',
          },
          labels: categories,
          //colors: ['#FF5733', '#33FF57', '#5733FF'],
          legend: {
            formatter: function(val, opts) {
              // Assuming opts.seriesIndex corresponds to the categories
              return categories[opts.seriesIndex] + " - " + opts.w.globals.series[opts.seriesIndex];
            }
          },
          title: {
            text: ''
          },
          responsive: [{
            breakpoint: 480,
            options: {
              chart: {
                width: 200
              },
              legend: {
                position: 'bottom'
              }
            }
          }]
        };
        var donutChart = new ApexCharts(document.querySelector("#pieChart"), donutChartData);
        donutChart.render();
        
        
        // bar chart
       
        var barChartData = {
              series: [{
              name: 'Total Shipment',
              type: 'column',
              data: monthTotalCount
            }, {
              name: 'Delivered',
              type: 'line',
              data: monthDeliveredCount
            }],
              chart: {
              height: 385,
              type: 'line',
            },
            stroke: {
              width: [0, 4]
            },
            title: {
              text: 'Shipment statistics'
            },
            dataLabels: {
              enabled: true,
              enabledOnSeries: [1]
            },
            labels: monthDate,
            xaxis: {
              type: 'datetime'
            },
            yaxis: [{
              title: {
                text: 'Total Shipment',
              },
            
            }, {
              opposite: true,
              title: {
                text: 'Delivered'
              }
            }]
            };
        var barChart = new ApexCharts(document.querySelector("#chart"), barChartData);
        barChart.render();
}
</script>

@endsection