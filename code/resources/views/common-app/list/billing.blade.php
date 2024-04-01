@extends('common-app/master')
@section('title', 'Billing List')
@section('content')
<!-- Counts Section -->
    <section class="py-filter">
	    <div class="col-md-12 text-right">
    		<!-- <a href="catalogus/all" class="btn btn-outline-dark btn-sm"><i class="mdi mdi-library-books"></i> Manage Catalogue</a> -->
    		<!-- <a href="product/all" class="btn btn-outline-dark btn-sm"><i class="mdi mdi-link-variant"></i>Product Catalog</a> -->
    		<a href="#" class="btn btn-outline-dark btn-sm"><i class="mdi mdi-arrow-down-bold-circle"></i> Export</a>
    		<button type="button" class="btn btn-sm btn-outline-dark refresh_orders_button"><i class="mdi mdi-refresh"></i> Refresh</button>
    		<button class="btn btn-outline-dark btn-sm" data-toggle="modal" data-target=".import_orders_modal"> <i class="mdi mdi-arrow-up-bold-circle"></i> Import</button>
    		<button class="btn btn-outline-dark btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Create Order</button>
    	    <ul class="dropdown-menu shadow-sm">
    			<li><a class="dropdown-item" href="/add-orders"><i class="mdi mdi-plus"></i> Create Forward Order</a></li>
    			<li><a class="dropdown-item" href="#!"><i class="mdi mdi-plus"></i> Create Reverse Order</a></li>
    			<li><a class="dropdown-item" href="#!"><i class="mdi mdi-plus"></i> Create Reverse QC Order</a></li>
    	    </ul>
    		<button type="button" class="btn btn-outline-dark show_hide_filter btn-sm" onclick="showFilter()"><i class="icon-placeholder mdi mdi-filter"></i> Filters</button>
            <button type="button" class="btn btn-outline-dark show_hide_filter btn-sm" style="display:none;"><i class="icon-placeholder mdi mdi-close"></i> Close</button>
	    </div>
		
		<div id='filter' style='display:none'>
		<div class="card-body">
			<div class="row">
				<div class="form-group col-lg-3">
					<label>From Date:</label>
					<input type="text" required="" class="form-control" id='reportrange' name="order_id" placeholder="Order Id" value="">
					<span class="errormsg" id="errormsg" style="color: #8b0001;font-weight: bold;"></span>
				</div>
				<div class="form-group col-lg-3">
					<label>Order ID(s):</label>
					<input type="text" required="" class="form-control" name="order_id" placeholder="Order Id" value="">
					<span class="errormsg" id="errormsg" style="color: #8b0001;font-weight: bold;"></span>
				</div>
				<div class="form-group col-lg-3">
					<label>Product Name:</label>
					<input type="text" required="" class="form-control" name="product_name" placeholder="Product Name" value="">
					<span class="errormsg" id="errormsg" style="color: #8b0001;font-weight: bold;"></span>
				</div>
				<div class="form-group col-lg-3">
					<label>Customer Name:</label>
					<input type="text" required="" class="form-control" name="customer_name" placeholder="Customer Name" value="">
					<span class="errormsg" id="errormsg" style="color: #8b0001;font-weight: bold;"></span>
				</div>
				<div class="form-group col-lg-3">
					<label>Channel:</label>
					<select id="payment_mode" required="" name="payment_mode" class="form-control">
						<option value="COD">Cash on Delivery</option>
						<option value="prepaid">Prepaid</option>
						<!-- <option  value="reverse">Reverse</option> -->
					</select>
					<span class="errormsg" id="errormsg" style="color: #8b0001;font-weight: bold;"></span>
				</div>
				<div class="form-group col-lg-3">
					<label>Shipment Status:</label>
					<select id="payment_mode" required="" name="payment_mode" class="form-control">
						<option value="COD">Cash on Delivery</option>
						<option value="prepaid">Prepaid</option>
						<!-- <option  value="reverse">Reverse</option> -->
					</select>
					<span class="errormsg" id="errormsg" style="color: #8b0001;font-weight: bold;"></span>
				</div>
				<div class="form-group col-lg-3">
				<label></label>
				   <button type="submit" name="submit" class="btn btn-primary form-control">Apply</button>
				</div>
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
    <section class="py-5" style='padding-top:0px !important'>
        <div class="container-fluid">
          <div class="row">
            <!--Tab-->
			<ul class="nav nav-tabs" id="myTab" role="tablist">
			  <li class="nav-item" role="presentation">
				<button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Wallet Recharge</button>
			  </li>
			  <li class="nav-item" role="presentation">
				<button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Shipping Charges</button>
			  </li>
			  <li class="nav-item" role="presentation">
				<button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false">COD Remittance</button>
			  </li>
			  <li class="nav-item" role="presentation">
				<button class="nav-link" id="disabled-tab" data-bs-toggle="tab" data-bs-target="#dispatch-tab-pane" type="button" role="tab" aria-controls="disabled-tab-pane" aria-selected="false">Transaction History</button>
			  </li>
			  <li class="nav-item" role="presentation">
				<button class="nav-link" id="disabled-tab" data-bs-toggle="tab" data-bs-target="#intransit-tab-pane" type="button" role="tab" aria-controls="disabled-tab-pane" aria-selected="false">Rate Card</button>
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
			  
			  </div>
			  <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
			     
				
			  </div>
			  <div class="tab-pane fade" id="contact-tab-pane" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">
			    
			  
			  </div>
			  <div class="tab-pane fade" id="dispatch-tab-pane" role="tabpanel" aria-labelledby="disabled-tab" tabindex="0">
			  
			  
			  
			  </div>
			  
			  <div class="tab-pane fade" id="intransit-tab-pane" role="tabpanel" aria-labelledby="disabled-tab" tabindex="0">
			  
			    
			  </div>
			  
			</div>
		  </div>
        </div>
      </section>
@endsection