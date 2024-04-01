@extends('common-app/master')
@section('title', 'Webhook List')
@section('content')
<!-- Counts Section -->

      <section class="py-filter">
        	<div class="col-md-12 text-end">
        		<button type="button" class="btn btn-sm btn-outline-dark refresh_orders_button"><i class="mdi mdi-refresh"></i> Refresh</button>
        		@if(!empty($userP) && $userP->write != '1')
			        <a href="#" onclick="checkPermission()" class="btn btn-outline-dark btn-sm">Create webhook</a>
			    @else
        		    <a href="{{ route('webhook.create') }}" class="btn btn-outline-dark btn-sm ">Create webhook</a>
        		@endif
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
    				  <th>Webhook Name</th>
    				  <th>Webhook Url</th>
                      <th>Webhook Secret</th>
    				  <th>status</th>
				</tr>
			  </thead>
			  <tbody>
              @foreach($webhooks as $webhook)
				<tr style='border-radius: 5px 5px 4px 5px;box-shadow: 1px 1px 1px 0px aqua;'>
				    <td>
				        @if(!empty($userP) && $userP->update != '1')
    			        <a href="#" onclick="checkPermission()">
    			            <svg class="svg-icon svg-icon-sm svg-icon-heavy me-2"><use xlink:href="#survey-1"> </use></svg>
    			        </a>
			             @else
    				   <a href="{{ route('webhook.edit',(\Crypt::encrypt($webhook->id))) }}"> 
    				    <svg class="svg-icon svg-icon-sm svg-icon-heavy me-2"><use xlink:href="#survey-1"> </use></svg>
    				   </a>
    				   @endif
				  </td>
				  <td>
					<p class="fw-bold mb-1">{{ $webhook->webhook_name }}</p>
				  </td>
                  <td>
					<p class="fw-normal mb-1">{{ $webhook->webhook_url }}</p>
					<!-- <p class="text-muted mb-0">07:18 PM</p> -->
				  </td>
				  <td>
					<p class="fw-normal mb-1">{{ $webhook->webhook_secret }}</p>
					<!-- <p class="text-muted mb-0">07:18 PM</p> -->
				  </td>
				  
				 <td>
				     @if(!empty($userP) && $userP->update != '1')
    					@if($webhook->webhook_status == 'Active')
    					    <button type="button" class="btn btn-xs btn-primary" onclick="checkPermission()">Active</button>
    					@elseif($webhook->webhook_status == 'Paused')
    						<button type="button" class="btn btn-xs btn-warning" onclick="checkPermission()">Paused</button>
                        @else
                            <button type="button" class="btn btn-xs btn-danger" onclick="checkPermission()">Disable</button>
    					@endif
    				@else
    				    @if($webhook->webhook_status == 'Active')
    					    <button type="button" class="btn btn-xs btn-primary" onclick="status({{ $webhook->id}})">Active</button>
    					@elseif($webhook->webhook_status == 'Paused')
    						<button type="button" class="btn btn-xs btn-warning" onclick="status({{ $webhook->id}})">Paused</button>
                        @else
                            <button type="button" class="btn btn-xs btn-danger" onclick="status({{ $webhook->id}})">Disable</button>
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