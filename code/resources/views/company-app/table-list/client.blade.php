@extends('common-app/master')
@section('title', 'Client List')
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
        		<a href="#" class="btn btn-outline-dark btn-sm"><i class="mdi mdi-arrow-down-bold-circle"></i> Export</a>
        		<button type="button" class="btn btn-sm btn-outline-dark refresh_orders_button"><i class="mdi mdi-refresh"></i> Refresh</button>
        		<button class="btn btn-outline-dark btn-sm" data-toggle="modal" data-target=".import_orders_modal"> <i class="mdi mdi-arrow-up-bold-circle"></i> Import</button>
        		<a href="{{ route('admin.client.create') }}" class="btn btn-outline-dark btn-sm ">Create Client</a>
        		<button type="button" class="btn btn-outline-dark show_hide_filter btn-sm" onclick="showFilter()"><i class="icon-placeholder mdi mdi-filter"></i> Filters</button>
        		<button type="button" class="btn btn-outline-dark show_hide_filter btn-sm" style="display:none;"><i class="icon-placeholder mdi mdi-close"></i> Close</button>
        	</div>
        	
        	<div class="col-lg-12" id='filter'>                           
                <div class="card">
                    <div class="card-header border-bottom">
                      	<h3 class="h4 mb-0">Filters</h3>
                    </div>
                    <div class="card-body">
        			    <form class="row g-3 align-items-center" method="GET" action="{{ route('admin.client.index') }}">	
                            <div class="row" style="margin-bottom: 1rem !important;">
            					<div class="col-lg-3">
            						<label class="p-b-10" for="order_id">Client Code:</label>
            						<div class="input-group">
            							<input type="text" class="form-control" name="client_code" placeholder="Client Code" value="{{ request()->input('client_code')}}">
            						</div>
            					</div>
            				
            					<div class="col-lg-3">
            						<button class="btn btn-primary" type="submit" style="margin-top: 24px;">Apply</button>
            						<a href="/client" class="btn btn-secondary" style="margin-top: 24px;">Clear</a>
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
    				  <th>Code</th>
    				  <th>Name</th>
                      <th>Company</th>
    				  <th>Contact</th>
    				  <th>Email</th>
    				  <th>City</th>
    				  <th>Pincode</th>
    				  <th>Status</th>
				</tr>
			  </thead>
			  <tbody>
              @foreach($clients as $client)
				<tr style='border-radius: 5px 5px 4px 5px;box-shadow: 1px 1px 1px 0px aqua;'>
				    <td>
    				   <a href="{{ route('admin.client.edit',(\Crypt::encrypt($client->id))) }}"> 
    				    <svg class="svg-icon svg-icon-sm svg-icon-heavy me-2"><use xlink:href="#survey-1"> </use></svg>
    				   </a>
				  </td>
				  <td>
					<p class="fw-bold mb-1">{{ $client->client_code }}</p>
				  </td>
                  <td>
					<p class="fw-normal mb-1">{{ $client->name }}</p>
					<!-- <p class="text-muted mb-0">07:18 PM</p> -->
				  </td>
				  <td>
					<p class="fw-normal mb-1">{{ $client->company->name }}</p>
					<!-- <p class="text-muted mb-0">07:18 PM</p> -->
				  </td>
				  <td>
					<p class="fw-normal mb-1">{{ $client->phone }}</p>
				  </td>
				  <td>
					<p class="fw-normal mb-1">{{ $client->email }}</p>
				  </td>
				  <td>
					<p class="fw-normal mb-1">{{ $client->city }}</p>
				  </td>
				  <td>
				  <p class="fw-normal mb-1">{{ $client->pincode }}</p>
				  </td>
                  <td>
				  	
				
					@if($client->status == '1')
					    <button type="button" class="btn btn-xs btn-primary" onclick="status({{ $client->id}})">Active</button>
					@else
						<button type="button" class="btn btn-xs btn-danger" onclick="status({{ $client->id}})">Banned</button>
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
            var url = "{{ route('admin.client.show', ['client' => ':clientId']) }}".replace(':clientId', n);
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