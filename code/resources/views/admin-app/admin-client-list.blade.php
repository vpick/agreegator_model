@extends('admin-app/admin-master')
@section('title', 'Client List')
@section('content')
<!-- Statistics Section-->
<header class="py-4">
  <div class="container-fluid py-2">
    <h1 class="h3 fw-normal mb-0">Clients List</h1>
  </div>
</header>
<section>
    
    <div class="container-fluid" data-masonry="{&quot;percentPosition&quot;: true }">
        <div class="row">
            <div class="col-md-12" >
        		@if(\Request::old('success'))
        		<div class="alert alert-success" > {{\Request::old('success')}} </div>
        		@elseif(\Request::old('error'))
        		<div class="alert alert-danger" > {{\Request::old('error')}} </div>
        		@endif
        	</div>
        </div>
        <div class="row msnry-grid">
            
            @foreach($clients as $client)
            <div class="col-md-6 col-xl-4">                       
                <div class="card">
                    <div class="card-body ">
                        <div class="d-flex align-items-center text-reset" >
                            <div class="ms-3 overflow-hidden">
                                <h5 class="card-text mb-2 text-uppercase market" id="{{ $client->id }}" style="cursor:pointer">{{ $client->name }}</h5>
                                <p class="card-text text-end"><a href="{{ url('/set-client-session',(\Crypt::encrypt($client->id))) }}" class="btn btn-sm btn-primary" >Warehouse list</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
             @endforeach
        </div>
    </div>
</section>

  <!-- Modal-->
  <div class="modal fade text-start" id="myModal"  tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="--bs-modal-width: 999px;!important">
    <div class="modal-dialog">
      <div class="modal-content" style="width:1024px!important">
        <div class="modal-header">
          <h5 class="modal-title" id="myModalLabel">Client Detail</h5>
          <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <!--<p>Lorem ipsum dolor sit amet consectetur.</p>-->
            <div class="card-header border-bottom">
                <h3 class="h4 mb-0">Basic Detail</h3>
            </div>
                <br>
            <div class="container-fluid">
                <div class="row g-3 align-items-center">
                    <div class="col-lg-3">
                    <label class="form-label" for="client_code">Client Code *</label>
                    <input class="form-control" value="" id="client_code" type="text" readonly>
                </div>
                    <div class="col-lg-3">
                    <label class="form-label" for="name">Client Name *</label>
                    <input class="form-control" id="name" name="name" value="" type="text" readonly>
                </div>
                    <div class="col-lg-3">
                    <label class="form-label" for="company_id">Company</label>
                    <input class="form-control" id="company_id" name="company_id" type="text" value="" readonly>
                   
                </div>
                    <div class="col-lg-3">
                    <label class="form-label" for="billing_address">Billing Address</label>
                    <input class="form-control" id="billing_address" type="text" name="billing_address" value="" readonly>
                  
                </div>
                    <div class="col-lg-3">
                    <label class="form-label" for="email">Email *</label>
                    <input class="form-control" id="email" type="email" name="email" value="" readonly>
                   
                </div>
                    <div class="col-lg-3">
                    <label class="form-label" for="phone">Contact No. *</label>
                    <input class="form-control" id="phone" type="text" name="phone" value="" readonly>
                  
                </div>
                <div class="col-lg-3">
                    <label class="form-label" for="country">Country</label>
                    <input class="form-control" id="country" name="country" type="text" value="" readonly>
                    
                </div>
                    <div class="col-lg-3">
                    <label class="form-label" for="state">State *</label>
                     <input class="form-control" id="state_id" name="state_id" type="text" value="" readonly>
                   
                </div>
                    <div class="col-lg-3">
                    <label class="form-label" for="city">City *</label>
                    <input class="form-control" id="city" name="city" type="text" value="" readonly>
                   
                </div>
                
                    <div class="col-lg-3">
                        <label class="form-label" for="pincode">Pin Code *</label>
                        <input class="form-control" id="pincode" name="pincode" type="text" value="" readonly>
                   
                    </div>
                    <div class="col-lg-3">
                        <label class="form-label" for="address1">Address 2</label>
                        <input class="form-control" id="address1" name="address1" type="text" value="" readonly>
                    
                    </div>
                    
                </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
               </div>
      </div>
    </div>
</div>
<script>
        $(document).ready(function () {
            $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
            $(".market").click(function (event) {
                $("#myModal").modal('show');
                var parentID = $(this).attr('id');
                console.log(parentID)
                if(parentID){
                    $.ajax({
                        url:'/get/client/'+parentID,
                        type:'GET',
                        success:function(res){                         
                            if(res.data !=''){
                                var client = res.data;
                                $('#client_code').val(client.client_code);
                                $('#name').val(client.name);
                                $('#company_id').val(client.company.name);
                                $('#billing_address').val(client.billing_address);
                                $('#email').val(client.email);
                                $('#phone').val(client.phone);
                                $('#state_id').val(client.state.state_name);
                                $('#city').val(client.city);
                                $('#country').val(client.country);
                                $('#pincode').val(client.pincode);
                                $('#address1').val(client.address2);
                            }   
                            else{
                                console.log(res.data);
                            }
                        },
                        error:function(res) {
                            console.log(res.error);
                        }
                    });
                }
              
            });
            
        });
      
    
</script>            

@endsection  