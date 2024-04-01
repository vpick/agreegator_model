@extends('admin-app/admin-master')
@section('title', 'Company List')
@section('content')
<!-- Statistics Section-->
<header class="py-4">
  <div class="container-fluid py-2">
    <h1 class="h3 fw-normal mb-0">Company List</h1>
  </div>
</header>
<section>
    <div class="container-fluid">
        <div class="row msnry-grid" data-masonry="{&quot;percentPosition&quot;: true }">
            @foreach($companies as $company)
            <div class="col-md-6 col-xl-4">                       
                <div class="card">
                    <div class="card-body ">
                        <div class="d-flex align-items-center text-reset" >
                           
                                @if(!empty($company->company_logo))
                                 <img class="avatar avatar-lg" src="{{ $company->company_logo }}" alt="{{ $company->name }}" title="Company logo">
                                @else
                                    <img class="avatar avatar-lg" src="{{ url('company_logo_default.png') }}" alt="{{ $company->name }}" title="Company logo">
                                @endif
                            
                            <div class="ms-3 overflow-hidden">
                                <h5 class="card-text mb-0 text-capitalize market" id="{{ $company->id }}" style="cursor:pointer">{{ $company->name }}</h5>
                                <p class="card-text text-muted text-sm">{{ $company->url }}</p>
                                <p class="card-text">{{ $company->email }}<br><abbr title="Phone">P:  </abbr>{{ $company->phone }}</p>
                            </div>
                        </div>
                         <a href="{{ url('/set-company-session',(\Crypt::encrypt($company->id))) }}" class="text-end btn btn-sm btn-primary" style="float: right">
                            Client list
                        </a>
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
                    <label class="form-label" for="company_code">Company Code *</label>
                    <input class="form-control" value="" id="company_code" type="text" readonly>
                </div>
                    <div class="col-lg-3">
                    <label class="form-label" for="name">Company Name *</label>
                    <input class="form-control" id="name" name="name" value="" type="text" readonly>
                </div>
                    <div class="col-lg-3">
                    <label class="form-label" for="url">Website URL</label>
                    <input class="form-control" id="url" name="url" type="url" value="" readonly>
                   
                </div>
                    <div class="col-lg-3">
                    <label class="form-label" for="pan_no">Pan Number *</label>
                    <input class="form-control" id="pan_no" type="text" name="pan_no" value="" readonly>
                  
                </div>
                    <div class="col-lg-3">
                    <label class="form-label" for="gst_no">GST Number</label>
                    <input class="form-control" id="gst_no" name="gst_no" type="text" value="" readonly>
                   
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
                    <label class="form-label" for="state">State *</label>
                     <input class="form-control" id="state_id" name="state_id" type="text" value="" readonly>
                   
                </div>
                    <div class="col-lg-3">
                    <label class="form-label" for="city">City *</label>
                    <input class="form-control" id="city" name="city" type="text" value="" readonly>
                   
                </div>
                    <div class="col-lg-3">
                    <label class="form-label" for="district">District *</label>
                    <input class="form-control" id="district" name="district" type="text" value="" readonly>
                    
                </div>
                    <div class="col-lg-3">
                        <label class="form-label" for="pincode">Pin Code *</label>
                        <input class="form-control" id="pincode" name="pincode" type="text" value="" readonly>
                   
                    </div>
                    <div class="col-lg-3">
                        <label class="form-label" for="address">Address</label>
                        <input class="form-control" id="address" name="address" type="text" value="" readonly>
                    
                    </div>
                    <div class="col-lg-3">
				        <img id="company_logo" src="{{ url('preview.jpg') }}" alt="your image" width="80" height="80" />
	                </div>
                    <div class="card-header border-bottom">
                        <h3 class="h4 mb-0">Account Detail</h3>
                    </div>
                    <div class="col-lg-3">
                        <label class="form-label" for="account_no">Account no</label>
                        <input class="form-control" id="account_no" name="account_no" type="text" value="" readonly>
                    
                    </div>
                    <div class="col-lg-3">
                        <label class="form-label" for="account_name">Account Name</label>
                        <input class="form-control" id="account_name" name="account_name" type="text" value="" readonly>
                    
                    </div>
                    <div class="col-lg-3">
                        <label class="form-label" for="bank_name">Bank Name</label>
                        <input class="form-control" id="bank_name" name="bank_name" type="text" value="" readonly>
                   
                    </div>
                    <div class="col-lg-3">
                        <label class="form-label" for="bank_branch">Bank Branch</label>
                        <input class="form-control" id="bank_branch" name="bank_branch" type="text" value="" readonly>
                    
                    </div>
                    <div class="col-lg-3">
                        <label class="form-label" for="account_type">Account Type</label>
                        <input class="form-control" id="account_type" name="account_type" type="text" value="" readonly>
                     </div>
                    <div class="col-lg-3">
                        <label class="form-label" for="ifsc_code">IFSC Code</label>
                        <input class="form-control" id="ifsc_code" name="ifsc_code" type="text" value="" readonly>
                    </div>
                    <div class="col-lg-3">
				      <img id="cancelled_cheque" src="{{ url('preview.jpg') }}" alt="Cancelled cheque" width="80" height="80" title="Cancelled Cheque" />
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
                        url:'/get/company/'+parentID,
                        type:'GET',
                        success:function(res){                         
                            if(res.data !=''){
                                var company = res.data;
                                $('#company_code').val(company.company_code);
                                $('#name').val(company.name);
                                $('#url').val(company.url);
                                $('#pan_no').val(company.pan_no);
                                $('#gst_no').val(company.gst_no);
                                $('#email').val(company.email);
                                $('#phone').val(company.phone);
                                $('#state_id').val(company.state.state_name);
                                $('#city').val(company.city);
                                $('#district').val(company.district);
                                $('#pincode').val(company.pincode);
                                $('#address').val(company.address);
                                $('#company_logo').attr('src',company.company_logo);
                                $('#account_no').val(company.account_no);
                                $('#account_name').val(company.account_name);
                                $('#bank_name').val(company.bank_name);
                                $('#bank_branch').val(company.bank_branch);
                                $('#account_type').val(company.account_type);
                                $('#ifsc_code').val(company.ifsc_code);
                                $('#cancelled_cheque').attr('src',company.cancelled_cheque);
                                
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