@extends('admin-app/admin-master')
@section('title', 'Client List')
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
			<!--<form action="{{ route('app-clients.import') }}" method="POST" enctype="multipart/form-data">-->
   <!--             @csrf-->
   <!--             <input type="file" name="file" class="form-control" style="display:none">-->
   <!--             <button class="btn btn-outline-dark btn-sm" data-toggle="modal" data-target=".import_orders_modal"> <i class="mdi mdi-arrow-up-bold-circle"></i> Import</button>              -->
				
   <!--         </form>-->
                 
			<a href="#" class="btn btn-outline-dark btn-sm"><i class="mdi mdi-arrow-down-bold-circle"></i> Export</a>
			<button type="button" class="btn btn-sm btn-outline-dark refresh_orders_button"><i class="mdi mdi-refresh"></i> Refresh</button>		
			<button class="btn btn-outline-dark btn-sm" data-toggle="modal" data-target=".import_orders_modal"> <i class="mdi mdi-arrow-up-bold-circle"></i> Import</button>
			<a href="{{ route('app-client.create') }}" class="btn btn-outline-dark btn-sm ">Create Client</a>
			<button type="button" class="btn btn-outline-dark show_hide_filter btn-sm" onclick="showFilter()"><i class="icon-placeholder mdi mdi-filter"></i> Filters</button>
			<button type="button" class="btn btn-outline-dark show_hide_filter btn-sm" style="display:none;"><i class="icon-placeholder mdi mdi-close"></i> Close</button>
		</div>		
		
		<div class="col-lg-12" id='filter' style='display:none'>                           
            <div class="card">
                <div class="card-header border-bottom">
                  	<h3 class="h4 mb-0">Filters</h3>
                </div>
                <div class="card-body">
                    <form class="row g-3 align-items-center" method="GET" action="{{ route('app-client.index') }}">	
                        <div class="row" style="margin-bottom: 1rem !important;">
        					<div class="col-lg-3">
        						<label for="client_code">Client Code:</label>
        						<input type="text" class="form-control" id="client_code" name="client_code" placeholder="Client code" value="{{ request()->input('client_code') ?? ''}}">	
        					</div>
        					<div class="col-lg-3">
        						<label for="client_name">Client Name:</label>
        						<input type="text" class="form-control" id="client_name" name="client_name" placeholder="Client Name" value="{{ request()->input('client_name') ?? ''}}">
        						
        					</div>
        					<div class="col-lg-3">
        						<label for="company">Company:</label>
        						<select id="company" name="company" class="form-control">
        							<option value="">Select company</option>
        							@foreach($companies as $company)
        								<option value="{{ $company->id }}" {{ request()->input('company') == $company->id ? 'selected' : ''}} >{{ $company->name }}</option>
        							@endforeach
        						</select>	
        					</div>						
        					<div class="col-sm-3">
        						<button class="btn btn-primary" type="submit" style="margin-top: 24px;">Apply</button>
        						<a href="{{ route('app-client.index') }}" class="btn btn-secondary" style="margin-top: 24px;">Clear</a>
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
     <section class="bg-white p-3">
        <div class="container-fluid">
          <div class="row d-flex align-items-md-stretch">
            <div class="tab-content" id="myTabContent">
			  <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
			  <table class="table align-middle mb-0 bg-white display nowrap"  style="font-size: 14px" style="width:100%">
			   <thead class="bg-light">
				<tr>
				  <th>Code</th>
				  <th>Name</th>
                  <th>Company</th>	
				  <th>Address</th>
				  <th>Phone</th>
				  <th>Email</th>
				  <th>Status</th>
				</tr>
				<tbody>
				@foreach($clients as $client)
				<tr style='border-radius: 5px 5px 4px 5px;box-shadow: 1px 1px 1px 0px aqua;'>
				  <td>
					<div class="d-flex align-items-center">
					  <!--<img src="https://mdbootstrap.com/img/new/avatars/8.jpg" alt="" style="width: 45px; height: 45px" class="rounded-circle" />-->
					  <a title='View Client' href="{{ route('app-client.edit',(\Crypt::encrypt($client->id))) }}" class="">
					      <svg class="svg-icon svg-icon-sm svg-icon-heavy me-2"><use xlink:href="#survey-1"> </use></svg>
					   </a>   
					  <div class="ms-3">
						<p class="fw-bold mb-1">{{ $client->client_code }}</p>
					  </div>
					</div>
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
					<p class="fw-normal mb-1">{{ $client->billing_address }}</p>
				  </td>
				  
				  <td>
					<p class="fw-normal mb-1">{{ $client->phone }}</p>
				  </td>
				  <td>
					<p class="fw-normal mb-1">{{ $client->email }}</p>
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
			  </thead>
			  </table>
			  {{ $clients->links() }}
			  </div>
			</div>
			</div>
        </div>
    </section>

<script>
        function status(n) {
            var url = "{{ route('app-client.show', ':clientId') }}".replace(':clientId', n);
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
<script>
	$(document).ready(function() {	
		$.ajaxSetup({
			headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});		
		$('#client-table').DataTable({				
			processing: true,
			serverSide: true,
			search: {
				return: true
			},
			scrollX: true,
			ajax: {
				url: "{{ route('app-clients.data') }}",
				type: 'GET',
				data: function (d) {
					d.client_id = $('#client_id').val();
					//d.end_date = $('#end_date').val();
				}
			},
			columns: [
				{ data: 'client_code'},
				{ data: 'name'},
				{ data: 'company.name'},
				{ data: 'billing_address'},
				{ data: 'address2'},
				{ data: 'email'},
				{ data: 'phone'},
				{ data: 'city'},
				{ data: 'country'},
				{ data: 'state.state_name'},
				{ data: 'pincode'},
				{ data: 'user.username'},
				{ data: 'user.username'},
				{ 
				data: 'status', 
					render: function(data) {
						if (data === '1') {
							return 'Active';
						} else {
							return 'Inactive';
						}
					}
				},
				{ 
				data: 'created_at', 
			
				render: function(data) {
					// Format the date using a library like Moment.js or a custom function
					// Here's an example using Moment.js:
					return moment(data).format('YYYY-MM-DD');
					}
				},
				{ 
				data: 'updated_at', 				
				render: function(data) {
					// Format the date using a library like Moment.js or a custom function
					// Here's an example using Moment.js:
					return moment(data).format('YYYY-MM-DD');
					}
				},					
				{ data: 'action' }
			]
		});
		$('#searchFilter').click(function(){
			console.log('hello');
			$('#client-table').DataTable().draw(true);
		});		
	});
</script>
@endsection