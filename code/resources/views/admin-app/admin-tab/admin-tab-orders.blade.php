@extends('admin-app/admin-master')
@section('title', 'Orders List')
@section('content')
    <section class="py-filter">
	    <div class="col-md-12 text-right">
    		<!-- <a href="catalogus/all" class="btn btn-outline-dark btn-sm"><i class="mdi mdi-library-books"></i> Manage Catalogue</a> -->
    		<!-- <a href="product/all" class="btn btn-outline-dark btn-sm"><i class="mdi mdi-link-variant"></i>Product Catalog</a> -->
    		<a href="#" class="btn btn-outline-dark btn-sm"><i class="mdi mdi-arrow-down-bold-circle"></i> Export</a>
    		<button type="button" class="btn btn-sm btn-outline-dark refresh_orders_button"><i class="mdi mdi-refresh"></i> Refresh</button>
    		<button class="btn btn-outline-dark btn-sm" data-toggle="modal" data-target=".import_orders_modal"> <i class="mdi mdi-arrow-up-bold-circle"></i> Import</button>
    		@if($data['permission'][0]->write == '1')
        		<button class="btn btn-outline-dark btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Create Order</button>
        	    <ul class="dropdown-menu shadow-sm">
        			<li><a class="dropdown-item" href="/add-app-orders"><i class="mdi mdi-plus"></i> Create Forward Order</a></li>
        			<li><a class="dropdown-item" href="#!"><i class="mdi mdi-plus"></i> Create Reverse Order</a></li>
        			<li><a class="dropdown-item" href="#!"><i class="mdi mdi-plus"></i> Create Reverse QC Order</a></li>
        	    </ul>
    	    @else
    	        <button class="btn btn-outline-dark btn-sm dropdown-toggle disable" title="No Permission"  type="button">Create Order</button>
    	    @endif
    		<button type="button" class="btn btn-outline-dark show_hide_filter btn-sm" onclick="showFilter()"><i class="icon-placeholder mdi mdi-filter"></i> Filters</button>
            <button type="button" class="btn btn-outline-dark show_hide_filter btn-sm" style="display:none;"><i class="icon-placeholder mdi mdi-close"></i> Close</button>
	    </div>
		<div class="col-lg-12" id='filter' style='display:block'>                           
            <div class="card">
                <div class="card-header border-bottom">
                  	<h3 class="h4 mb-0">Filters</h3>
                </div>
                <div class="card-body">
                    <form class="row g-3 align-items-center" method="GET" action="/orders">						
						<div class="col-lg">
							<label class="p-b-10" for="reportrange">From Date:</label>
							<div class="input-group">
								<input class="form-control" id='reportrange' name="from_date" type="text" placeholder="From Date" >
							</div>
						</div>
						<div class="col-lg">
							<label class="p-b-10" for="order_id">Order ID(s):</label>
							<div class="input-group">
								<input class="form-control" id="order_id" name="order_id"  type="text" placeholder="Order Id" value="{{ request()->input('order_id') ?? '' }}">
							</div>
						</div>
						<!-- <div class="col-lg">
							<label class="p-b-10" for="product_name">Product Name:</label>
							<div class="input-group">
								<input class="form-control" id="product_name" type="text" name="product_name" placeholder="Product Name">
							</div>
						</div> -->
						<div class="col-lg">
							<label class="p-b-10" for="customer_name">Customer Name:</label>
							<div class="input-group">
								<input class="form-control" id="customer_name" name="customer_name" type="text" placeholder="Customer Name" value="{{ request()->input('customer_name') ?? '' }}">
							</div>
						</div>
						<div class="col-lg">
							<label class="p-b-10" for="courier">Courier:</label>
							<select class="form-select input-group" id="courier" name="courier" placeholder="Courier">
							<option value="">Select</option>
								@foreach($couriers as $courier)
									<option value="{{ $courier->id }}" {{ request()->input('courier') == $courier->id ? 'selected' : '' }}>{{ $courier->logistics_name }}</option>
								@endforeach
							</select>
						</div>
						<div class="col-lg">
							<label class="p-b-10" for="payment_mode">Payment Mode:</label>
							<select class="form-select input-group" id="payment_mode" name="payment_mode" placeholder="Payment Mode">		
								<option value="">Select</option>
								<option value="COD" {{ request()->input('payment_mode') == 'COD' ? 'selected' : '' }}>Cash on Delivery</option>
								<option value="prepaid" {{ request()->input('payment_mode') == 'prepaid' ? 'selected' : '' }}>Prepaid</option>
							</select>
						</div>
						<div class="col-lg">
							<label class="p-b-10" for="shipment_status">Shipment Status:</label>
							<select class="form-select" id="shipment_status" name="shipment_status" placeholder="shipment_status">	
								<option value="">Select</option>	
								<option value="Booked" {{ request()->input('shipment_status') == 'Booked' ? 'selected' : '' }}>Booked</option>
								<option value="Cancelled" {{ request()->input('shipment_status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
								<option value="Ship" {{ request()->input('shipment_status') == 'Ship' ? 'selected' : '' }}>Ship</option>
							</select>
						</div>
						<div class="col-lg">
							<button class="btn btn-primary" type="submit" style="margin-top: 33px;">Submit</button>
							<a href="/orders" class="btn btn-secondary" style="margin-top: 33px;">Clear</a>
						</div>						
                  	</form>
                </div>
            </div>
        </div>
	<hr />
	@if(session('status'))
		<div class="alert alert-success">
			{{ session('status') }}
		</div>
	@endif
	</section> 
    <!-- Counts Section -->
    <section class="py-5" style='padding-top:0px !important'>
        <div class="container-fluid">
          <div class="row">
            <!--Tab-->
			<ul class="nav nav-tabs" id="myTab" role="tablist">
			  <li class="nav-item" role="presentation">
				<!--<button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">All Orders</button>-->
				<a class="nav-link" href="{{ url('/order-list') }}" id="contact-tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false">All Orders</a>
			  </li>
			  <li class="nav-item" role="presentation">
				<!--<button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">New Orders</button>-->
				<a class="nav-link" href="{{ url('/order-list?ord=Booked') }}" id="contact-tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false">New Orders</a>
			  </li>
			  <li class="nav-item" role="presentation">
				<a class="nav-link" href="{{ url('/order-list?ord=Pickup Pending') }}" id="contact-tab" data-bs-target="#contact-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false">Pending in Warehouse</a>
			  </li>
			  <li class="nav-item" role="presentation">
				<button class="nav-link" id="disabled-tab" data-bs-toggle="tab" data-bs-target="#dispatch-tab-pane" type="button" role="tab" aria-controls="disabled-tab-pane" aria-selected="false">Ready to Dispatch</button>
			  </li>
			  <li class="nav-item" role="presentation">
				<button class="nav-link" id="disabled-tab" data-bs-toggle="tab" data-bs-target="#intransit-tab-pane" type="button" role="tab" aria-controls="disabled-tab-pane" aria-selected="false">In-Transit</button>
			  </li>
			  <li class="nav-item" role="presentation">
				<button class="nav-link" id="disabled-tab" data-bs-toggle="tab" data-bs-target="#delivere-tab-pane" type="button" role="tab" aria-controls="disabled-tab-pane" aria-selected="false">Delivered</button>
			  </li>
			  <li class="nav-item" role="presentation">
				<button class="nav-link" id="disabled-tab" data-bs-toggle="tab" data-bs-target="#rto-tab-pane" type="button" role="tab" aria-controls="disabled-tab-pane" aria-selected="false">RTO</button>
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
			  <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
			  <table class="table align-middle mb-0 bg-white">
			   <thead class="bg-light">
				<tr>
				  <th>Order ID</th>
				  <th>Order Date</th>
				  <!--<th>Product Name</th>-->
				  <th>Customer Name</th>
				  <th>Shipment Status</th>
				  <th>Value</th>
				  <th>Partner</th>
				  <th>Action</th>
				</tr>
			  </thead>
			  <tbody>
			  @foreach($data['orders'] as $order)
			    <tr style='border-radius: 5px 5px 4px 5px;box-shadow: 1px 1px 1px 0px aqua;'>
				  <td>
					<div class="d-flex align-items-center">
					  <!--<img src="https://mdbootstrap.com/img/new/avatars/8.jpg" alt="" style="width: 45px; height: 45px" class="rounded-circle" />-->
					  <a title='View Order' href='show-order?ord={{Crypt::encrypt($order->id)}}'><svg class="svg-icon svg-icon-sm svg-icon-heavy me-2"><use xlink:href="#survey-1"></use></svg></a>
					  <div class="ms-3">
						<p class="fw-bold mb-1">{{$order->order_no}}</p>
					  </div>
					</div>
				  </td>
				  <td>
					<p class="fw-normal mb-1">{{$order->created_at}}</p>
					<!--<p class="text-muted mb-0">07:18 PM</p>-->
				  </td>
				  <!--<td>
					<p class="fw-normal mb-1">Ayurveda</p>
				  </td>-->
				  <td>
					<p class="fw-normal mb-1">{{$order->shipping_first_name}}</p>
				  </td>
				  <td>
					<p class="fw-normal mb-1"><span class="badge text-bg-primary p-2">{{$order->order_status}}</span></p>
				  </td>
				  <td>
					₹ {{$order->invoice_amount}}
				  </td>
				  <td>{{$order->courier_name}}</td>
				  <td>
				   <a href="{{ route('admin-app.track',(\Crypt::encrypt($order->id))) }}" type="button" class="btn btn-outline-primary">Track</a>
				  </td>
				</tr>
			  @endforeach 	
			  </tbody>
			  </table>
			  {{ $data['orders']->links() }}
			  </div>
			  
			  <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
			    <table class="table align-middle mb-0 bg-white">
			    <thead class="bg-light">
				<tr>
				  <th>Order ID</th>
				  <th>Order Date</th>
				  <th>Product Name</th>
				  <th>Customer Name</th>
				  <th>Shipment Status</th>
				  <th>Value</th>
				  <th>Partner</th>
				  <th>Action</th>
				</tr>
			  </thead>
			  <tbody>
				<tr style='border-radius: 5px 5px 4px 5px;box-shadow: 1px 1px 1px 0px aqua;'>
				  <td>
					<div class="d-flex align-items-center">
					  <!--<img src="https://mdbootstrap.com/img/new/avatars/8.jpg" alt="" style="width: 45px; height: 45px" class="rounded-circle" />-->
					  <svg class="svg-icon svg-icon-sm svg-icon-heavy me-2"><use xlink:href="#survey-1"> </use></svg>
					  <div class="ms-3">
						<p class="fw-bold mb-1">NS-226890</p>
					  </div>
					</div>
				  </td>
				  <td>
					<p class="fw-normal mb-1">27 Apr 23</p>
					<p class="text-muted mb-0">07:18 PM</p>
				  </td>
				  <td>
					<p class="fw-normal mb-1">Ayurveda</p>
				  </td>
				  <td>
					<p class="fw-normal mb-1">Krish</p>
				  </td>
				  <td>
					<p class="fw-normal mb-1"><span class="badge text-bg-primary">Delivered</span></p>
				  </td>
				  <td>
					₹ 1860.00
				  </td>
				  <td>Xpress</td>
				  <td>
				   <a href="/track" type="button" class="btn btn-outline-primary">Track</a>
				  </td>
				</tr>
			  </tbody>
			  </table>
			  </div>
			  <div class="tab-pane fade" id="contact-tab-pane" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">
			   <table class="table align-middle mb-0 bg-white">
			   <thead class="bg-light">
				<tr>
				  <th>Order ID</th>
				  <th>Order Date</th>
				  <th>Product Name</th>
				  <th>Customer Name</th>
				  <th>Shipment Status</th>
				  <th>Value</th>
				  <th>Partner</th>
				  <th>Action</th>
				</tr>
			  </thead>
			  <tbody>
				<tr style='border-radius: 5px 5px 4px 5px;box-shadow: 1px 1px 1px 0px aqua;'>
				  <td>
					<div class="d-flex align-items-center">
					  <!--<img src="https://mdbootstrap.com/img/new/avatars/8.jpg" alt="" style="width: 45px; height: 45px" class="rounded-circle" />-->
					  <svg class="svg-icon svg-icon-sm svg-icon-heavy me-2"><use xlink:href="#survey-1"> </use></svg>
					  <div class="ms-3">
						<p class="fw-bold mb-1">NS-226890</p>
					  </div>
					</div>
				  </td>
				  <td>
					<p class="fw-normal mb-1">27 Apr 23</p>
					<p class="text-muted mb-0">07:18 PM</p>
				  </td>
				  <td>
					<p class="fw-normal mb-1">Ayurveda</p>
				  </td>
				  <td>
					<p class="fw-normal mb-1">Krish</p>
				  </td>
				  <td>
					<p class="fw-normal mb-1"><span class="badge text-bg-primary">Delivered</span></p>
				  </td>
				  <td>
					₹ 1860.00
				  </td>
				  <td>Xpress</td>
				  <td>
				   <a href="/track" type="button" class="btn btn-outline-primary">Track</a>
				  </td>
				</tr>
			  </tbody>
			  </table> 
			  </div>
			  <div class="tab-pane fade" id="dispatch-tab-pane" role="tabpanel" aria-labelledby="disabled-tab" tabindex="0">
			   <table class="table align-middle mb-0 bg-white">
			   <thead class="bg-light">
				<tr>
				  <th>Order ID</th>
				  <th>Order Date</th>
				  <th>Product Name</th>
				  <th>Customer Name</th>
				  <th>Shipment Status</th>
				  <th>Value</th>
				  <th>Partner</th>
				  <th>Action</th>
				</tr>
			  </thead>
			  <tbody>
				<tr style='border-radius: 5px 5px 4px 5px;box-shadow: 1px 1px 1px 0px aqua;'>
				  <td>
					<div class="d-flex align-items-center">
					  <!--<img src="https://mdbootstrap.com/img/new/avatars/8.jpg" alt="" style="width: 45px; height: 45px" class="rounded-circle" />-->
					  <svg class="svg-icon svg-icon-sm svg-icon-heavy me-2"><use xlink:href="#survey-1"> </use></svg>
					  <div class="ms-3">
						<p class="fw-bold mb-1">NS-226890</p>
					  </div>
					</div>
				  </td>
				  <td>
					<p class="fw-normal mb-1">27 Apr 23</p>
					<p class="text-muted mb-0">07:18 PM</p>
				  </td>
				  <td>
					<p class="fw-normal mb-1">Ayurveda</p>
				  </td>
				  <td>
					<p class="fw-normal mb-1">Krish</p>
				  </td>
				  <td>
					<p class="fw-normal mb-1"><span class="badge text-bg-primary">Delivered</span></p>
				  </td>
				  <td>
					₹ 1860.00
				  </td>
				  <td>Xpress</td>
				  <td>
				   <a href="/track" type="button" class="btn btn-outline-primary">Track</a>
				  </td>
				</tr>
			  </tbody>
			  </table>
			  </div>
			  <div class="tab-pane fade" id="intransit-tab-pane" role="tabpanel" aria-labelledby="disabled-tab" tabindex="0">
			   <table class="table align-middle mb-0 bg-white">
			   <thead class="bg-light">
				<tr>
				  <th>Order ID</th>
				  <th>Order Date</th>
				  <th>Product Name</th>
				  <th>Customer Name</th>
				  <th>Shipment Status</th>
				  <th>Value</th>
				  <th>Partner</th>
				  <th>Action</th>
				</tr>
			  </thead>
			  <tbody>
				<tr style='border-radius: 5px 5px 4px 5px;box-shadow: 1px 1px 1px 0px aqua;'>
				  <td>
					<div class="d-flex align-items-center">
					  <!--<img src="https://mdbootstrap.com/img/new/avatars/8.jpg" alt="" style="width: 45px; height: 45px" class="rounded-circle" />-->
					  <svg class="svg-icon svg-icon-sm svg-icon-heavy me-2"><use xlink:href="#survey-1"> </use></svg>
					  <div class="ms-3">
						<p class="fw-bold mb-1">NS-226890</p>
					  </div>
					</div>
				  </td>
				  <td>
					<p class="fw-normal mb-1">27 Apr 23</p>
					<p class="text-muted mb-0">07:18 PM</p>
				  </td>
				  <td>
					<p class="fw-normal mb-1">Ayurveda</p>
				  </td>
				  <td>
					<p class="fw-normal mb-1">Krish</p>
				  </td>
				  <td>
					<p class="fw-normal mb-1"><span class="badge text-bg-primary">Delivered</span></p>
				  </td>
				  <td>
					₹ 1860.00
				  </td>
				  <td>Xpress</td>
				  <td>
				   <a href="/track" type="button" class="btn btn-outline-primary">Track</a>
				  </td>
				</tr>
			  </tbody>
			  </table>
			  </div>
			  <div class="tab-pane fade" id="<span class="badge text-bg-primary">Delivered</span>-tab-pane" role="tabpanel" aria-labelledby="disabled-tab" tabindex="0">
			   <table class="table align-middle mb-0 bg-white">
			   <thead class="bg-light">
				<tr>
				  <th>Order ID</th>
				  <th>Order Date</th>
				  <th>Product Name</th>
				  <th>Customer Name</th>
				  <th>Shipment Status</th>
				  <th>Value</th>
				  <th>Partner</th>
				  <th>Action</th>
				</tr>
			  </thead>
			  <tbody>
				<tr style='border-radius: 5px 5px 4px 5px;box-shadow: 1px 1px 1px 0px aqua;'>
				  <td>
					<div class="d-flex align-items-center">
					  <!--<img src="https://mdbootstrap.com/img/new/avatars/8.jpg" alt="" style="width: 45px; height: 45px" class="rounded-circle" />-->
					  <svg class="svg-icon svg-icon-sm svg-icon-heavy me-2"><use xlink:href="#survey-1"> </use></svg>
					  <div class="ms-3">
						<p class="fw-bold mb-1">NS-226890</p>
					  </div>
					</div>
				  </td>
				  <td>
					<p class="fw-normal mb-1">27 Apr 23</p>
					<p class="text-muted mb-0">07:18 PM</p>
				  </td>
				  <td>
					<p class="fw-normal mb-1">Ayurveda</p>
				  </td>
				  <td>
					<p class="fw-normal mb-1">Krish</p>
				  </td>
				  <td>
					<p class="fw-normal mb-1"><span class="badge text-bg-primary">Delivered</span></p>
				  </td>
				  <td>
					₹ 1860.00
				  </td>
				  <td>Xpress</td>
				  <td>
				   <a href="/track" type="button" class="btn btn-outline-primary">Track</a>
				  </td>
				</tr>
			  </tbody>
			  </table>
			  </div>
			  <div class="tab-pane fade" id="rto-tab-pane" role="tabpanel" aria-labelledby="disabled-tab" tabindex="0">
			  <table class="table align-middle mb-0 bg-white">
			   <thead class="bg-light">
				<tr>
				  <th>Order ID</th>
				  <th>Order Date</th>
				  <th>Product Name</th>
				  <th>Customer Name</th>
				  <th>Shipment Status</th>
				  <th>Value</th>
				  <th>Partner</th>
				  <th>Action</th>
				</tr>
			  </thead>
			  <tbody>
				<tr style='border-radius: 5px 5px 4px 5px;box-shadow: 1px 1px 1px 0px aqua;'>
				  <td>
					<div class="d-flex align-items-center">
					  <!--<img src="https://mdbootstrap.com/img/new/avatars/8.jpg" alt="" style="width: 45px; height: 45px" class="rounded-circle" />-->
					  <svg class="svg-icon svg-icon-sm svg-icon-heavy me-2"><use xlink:href="#survey-1"> </use></svg>
					  <div class="ms-3">
						<p class="fw-bold mb-1">NS-226890</p>
					  </div>
					</div>
				  </td>
				  <td>
					<p class="fw-normal mb-1">27 Apr 23</p>
					<p class="text-muted mb-0">07:18 PM</p>
				  </td>
				  <td>
					<p class="fw-normal mb-1">Ayurveda</p>
				  </td>
				  <td>
					<p class="fw-normal mb-1">Krish</p>
				  </td>
				  <td>
					<p class="fw-normal mb-1"><span class="badge text-bg-primary">Delivered</span></p>
				  </td>
				  <td>
					₹ 1860.00
				  </td>
				  <td>Xpress</td>
				  <td>
				   <a href="/track" type="button" class="btn btn-outline-primary">Track</a>
				  </td>
				</tr>
			  </tbody>
			  </table>
			  </div>
			</div>
		  </div>
        </div>
    </section>
@endsection