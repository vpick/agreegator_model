@extends('admin-app/admin-master')
@section('title', 'Add Weight')
@section('content')
<header class="py-4">
	<div class="container-fluid py-2">
	  <h1 class="h3 fw-normal mb-0">Weight</h1>
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
                    <form  class='g-3 align-items-center '  method="post" action="{{ route('weight-range.update', $data->id) }}">
              	@method('PUT')
				@else
				    <form class='row g-3 align-items-center'  method="post" action="{{ route('weight-range.store') }}">
				@endif
				@csrf
					
					<div class="col-lg-3">
					    <label class="<!--visually-hidden-->" for="min_weight">Min Weight*</label>
					    <input class="form-control" id="min_weight" name='min_weight' type="text" placeholder="Min Weight" required value="{{ $data ? $data->min : old('min_weight') }}"> 
						<p>
						@if($errors->has('min_weight'))
							<div class="error">{{ $errors->first('min_weight') }}</div>
						@endif
						</p>
					</div>
					<div class="col-lg-3">
					    <label class="<!--visually-hidden-->" for="operator">Operator*</label>
					    <input class="form-control" id="operator" name='operator' type="text" placeholder="operator" required value="{{ $data ? $data->operator : old('operator') }}"> 
						<p>
						@if($errors->has('operator'))
							<div class="error">{{ $errors->first('operator') }}</div>
						@endif
						</p>
					</div>
					<div class="col-lg-3">
					    <label class="<!--visually-hidden-->" for="max_weight">Max Weight*</label>
						
						@php
						if($data){
							if($data->operator == 'above'){
								$max = '';
							}
							else{
								$max = $data->max;
							}
						}
						@endphp
					    <input class="form-control" id="max_weight" name='max_weight' type="text" placeholder="Max Weight" value="{{ $data ? $max : old('max_weight') }}"> 
						<p>
						@if($errors->has('max_weight'))
							<div class="error">{{ $errors->first('max_weight') }}</div>
						@endif
						</p>
					</div>
					<div class="col-lg-3">
					    <label class="<!--visually-hidden-->" for="description">Description*</label>
					    <input class="form-control" id="description" name='description' type="text" placeholder="Description" required value="{{ $data ? $data->description : old('description') }}"> 
						<p>
						@if($errors->has('description'))
							<div class="error">{{ $errors->first('description') }}</div>
						@endif
						</p>
					</div>
					<div class="col-lg-3">
					  <button class="btn btn-primary" type="submit">Submit</button>
					  <a href="{{ route('weight-range.index') }}" class="btn btn-warning">Cancel</a>
						</div>
				  </form>
				</div>
			</div>
		</div>
		</div>
	</div>
  </section>
@endsection
