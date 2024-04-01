@extends('admin-app/admin-master')
@section('title', 'Add Weight')
@section('content')
<style>
	.ms-choice {
        display: block;
        width: 450px!important;
        height: 36px!important;
        padding: 0;
        overflow: hidden;
        cursor: pointer;
        border: 1px solid #ced4da!important;
        text-align: left;
        white-space: nowrap;
        line-height: 36px!important;
        color: #444;
        text-decoration: none;
        border-radius: 4px;
        background-color: #fff;
    }
    .ms-choice>span.placeholder {
        color: transparent!important;
    }
    .ms-choice>div.icon-caret {
    	display: none!important;
    }
	select {
      width: 100%;
    }
    .ms-choice>span.placeholder {
        color: transparent!important;
        display: none!important;
    }
</style>
<header class="py-4">
	<div class="container-fluid py-2">
	  <h1 class="h3 fw-normal mb-0">region</h1>
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
				</div>-->			
				<div class="card-body">
				@if (!empty($data))
                    <form  class='g-3 align-items-center '  method="post" action="{{ route('region.update', $data->id) }}">
              	@method('PUT')
				@else
				    <form class='row g-3 align-items-center'  method="post" action="{{ route('region.store') }}">
				@endif
				@csrf
					<div class="col-lg-4">
					    <label class="<!--visually-hidden-->" for="region">Region*</label>
					    <input class="form-control" id="region" name='region' type="text" placeholder="Region" required value="{{ $data ? $data->region : old('region') }}"> 
						<p>
						@if($errors->has('region'))
							<div class="error">{{ $errors->first('region') }}</div>
						@endif
						</p>
					</div>
                    
					<div class="col-lg-4">
					    <label class="<!--visually-hidden-->" for="state">States*</label>
						@if(!empty($data))
						@php
							$selectedState =  explode(',',$data->destinations);
							
						@endphp
					    <select class="multiple-select" id="state" name="state[]" multiple="multiple" placeholder="States">
							@foreach($states as $key => $value)		
										
								<option value="{{ $value }}" {{ $selectedState ? ((in_array($value, $selectedState)) ? 'selected' : '') : '' }}>{{$value}}</option>
							@endforeach
						</select>
						@else
						<select class="multiple-select" id="state" name="state[]" multiple="multiple" placeholder="States">
							@foreach($states as $key => $value)
								<option value="{{ $value }}">{{ $value }}</option>
							@endforeach
						</select>
						@endif
						<p>
						@if($errors->has('state'))
							<div class="error">{{ $errors->first('state') }}</div>
						@endif
						</p>
					</div>
					<div class="col-lg-3">
					  <button class="btn btn-primary" type="submit">Submit</button>
					  <a href="{{ route('region.index') }}" class="btn btn-warning">Cancel</a>
						</div>
				  </form>
				</div>
			</div>
		</div>
		</div>
	</div>
  </section>
@endsection
