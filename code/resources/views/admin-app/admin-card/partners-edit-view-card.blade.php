@extends('admin-app/admin-master')
@section('title', 'Add Logistics')
@section('content')
<header class="py-4">
	<div class="container-fluid py-2">
	  <h1 class="h3 fw-normal mb-0">Logistics details</h1>
	</div>
</header>
  <section class="pb-5"> 
	<div class="container-fluid">
	  <div class="row">
		<!-- Basic Form-->
		<div class="col-lg-12">
			<div class="card">
				@if(session('status'))
					<div class="alert alert-success">
						{{ session('status') }}
					</div>
				@endif
				@if($errors->any())
					<div class="alert alert-danger">
						<ul>
							@foreach($errors->all() as $error)
								<li>{{ $error }}</li>
							@endforeach
						</ul>
					</div>
				@endif
				<div class="card-body">
				  <form class="row g-3 align-items-center" method="post" action="{{url('edit-partners?ord=')}}{{Crypt::encrypt($partners->id)}}">
				  @method('PUT')
				   @csrf
					<div class="col-lg-3">
					    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
					    <label class="<!--visually-hidden-->" for="inlineFormInputGroupUsername">Logistics Type*</label>
					    <select class="form-control" name='logistics_type' required>
						 <option value=''>Select Type</option>
						 <option value='Aggrigator' {{ $partners->logistics_type == 'Aggrigator' ? 'selected' : '' }}>Aggrigator</option>
						 <option value='Currior' {{ $partners->logistics_type == 'Currior' ? 'selected' : '' }}>Currior</option>
						</select>
						<p>
						@if($errors->has('logistics_type'))
							<div class="error">{{ $errors->first('logistics_type') }}</div>
						@endif
						</p>
					</div>
					<div class="col-lg-3">
					    <label class="<!--visually-hidden-->" for="inlineFormInputGroupUsername">Logistics Name*</label>
					    <input class="form-control" id="" name='logistics_name' type="text" value="{{ $partners?$partners->logistics_name:old('logistics_name') }}" placeholder="Logistics Name" required>
						<p>
						@if($errors->has('logistics_name'))
							<div class="error">{{ $errors->first('logistics_name') }}</div>
						@endif
						</p>
					</div>
					<div class="col-lg-3">
					    <label class="<!--visually-hidden-->" for="inlineFormInputGroupUsername">Business Account*</label>
					    <input class="form-control" id="" name='logistics_business_acc' value="{{ $partners?$partners->logistics_business_acc:old('logistics_business_acc') }}" type="text" placeholder="Business Account" required>
						<p>
						@if($errors->has('logistics_business_acc'))
							<div class="error">{{ $errors->first('logistics_business_acc') }}</div>
						@endif
						</p>
					</div>
					<div class="col-lg-3">
					    <label class="<!--visually-hidden-->" for="inlineFormInputGroupUsername">Auth Key*</label>
					    <input class="form-control" id="" name='logistics_auth_key' value="{{ $partners?$partners->logistics_auth_key:old('logistics_auth_key') }}" type="text" placeholder="Auth Key">
						<p>
						@if($errors->has('logistics_auth_key'))
							<div class="error">{{ $errors->first('logistics_auth_key') }}</div>
						@endif
						</p>
					</div>
					
					<div class="col-lg-3">
					    <label class="<!--visually-hidden-->" for="inlineFormInputGroupUsername">User Id*</label>
					    <input class="form-control" id="" name='logistics_auth_name' value="{{ $partners?$partners->logistics_auth_name:old('logistics_auth_name') }}" type="text" placeholder="User Id">
						<p>
						@if($errors->has('logistics_auth_name'))
							<div class="error">{{ $errors->first('logistics_auth_name') }}</div>
						@endif
						</p>
					</div>
					<div class="col-lg-3">
					    <label class="<!--visually-hidden-->" for="inlineFormInputGroupUsername">User Password*</label>
					    <input class="form-control" id="" name='logistics_auth_password' value="{{ $partners?$partners->logistics_auth_password:old('logistics_auth_password') }}" type="text" placeholder="User Password">
						<p>
						@if($errors->has('logistics_auth_password'))
							<div class="error">{{ $errors->first('logistics_auth_password') }}</div>
						@endif
						</p>
					</div>
					<div class="col-lg-3">
					    <label class="<!--visually-hidden-->" for="inlineFormInputGroupUsername">Secret Key*</label>
					    <input class="form-control" id="" name='logistics_auth_secret' value="{{ $partners?$partners->logistics_auth_secret:old('logistics_auth_secret') }}" type="text" placeholder="Secret Key">
						<p>
						@if($errors->has('logistics_auth_secret'))
							<div class="error">{{ $errors->first('logistics_auth_secret') }}</div>
						@endif
						</p>
					</div>
					<div class="col-lg-3">
					    <label class="<!--visually-hidden-->" for="inlineFormInputGroupUsername">Currior Id</label>
					    <input class="form-control" id="" name='logistics_currior_id' value="{{ $partners?$partners->logistics_currior_id:old(logistics_currior_id) }}" type="text" placeholder="Currior Id">
						<p>
						@if($errors->has('logistics_currior_id'))
							<div class="error">{{ $errors->first('logistics_currior_id') }}</div>
						@endif
						</p>
					</div>
					
					<div class="col-lg-3">
					    <label class="<!--visually-hidden-->" for="inlineFormInputGroupUsername">Status</label>
					    <select class="form-control" name='logistics_status' required>
						 <option value=''>Select Status</option>
						 <option value='Active' {{ $partners->logistics_status == 'Active' ? 'selected' : '' }}>Active</option>
						 <option value='Inactive' {{ $partners->logistics_status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
						</select>
						<p>
						@if($errors->has('logistics_status'))
							<div class="error">{{ $errors->first('logistics_status') }}</div>
						@endif
						</p>
					</div>
					
					
					<div class="col-lg-3">
					  <label class="<!--visually-hidden-->" for="inlineFormSelectPref">Logistics Logo*</label>
					    <input class="form-control" id="" onchange="previewImage(this);" type="file" placeholder="Logistics Logo" accept="image/png,image/jpeg">
						<input class="form-control" value='{{ $partners->logistics_logo }}' name='logistics_logo' id='logistics_logo' type="hidden" readonly>
 					    <p>
						@if($errors->has('logistics_logo'))
							<div class="error">{{ $errors->first('logistics_logo') }}</div>
						@endif
						</p>
					</div>
					<div class="col-lg">
					<img id="logo-preview" src="{{ $partners->logistics_logo }}" alt="your image" width="80" height="80" />
					</div>
					<button class="btn btn-primary" type="submit">Submit</button>
					<p></p>
				  </form>
				</div>
			</div>
		</div>
		</div>
	</div>
  </section>
@endsection
<script>
    function previewImage(element)
    {
		debugger
		var file = element.files[0];
		var reader = new FileReader();
		reader.onloadend = function() 
		{
			$("#logistics_logo").attr("value",reader.result);
			$('#logo-preview').attr('src', reader.result);
		}
		reader.readAsDataURL(file);
    }
</script>