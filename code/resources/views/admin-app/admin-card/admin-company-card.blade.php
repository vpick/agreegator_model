@extends('admin-app/admin-master')
@section('title', 'Company Card')
@section('content')

<style>
    .mb-15 {
    margin-bottom: 1.5rem !important;
}
</style>
<header class="py-4">
  <div class="container-fluid py-2">
    <h1 class="h3 fw-normal mb-0">Company details</h1>
  </div>
</header>
<!-- Forms Section-->
<section class="pb-5"> 
  <div class="container-fluid">
    <div class="row">
      <!-- Basic Form-->
      <div class="col-md-12" >
        @if(\Request::old('success'))
        <div class="alert alert-success" > {{\Request::old('success')}} </div>
        @elseif(\Request::old('error'))
        <div class="alert alert-danger" > {{\Request::old('error')}} </div>
        @endif
    </div>
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
              @if (!empty($data))
              <form  class='g-3 align-items-center '  method="post" action="{{ route('company.update', $data->id) }}">
              @method('PUT')
            @else
              <form class='g-3 align-items-center'  method="post" action="{{ route('company.store') }}">
            @endif
            @csrf
            <div class="row ">
              <div class="card-header mb-15">
                <h3 class="h4 mb-0">Basic Detail</h3>
              </div>
              @if (!empty($data))
              <div class="col-lg-3 mb-2">
                <label class="form-label" for="name">Company Code *</label>
                <input class="form-control" value="{{ $data->company_code ?? '' }}" type="text" disabled>
             
              </div>
              @endif
              <div class="col-lg-3 mb-2">
                <label class="form-label" for="name">Company Name *</label>
                <input class="form-control" id="name" name="name" value="{{ $data ? $data->name : old('name') }}" type="text" required>
                @error('name')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>
              <div class="col-lg-3 mb-2">
                <label class="form-label" for="url">Website URL </label>
                <input class="form-control" id="url" name="url" type="url" value="{{ $data ? $data->url : old('url') }}">
                @error('url')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>
              <div class="col-lg-3 mb-2">
                <label class="form-label" for="pan_no">Pan Number *</label>
                <input class="form-control" id="pan_no" type="text" name="pan_no" value="{{ $data ? $data->pan_no : old('pan_no') }}" required>
                @error('pan_no')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>
              <div class="col-lg-3 mb-2">
                <label class="form-label" for="gst_no">GST Number *</label>
                <input class="form-control" id="gst_no" name="gst_no" type="text" value="{{ $data ? $data->gst_no : old('gst_no') }}">
                @error('gst_no')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>
              <div class="col-lg-3 mb-2">
                <label class="form-label" for="email">Email *</label>
                <input class="form-control" id="email" type="email" name="email" value="{{ $data ? $data->email : old('email') }}" required>
                @error('email')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>
              <div class="col-lg-3 mb-2">
                <label class="form-label" for="phone">Contact No. *</label>
                <input class="form-control" id="phone" type="text" name="phone" required value="{{ $data ? $data->phone :old('phone') }}">
                @error('phone')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>
              <div class="col-lg-3 mb-2">
                <label class="form-label" for="state">State *</label>
                <select class="form-control" id="state" name="state_id" required>
                  <option value="">Select state</option>
                  @foreach($states as $state)
                    <option value="{{ $state->id }}" {{ $data ? ($data->state_id == $state->id ? 'selected' : '') : (old('state_id') == $state->id ? 'selected' : '' )}}>{{ $state->state_name }}</option>
                  @endforeach
                </select>
                @error('state_id')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>
              <div class="col-lg-3 mb-2">
                <label class="form-label" for="city">City *</label>
                <input class="form-control" id="city" name="city" type="text" required value="{{ $data ? $data->city :old('city') }}">
                @error('city')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>
              <div class="col-lg-3 mb-2">
                <label class="form-label" for="district">District *</label>
                <input class="form-control" id="district" name="district" type="text" required value="{{ $data ? $data->district :old('district') }}">
                @error('district')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>
              <div class="col-lg-3 mb-2">
                <label class="form-label" for="pincode">Pin Code *</label>
                <input class="form-control" id="pincode" name="pincode" type="text" required value="{{ $data ? $data->pincode:old('pincode') }}">
                @error('pincode')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>
              </div>
                <div class="row py-2">
              <div class="col-lg-3 mb-2">
                <label class="form-label" for="company_logo_image">Upload Your Company Logo Image</label>
                <input class="form-control" id="company_logo_image" onchange="previewlogoImage(this);" type="file" placeholder="Company Logo" accept="image/png,image/jpeg">
				<input class="form-control" value='' name='company_logo' id='company_logo' type="hidden" readonly>
                @error('company_logo')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>
              <div class="col-lg-3">
			        <img id="logo-preview" src="{{ $data ? $data->company_logo : url('preview.jpg') }}" alt="your image" width="60" height="60" style="margin-top: 15px;" />
				</div>
             </div>
                <div class="row py-2">
              

              
			<div class="col-lg-3 mb-2">
                <label class="form-label" for="address">Address *</label>
                <textarea class="form-control" id="address" name="address" required rows="2">{{ $data ? $data->address : old('address') }}</textarea>
              </div>
              <div class="card-header border-bottom mb-2">
                <h3 class="h4 mb-0">Account Detail</h3>
              </div>
              <div class="col-lg-3 mb-2">
                <label class="form-label" for="account_no">Account no*</label>
                <input class="form-control" id="account_no" name="account_no" type="text" value="{{ $data ? $data->account_no :old('account_no') }}">
                @error('account_no')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>
              <div class="col-lg-3 mb-2">
                <label class="form-label" for="account_name">Account Name*</label>
                <input class="form-control" id="account_name" name="account_name" type="text" value="{{ $data ? $data->account_name :old('account_name') }}">
                @error('account_name')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>
              <div class="col-lg-3 mb-2">
                <label class="form-label" for="bank_name">Bank Name*</label>
                <input class="form-control" id="bank_name" name="bank_name" type="text" value="{{ $data ? $data->bank_name :old('bank_name') }}">
                @error('bank_name')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>
              <div class="col-lg-3 mb-2">
                <label class="form-label" for="bank_branch">Bank Branch*</label>
                <input class="form-control" id="bank_branch" name="bank_branch" type="text" value="{{ $data ? $data->bank_branch :old('bank_branch') }}">
                @error('bank_branch')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>
              <div class="col-lg-3 mb-2">
                <label class="form-label" for="account_type">Account Type*</label>
                <input class="form-control" id="account_type" name="account_type" type="text" value="{{ $data ? $data->account_type :old('account_type') }}">
                @error('account_type')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>
              <div class="col-lg-3 mb-2">
                <label class="form-label" for="ifsc_code">IFSC Code*</label>
                <input class="form-control" id="ifsc_code" name="ifsc_code" type="text" value="{{ $data ? $data->ifsc_code :old('ifsc_code') }}">
                @error('ifsc_code')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>
              <div class="col-lg-3 mb-2">
                <label class="form-label" for="cancelled_cheque_image">Upload Your Cancelled Cheque Image*</label>
                <input class="form-control" id="cancelled_cheque_image" onchange="previewImage(this);" type="file" placeholder="Cancelled Cheque" accept="image/png,image/jpeg"0>
						    <input class="form-control" value='' name='cancelled_cheque' id='cancelled_cheque' type="hidden" readonly>
                @error('cancelled_cheque')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>
              <div class="col-lg">
					      <img id="cheque-preview" src="{{ $data ? $data->cancelled_cheque :url('preview.jpg') }}" alt="your image" width="80" height="80" />
					    </div>
              <div class="col-lg-4">
                <div class="pt-2">
                  <button class="btn btn-primary" type="submit">Submit</button>
                  <a href="{{ route('company.index') }}" class="btn btn-danger">Cancel</a>
                </div> 
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>         
  </div>
</section>
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
<script>
    function previewImage(element)
    {
		debugger
		var file = element.files[0];
		var reader = new FileReader();
		reader.onloadend = function() 
		{
     //alert(reader.result);
			$("#cancelled_cheque").attr("value",reader.result);
			$('#cheque-preview').attr('src', reader.result);
		}
		reader.readAsDataURL(file);
    }
</script>
<script>
    function previewlogoImage(element)
    {
		debugger
		var file = element.files[0];
		var reader = new FileReader();
		reader.onloadend = function() 
		{
     //alert(reader.result);
			$("#company_logo").attr("value",reader.result);
			$('#logo-preview').attr('src', reader.result);
		}
		reader.readAsDataURL(file);
    }
</script>