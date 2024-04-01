@extends('common-app/master')
@section('title', 'Logistics')
@section('content')
<style>
.col-lg-3 {
    -webkit-box-flex: 0;
    -ms-flex: 0 0 auto;
    flex: 0 0 auto;
    width: 20% !important;
}
.channel-img{
	width:50% !important;
	min-height: 98px !important;
}
</style>

    <section class="bg-white">
	  	<hr />
	 
	  	<div class="container-fluid">
        	<div class="row align-items-stretch gy-3">
				@foreach($logistics as $logistic)
				@php
				$style='';
				$button = '';
				if($logistic->logistics_status !='Active')
				{	
					$style = 'background: #f7f4f4;';
					$button = 'disabled';   
				}
				@endphp
				<div class="col-lg-3">
              		<!-- Income-->
              		<div class="card text-center h-100 mb-0" style='{{$style}}'>
                		<div class="card-body">
						<img src='{{ $logistic->logistics_logo }}' class='channel-img rounded-circle' alt='{{ $logistic->logistics_name }}'>
						<p class="text-gray-700 display-6">{{ $logistic->logistics_name }}</p>
						<p class="text-primary h2 fw-bold">
				    	<div class="btn-group">
							@if(!empty($userP) && $userP->write != '1')
								<button type="button" onclick="checkPermission()" class="btn btn-primary" {{$button}}>Setup Integration</button>
							@else
								<button class="btn btn-primary" type="button" onclick="mapPartner({{ $logistic->id }})" {{$button}}>Setup Integration</button>
							@endif
						</div>
				  	</p>	
                </div>
              </div>
            </div>
			
		    @endforeach
		</div>	
	  </div>
    </section>
	<!-- Modal-->
	<div class="modal fade text-start" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content" style="width: 875px!important;margin-left: -120px!important;">
				<div class="modal-header">
				    
					<h5 class="modal-title" id="myModalLabel">Auth Configuration <span style="float:right;padding-left: 510px;"><a class="btn btn-info updateBtn" id="updateBtn">Add Field</a></span><br />
						<div class="form-text" id="emailHelp">Mark checked if you want to use system contract</div>
						
						@php  
							if($user->user_type !='isCompany' )
							{
								echo '<input type="checkbox" data-id="" id="configType" name="configType" class="js-switch">';
							}
						@endphp		  
					</h5>
					
				</div>
			    <form class='row g-3 align-items-center' method="post" action="{{url('map-partner')}}">
				    <div class="modal-body">
					    <input type="hidden" id="status" name="status" value="">
					    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
					    <div class="row">
    						<div class="form-group col-lg-4">
    							<label class="form-label" for="modalInputEmail1">User Id *</label>
    							<input class="form-control" id="user_id" name="user_id" type="text" aria-describedby="emailHelp" required>
    							<div class="form-text" id="emailHelp">Enter your own user id</div>
    							<input class="form-control" id="partner_id" name="partner_id" type="hidden" readonly aria-describedby="emailHelp" required>
    						</div>
    						<div class="form-group col-lg-4">
    							<label class="form-label" for="modalInputPassword1">Password *</label>
    							<input class="form-control" id="password" type="password" name="password" required>
    						</div>
    						<div class="form-group col-lg-4">
    							<label class="form-label" for="modalInputEmail1">Auth Key</label>
    							<input class="form-control" id="auth_key" type="text" aria-describedby="emailHelp" name="auth_key">
    							<div class="form-text" id="emailHelp">Enter your own auth key</div>
    						</div>
    						<div class="form-group col-lg-4">
    							<label class="form-label" for="modalInputPassword1">Secret Key</label>
    							<input class="form-control" id="secret_key" type="text" name="secret_key">
    							<div class="form-text" id="emailHelp">Enter your own secret key</div>
    						</div>
    						<div class="form-group col-lg-4">
    							<label class="form-label" for="modalInputPassword1">Business Account</label>
    							<input class="form-control" id="business_acc" type="text" name="business_acc">
    							<div class="form-text" id="emailHelp">Enter your own business account</div>
    						</div>
    						
					    </div>
					    <div class="row" id="field_mapping">
    					</div>
				    </div>
				    <div class="modal-footer">		
				        
					    <button class="btn btn-primary" type="submit">Save changes</button>
					    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
				    </div>
				</form>
				
				
			</div>
		</div>
	</div>  
	<script>
    	function mapPartner(partnerId)
    	{
    	    $("#user_id").val('');
    		$("#password").val('');
    		$("#auth_key").val('');
    		$("#secret_key").val('');
    		$("#business_acc").val('');
    	    $.ajax({
    			url: '/mapping-list/' + partnerId,
    			method: 'GET',
    			dataType: 'json',
    			success: function (data) {
    			   
    				if(data.mapping.length>0 &&  data[0])
    				{	
    				    $('#status').val(data[0].base_of);
    					$("#user_id").val(data[0].user_name);
    					$("#password").val(data[0].password);
    					$("#auth_key").val(data[0].auth_key);
    					$("#secret_key").val(data[0].auth_secret);
    					$("#business_acc").val(data[0].business_acc);
    					var fieldata = data[0].add_fields;
    					$('#field_mapping').empty();    
    					if (fieldata && fieldata.length > 0) 
    					{
    						var fieldArr = fieldata.split(',');
    						var content = '';
    						$.each(fieldArr, function(index, val) {
    							var field = val.trim().toUpperCase();   
    							var fieldName = field.split('_');
    							var fieldValue=  val.trim()     
    							
    							content += `<div class="form-group col-lg-4">
    											<label class="form-label" for="${fieldName[0]} ${fieldName[1] ?? ''}">${fieldName[0]} ${fieldName[1] ?? ''}*</label>
    											<input class="form-control" id="${fieldValue}" type="text" name="${fieldValue}" value="${data[0][fieldValue]}" required>
    											<div class="form-text">Enter your ${fieldName[0]} ${fieldName[1] ?? ''}</div>
    										</div>`;
    						});
    						
    						$('#field_mapping').append(content);
    					} 
    					else 
    					{
    						// Handle the case when there is no data in data[0].add_fields
    						$('#field_mapping').empty();
    					}
    					
    					
    					
    				}
    				else
    				{
    				    $('#field_mapping').empty();
    					$("#user_id").val('');
    					$("#password").val('');
    					$("#auth_key").val('');
    					$("#secret_key").val('');
    					$("#business_acc").val('');
    					
    					if (data.columns && data.columns.length > 0) 
    					{
    						var fieldArr = data.columns;
    						var content = '';
    						$.each(fieldArr, function(index, val) {
    							var field = val.trim().toUpperCase();   
    							var fieldName = field.split('_');
    							var fieldValue=  val.trim()     
    							
    							content += `<div class="form-group col-lg-4">
    											<label class="form-label" for="${fieldName[0]} ${fieldName[1] ?? ''}">${fieldName[0]} ${fieldName[1] ?? ''}*</label>
    											<input class="form-control" id="${fieldValue}" type="text" name="${fieldValue}" value="" required>
    											<div class="form-text">Enter your ${fieldName[0]} ${fieldName[1] ?? ''}</div>
    										</div>`;
    						});
    						
    						$('#field_mapping').append(content);
    					} 
    					else 
    					{
    						// Handle the case when there is no data in data[0].add_fields
    						$('#field_mapping').empty();
    					}
    				}
    			},
    			error: function (xhr, status, error) {
    				console.log(xhr.responseText); // Check the error response
    			}
    		});
    		$("#partner_id").val(partnerId);
    		$('#myModal').modal('show');
    	}
    	$("#configType").change(function() {
    		if(this.checked) 
    		{
    			var checkbox = this;
    		    $("#user_id").val('');
        		$("#password").val('');
        		$("#auth_key").val('');
        		$("#secret_key").val('');
        		$("#business_acc").val('');
    			partnerId = $("#partner_id").val();
    			$.ajax({
    				url: '/mapping-list-comp/' + partnerId,
    				method: 'GET',
    				dataType: 'json',
    				success: function (data) {
    				    console.log(data[0]);
    					if(data[0])
    					{
    						$("#user_id").attr('readonly',true);
    						$("#password").attr('readonly',true);
    						$("#auth_key").attr('readonly',true);
    						$("#secret_key").attr('readonly',true);
    						$("#business_acc").attr('readonly',true);
    						
    						$("#status").val(data[0].base_of);
    						$("#user_id").val(data[0].user_name);
    						$("#password").val(data[0].password);
    						$("#auth_key").val(data[0].auth_key);
    						$("#secret_key").val(data[0].auth_secret);
    						$("#business_acc").val(data[0].business_acc);
    						var fieldata = data[0].add_fields;
    						console.log(fieldata);
    						$('#field_mapping').empty();    
    						if (fieldata && fieldata.length > 0) 
    						{
    							var fieldArr = fieldata.split(',');
    							var content = '';
    							$.each(fieldArr, function(index, val) {
    								var field = val.trim().toUpperCase();   
    								var fieldName = field.split('_');
    								var fieldValue=  val.trim()     
    								console.log('hello',data[0][fieldValue]);    
    								content += `<div class="form-group col-lg-4">
    												<label class="form-label" for="${fieldName[0]} ${fieldName[1] ?? ''}">${fieldName[0]} ${fieldName[1] ?? ''}*</label>
    												<input class="form-control" id="${fieldValue}" type="text" name="${fieldValue}" value="${data[0][fieldValue]}" readonly>
    												<div class="form-text">Enter your ${fieldName[0]} ${fieldName[1] ?? ''}</div>
    											</div>`;
    							});
    							$('#field_mapping').append(content);
    						} 
    						else 
    						{
    							// Handle the case when there is no data in data[0].add_fields
    							$('#field_mapping').empty();
    						}
    					}
    					else
    					{ 
    					    $("#status").val('isCompany');
    						$("#user_id").val('');
    						$("#password").val('');
    						$("#auth_key").val('');
    						$("#secret_key").val('');
    						$("#business_acc").val('');
    						var fieldata = data[0].add_fields;
    						$('#field_mapping').empty();    
    						if (fieldata && fieldata.length > 0) 
    						{
    							var fieldArr = fieldata.split(',');
    							var content = '';
    							$.each(fieldArr, function(index, val) {
    								var field = val.trim().toUpperCase();   
    								var fieldName = field.split('_');
    								var fieldValue=  val.trim()     
    								console.log(data[0][fieldValue]);    
    								content += `<div class="form-group col-lg-4">
    												<label class="form-label" for="${fieldName[0]} ${fieldName[1] ?? ''}">${fieldName[0]} ${fieldName[1] ?? ''}*</label>
    												<input class="form-control" id="${fieldValue}" type="text" name="${fieldValue}" value="" required>
    												<div class="form-text">Enter your ${fieldName[0]} ${fieldName[1] ?? ''}</div>
    											</div>`;
    							});
    							
    							$('#field_mapping').append(content);
    						} 
    						else 
    						{
    							// Handle the case when there is no data in data[0].add_fields
    							$('#field_mapping').empty();
    						}
    						Swal.fire({
    							title: 'Warning!',
    							text: "Not active for company",
    							timer: 5000,
    							icon: 'warning'
    						});
    						//$(checkbox).prop('checked', false);
    						$("#configType").siblings('span').remove();
    						var sw = new Switchery($("#configType")[0]);
    						sw.setPosition(true);
    					}
    				},
    				error: function (xhr, status, error) {
    					console.log(xhr.responseText); // Check the error response
    				}
    			});
    		}
    		else
    		{
    			$("#user_id").attr('readonly',false);
    			$("#password").attr('readonly',false);
    			$("#auth_key").attr('readonly',false);
    			$("#secret_key").attr('readonly',false);
    			$("#business_acc").attr('readonly',false);
    						
    			$("#user_id").val('');
    			$("#password").val('');
    			$("#auth_key").val('');
    			$("#secret_key").val('');
    			$("#business_acc").val('');
    			
    			
    			$("#user_id").val('');
        		$("#password").val('');
        		$("#auth_key").val('');
        		$("#secret_key").val('');
        		$("#business_acc").val('');
    			partnerId = $("#partner_id").val();
    			$.ajax({
    				url: '/mapping-list-clinet/' + partnerId,
    				method: 'GET',
    				dataType: 'json',
    				success: function (data) {
    					if(data[0])
    					{
    						$("#user_id").attr('readonly',false);
    						$("#password").attr('readonly',false);
    						$("#auth_key").attr('readonly',false);
    						$("#secret_key").attr('readonly',false);
    						$("#business_acc").attr('readonly',false);
    						$("#status").val(data[0].base_of);
    						$("#user_id").val(data[0].user_name);
    						$("#password").val(data[0].password);
    						$("#auth_key").val(data[0].auth_key);
    						$("#secret_key").val(data[0].auth_secret);
    						$("#business_acc").val(data[0].business_acc);
    						var fieldata = data[0].add_fields;
    						$('#field_mapping').empty();    
    						if (fieldata && fieldata.length > 0) 
    						{
    							var fieldArr = fieldata.split(',');
    							
    							var content = '';
    
    							$.each(fieldArr, function(index, val) {
    								var field = val.trim().toUpperCase();   
    								var fieldName = field.split('_');
    								var fieldValue=  val.trim()     
    								console.log(data[0][fieldValue]);    
    								content += `<div class="form-group col-lg-4">
    												<label class="form-label" for="${fieldName[0]} ${fieldName[1] ?? ''}">${fieldName[0]} ${fieldName[1] ?? ''}*</label>
    												<input class="form-control" id="${fieldValue}" type="text" name="${fieldValue}" value="${data[0][fieldValue]}" required>
    												<div class="form-text">Enter your ${fieldName[0]} ${fieldName[1] ?? ''}</div>
    											</div>`;
    							});
    							
    							$('#field_mapping').append(content);
    						} 
    						else 
    						{
    							// Handle the case when there is no data in data[0].add_fields
    							$('#field_mapping').empty();
    						}
    					}
    					else
    					{
    					    $("#status").val('isClient');
    						$("#user_id").val('');
    						$("#password").val('');
    						$("#auth_key").val('');
    						$("#secret_key").val('');
    						$("#business_acc").val('');
    						var fieldata = data[0].add_fields;
    						$('#field_mapping').empty();    
    						if (fieldata && fieldata.length > 0) 
    						{
    							var fieldArr = fieldata.split(',');
    							
    							var content = '';
    
    							$.each(fieldArr, function(index, val) {
    								var field = val.trim().toUpperCase();   
    								var fieldName = field.split('_');
    								var fieldValue=  val.trim()     
    								console.log(data[0][fieldValue]);    
    								content += `<div class="form-group col-lg-6">
    												<label class="form-label" for="${fieldName[0]} ${fieldName[1] ?? ''}">${fieldName[0]} ${fieldName[1] ?? ''}*</label>
    												<input class="form-control" id="${fieldValue}" type="text" name="${fieldValue}" value="" required>
    												<div class="form-text">Enter your ${fieldName[0]} ${fieldName[1] ?? ''}</div>
    											</div>`;
    							});
    							
    							$('#field_mapping').append(content);
    						} 
    						else 
    						{
    							// Handle the case when there is no data in data[0].add_fields
    							$('#field_mapping').empty();
    						}
    						Swal.fire({
    							title: 'Warning!',
    							text: "Not active for client",
    							timer: 5000,
    							icon: 'warning'
    						});
    					}
    				},
    				error: function (xhr, status, error) {
    					console.log(xhr.responseText); // Check the error response
    				}
    			});
    		}
    	});
    	$(".updateBtn").click(function() 
    	{
    		var parent = $(this).attr('id');
    		var partnerId = $("#partner_id").val();
    		if(partnerId !='')
    		{
    			location.href = 'add-field/' + partnerId;
    		}
    		else
    		{
    			Swal.fire({
    				title: 'Warning!',
    				text: "no partner selected",
    				timer: 5000,
    				icon: 'warning'
    			});
    		}
    	});
    </script>
@endsection