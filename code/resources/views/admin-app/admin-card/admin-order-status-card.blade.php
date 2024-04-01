@extends('admin-app/admin-master')
@section('title', 'Add Order Status')
@section('content')
<header class="py-4">
	<div class="container-fluid py-2">
	  <h1 class="h3 fw-normal mb-0">Order Status</h1>
	</div>
</header>
<section class="pb-5"> 
	<div class="container-fluid">
	    <div class="row">
		    <!-- Basic Form-->
		    <div class="col-lg-12">
			    <div class="card">
    				<div class="card-body">
        				@if (!empty($data))
                            <form  class='row g-3 align-items-center'  method="post" action="{{ route('order-status.update', $data->id) }}">
                             @method('PUT')
                        @else
                            <form class='row g-3 align-items-center'  method="post" action="{{ route('status.store') }}">
                        @endif
                        @csrf
    					<div class="col-lg-4">
    					    <label class="<!--visually-hidden-->" for="oder_status">Order Status*</label>
    					    <input class="form-control" id="order_status" name='order_status' type="text" placeholder="Order Status" required value="{{ $data ? $data->order_status : ''}}"> 
    						<p>
    						@if($errors->has('oder_status'))
    							<div class="error">{{ $errors->first('oder_status') }}</div>
    						@endif
    						</p>
    					</div>
    					<div class="col-lg-8"></div>
    					<div class="col-lg-3">
    					  <button class="btn btn-primary" type="submit">Submit</button>
    					    <a href="{{ route('order-status.index') }}" class="btn btn-warning">Cancel</a>
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