@extends('admin-app/admin-master')
@section('title', 'Add Chanel')
@section('content')
<header class="py-4">
	<div class="container-fluid py-2">
	  <h1 class="h3 fw-normal mb-0">Chanel details</h1>
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
				  <form class="row g-3 align-items-center" method="post" action="{{url('edit-chanel?ord=')}}{{Crypt::encrypt($chanels->id)}}">
				    @method('PUT')
				    @csrf
					<div class="col-lg">
					  <input type="hidden" name="_token" value="{{ csrf_token() }}" />
					  <label class="visually-hidden" for="inlineFormInputGroupUsername">Chanel Name*</label>
					  <div class="input-group">
						<div class="input-group-text">*</div>
						<input class="form-control" id="" name='chanel_name' value="{{ $chanels?$chanels->chanel_name:old('chanel_name') }}" type="text" placeholder="Chanel Name">
						</div>
						<p>
						@if($errors->has('chanel_name'))
							<div class="error">{{ $errors->first('chanel_name') }}</div>
						@endif
						</p>
					</div>
					<div class="col-lg">
					  <label class="visually-hidden" for="inlineFormSelectPref">Chanel Logo*</label>
					  <div class="input-group">
						<div class="input-group-text">*</div>
					      <input class="form-control" id="" onchange="previewImage(this);" type="file" placeholder="Chanel Logo" accept="image/png,image/jpeg">
						  <input class="form-control" name='chanel_logo' value="{{ $chanels?$chanels->chanel_logo:old('chanel_logo') }}" id='chanel_logo' type="hidden">
					  </div>
					   <p>
						@if($errors->has('chanel_logo'))
							<div class="error">{{ $errors->first('chanel_logo') }}</div>
						@endif
						</p>
					</div>
					<div class="col-lg">
					<img id="logo-preview" src="{{ $chanels?$chanels->chanel_logo:old('chanel_logo') }}" alt="your image" width="80" height="80" />
					</div>
					<div class="col-lg">
					  <button class="btn btn-primary" type="submit">Submit</button>
					  <p></p>
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
			$("#chanel_logo").attr("value",reader.result);
			$('#logo-preview').attr('src', reader.result);
		}
		reader.readAsDataURL(file);
    }
</script>