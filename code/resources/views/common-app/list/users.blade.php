@extends('common-app/master')
@section('title', 'Users List')
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
    <section class="py-filter">
    	<div class="col-md-12 text-end">
    		@php
                $currentUrl = url()->current(); // Get the current URL
                $exportUrl = url('export-users') . '?' . http_build_query(request()->all());
            @endphp
            @if(!empty($userP) && $userP->download != '1')
    		     <a href="#" onclick="checkPermission()" class="btn btn-outline-dark btn-sm"><i class="mdi mdi-arrow-down-bold-circle"></i> Export</a>
    		@else
    		   <a  href="{{ $exportUrl }}" class="btn btn-outline-dark btn-sm"><i class="mdi mdi-arrow-down-bold-circle"></i> Export</a>
    		@endif
    		<button type="button" class="btn btn-sm btn-outline-dark refresh_orders_button"><i class="mdi mdi-refresh"></i> Refresh</button>
    		 @if(!empty($userP) && $userP->write != '1')
        		 <a href="#" onclick="checkPermission()" class="btn btn-outline-dark btn-sm ">Create user</a>
    		 @else
        		<a href="{{ route('user.create') }}" class="btn btn-outline-dark btn-sm ">Create user</a>
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
    			    <form class="row g-3 align-items-center" method="GET" action="{{ route('user.index') }}">	
                        <div class="row" style="margin-bottom: 1rem !important;">
        					<div class="col-lg">
        						<label class="p-b-10" for="order_id">User Code:</label>
        						<div class="input-group">
        							<input type="text" class="form-control" name="user_code" placeholder="User Code" value="{{ request()->input('user_code')}}">
        						</div>
        					</div>
        					<div class="col-lg">
        						<label class="p-b-10" for="partner">User Type: </label>
        						<select class="form-control" id="user_type" name="user_type">    
                                    <option value="">Select User type</option>
                                    <option value="isClient" {{ request()->input('user_type') == 'isClient' ? 'selected' : ''}}>Is Client</option> 
                                    <option value="isUser" {{ request()->input('user_type') == 'isUser' ? 'selected' : ''}}>Is User</option>  
                              </select>
        					</div>
        					<div class="col-lg">
        						<label class="p-b-10" for="courier_name">Status: </label>
        						<select class="form-control" id="status" name="status">    
                                    <option value="">Select status</option>
                                    <option value="1" {{ request()->input('status') == '1' ? 'selected' : ''}}>Active</option> 
                                    <option value="0" {{ request()->input('status') == '0' ? 'selected' : ''}}>Inactive</option>  
                              </select>
        					</div>
        					<div class="col-lg">
        						<button class="btn btn-primary" type="submit" style="margin-top: 24px;">Apply</button>
        						<a href="/user" class="btn btn-secondary" style="margin-top: 24px;">Clear</a>
        					</div>	
        				</div>
                  	</form>
                </div>
            </div>
        </div>
    	<hr />
    </section> 
	<div class="col-md-12" >
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
			  <div class="tab-pane fade show active " id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
				<div class="table-responsive">
					<table class="table align-middle mb-0 bg-white">
					<thead class="bg-light">
						<tr>
						<th colspan="4" width="10%">Action</th>
							<th>User Code</th>
							<th>User Role</th>
							<th>User Type</th>
							<th>User Name</th>
							<th>User Contact</th>
							<th>User Email</th>
							<th>Company Name</th>
							<th>Default Client</th>
							<th>Default Warehouse</th>
							<th>Status</th>
							
						</tr>
					</thead>
					<tbody>
						@foreach($users as $user)
						<tr style='border-radius: 5px 5px 4px 5px;box-shadow: 1px 1px 1px 0px aqua;'>
							<td colspan="4" >
							    @if(!empty($userP) && $userP->update != '1')
        				            <a href="#" onclick="checkPermission()"><svg class="svg-icon svg-icon-sm svg-icon-heavy me-2"><use xlink:href="#survey-1"> </use></svg></a>
        				            <a href="#" onclick="checkPermission()" class="btn btn-sm btn-outline-primary img-pos"><img src="{{ url('icon/permission.png') }}" alt="permission" class="img-icon" title="Permission"></a>				
    							    <a href="#" onclick="checkPermission()" class="btn btn-sm btn-outline-primary img-pos" style="margin-left: 33px"><img src="{{ url('icon/mapping.png') }}" alt="mapping" class="img-icon" title="Mapping"></a>
        				        @else
        							<a title='View User' class="btn btn-sm btn-outline-primary" style="padding-left: 7px;padding-right: 0px;" href="{{ route('user.edit', \Crypt::encrypt($user->id)) }}">
        								<svg class="svg-icon svg-icon-sm svg-icon-heavy me-2 img-icon"><use xlink:href="#survey-1"> </use></svg>
        							</a>														
        							<a href="{{ url('/permission',(\Crypt::encrypt($user->id))) }}" class="btn btn-sm btn-outline-primary img-pos"><img src="{{ url('icon/permission.png') }}" alt="permission" class="img-icon" title="Permission"></a>				
        							<a href="{{ url('/mapping',(\Crypt::encrypt($user->id))) }}" class="btn btn-sm btn-outline-primary img-pos" style="margin-left: 33px"><img src="{{ url('icon/mapping.png') }}" alt="mapping" class="img-icon" title="Mapping"></a>								
							    @endif
							</td>
							<td><p class="fw-normal mb-1">{{ $user->user_code }}</p>
							</td>		
							<td><p class="fw-normal mb-1">{{ $user->role->role }}</p>
							</td>
							<td><p class="fw-normal mb-1">{{ $user->user_type }}</p>
							</td>
							<td><p class="fw-normal mb-1">{{ $user->username }}</p>
							</td>
							<td><p class="fw-normal mb-1">{{ $user->mobile }}</p>
							</td>
							<td><p class="fw-normal mb-1">{{ $user->email }}</p>
							</td>
							<td><p class="fw-normal mb-1">{{ $user->company->name }}</p>
							</td>
							<td><p class="fw-normal mb-1">{{ $user->client->client_code ?? ''}}</p>
							</td>
							<td><p class="fw-normal mb-1">{{ $user->warehouse->warehouse_code ?? '' }}</p>
							</td>						
							<td>
								@if(!empty($userP) && $userP->update != '1')
								    @if($user->status == '1')
                					    <button type="button" onclick="checkPermission()" class="btn btn-sm btn-primary">Active</button>
                					@else
                						<button type="button" onclick="checkPermission()" class="btn btn-sm btn-danger">Banned</button>
                					@endif
								@else
    								@if($user->status == '1')
                					    <button type="button" class="btn btn-sm btn-primary" onclick="status({{ $user->id}})">Active</button>
                					@else
                						<button type="button" class="btn btn-sm btn-danger" onclick="status({{ $user->id}})">Banned</button>
                					@endif
                				@endif
							</td>
										
						</tr>
						@endforeach
					</tbody>
				</div>
			  </table>
			  </div>
			</div>
			</div>
        </div>
        </div>
      </section>
    <script>
        function status(n) {
            var url = "{{ route('user.show', ['user' => ':userId']) }}".replace(':userId', n);
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