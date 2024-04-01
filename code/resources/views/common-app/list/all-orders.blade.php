@extends('common-app/master')
@section('title', 'Orders List')
@section('content')
<style>
    .custom-alert{
        background: rgb(115,125,251);
        background: linear-gradient(0deg, rgba(45,210,253,1) 0%, rgba(45,210,253,1) 100%);
        color: #fff;
        font-weight:bold;
    }
    .custom-alert small{position: absolute;
        right: 1px;
        bottom: 1px;
        background: linear-gradient(0deg, #F3D832 0%, #EDCF17 100%);
        color: #0F0E0E;
        padding: 2px 5px;
        
        border-radius: 2px 0px 0px 2px;
        font-weight: 600;
    }
    .modal_scroll {
        max-height: 350px;
        overflow-y: scroll;
        /* Add the ability to scroll */
    }

    /* Hide scrollbar for Chrome, Safari and Opera */
    /* .modal_scroll::-webkit-scrollbar {
        display: none;
    } */

    /* Hide scrollbar for IE and Edge */
    /* .modal_scroll {
        -ms-overflow-style: none;
    } */
    .input-group-text 
    {
        font-size: .9rem;
        font-weight: 400;
        line-height: 1.7;
        display: flex;
        margin-bottom: 0;
        padding: .375rem .75rem;
        text-align: center;
        white-space: nowrap;
        color: #2e384d;
        border: 1px solid #dce4ec;
        /*border-radius: .25rem;*/
        margin-right: 5px;
        background-color: #fff;
        align-items: center
    }
    .m-b-10{
        margin-bottom:10px;
    }
    .p-t-10 {
        padding-top: 10px;
    }
    
    .border-top {
        border-top: 1px solid #dce4ec!important;
    }
    .ms-choice {
        display: block;
        width: 210px!important;
        height: 36px!important;
        padding: 0;
        overflow: hidden;
        cursor: pointer;
        border: 1px solid #ced4da!important;
        text-align: left;
        white-space: nowrap;
        line-height: 36px!important;
        color: #444;
        text-decoration: none;
        border-radius: 4px;
        background-color: #fff;
    }
    .ms-choice>span.placeholder {
        color: transparent!important;
    }
    .ms-choice>div.icon-caret {
    	display: none!important;
    }
    select {
      width: 100%;
    }
    .ms-choice>span.placeholder {
        color: transparent!important;
        display: none!important;
    }
    .btn-black{
    	color: #fff!important;
      
        background-color: #12263f!important;
        
    }
    .section-css{
    	background:white;
    	padding: 0px;
    	height: 80px;
    }
    .btn-css {
        border-radius: 4px;
        padding: 6px;
        font-size: medium;
        border-color: #12263f!important;
        margin-left:5px !important;
        margin-bottom:5px !important;
        margin-top:5px !important;
    }
    .btn-white{
    	color: #12263f!important;
        
        background-color: #fff!important;
       
    }
    /*.btn-css{*/
    /*	 border-radius: 4px;*/
    /*	padding: 6px;*/
    /*    font-size: medium;*/
    /*	border-color: #12263f!important;*/
    /*}*/
   
    .map-css{
	height: 30px;
}
.a-css{
    	color: #0090ff!important;
    	font-weight: 400;
        font-size: 14px;
        cursor: pointer;
    }
	.bg-span{
		background-color: lightgray;
	    width: 127px;
		padding: 0.35rem 0.5rem!important;
	}
	.alert {
    position: relative;
    margin-bottom: 1rem;
	padding: 0.35rem 0.5rem!important;
    border: 1px solid transparent;
    border-radius: 0.25rem;
}
</style>


<section class="py-filter">
    <div class="row">
        <div class="col-md-6 text-left"> 
            <a href="/all-orders" class="btn btn-outline-dark btn-sm  @if(request()->segment(1) == 'all-orders') btn-black @endif"><i class="mdi mdi-arrow-down-bold-circle"></i>Forward Order</a>
            <!--<a href="#" class="btn btn-outline-dark btn-sm @if(request()->segment(1) == 'rorders') btn-black @endif"><i class="mdi mdi-arrow-down-bold-circle"></i>Reverse Order</a>-->
            <small>By default 1 month data appear</small>
        </div>
        <div class="col-md-6 text-right">
  <!--      <a href="{{ route('cron-job.index') }}" class="btn btn-outline-dark btn-sm"><i class="mdi mdi-arrow-down-bold-circle"></i>Job Scheduler</a>-->
  <!--      <a href="{{ route('label.print') }}" class="btn btn-outline-dark btn-sm"><i class="mdi mdi-arrow-down-bold-circle"></i><i class="fa fa-print"></i>  <i class="fa fa-cog"></i> </a>-->
		<!--<a href="{{ route('tracking.shipment') }}" class="btn btn-outline-dark btn-sm" style="display:none"><i class="mdi mdi-library-books"></i> Bulk Track</a> -->
		@php
            $currentUrl = url()->current(); // Get the current URL
            $exportUrl = url('export-orders') . '?' . http_build_query(request()->all());
        @endphp
        @if(!empty($userP) && $userP->download != '1')
		    <a href="#" onclick="checkPermission()" class="btn btn-outline-dark btn-sm"><i class="mdi mdi-arrow-down-bold-circle"></i> Export</a>
		@else
		    <a  href="{{ $exportUrl }}" class="btn btn-outline-dark btn-sm"><i class="mdi mdi-arrow-down-bold-circle"></i> Export</a>
		@endif
		<a href="{{ route('order.all') }}" class="btn btn-sm btn-outline-dark refresh_orders_button"><i class="mdi mdi-refresh"></i> Refresh</a>
	
		<button type="button" class="btn btn-outline-dark show_hide_filter btn-sm" onclick="showFilter()"><i class="icon-placeholder mdi mdi-filter"></i> Filters</button>
        <button type="button" class="btn btn-outline-dark show_hide_filter btn-sm" style="display:none;"><i class="icon-placeholder mdi mdi-close"></i> Close</button>
    </div>
    </div>
    
	<div class="col-lg-12" id='filter'>                           
        <div class="card">
            <div class="card-header border-bottom">
              	<h3 class="h4 mb-0">Filters</h3>
            </div>
            <div class="card-body">
                <form class="row g-3 align-items-center" method="GET" action="/all-orders">	
                    <div class="row" style="margin-bottom: 1rem !important;">
    					<div class="col-lg">
    						<label class="p-b-10" for="reportrange">From Date:</label>
    						<div class="input-group">
    						    <input type="hidden" id="range" value="{{ request()->input('from_date') ?? ''}}">
    							<input class="form-control" id='reportrange' name="from_date" type="text" placeholder="From Date" >
    						</div>
    					</div>
    					<div class="col-lg">
    					    @php
    					        $selectedShip =  request()->input('client');
    					    @endphp
    						<label class="p-b-10" for="client">Client: </label>
    						<select class="multiple-select" id="partner" name="client[]" multiple="multiple" placeholder="client">
    						    @foreach($clients as $client)
    								<option value="{{ $client->client_code }} " {{ $selectedShip ? ((in_array($client->client_code, $selectedShip)) ? 'selected' : '') : '' }}>{{ $client->client_code }}</option>
    							@endforeach
    						</select>
    					</div>
    					<div class="col-lg">
    						<label class="p-b-10" for="order_id">Order ID(s):</label>
    						<div class="input-group">
    							<input class="form-control" id="order_id" name="order_id"  type="text" placeholder="Seperated with comma" value="{{ request()->input('order_id') ?? '' }}">
    						</div>
    					</div>
    					<div class="col-lg">
    						<label class="p-b-10" for="awb_no">Awb No(s):</label>
    						<div class="input-group">
    							<input class="form-control" id="awb_no" name="awb_no"  type="text" placeholder="Seperated with comma" value="{{ request()->input('awb_no') ?? '' }}">
    						</div>
    					</div>
    					<div class="col-lg">
    					    @php
    					        $selectedShip =  request()->input('partner');
    					    @endphp
    						<label class="p-b-10" for="partner">Partner: </label>
    						<select class="multiple-select" id="partner" name="partner[]" multiple="multiple" placeholder="partner">
    						    @foreach($aggrigators as $aggrigator)
    								<option value="{{ $aggrigator->request_partner }} " {{ $selectedShip ? ((in_array($aggrigator->request_partner, $selectedShip)) ? 'selected' : '') : '' }}>{{ $aggrigator->request_partner }}</option>
    							@endforeach
    						</select>
    					</div>
    					<div class="col-lg">
    					    @php
    					        $selectedShip =  request()->input('courier_name');
    					    @endphp
    						<label class="p-b-10" for="courier_name">Last Mile Partner: </label>
    						<select class="multiple-select" id="partner" name="courier_name[]" multiple="multiple" placeholder="courier_name">
    						    @foreach($partners as $partner)
    								<option value="{{ $partner->courier_name }} " {{ $selectedShip ? ((in_array($partner->courier_name, $selectedShip)) ? 'selected' : '') : '' }}>{{ $partner->courier_name }}</option>
    							@endforeach
    						</select>
    					</div>
    					<div class="col-lg">
    						<label class="p-b-10" for="customer_name">Customer Name:</label>
    						<div class="input-group">
    							<input class="form-control" id="customer_name" name="customer_name" type="text" placeholder="Customer Name" value="{{ request()->input('customer_name') ?? '' }}">
    						</div>
    					</div>
					</div>
					<div class="row">
    					<div class="col-lg">
    						<label class="p-b-10" for="payment_mode">Payment Mode:</label>
    						<select class="form-select input-group" id="payment_mode" name="payment_mode" placeholder="Payment Mode">		
    							<option value="">Select</option>
    							<option value="COD" {{ request()->input('payment_mode') == 'COD' ? 'selected' : '' }}>Cash on Delivery</option>
    							<option value="prepaid" {{ request()->input('payment_mode') == 'prepaid' ? 'selected' : '' }}>Prepaid</option>
    						</select>
    					</div>
    					<div class="col-lg">
    					    @php
    					        $selectedShip =  request()->input('shipment_status');
    					    @endphp
    						<label class="p-b-10" for="shipment_status">Shipment Status: </label>
    						<select class="multiple-select" id="shipment_status" name="shipment_status[]" multiple="multiple" placeholder="shipment_status">
    						    @foreach($statuses as $status)
    								<option value="{{ $status->order_status }} " {{ $selectedShip ? ((in_array($status->order_status, $selectedShip)) ? 'selected' : '') : '' }}>{{ $status->order_status }}</option>
    								
    							@endforeach
    						</select>
    					</div>
    					<div class="col-lg">
    						<button class="btn btn-primary" type="submit" style="margin-top: 24px;">Apply</button>
    						<a href="/orders" class="btn btn-secondary" style="margin-top: 24px;">Clear</a>
    					</div>	
    					<div class="col-lg"></div>
    					<div class="col-lg"></div>
    					<div class="col-lg"></div>
    				</div>
              	</form>
            </div>
        </div>
    </div>
    
<hr />

</section> 

<!-- Counts Section -->
<section class="<!--py-5-->" style='padding-top:0px !important;margin-bottom: 20px;'>
    <div class="container-fluid">
        <div class="row">
        <!--Tab-->
         @php
            $ord = request()->input('ord');
        @endphp
		    <ul class="nav nav-tabs" id="myTab" role="tablist" >
		       
			  <li class="nav-item me-2" role="presentation">
			    
				<a class="nav-link btn-css  {{ $ord ? 'btn-white' : 'activeClass btn-black'}}" href="{{ url('/all-orders') }}" id="contact-tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false">All Shipments ( {{ $total_shipment  }} )</a>
			  </li>
			  <li class="nav-item me-2" role="presentation">
			
				<a class="nav-link btn-css  {{ $ord=='Booked,ship' ? 'activeClass btn-black':'btn-white' }}" href="{{ url('/all-orders?ord=Booked,ship') }}" id="contact-tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false">New Shipments ({{ $book }})</a>
			  </li>
			 <!-- <li class="nav-item me-2" role="presentation">-->
				<!--<a class="nav-link btn-css {{ $ord=='ship' ? 'activeClass btn-black':'btn-white' }}" href="{{ url('/orders?ord=ship') }}" id="contact-tab" data-bs-target="#contact-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false">Ship Shipments ({{ $ship }})</a>-->
			 <!-- </li>-->
			  <li class="nav-item me-2" role="presentation">
				<a class="nav-link btn-css {{ $ord=='Pending Pickup' ? 'activeClass btn-black':'btn-white' }}" href="{{ url('/all-orders?ord=Pending Pickup') }}" id="contact-tab" data-bs-target="#contact-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false">Pending Pick ({{ $pick }})</a>
			  </li>
	      <!--    <li class="nav-item me-2" role="presentation">-->
    			<!--<a class="nav-link btn-css {{ $ord=='Ready to Dispatch' ? 'activeClass btn-black':'btn-white' }}" href="{{ url('/orders?ord=Ready to Dispatch') }}" id="contact-tab" data-bs-target="#contact-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false">Ready to Dispatch (0)</a>-->
    		 <!-- </li>-->
    		  <li class="nav-item me-2" role="presentation">
    			<a class="nav-link btn-css {{ $ord=='In Transit' ? 'activeClass btn-black':'btn-white' }}" href="{{ url('/all-orders?ord=In Transit') }}" id="contact-tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false">In-Transit ({{ $transit }})</a>
    		  </li>
    		  <li class="nav-item me-2" role="presentation">
    			<a class="nav-link btn-css {{ $ord=='Out for Delivery' ? 'activeClass btn-black':'btn-white' }}" href="{{ url('/all-orders?ord=Out for Delivery') }}" id="contact-tab" data-bs-target="#contact-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false">Out for Delivery (0)</a>
    		  </li>
    		  <li class="nav-item me-2" role="presentation">
    			<a class="nav-link btn-css {{ $ord=='Delivered' ? 'activeClass btn-black':'btn-white' }}" href="{{ url('/all-orders?ord=Delivered') }}" id="contact-tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false">Delivered ({{ $delivered }})</a>
    		  </li>
    		  <li class="nav-item me-2" role="presentation">
    		      <button class="btn btn-css dropdown-toggle " type="button" data-bs-toggle="dropdown" aria-expanded="false" style="margin-top: 5px;background: white;border-radius: 5px;color: #12263f!important;">@if($ord == 'RTO') RTO Shipments @elseif($ord == 'Cancelled') Cancelled @else More @endif</button>
                    <ul class="dropdown-menu shadow-sm">
                        <li class="nav-item me-2" role="presentation">
                			<a class="nav-link btn-css {{ $ord=='RTO' ? 'activeClass btn-black':'btn-white' }}" href="{{ url('/all-orders?ord=RTO') }}" type="button">RTO Shipments ({{ $rto }})</a>
                		  </li>
                		  <li class="nav-item me-2" role="presentation">
                			<a class="nav-link btn-css {{ $ord=='Cancelled' ? 'activeClass btn-black':'btn-white' }}" href="{{ url('/all-orders?ord=Cancelled') }}" type="button">Cancelled ({{ $cancelled }})</a>
                		  </li>
                    </ul>
    		  </li>
    		  
		    </ul>
		</div>
    </div>
</section>
<!-- Header Section-->
<section class="bg-white">
    <div class="container-fluid">
        <div class="row d-flex align-items-md-stretch">
            <div class="tab-content" id="myTabContent">
		        <div class="tab-pane fade show active " id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
		            <table class="table align-middle mb-0 bg-white" >
		                <thead class="bg-light">
                		   <tr>
                		      <th>#</th>
                			  <th class="text-center">Order ID</th>
                			  <!--<th>Channel</th>-->
                			  <th class="text-center">Order Date</th>
                			  <!--<th>Consignment Type</th>-->
                			  <th class="text-center">Shipment Value</th>
                			  <th>Pay Mode</th>
                			  <th>Recipient</th>
                			  <th>Aggregator/Partner</th>
                			  <th>LMP/LSP</th>
                			  <th>Weight</th>
                			  <th>AWB No.</th>
                			  <th>Shipment Status</th>
                			  <!--<th>Journey</th>-->
                			  <!--<th>Remarks</th>-->
                			</tr>
	                    </thead>
    		            <tbody>
                		  @foreach($data['orders'] as $order)
                		    @php
                		        $rowStyle = '';
                		        if(ucwords($order->order_status) == 'Cancelled')
                		        {
                		            $btn = 'btn-danger';
                		            $rowStyle = 'background: antiquewhite;';
                		        }
                		        elseif(ucwords($order->order_status) == 'Delivered')
                		        {
                		            $btn = 'btn-success';
                		            $rowStyle = 'background: floralwhite;';
                		        }
                		        else
                		        {
                		            $btn = 'btn-outline-dark';
                		        }
                	        @endphp
    		                <tr style='border-radius: 5px 5px 4px 5px;box-shadow: 1px 1px 1px 0px aqua;{{$rowStyle}}'>
                    		      <td></td>
                    			  <td class="text-center">
                    				<div class="d-flex align-items-center">
                    				  <!--<a title='View Order' href='view-order?ord={{Crypt::encrypt($order->id)}}' ><svg class="svg-icon svg-icon-sm svg-icon-heavy me-2"><use xlink:href="#survey-1"> </use></svg></a>-->
                    				  <div class="ms-3">
                    					<p class="fw-bold mb-1">@if($order->source == 'Manual')<span class="h6 m-0" style="color:var(--bs-link-color);padding-right:10px">MW </span>@endif{{$order->order_no}}</p>
                    				  </div>
                    				    
                    				</div>
                    			  </td>
                    			  
                    			  <!--<td>-->
                    			  <!--    {{ $order->channel ?? '' }}-->
                    			  <!--</td>-->
                    			  <td class="text-center">
                    				<p class="fw-normal mb-1">{{$order->created_at}}</p>
                    			  </td>
                    			 <!-- <td>-->
                    				<!--<p class="fw-normal mb-1">{{$order->consignment_type}}</p>-->
                    			 <!-- </td>-->
                    			  <td>
                    				<p class="fw-normal mb-1">₹ {{$order->total_amount}}</p>
                    			  </td>
                    			  <td>
                    				<p class="fw-normal mb-1">{{$order->payment_mode}}</p>
                    			  </td>
                    			  <td>
                    				<p class="fw-normal mb-1" title="{{ $order->shipping_first_name.' '.$order->shipping_last_name }}">{{substr($order->shipping_first_name, 0, 15) . "...";}}</p>
                    			  </td>
                    			  @php
                    			    $requestPartner = explode('App',$order->request_partner);
                    			    
                    			  @endphp
                    			  <td>
                    				<p class="fw-normal mb-1">{{$requestPartner[0]}}</p>
                    			  </td>
                    			  <td>
                    				<p class="fw-normal mb-1">{{$order->courier_name?$order->courier_name:'XXXXXXXXX'}}</p>
                    			  </td>
                    			  <td>
                    				<p class="fw-normal mb-1">{{$order->total_weight/1000}} Kg</p>
                    			  </td>
                    			  <td>
                    				   <p class="fw-normal mb-1">{{ $order->awb_no?$order->awb_no:'XXXXXXXXX'}}</p></a>
                    			  </td>
                    			  <td>
                    				  <p class="fw-normal mb-1"><span class="btn {{ $btn }} btn-sm " style="width: 90%!important;">{{ucwords($order->order_status)}}</span></p>
                    			
                    		        </td>
                    			
                    		</tr>
    		            @endforeach 	
    	            </tbody>
		            </table>
		            {{ $data['orders']->links() }}
		           
                </div>
		    </div>
	    </div>
    </div>
</section>

<script>
$(document).ready(function () { 
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
    });
    var ordArr = [];
	$(document).on('change', '#checkAll', function() {
		while (ordArr.length > 0) {
			ordArr.pop();	
		}
		var isChecked = $(this).prop('checked');
		if(isChecked == true)
		{
			$('.checkClass').prop('checked',true);
			$('#action-tab').css("display", "inherit");
			$('#myTab').css("display","none");
			var checkboxes = document.querySelectorAll('input[type=checkbox]:checked')
			for (var i = 1; i < checkboxes.length; i++) {
				ordArr.push(checkboxes[i].value)
			}
			$('#selected-order').text(ordArr.length+' selected');
			console.log(ordArr);
		}
		else
		{
			$('#checkAll').prop('checked',false);
			$('.checkClass').prop('checked',false);
			$('#action-tab').css("display", "none");
			$('#myTab').css("display","inherit");
		}
	});
	$(document).on('change', '.checkClass', function() {
    		$('#action-tab').css("display", "inherit");
    		$('#myTab').css("display","none");
    		var cid= $(this).attr('id');			
    		var data = cid.split(':');			
    		var order_num  = data[1]; 			
    		var isChecked = $(this).prop('checked');
    		if(isChecked == true){
    			$('#'+cid).prop('checked',true);	
    			ordArr.push(order_num);
    			console.log(ordArr);
    			$('#selected-order').text(ordArr.length+' selected');
    		}
    		else if(isChecked == false){
    			console.log('else');
    			$('#'+cid).prop('checked',false);
    			var index = ordArr.indexOf(order_num);
    			if (index !== -1) {
    				ordArr.splice(index, 1);
    			}
    			console.log(ordArr);
    			$('#selected-order').text(ordArr.length+' selected');
    			if(ordArr.length == 0){
    				$('#action-tab').css("display", "none");
    				$('#myTab').css("display","inherit");
    				$('#checkAll').prop('checked',false);
    			}
    		}
	});
	$(document).on('click', '.order-action', function() {
    	var act = $(this).attr('id');
    	console.log(act);
    	var orders = ordArr;
    	if (act === 'bulk-ship-order') {
    		var status = 'ship';
    		$.ajax({
    				url: "{{ route('order.bulk.status')}}", // Ensure the route is correct and defined in your Laravel routes file
    				type: "POST",
    				data: {
    					_token: '{{ csrf_token() }}',
    					orders: orders,
    					status: status
    				},
    				
    				success: function(res) {
    					if (res.data == true) {
    						console.log(res.data);
    						Swal.fire({
    							title: 'Success!',
    							text: "Status Updated!!!",
    							timer: 2000,
    							icon: 'success'
    						});
    					} else if (res.error) {
    						console.log(res.error);
    						Swal.fire({
    							title: 'Failed!',
    							text: "{{ $errors->first() }}",
    							timer: 5000,
    							icon: 'error'
    						});
    					}
    				},
    				error: function(xhr, textStatus, errorThrown) {
    					// Handle error
    					console.log('Error:', errorThrown);
    					Swal.fire({
    							title: 'Failed!',
    							text: errorThrown,
    							timer: 5000,
    							icon: 'error'
    						});
    				}
			    });
		} 
		if (act === 'cancel-order') {
			var status = 'Cancelled';
			swal.fire({
            title: "Warning",
            text: "Do want to change ? ",
            type: "warning",
            showCancelButton: true,
            confirmButtonText: "Confirm",
            cancelButtonText: "Cancel",
          }).then((result) => {
            if (result.isConfirmed) {
              $.ajax({
				url: "{{ route('order.bulk.status')}}", // Ensure the route is correct and defined in your Laravel routes file
				type: "POST",
				data: {
					_token: '{{ csrf_token() }}',
					orders: orders,
					status: status
				},
				success: function(res) {
					if (res.data == true) {
						console.log(res.data);
						Swal.fire({
							title: 'Success!',
							text: "Status Updated!!!",
							timer: 2000,
							icon: 'success'
						}).then(function() {
						    location.reload();
						});
					} else if (res.error) {
						console.log(res.error);
						Swal.fire({
							title: 'Failed!',
							text: "{{ $errors->first() }}",
							timer: 5000,
							icon: 'error'
						});
					}
				},
				error: function(xhr, textStatus, errorThrown) {
					// Handle error
					console.log('Error:', errorThrown);
					Swal.fire({
							title: 'Failed!',
							text: errorThrown,
							timer: 5000,
							icon: 'error'
						});
				}
			});
            }
          });
		}		
	});
    $('#select_warehouse_dropdown').on('change', function() {
        var order_id = $(this).attr('data-order-id');
        var warehouse_id = $(this).val();
        $.ajax({
            url: 'orders/get_delivery_info/' + order_id + '/' + warehouse_id,
            type: "GET",
            datatype: "JSON",
            cache: false,
            success: function(data) {
                $('#fulfillment_info').html(data);
            }
        });
    });
    function weightFun(weight,callback) {
	      $.ajax({
                url: '/get/weight',
                type: "GET",
                data: {
                    weight:weight
                },
                success: function(res) {
                    if(res.data!=''){
                        $('input[name="weight_range"][value="' + res.data.weight_range + '"]').prop('checked', true);
                        $('input[name="weight_range"][value!="' + res.data.weight_range + '"]').attr('disabled', true); // Fixed 'disable' to 'disabled'
                        callback({'status':true,'weight':weight});
                    }
                    else{
                        console.log(res.error);
                        callback({'status':false});
                    }
                },
                error: function(xhr, textStatus, errorThrown) {
    				// Handle error
    				console.log('Error:', errorThrown);
    				callback({'status':false});
                    // Handle the AJAX error here
                }
            });
	}
	$(".popUp").click(async function (event) {
	    $('#shipBtn').attr('disabled',false);
	        $('#courier_rate').text(''); 
			$("#myModal").modal('show');
			var order_no = $(this).attr('id');
			$('#order_no').val(order_no);
			var total_price = $('#total_price' + order_no).val();	
			var total_weight = $('#weight' + order_no).val();	
			console.log(total_weight);
			var vol_weight = $('#volumetric_weight' + order_no).val();
			var volumetric_weight = vol_weight/1000;
			var weight= 0;
			if(total_weight>volumetric_weight){
			    weight = total_weight;
			}
			else{
			    weight = volumetric_weight;
			}
			console.log(weight);
			var pincode = $('#pincode' + order_no).val();
			var payment_mode = $('#payment_mode' + order_no).val();
			await weightFun(weight,function(resp){
		    if(resp.status==true)
		    {
			    $('input[type="radio"][name="courier_id"]').prop('checked', false);
			    var weightRangeSelected = $('input[name="weight_range"]:checked').val();
                console.log(weightRangeSelected);
                $.ajax({
                    url: '/get/zone_rate',
                    type: "GET",
                    data: {
                        pincode:pincode,
                        weight_range:weightRangeSelected,
                        weight:resp.weight,
                        payment_mode:payment_mode,
                        total_price:total_price
                    },
                    success: function(response) {
                        if(response.data!='')
                        {
                            $('#courier_data').val(JSON.stringify(response));
                            var content = '';
                            var  resultData = response.data;
                            var sortedData = resultData.sort(function (a, b) {
                                return parseInt(a.final_charge) - parseInt(b.final_charge);
                            });
                            console.log(sortedData);
                            $.each(sortedData, function(index, val) {   
                                content += `<div class="col-sm-4 couriourval notprefered " data-target="- ">
                                <div class="alert alert-success  fade show" role="alert">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" required="" name="courier_id" class="courier_id custom-control-input" value="${val['aggregator'] }-${val['courier_name']}-${val['shipment_mode']}">
                                        <label class="custom-control-label" for="courier_id" style='font-size:9px'>
                                            <i class="mdi mdi-flash"></i> ${val['aggregator'] } ${val['courier_name'] }
                                            <span style="margin-left:5px">${val['shipment_mode'] }</span> 
                                            <span style="margin-left:5px">${val['weight'] }</span>
                                            <span style="margin-left:5px">(Rs. ${val['courier_rate'] }) </span>
                                            <span style="margin-left:5px">Final Rate (Rs. ${val['final_charge'] })</span><br>
                                            <span style="margin-left:5px">(Add. weight ${val['additional_weight'] }, Add. charges Rs. ${val['additional_charge'] }, Cod Charge Rs. ${val['cod'] })</span>
                                            <br>
                                        </label>
                                    </div>                                                       
                                </div>
                            </div>`
                            });
                            $('#courier_rate').text(''); 
                            $('#courier_rate').append(content); 
                        }
                        else
                        {
                            console.log(response.error);
                            $('#shipBtn').attr('disabled',true);
                        }
                    },
                    error: function(xhr, textStatus, errorThrown) {
        				// Handle error
        				console.log('Error:', errorThrown);
                        // Handle the AJAX error here
                    }
                });
			    $('.shipment_type').prop('checked', false);
			}
		       //console.log(resp);
		   })
	});	
	$('.shipment_type').change(function () {
	    $('#shipBtn').attr('disabled',false);
        var ship_val = $(this).val();
        console.log(ship_val);
        var courier_data = $('#courier_data').val();
        var response = JSON.parse(courier_data);
        $('#courier_rate').empty();
        var content = '';
    
        var filteredData = (ship_val === 'All') ? response.data : response.data.filter(function (item) {
            return item.shipment_mode === ship_val;
        });
        var sortedData = filteredData.sort(function (a, b) {
            return parseInt(a.final_charge) - parseInt(b.final_charge);
        });
        console.log(sortedData);
        if (sortedData.length > 0) {
            $.each(sortedData, function (index, val) {
                content += `<div class="col-sm-4 couriourval notprefered" data-target="-">
                                <div class="alert alert-success fade show" role="alert">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" required="" name="courier_id" class="courier_id custom-control-input" value="${val['aggregator']}-${val['courier_name']}-${val['shipment_mode']}">
                                        <label class="custom-control-label" for="courier_id" style='font-size:9px'><i class="mdi mdi-flash"></i> ${val['aggregator']} ${val['courier_name']}
                                        <span style="margin-left:5px">${val['shipment_mode']}</span>
                                        <span style="margin-left:5px">${val['weight']}</span>
                                        <span style="margin-left:5px">(Rs. ${val['courier_rate']}) </span>
                                        <span style="margin-left:5px">Final Rate (Rs. ${val['final_charge']})</span><br>
                                        <span style="margin-left:5px">(Add. weight ${val['additional_weight']}, Add. charges Rs. ${val['additional_charge']},Cod Charge Rs. ${val['cod']})</span><br>
                                    </label>
                                </div>
                            </div>
                        </div>`;
            });
    
            // Append the new content
            $('#courier_rate').append(content);
        } 
        else 
        {
            $('#shipBtn').attr('disabled',true);
            console.log('No data found for the selected shipment type.');
        }
    });

	$('.courier_partner').change(function () {  
			var order_no = $('#order_no').val();     
			if ($('.shipment_type:checked').length === 0) {
				console.log('if');   
				$('.courier_partner').attr('id', 'courier_id' + order_no);
		 		$('.courier_partner').attr('class', 'courier_class' + order_no);
				swal.fire({
					title: "Warning",
					text: "Select Shipment mode first?",
					type: "warning",
					confirmButtonText: "OK",
				});
				$('.courier_class' + order_no).prop('checked', false);
			} 
			else{
				console.log('else');   
				$('.courier_id' + order_no).prop('checked', true);
			}
		})    
	$('.ship_order').click(function () {  
	    var order_id = $(this).attr('id');
	    console.log(order_id);
	    if(order_id !='' || order_id !='undefined' || order_id !='Null'){
	        $.ajax({
                url: "{{ route('ship.order') }}",
                type: "Post",
                data: {
                    
                    order_no:order_id,
                    _token: '{{ csrf_token() }}',
                },
                success: function(res) {
                    console.log(res.response);
                    if(res.response.status !=false){
                        swal.fire({
                            title: "success",
                            text: "Shipment Proceed successfully",
                            type: "success",
                            icon: "success"
                         })
                    }
                    else{
                        swal.fire({
                            title: "Error",
                            text: res.response.message,
                            type: "error",
                            icon: "error"
                         })
                    }
                },
                error: function(xhr, textStatus, errorThrown) {
    				// Handle error
    				console.log('Error:', errorThrown);
    				swal.fire({
                            title: "Error",
                            text: errorThrown,
                            type: "error",
                            icon: "error"
                         })
    				
                }
            });
	    }
	    
		else{
		    swal.fire({
            title: "Info",
            text: "Order number not found",
            type: "warning",
          })
		}  
		
	}) 
});
function imports()
{
	$('#exModal').modal('show');
}       
function checkPermission(){
      
          swal.fire({
            title: "Info",
            text: "You do not have permission ",
            type: "warning",
          })
        
    }
</script>
@endsection