@extends('common-app/master')
@section('title', 'Warehouse List')
@section('content')
@php
if(Auth::user()->user_type == 'isCompany'){
    if(session()->has('client') && !session()->has('warehouse')) {
        echo '<script>window.location.href = "'.url('/get-client', [\Crypt::encrypt(Auth::user()->company->id)]).'";</script>';
    }
    else {
        // Your alternative logic if the condition is not met
    }
}
else{
}
@endphp

<!-- Counts Section -->
	<section class="py-filter">
		<div class="col-md-12 text-end">
			@php
                $currentUrl = url()->current(); // Get the current URL
                $exportUrl = url('export-orders') . '?' . http_build_query(request()->all());
            @endphp
            @if(!empty($userP) && $userP->update != '1')
    		    <a href="#" onclick="checkPermission()" class="btn btn-outline-dark btn-sm"><i class="mdi mdi-arrow-down-bold-circle"></i> Export</a>
    		@else
    		    <a  href="{{ $exportUrl }}" class="btn btn-outline-dark btn-sm"><i class="mdi mdi-arrow-down-bold-circle"></i> Export</a>
    		@endif
			<button type="button" class="btn btn-sm btn-outline-dark refresh_orders_button"><i class="mdi mdi-refresh"></i> Refresh</button>
			
			 @if(!empty($userP) && $userP->write != '1')
			    <a href="#" onclick="checkPermission()" class="btn btn-outline-dark btn-sm">Create warehouse</a>
			    <a href="#" onclick="checkPermission()"><button class="btn btn-outline-dark btn-sm"> <i class="mdi mdi-arrow-up-bold-circle"></i> Import</button></a>
			    <a href="#" onclick="checkPermission()" class="btn btn-outline-dark btn-sm ">Create warehouse for DSP</a>
			 @else
			    <button class="btn btn-outline-dark btn-sm" data-toggle="modal" data-target=".import_orders_modal"> <i class="mdi mdi-arrow-up-bold-circle"></i> Import</button>
			    <a href="{{ route('warehouse.create') }}" class="btn btn-outline-dark btn-sm ">Create warehouse</a>
			    <a href="{{ route('warehouse_dsp.create') }}" class="btn btn-outline-dark btn-sm ">Create warehouse for DSP</a>
			 @endif
			
			<button type="button" class="btn btn-outline-dark show_hide_filter btn-sm" onclick="showFilter()"><i class="icon-placeholder mdi mdi-filter"></i> Filters</button>
			<button type="button" class="btn btn-outline-dark show_hide_filter btn-sm" style="display:none;"><i class="icon-placeholder mdi mdi-close"></i> Close</button>
		</div>
		
		<div class="col-lg-12" id='filter'>                           
            <div class="card">
                <div class="card-header border-bottom">
                  	<h3 class="h4 mb-0">Filters</h3>
                </div>
                <div class="card-body">
    			    <form class="row g-3 align-items-center" method="GET" action="{{ route('warehouse.index') }}">	
                        <div class="row" style="margin-bottom: 1rem !important;">
        					<div class="col-lg-3">
        						<label class="p-b-10" for="order_id">Warehouse Code:</label>
        						<div class="input-group">
        							<input type="text" class="form-control" name="warehouse_code" placeholder="Warehouse Code" value="{{ request()->input('warehouse_code')}}">
        						</div>
        					</div>
        					<!--<div class="col-lg">-->
        					<!--	<label class="p-b-10" for="partner">State: </label>-->
        					<!--	<select class="form-control" id="user_type" name="user_type">    -->
             <!--                       <option value="">Select User type</option>-->
             <!--                       <option value="isClient" {{ request()->input('user_type') == 'isClient' ? 'selected' : ''}}>Is Client</option> -->
             <!--                       <option value="isUser" {{ request()->input('user_type') == 'isUser' ? 'selected' : ''}}>Is User</option>  -->
             <!--                 </select>-->
        					<!--</div>-->
        					<!--<div class="col-lg">-->
        					<!--	<label class="p-b-10" for="courier_name">Company: </label>-->
        					<!--	<select class="form-control" id="status" name="status">    -->
             <!--                       <option value="">Select status</option>-->
             <!--                       <option value="1" {{ request()->input('status') == '1' ? 'selected' : ''}}>Active</option> -->
             <!--                       <option value="0" {{ request()->input('status') == '0' ? 'selected' : ''}}>Inactive</option>  -->
             <!--                 </select>-->
        					<!--</div>-->
        					<div class="col-lg-3">
        						<button class="btn btn-primary" type="submit" style="margin-top: 24px;">Apply</button>
        						<a href="/warehouse" class="btn btn-secondary" style="margin-top: 24px;">Clear</a>
        					</div>	
        				</div>
                  	</form>
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
				  <th>Code</th>
				  <th>Warehouse</th>
				  <th>Contact Person</th>
				  <th>Company</th>
				  <th>Client</th>
				  <th>Phone</th>
				  <th>Email</th>				  
				  <th>GST No.</th>
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
				        @if(!empty($userP) && $userP->update != '1')
				            <a href="#" onclick="checkPermission()"><svg class="svg-icon svg-icon-sm svg-icon-heavy me-2"><use xlink:href="#survey-1"> </use></svg></a>
				        @else
				            <a href="{{ route('warehouse.edit',(\Crypt::encrypt($warehouse->id))) }}" >
				                <svg class="svg-icon svg-icon-sm svg-icon-heavy me-2"><use xlink:href="#survey-1"> </use></svg>
				            </a>
				        @endif
				    </td>
				  <td>
					<p class="fw-bold mb-1">{{ $warehouse->warehouse_code }}</p>
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
				   @if(!empty($userP) && $userP->update != '1')
    					@if($warehouse->status == '1')
    					    <a href="#" onclick="checkPermission()"><button type="button" class="btn btn-sm btn-primary">Active</button></a>
    					@else
    						<a href="#" onclick="checkPermission()"><button type="button" class="btn btn-sm btn-danger">Banned</button></a>
    					@endif
    				@else
    				    @if($warehouse->status == '1')
    					    <button type="button" class="btn btn-sm btn-primary" onclick="status({{ $warehouse->id}})">Active</button>
    					@else
    						<button type="button" class="btn btn-sm btn-danger" onclick="status({{ $warehouse->id}})">Banned</button>
    					@endif
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
            var url = "{{ route('warehouse.show', ['warehouse' => ':warehouseId']) }}".replace(':warehouseId', n);
            if (n) {
              swal.fire({
                title: "Warning",
                text: "Do want to change ? ",
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