@extends('admin-app/admin-master')
@section('title', 'Add Shipment Type')
@section('content')
<header class="py-4">
	<div class="container-fluid py-2">
	  <h1 class="h3 fw-normal mb-0">Shipment Types</h1>
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
              <form  class='row g-3 align-items-center'  method="post" action="{{url('update-shipmentType?type=')}}{{Crypt::encrypt($data->id)}}">
              	@method('PUT')
				@else
				<form class='row g-3 align-items-center'  method="post" action="{{ route('shipmentType.store') }}">
				@endif
				@csrf
					
					<div class="col-lg-4">
					    <label class="<!--visually-hidden-->" for="shipment_type">Shipment Type*</label>
					    <input class="form-control" id="shipment_type" name='shipment_type' type="text" placeholder="Shipment Type" required value="{{ $data ? $data->shipment_type : old('shipment_type') }}"> 
						<p>
						@if($errors->has('shipment_type'))
							<div class="error">{{ $errors->first('shipment_type') }}</div>
						@endif
						</p>
					</div>
					
				
					
					
					<div class="col-lg-3">
					  <button class="btn btn-primary" type="submit">Submit</button>
					  <a href="/shipmentType" class="btn btn-warning">Cancel</a>
						</div>
				  </form>
				</div>
			</div>
		</div>
		</div>
	</div>
  </section>
@endsection
