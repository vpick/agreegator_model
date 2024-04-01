@extends('common-app/master')
@section('title', 'ERP List')
@section('content')
<style>
.col-lg-2 {
    -webkit-box-flex: 0;
    -ms-flex: 0 0 auto;
    flex: 0 0 auto;
    width: 15% !important;
}
.channel-img{
	width:35% !important;
	min-height: 50px !important;
}
</style>
      
	<header class="py-4">
		<div class="container-fluid py-2">
			<h1 class="h3 fw-normal mb-0">WMS List</h1>
		</div>
	</header>
	  <div class="container-fluid">
        <div class="row align-items-stretch gy-3">
            @foreach($wms as $wm)
               @php
    			$style='';
    			$button = '';
                if($wm->status !='1')
    			{	
    			    $style = 'background: #92929e;;';
    				$button = 'disabled';   
    			}
                @endphp
				
    			<div class="col-lg-2">
                  <!-- Income-->
                  <div class="card text-center h-100 mb-0" style='{{$style}}'>
                    <div class="card-body">
                      <img src="{{ $wm->erp_logo }}" class='channel-img rounded-circle' alt="{{ $wm->erp_name }}">
                      <p class="text-gray-700 display-6" style="font-size:15px;font-weight:400">{{ $wm->erp_name }}</p>
                      @if(Auth::user()->user_type != 'isUser')
                      <p class="text-primary h2 fw-bold">
    				    <div class="btn-group">
    					  <button class="btn btn-primary btn-sm" type="button"  onclick="mapErp({{ $wm->id }})" {{$button}}>Setup Integration</button>
    					</div>
    				  </p>
    				  @endif
    					<!--<p class="text-primary h2 fw-bold">Shopify</p>
                        <p class="text-xs text-gray-600 mb-0">Lorem ipsum dolor sit amet, consectetur adipisicing elit sed do.</p>-->
                    </div>
                  </div>
                </div>   
            @endforeach
		</div>	
	  </div>
	  <header class="py-4">
		<div class="container-fluid py-2">
			<h1 class="h3 fw-normal mb-0">ERP List</h1>
		</div>
	</header>
	<div class="container-fluid">
        <div class="row align-items-stretch gy-3">
            @foreach($erps as $erp)
               	@php
					$style='';
					$button = '';
					if($erp->status !='1')
					{	
						$style = 'background: #92929e;;';
						$button = 'disabled';   
					}
                @endphp				
    			<div class="col-lg-2">
                  <!-- Income-->
                  	<div class="card text-center h-100 mb-0" style='{{$style}}'>
                    	<div class="card-body">
                      		<img src="{{ $erp->erp_logo }}" class='channel-img rounded-circle' alt="{{ $erp->erp_name }}">
                      		<p class="text-gray-700 display-6" style="font-size:15px;font-weight:400">{{ $erp->erp_name }}</p>
                     		@if(Auth::user()->user_type != 'isUser')
								<p class="text-primary h2 fw-bold">
									<div class="btn-group">
									<button class="btn btn-primary btn-sm" type="button"  onclick="mapErp({{ $erp->id }})" {{$button}}>Setup Integration</button>
									</div>
								</p>
    				  		@endif					
                    	</div>
                	</div>
            	</div>   
            @endforeach
		</div>	
	</div>

	
	<!-- Modal-->
   <div class="modal fade text-start" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
	 <div class="modal-dialog">
	  <div class="modal-content" style="padding: 16px!important">
		<div class="modal-header">
		  <h5 class="modal-title" id="myModalLabel">Auth Configuration <br />
		  <!--<div class="form-text" id="emailHelp">Mark checked if you want to use system contract</div>-->
		 		  
		  </h5>
		  <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<form class='row g-3 align-items-center' method="post" action="{{ route('erp.map') }}">
		<div class="modal-body">
		    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
			<div class="row">
				<div class="form-group col-lg-6">
				  <label class="form-label" for="modalInputEmail1">User Id *</label>
				  <input class="form-control" id="user_id" name="user_id" type="text" aria-describedby="emailHelp" required>
				  <div class="form-text" id="emailHelp">Enter your own user id</div>
				  <input class="form-control" id="partner_id" name="partner_id" type="hidden" readonly aria-describedby="emailHelp" required>
				</div>
				<div class="form-group col-lg-6">
				  <label class="form-label" for="modalInputPassword1">Password *</label>
				  <input class="form-control" id="password" type="password" name="password" required>
				</div>
			<div class="form-group col-lg-6">
			  <label class="form-label" for="modalInputEmail1">Auth Key</label>
			  <input class="form-control" id="auth_key" type="text" aria-describedby="emailHelp" name="auth_key">
			  <div class="form-text" id="emailHelp">Enter your own auth key</div>
			</div>
			<div class="form-group col-lg-6">
			  <label class="form-label" for="modalInputPassword1">Secret Key</label>
			  <input class="form-control" id="secret_key" type="text" name="secret_key">
			  <div class="form-text" id="emailHelp">Enter your own secret key</div>
			</div>
			<div class="form-group col-lg-6">
			  <label class="form-label" for="modalInputPassword1">Business Account</label>
			  <input class="form-control" id="business_acc" type="text" name="business_acc">
			  <div class="form-text" id="emailHelp">Enter your own business account</div>
			</div>
			</div>
		</div>
		<div class="modal-footer">
		  <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
		  <button class="btn btn-primary" type="submit">Save changes</button>
		</div>
		</form>
	  </div>
	</div>
    </div>  
	<script>
	function mapErp(partnerId)
	{
	    
	  debugger
	    $("#user_id").val('');
		$("#password").val('');
		$("#auth_key").val('');
		$("#secret_key").val('');
		$("#business_acc").val('');
	    
	    
	    $.ajax({
			url: '/erp-data/' + partnerId,
			method: 'GET',
			dataType: 'json',
			success: function (res) {
			    var  data= res.data;
				if(data)
				{
					$("#user_id").val(data.user_name);
					$("#password").val(data.password);
					$("#auth_key").val(data.auth_key);
					$("#secret_key").val(data.auth_secret);
					$("#business_acc").val(data.business_acc);
				}
				else
				{
					$("#user_id").val('');
					$("#password").val('');
					$("#auth_key").val('');
					$("#secret_key").val('');
					$("#business_acc").val('');
				}
			},
			error: function (xhr, status, error) {
				console.log(xhr.responseText); // Check the error response
			}
		});
		$("#partner_id").val(partnerId);
		$('#myModal').modal('show');
	}
