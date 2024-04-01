@extends('admin-app.admin-master')
@section('title', 'Warehouse List')
@section('content')
@php
    if(session()->has('company') && !session()->has('client') && !session()->has('warehouse')){
          echo '<script>window.location.href = "/company_list";</script>';
    }
    else if(session()->has('company') && session()->has('client') && !session()->has('warehouse')){
          echo '<script>window.location.href = "/company_list";</script>';
    }
    else{
         
    }
@endphp
<!-- Counts Section -->
	<section class="py-filter">
		<div class="col-md-12 text-end">
			<a href="#" class="btn btn-outline-dark btn-sm"><i class="mdi mdi-arrow-down-bold-circle"></i> Export</a>
			<button type="button" class="btn btn-sm btn-outline-dark refresh_orders_button"><i class="mdi mdi-refresh"></i> Refresh</button>
			<button class="btn btn-outline-dark btn-sm" data-toggle="modal" data-target=".import_orders_modal"> <i class="mdi mdi-arrow-up-bold-circle"></i> Import</button>
			<a href="{{ route('app-warehouse.create') }}" class="btn btn-outline-dark btn-sm ">Create warehouse</a>
			<button type="button" class="btn btn-outline-dark show_hide_filter btn-sm" onclick="showFilter()"><i class="icon-placeholder mdi mdi-filter"></i> Filters</button>
			<button type="button" class="btn btn-outline-dark show_hide_filter btn-sm" style="display:none;"><i class="icon-placeholder mdi mdi-close"></i> Close</button>
		</div>
		
		<div id='filter' style='display:none'>
			<div class="card-body">
				<div class="row">
					<div class="form-group col-lg-3">
						<label>From Date:</label>
						<input type="text" required="" class="form-control" name="order_id" placeholder="Order Id" value="">
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
					
					<div class="form-group col-lg-2 py-4">				
					<button type="submit" name="submit" class="btn btn-primary form-control">Apply</button>
					</div>
				</div>
			</div>
		</div>
		<hr />
	</section> 
	<div class="col order-last">
		@if(\Request::old('success'))
		<div class="alert alert-success" > {{\Request::old('success')}} </div>
		@elseif(\Request::old('error'))
		<div class="alert alert-danger" > {{\Request::old('error')}} </div>
		@endif
	</div>
      <!-- Header Section-->
	  
      <section class="bg-white">
        <div class="container-fluid">
          <div class="row d-flex align-items-md-stretch">
            <div class="tab-content" id="myTabContent">
			  <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
			  <table class="table align-middle mb-0 bg-white">
			   <thead class="bg-light">
				<tr>
				    <th>Action</th>
				  <th>Warehouse Code</th>
				  <th>Warehouse</th>
				  <th>Contact Person</th>
				  <th>Company</th>
				  <th>Client</th>
				  <th>Support Phone no</th>
				  <th>Support Email id</th>				  
				  <th>GST no</th>
				  <th>State</th>
				  <th>City</th>
				  <th>Pincode</th>
				  <th>Status</th>
				</tr>
			  </thead>
			  <tbody>
			  @foreach($warehouses as $warehouse)
				<tr style='border-radius: 5px 5px 4px 5px;box-shadow: 1px 1px 1px 0px aqua;'>
				    <td>
				        <a href="{{ route('app-warehouse.edit',(\Crypt::encrypt($warehouse->id))) }}">
				            <svg class="svg-icon svg-icon-sm svg-icon-heavy me-2"><use xlink:href="#survey-1"> </use></svg>
				        </a>
				    </td>
				  <td >
					<div class="d-flex align-items-center">
					  <!--<img src="https://mdbootstrap.com/img/new/avatars/8.jpg" alt="" style="width: 45px; height: 45px" class="rounded-circle" />-->
					  
					  <div class="ms-3">
						<p class="fw-bold mb-1">{{ $warehouse->warehouse_code }}</p>
					  </div>
					</div>
				  </td>
                  <td>
					<p class="fw-normal mb-1">{{ $warehouse->warehouse_name }}</p>
				  </td>
				  <td>
					<p class="fw-normal mb-1">{{ $warehouse->contact_name }}</p>
				  </td>
				  <td>
					<p class="fw-normal mb-1">{{ $warehouse->company->name }}</p>
				  </td>
				  <td>
					<p class="fw-normal mb-1">{{ $warehouse->client->name }}</p>
				  </td>
				  <td>
					<p class="fw-normal mb-1">{{ $warehouse->support_phone }}</p>
				  </td>
				  <td>
					<p class="fw-normal mb-1">{{ $warehouse->support_email }}</p>
				  </td>
                  <td> 
				  	<p class="fw-normal mb-1">{{ $warehouse->gst_no }}</p>
				  </td>
				  <td> 
				  	<p class="fw-normal mb-1">{{ $warehouse->state->state_name }}</p>
				  </td>
				  <td> 
				  	<p class="fw-normal mb-1">{{ $warehouse->city }}</p>
				  </td>
				  <td> 
				  	<p class="fw-normal mb-1">{{ $warehouse->pincode }}</p>
				  </td>
				  <td>
				  	
					@if($warehouse->status == '1')
					    <button type="button" class="btn btn-xs btn-primary" onclick="status({{ $warehouse->id}})">Active</button>
					@else
						<button type="button" class="btn btn-xs btn-danger" onclick="status({{ $warehouse->id}})">Banned</button>
					@endif
				  </td>
				</tr>
				@endforeach
				
			  </tbody>
			  </table>
			  </div>
			</div>
			</div>
        </div>
      </section>
      <script>
        function status(n) {
            var url = "{{ route('app-warehouse.show', ':warehouseId') }}".replace(':warehouseId', n);
            if (n) {
              swal.fire({
                title: "Warning",
                text: "Are you sure?",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Confirm",
                cancelButtonText: "Cancel",
              }).then((result) => {
                if (result.isConfirmed) {
                  window.location.href = url;
                }
              });
            }
        }
</script>
@endsection