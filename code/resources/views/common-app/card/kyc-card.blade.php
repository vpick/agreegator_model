@extends('common-app/master')
@section('title', 'kyc Card')
@section('content')
<header class="py-4">
  <div class="container-fluid py-2">
    <h1 class="h3 fw-normal mb-0">KYC details</h1>
  </div>
</header>

 <!-- Counts Section -->
  <section class="py-5" style='padding-top:0px !important'>
    <div class="container-fluid">
      <div class="row">
        <!--Tab-->
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Basic Information</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">KYC Detail</button>
          </li>
        </ul>
        </div>
    </div>
	</section>
<!-- Forms Section-->
<section class="pb-5"> 
  <div class="container-fluid">
    <div class="row">
      <div class="tab-content" id="myTabContent">
			    <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
            <!-- Basic Form-->
            <div class="col-lg-12">
              <div class="card">
                <div class="card-body">
                  <form class="row g-3 align-items-center" action="{{ route('user.profile') }}" method="POST">
                   @csrf
                    <div class="col-lg-6">
                      <label class="form-label" for="username">User Name</label>
                      <input type="text" class="form-control" id="username" value="{{ Auth::user()->username }}" disabled>
                    </div>
                    <div class="col-lg-6">
                      <label class="form-label" for="user_code">User Code</label>
                      <input type="text" class="form-control" id="user_code" value="{{ Auth::user()->user_code }}" disabled>
                    </div>
                    <div class="col-lg-6">
                      <label class="form-label" for="user_email">User Email</label>
                      <input type="text" class="form-control" id="user_email" name="email" value="{{ Auth::user()->email }}">
                    </div>
                    <div class="col-lg-6">
                      <label class="form-label" for="user_mobile">User Mobile</label>
                      <input type="text" class="form-control" id="user_mobile" name="mobile" value="{{ Auth::user()->mobile }}">
                    </div>
                    <!-- <div class="col-lg-6">
                      <label class="form-label" for="password">Password</label>
                      <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <div class="col-lg-6">
                      <label class="form-label" for="confirm_password">Confirm Password</label>
                      <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                    </div> -->
                    <div class="col-lg-3">
                      <button class="btn btn-primary" type="submit">Submit</button>
                      <!--<a href="#" class="btn btn-danger">Cancel</a>-->
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <div class="tab-pane fade show" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
            <!-- Basic Form-->
            <div class="col-lg-12">
              <div class="card">
                <div class="card-body">
                  <form class="row g-3 align-items-center" method="POST" action="{{ route('kyc.store') }}">
                    @csrf
                    <div class="col-lg-3">
                      <label class="form-label" for="kyc_type">Kyc Type *</label>
                      <select class="form-control" id="kyc_type" name="kyc_type" required>
                        <option value="">Select type</option>
                        <option value="Sole Proprietorship" {{ $data ? ($data->kyc_type == 'Sole Proprietorship' ? 'selected' : '') : '' }}>Sole Proprietorship</option> 
                        <option value="Partnership" {{ $data ? ($data->kyc_type == 'Partnership' ? 'selected' : '') : '' }}>Partnership</option>
                        <option value="Limited Liability Partnership" {{ $data ? ($data->kyc_type == 'Limited Liability Partnership' ? 'selected' : '') : '' }}>Limited Liability Partnership</option>
                        <option value="Public Limited Company" {{ $data ? ($data->kyc_type == 'Public Limited Company' ? 'selected' : '') : '' }}>Public Limited Company</option>
                        <option value="Private Limited Company" {{ $data ? ($data->kyc_type == 'Private Limited Company' ? 'selected' : '') : '' }}>Private Limited Company</option>
                      </select>
                      @error('kyc_type')
                          <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-lg-3">
                      <label class="form-label" for="shipment_type">Type of shipment*</label>
                      <select class="form-control" id="shipment_type" name="shipment_type" required>
                        <option value="">Select type</option>
                        <option value="Commercial" {{ $data ? ($data->shipment_type == 'Commercial' ? 'selected' : '') : '' }}>Commercial</option> 
                        <option value="Non Commercial" {{ $data ? ($data->shipment_type == 'Non Commercial' ? 'selected' : '') : '' }}>Non Commercial</option>
                      </select>
                      @error('shipment_type')
                          <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-lg-3">
                      <label class="form-label" for="iec_code">Import Export Code (IEC)*</label>
                      <input type="text" class="form-control" placeholder="Import Export Code (IEC)" id="iec_code" name="iec_code" value="{{ $data ? $data->iec_code :old('iec_code') }}">
                      @error('iec_code')
                          <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-lg-3">
                      <label class="form-label" for="iec_branch_code">IEC Branch Code*</label>
                      <input type="text" class="form-control" placeholder="Import Export Code (IEC)" id="iec_branch_code" name="iec_branch_code" value="{{ $data ? $data->iec_branch_code : old('iec_branch_code') }}">
                        @error('iec_branch_code')
                          <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="card-header border-bottom">
                      <h3 class="h4 mb-0">Document 1</h3>
                    </div>
                    <div class="col-lg-6">
                      <label class="form-label" for="document_type1">Document Type1*</label>
                      <select class="form-control docmt" id="document_type1" name="document_type1" required>
                        <option value="">Select type</option>
                        <option value="Aadhar Card" {{ $data ? ($data->document_type1 == 'Aadhar Card' ? 'selected' : '') : '' }}>Aadhar Card</option> 
                        <option value="PAN Card" {{ $data ? ($data->document_type1 == 'PAN Card' ? 'selected' : '') : '' }}>PAN Card</option>
                        <option value="Valid Passport" {{ $data ? ($data->document_type1 == 'Valid Passport' ? 'selected' : '') : '' }}>Valid Passport</option>
                        <option value="Voter Id Card" {{ $data ? ($data->document_type1 == 'Voter Id Card' ? 'selected' : '') : '' }}>Voter Id Card</option>
                      </select>
                      @error('document_type1')
                          <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-lg-3">
                      <label class="form-label" for="iec_photo">Upload Document (IEC)*</label>
                      <input type="file" class="form-control" placeholder="Upload Document (IEC)*" onchange="previewIecImage(this);" >
                      <input type="hidden" class="form-control" value="{{ $data ? $data->iec_photo : '' }}" id="iec_photo" name="iec_photo">
                      @error('iec_photo')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                    </div>
                    <div class="col-lg-3">
                      <img id="iec_photo_preview" src="{{ $data ? $data->iec_photo : url('preview.jpg') }}" alt="your image" width="100" height="80" />
                    </div>
                    <div class="col-lg-3">
                      <label class="form-label" for="document_id1">Document Id*</label>
                      <input type="text" class="form-control" placeholder="Document Id" id="document_id1" name="document_id1" value="{{ $data ? $data->document_id1 : old('document_id1') }}">
                      @error('document_id1')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                    </div>
                    <div class="col-lg-3">
                      <label class="form-label" for="name_on_doc1">Name on document*</label>
                      <input type="text" class="form-control" placeholder="Name on document" id="name_on_doc1" name="name_on_doc1" value="{{ $data ? $data->name_on_doc1 : old('name_on_doc1') }}">
                      @error('name_on_doc1')
                          <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                  
                    <div class="col-lg-6"></div>
                    <div class="col-lg-3">
                      <label class="form-label" for="doc_photo1">Upload Document*</label>
                     
                      <input type="file" class="form-control" placeholder="Upload Document image*" onchange="previewDocImage(this);" >
                      <input type="hidden" class="form-control" value="{{ $data ? $data->doc_photo1 : '' }}" id="doc_photo1" name="doc_photo1">
                      @error('doc_photo1')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                     
                    </div>
                    <div class="col-lg-3">
                      <img id="doc_photo1_preview" src="{{ $data ? $data->doc_photo1 : url('preview.jpg') }}" alt="your image" width="100" height="80" />
                    </div>
                    <div class="card-header border-bottom">
                      <h3 class="h4 mb-0">Document 2</h3>
                    </div>
                    <div class="col-lg-6">
                      <label class="form-label" for="document_type2">Document Type2*</label>
                      <select class="form-control docmt" id="document_type2" name="document_type2" required>
                        <option value="">Select type</option>
                        <option value="Aadhar Card" {{ $data ? ($data->document_type2 == 'Aadhar Card' ? 'selected' : '') : '' }}>Aadhar Card</option> 
                        <option value="PAN Card" {{ $data ? ($data->document_type2 == 'PAN Card' ? 'selected' : '') : '' }}>PAN Card</option>
                        <option value="Valid Passport" {{ $data ? ($data->document_type2 == 'Valid Passport' ? 'selected' : '') : '' }}>Valid Passport</option>
                        <option value="Voter Id Card" {{ $data ? ($data->document_type2 == 'Voter Id Card' ? 'selected' : '') : '' }}>Voter Id Card</option>
                      </select>
                      @error('document_type2')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
                    </div>
                    <div class="col-lg-3">
                      <label class="form-label" for="gst_certificate">Upload GST Certificate*</label>
                      
                      <input type="file" class="form-control" placeholder="Upload GST Certificate*" onchange="previewGstImage(this);" >
                      <input type="hidden" class="form-control" value="{{ $data ? $data->gst_certificate : '' }}" id="gst_certificate" name="gst_certificate">
                     @error('gst_certificate')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
                    </div>
                    <div class="col-lg-3">
                      <img id="gst-preview" src="{{ $data ? $data->gst_certificate : url('preview.jpg') }}" alt="your image" width="100" height="80" />
                    </div>
                    <div class="col-lg-3">
                      <label class="form-label" for="document_id2">Document Id*</label>
                      <input type="text" class="form-control" placeholder="Document Id" id="document_id2" name="document_id2" value="{{ $data ? $data->document_id2 :old('document_id2') }}">
                      @error('document_id2')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
                    </div>
                    <div class="col-lg-3">
                      <label class="form-label" for="name_on_doc2">Name on document*</label>
                      <input type="text" class="form-control" placeholder="Name on document" id="name_on_doc" name="name_on_doc2" value="{{ $data ? $data->name_on_doc2 :old('name_on_doc2') }}">
                      @error('name_on_doc2')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
                    </div>             
                    <div class="col-lg-6"></div>
                    <div class="col-lg-3 mb-6">
                      <label class="form-label" for="doc_photo2">Upload Document*</label>
                      <input type="file" class="form-control" placeholder="Upload Document image*" onchange="previewImage(this);" >
                      <input type="hidden" class="form-control" value="{{ $data ? $data->doc_photo2 : '' }}" id="doc_photo2" name="doc_photo2">
                      @error('doc_photo2')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
                    </div>
                    <div class="col-lg-3">
                      <img id="doc_photo2_preview" src="{{ $data ? $data->doc_photo2 : url('preview.jpg') }}" alt="your image" width="100" height="80" />
                    </div>
                    <div class="col-lg-6"></div>
                    <div class="col-lg-3">
                      <button class="btn btn-primary" type="submit" id="submitBtn">Submit</button>
                      <!--<a href="#" class="btn btn-danger">Cancel</a>-->
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>         
  </div>