// 	$("#configType").change(function() {
// 		if(this.checked) 
// 		{
// 			var checkbox = this;
// 		    $("#user_id").val('');
//     		$("#password").val('');
//     		$("#auth_key").val('');
//     		$("#secret_key").val('');
//     		$("#business_acc").val('');
// 			partnerId = $("#partner_id").val();
// 			$.ajax({
// 				url: '/mapping-list-comp/' + partnerId,
// 				method: 'GET',
// 				dataType: 'json',
// 				success: function (data) {
// 					if(data[0])
// 					{
// 						$("#user_id").attr('readonly',true);
// 						$("#password").attr('readonly',true);
// 						$("#auth_key").attr('readonly',true);
// 						$("#secret_key").attr('readonly',true);
// 						$("#business_acc").attr('readonly',true);
						
// 						$("#user_id").val(data[0].user_name);
// 						$("#password").val(data[0].password);
// 						$("#auth_key").val(data[0].auth_key);
// 						$("#secret_key").val(data[0].auth_secret);
// 						$("#business_acc").val(data[0].business_acc);
// 					}
// 					else
// 					{
// 						$("#user_id").val('');
// 						$("#password").val('');
// 						$("#auth_key").val('');
// 						$("#secret_key").val('');
// 						$("#business_acc").val('');
// 						Swal.fire({
// 							title: 'Warning!',
// 							text: "Not active for company",
// 							timer: 5000,
// 							icon: 'warning'
// 						});
// 						//$(checkbox).prop('checked', false);
// 						$("#configType").siblings('span').remove();
// 						var sw = new Switchery($("#configType")[0]);
// 						sw.setPosition(true);
// 					}
// 				},
// 				error: function (xhr, status, error) {
// 					console.log(xhr.responseText); // Check the error response
// 				}
// 			});
// 		}
// 		else
// 		{
// 			$("#user_id").attr('readonly',false);
// 			$("#password").attr('readonly',false);
// 			$("#auth_key").attr('readonly',false);
// 			$("#secret_key").attr('readonly',false);
// 			$("#business_acc").attr('readonly',false);
						
// 			$("#user_id").val('');
// 			$("#password").val('');
// 			$("#auth_key").val('');
// 			$("#secret_key").val('');
// 			$("#business_acc").val('');
			
			
// 			$("#user_id").val('');
//     		$("#password").val('');
//     		$("#auth_key").val('');
//     		$("#secret_key").val('');
//     		$("#business_acc").val('');
// 			partnerId = $("#partner_id").val();
// 			$.ajax({
// 				url: '/mapping-list-clinet/' + partnerId,
// 				method: 'GET',
// 				dataType: 'json',
// 				success: function (data) {
// 					if(data[0])
// 					{
// 						$("#user_id").attr('readonly',true);
// 						$("#password").attr('readonly',true);
// 						$("#auth_key").attr('readonly',true);
// 						$("#secret_key").attr('readonly',true);
// 						$("#business_acc").attr('readonly',true);
						
// 						$("#user_id").val(data[0].user_name);
// 						$("#password").val(data[0].password);
// 						$("#auth_key").val(data[0].auth_key);
// 						$("#secret_key").val(data[0].auth_secret);
// 						$("#business_acc").val(data[0].business_acc);
// 					}
// 					else
// 					{
// 						$("#user_id").val('');
// 						$("#password").val('');
// 						$("#auth_key").val('');
// 						$("#secret_key").val('');
// 						$("#business_acc").val('');
// 						Swal.fire({
// 							title: 'Warning!',
// 							text: "Not active for client",
// 							timer: 5000,
// 							icon: 'warning'
// 						});
// 					}
// 				},
// 				error: function (xhr, status, error) {
// 					console.log(xhr.responseText); // Check the error response
// 				}
// 			});
// 		}
// 	});
</script>
	
@endsection