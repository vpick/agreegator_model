@extends('admin-app.admin-master')
@section('title', 'Company List')
@section('content')
<!-- Counts Section -->
	  <section class="py-filter">
	    <div class="col-md-12 text-end">
    		<a href="#" class="btn btn-outline-dark btn-sm"><i class="mdi mdi-arrow-down-bold-circle"></i> Export</a>
    		<button type="button" class="btn btn-sm btn-outline-dark refresh_orders_button"><i class="mdi mdi-refresh"></i> Refresh</button>
    		<button class="btn btn-outline-dark btn-sm" data-toggle="modal" data-target=".import_orders_modal"> <i class="mdi mdi-arrow-up-bold-circle"></i> Import</button>
    		<a href="{{ route('company.create') }}" class="btn btn-outline-dark btn-sm ">Create Company</a>
    		<button type="button" class="btn btn-outline-dark show_hide_filter btn-sm" onclick="showFilter()"><i class="icon-placeholder mdi mdi-filter"></i> Filters</button>
            <button type="button" class="btn btn-outline-dark show_hide_filter btn-sm" style="display:none;"><i class="icon-placeholder mdi mdi-close"></i> Close</button>
	    </div>
		<div class="col-lg-12" id='filter' style='display:none'>                           
            <div class="card">
                <div class="card-header border-bottom">
                  	<h3 class="h4 mb-0">Filters</h3>
                </div>
                <div class="card-body">
                    <form class="row g-3 align-items-center" method="GET" action="{{ route('company.index') }}">	
                        <div class="row" style="margin-bottom: 1rem !important;">
        					<div class="col-sm-3">
        					    <label>Company Name:</label>
                				<input type="text" class="form-control" name="company_name" placeholder="Company Name" value="{{ request()->input('company_name') ?? ''}}">
                				<span class="errormsg" id="errormsg" style="color: #8b0001;font-weight: bold;"></span>
        					</div>
        					<div class="col-sm-3">
        					    <label>Company Code:</label>
            					<input type="text" class="form-control" name="company_code" placeholder="Company Code" value="{{ request()->input('company_code') ?? ''}}">
            					<span class="errormsg" id="errormsg" style="color: #8b0001;font-weight: bold;"></span>
        					</div>
        					<div class="col-sm-3">
        						<button class="btn btn-primary" type="submit" style="margin-top: 24px;">Apply</button>
        						<a href="{{ route('company.index') }}" class="btn btn-secondary" style="margin-top: 24px;">Clear</a>
        					</div>
        				</div>
                  	</form>
                </div>
        </div>
    </div>

	<hr />
	</section> 
      <!-- Header Section-->
	  <div class="col-md-12" >
		@if(\Request::old('success'))
		<div class="alert alert-success" > {{\Request::old('success')}} </div>
		@elseif(\Request::old('error'))
		<div class="alert alert-danger" > {{\Request::old('error')}} </div>
		@endif
	</div>
      <section class="bg-white">
        <div class="container-fluid">
          <div class="row d-flex align-items-md-stretch">
            <div class="tab-content" id="myTabContent">
			  <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
			  <table class="table align-middle mb-0 bg-white">
			   <thead class="bg-light">
				<tr>
				      <th>Action</th>
    				  <th>Compnay Code</th>
    				  <th>Company Name</th>
    				  <th>Company Contact</th>
    				  <th>Compnay Email</th>
    				  <th>Company GST No.</th>
    				  <th>Compnay PAN No.</th>
    				  <th>Status</th>
				</tr>
			  </thead>
			  <tbody>
				@foreach($companies as $company)
				<tr style='border-radius: 5px 5px 4px 5px;box-shadow: 1px 1px 1px 0px aqua;'>
				    <td>
				        <a href="{{ route('company.edit',(\Crypt::encrypt($company->id))) }}">
				        <svg class="svg-icon svg-icon-sm svg-icon-heavy me-2"><use xlink:href="#survey-1"> </use></svg>
				        </a>
				    </td>
				  <td>
					<div class="d-flex align-items-center">
					  <img src="{{ $company->company_logo }}" alt="" style="width: 45px; height: 45px" class="rounded-circle" />
					
					  <div class="ms-3">
						<p class="fw-bold mb-1">{{ $company->company_code }}</p>
					  </div>
					</div>
				  </td>
				  <td>
					<p class="fw-normal mb-1">{{ $company->name }}</p>
					<!-- <p class="text-muted mb-0">07:18 PM</p> -->
				  </td>
				  <td>
					<p class="fw-normal mb-1">{{ $company->phone }}</p>
				  </td>
				  <td>
					<p class="fw-normal mb-1">{{ $company->email }}</p>
				  </td>
				  <td>
					<p class="fw-normal mb-1">{{ $company->gst_no }}</p>
				  </td>
				  <td>
				  <p class="fw-normal mb-1">{{ $company->pan_no }}</p>
				  </td>
				 
				  <td>
				  
					@if($company->status == '1')
					    <button type="button" class="btn btn-xs btn-primary" onclick="status({{ $company->id}})">Active</button>
					@else
						<button type="button" class="btn btn-xs btn-danger" onclick="status({{ $company->id}})">Banned</button>
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
      function showFilter()
	  {
	    
		$("#filter").toggle();  
	  }
        function status(n) {
         
            var url = "{{ route('company.show', ['company' => ':companyId']) }}".replace(':companyId', n);
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