</section>
<script type="text/javascript">
    $(document).ready(function() {   
      //get client
        
        $(document).on('change', '.docmt', function() {
            debugger
            var type = $(this).attr('id');
            var val1 =$(this).val();
            console.log(type);
            if(type == 'document_type1'){
                console.log('if');
                if(val1 == $('#document_type2').val()){
                  //alert('document type1 and documnet type2 can not be same');
                  $('#submitBtn').prop('disabled', true);
                     Swal.fire({
        				title: 'Warning!',
        				text: "document type1 and documnet type2 can not be same",
        				timer: 2000,
        				icon: 'info'
        			});
                  
                }
                else{
                  $('#submitBtn').prop('disabled', false);
                }
            }
            else{
                console.log('else');
              if(val1 == $('#document_type1').val()){
                  //alert('document type1 and documnet type2 can not be same');
                  $('#submitBtn').prop('disabled', true);
                  Swal.fire({
        				title: 'Warning!',
        				text: "document type1 and documnet type2 can not be same",
        				timer: 2000,
        				icon: 'info'
        			});
                }
                else{
                  $('#submitBtn').prop('disabled', false);
                }
            }
            
        });
    });
  
    
</script>
<script>
    function previewImage(element)
    {
		
		var file = element.files[0];
		var reader = new FileReader();
		reader.onloadend = function() 
		{
     
			$("#doc_photo2").attr("value",reader.result);
			$('#doc_photo2_preview').attr('src', reader.result);
		}
		reader.readAsDataURL(file);
    }
</script>
<script>
    function previewDocImage(element)
    {
		
		var file = element.files[0];
		var reader = new FileReader();
		reader.onloadend = function() 
		{
     
			$("#doc_photo1").attr("value",reader.result);
			$('#doc_photo1_preview').attr('src', reader.result);
		}
		reader.readAsDataURL(file);
    }
</script>
<script>
    function previewIecImage(element)
    {
		
		var file = element.files[0];
		var reader = new FileReader();
		reader.onloadend = function() 
		{
     
			$("#iec_photo").attr("value",reader.result);
			$('#iec_photo_preview').attr('src', reader.result);
		}
		reader.readAsDataURL(file);
    }
</script>
<script>
    function previewGstImage(element)
    {
		
		var file = element.files[0];
		var reader = new FileReader();
		reader.onloadend = function() 
		{
     
			$("#gst_certificate").attr("value",reader.result);
			$('#gst-preview').attr('src', reader.result);
		}
		reader.readAsDataURL(file);
    }
</script>
@endsection

