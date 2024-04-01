@extends('admin-app/admin-master')
@section('title', 'Warehouse List')
@section('content')
<!-- Statistics Section-->
<header class="py-4">
  <div class="container-fluid py-2">
    <h1 class="h3 fw-normal mb-0">Warehouse List</h1>
  </div>
</header>
<section>
    
    <div class="container-fluid">
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
            
            @foreach($warehouses as $warehouse)
            <div class="col-md-6 col-xl-4">                       
                <div class="card">
                    <div class="card-body ">
                        <div class="d-flex align-items-center text-reset" >
                            <div class="ms-3 overflow-hidden">
                                <h5 class="card-text mb-2 text-uppercase market" id="{{ $warehouse->id }}" style="cursor:pointer">{{ $warehouse->warehouse_name }}</h5>
                                 <a href="{{ url('/set-session',(\Crypt::encrypt($warehouse->id))) }}" class="btn btn-sm btn-primary" >View</a>
                                <!--<a href="{{ route('app-warehouse.edit',(\Crypt::encrypt($warehouse->id))) }}" class="btn btn-sm btn-primary" >view</a>-->
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
    <div class="modal fade text-start" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="--bs-modal-width: 999px;!important">
        <div class="modal-dialog">
            <div class="modal-content" style="width:1024px!important">
                <div class="modal-header">
                  <h5 class="modal-title" id="myModalLabel">Company Detail</h5>
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
                            <label class="form-label" for="warehouse_code">Warehouse Code </label>
                            <input class="form-control" value="" id="warehouse_code" type="text" readonly>
                        </div>
                            <div class="col-lg-3">
                            <label class="form-label" for="warehouse_name">Warehouse Name </label>
                            <input class="form-control" id="warehouse_name" name="warehouse_name" value="" type="text" readonly>
                        </div>
                            <div class="col-lg-3">
                            <label class="form-label" for="contact_name">Contact Name</label>
                            <input class="form-control" id="contact_name" name="contact_name" type="text" value="" readonly>
                           
                        </div>
                            <div class="col-lg-3">
                            <label class="form-label" for="company_id">Company</label>
                            <input class="form-control" id="company_id" type="text" name="company_id" value="" readonly>
                        </div>
                            <div class="col-lg-3">
                            <label class="form-label" for="client_id">Client</label>
                            <input class="form-control" id="client_id" name="client_id" type="text" value="" readonly>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label" for="gst_no">Gst no</label>
                            <input class="form-control" id="gst_no" name="gst_no" type="text" value="" readonly>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label" for="support_email">Support Email Id</label>
                            <input class="form-control" id="support_email" type="email" name="support_email" value="" readonly>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label" for="support_phone">Support Phone no</label>
                            <input class="form-control" id="support_phone" type="text" name="support_phone" value="" readonly>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label" for="phone">Contact No. </label>
                            <input class="form-control" id="phone" type="text" name="phone" value="" readonly>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label" for="state">State </label>
                             <input class="form-control" id="state_id" name="state_id" type="text" value="" readonly>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label" for="city">City </label>
                            <input class="form-control" id="city" name="city" type="text" value="" readonly>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label" for="pincode">Pin Code </label>
                            <input class="form-control" id="pincode" name="pincode" type="text" value="" readonly>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label" for="address1">Address 1</label>
                            <input class="form-control" id="address1" name="address1" type="text" value="" readonly>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label" for="address2">Address 2</label>
                            <input class="form-control" id="address2" name="address2" type="text" value="" readonly>
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
                        url:'/get/warehouse/'+parentID,
                        type:'GET',
                        success:function(res){                         
                            if(res.data !=''){
                                var warehouse = res.data;
                                $('#warehouse_code').val(warehouse.warehouse_code);
                                $('#warehouse_name').val(warehouse.warehouse_name);
                                $('#contact_name').val(warehouse.contact_name);
                                $('#company_id').val(warehouse.company.name);
                                $('#client_id').val(warehouse.client.name);
                                $('#support_email').val(warehouse.support_email);
                                $('#support_phone').val(warehouse.support_phone);
                                $('#phone').val(warehouse.phone);
                                $('#state_id').val(warehouse.state.state_name);
                                $('#city').val(warehouse.city);
                                $('#gst_no').val(warehouse.gst_no);
                                $('#pincode').val(warehouse.pincode);
                                $('#address1').val(warehouse.address1);
                                $('#address2').val(warehouse.address2);
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