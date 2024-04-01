@extends('admin-app/admin-master')
@section('title', 'Add erp')
@section('content')
<header class="py-4">
	<div class="container-fluid py-2">
	  <h1 class="h3 fw-normal mb-0">erp details</h1>
	</div>
</header>
  <section class="pb-5"> 
	<div class="container-fluid">
	  <div class="row">
		<!-- Basic Form-->
		<div class="col-lg-12">
			<div class="card">
				<!--<div class="card-header border-bottom">
				  <h3 class="h4 mb-0">Inline Form</h3>
				</div>
				-->
				@if(session('status'))
					<div class="alert alert-success">
						{{ session('status') }}
					</div>
				@endif
				<div class="card-body">
				@if (!empty($data))
              <form  class='row g-3 align-items-center'  method="post" action="{{ route('app-erp.update', $data->id) }}">
              	@method('PUT')
				@else
				<form class='row g-3 align-items-center'  method="post" action="{{ route('app-erp.store') }}">
				@endif
				@csrf
					<div class="col-lg-4">
					    <label class="<!--visually-hidden-->" for="erp_type">Type*</label>
					    <select class="form-control" name='erp_type' required id="erp_type">
							<option value=''>Select Type</option>
							<option value='WMS' {{ $data ? ($data->erp_type == 'WMS' ? 'selected' : '') :(old('erp_type')=='WMS' ? 'selected' : '')}}>Wms</option>
							<option value='ERP' {{ $data ? ($data->erp_type == 'ERP' ? 'selected' : '') :(old('erp_type')=='ERP' ? 'selected' : '')}}>ERP</option>
							
						</select>
						<p>
						@if($errors->has('erp_type'))
							<div class="error">{{ $errors->first('erp_type') }}</div>
						@endif
						</p>
					</div>
					<div class="col-lg-4">
					    <label class="<!--visually-hidden-->" for="erp_name">Name*</label>
					    <input class="form-control" id="erp_name" name='erp_name' type="text" placeholder="Name" required value="{{ $data ? $data->erp_name : old('erp_name') }}"> 
						<p>
						@if($errors->has('erp_name'))
							<div class="error">{{ $errors->first('erp_name') }}</div>
						@endif
						</p>
					</div>
					
					<div class="col-lg-4">
					    <label class="<!--visually-hidden-->" for="auth_key">Auth Key*</label>
					    <input class="form-control" id="auth_key" name='auth_key' type="text" placeholder="Auth Key" value="{{ $data ? $data->auth_key : old('auth_key') }}">
						<p>
						@if($errors->has('auth_key'))
							<div class="error">{{ $errors->first('auth_key') }}</div>
						@endif
						</p>
					</div>
					
					<div class="col-lg-4">
					    <label class="<!--visually-hidden-->" for="auth_name">User Id*</label>
					    <input class="form-control" id="auth_name" name='auth_name' type="text" placeholder="User Id" value="{{ $data ? $data->auth_name : old('auth_name') }}">
						<p>
						@if($errors->has('auth_name'))
							<div class="error">{{ $errors->first('auth_name') }}</div>
						@endif
						</p>
					</div>
					<div class="col-lg-4">
					    <label class="<!--visually-hidden-->" for="auth_password">User Password*</label>
					    <input class="form-control" id="auth_password" name='auth_password' type="text" placeholder="User Password" value="{{ $data ? $data->auth_password : old('auth_password') }}">
						<p>
						@if($errors->has('auth_password'))
							<div class="error">{{ $errors->first('auth_password') }}</div>
						@endif
						</p>
					</div>
					<div class="col-lg-4">
					    <label class="<!--visually-hidden-->" for="auth_secret">Secret Key*</label>
					    <input class="form-control" id="auth_secret" name='auth_secret' type="text" placeholder="Secret Key" value="{{ $data ? $data->auth_secret : old('auth_secret') }}">
						<p>
						@if($errors->has('erp_auth_secret'))
							<div class="error">{{ $errors->first('erp_auth_secret') }}</div>
						@endif
						</p>
					</div>
					
					
					<div class="col-lg-4">
					    <label class="<!--visually-hidden-->" for="status">Status</label>
					    <select class="form-control" name='status' id="status" required>
                            <option value=''>Select Status</option>
                            <option value='1' {{ $data ? ($data->status == '1' ? 'selected' : '') :(old('status')=='1' ? 'selected' : '')}}>Active</option>
                            <option value='0' {{ $data ? ($data->status == '0' ? 'selected' : '') :(old('status')=='0' ? 'selected' : '')}}>Inactive</option>
						</select>
						<p>
						@if($errors->has('status'))
							<div class="error">{{ $errors->first('status') }}</div>
						@endif
						</p>
					</div>
					
					
					<div class="col-lg-4">
					  <label class="<!--visually-hidden-->" for="logo">Logo*</label>
					    <input class="form-control" id="erp_logo" onchange="previewImage(this);" type="file" placeholder="erp Logo" accept="image/png,image/jpeg">
						<input class="form-control" value="{{ $data ? $data->erp_logo : '' }}" name='logo' id='logo' type="hidden" readonly>
 					    <p>
						@if($errors->has('logo'))
							<div class="error">{{ $errors->first('erp_logo') }}</div>
						@endif
						</p>
					</div>
					
					<div class="col-lg-3">
						<img id="logo-preview" src="{{ $data ? $data->erp_logo : url('preview.jpg') }}" alt="your image" width="80" height="80" />
					</div>
					
					<div class="col-lg-3">
					  <button class="btn btn-primary" type="submit">Submit</button>
					  <a href="{{ route('app-erp.index') }}" class="btn btn-warning">Cancel</a>
						</div>
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
			$("#logo").attr("value",reader.result);
			$('#logo-preview').attr('src', reader.result);
		}
		reader.readAsDataURL(file);
    }
</script>