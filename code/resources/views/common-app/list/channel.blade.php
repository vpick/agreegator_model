@extends('common-app/master')
@section('title', 'Channel')
@section('content')
<style>
.col-lg-3 {
    -webkit-box-flex: 0;
    -ms-flex: 0 0 auto;
    flex: 0 0 auto;
    width: 20% !important;
}
.channel-img{
	width:50% !important;
	min-height: 98px !important;
}
</style>
      <!-- Counts Section -->
      <!--<section class="py-5"></section>-->
	  <!-- Header Section-->
      <section class="bg-white">
	  <div class="container-fluid">
        <div class="row align-items-stretch gy-3">
            @foreach($chanels as $chanel)
			<div class="col-lg-3">
              <!-- Income-->
              <div class="card text-center h-100 mb-0">
                <div class="card-body">
                  <img src='{{ $chanel->chanel_logo }}' class='channel-img rounded-circle' alt='{{ $chanel->chanel_name }}'>
                  <p class="text-gray-700 display-6">{{ $chanel->chanel_name }}</p>
                  <p class="text-primary h2 fw-bold">
				    <div class="btn-group">
					  <input type="radio" class="btn-check" name="options" id="option1" autocomplete="off" checked />
					  <label class="btn btn-secondary" for="option1">Active</label>
					  <input type="radio" class="btn-check" name="options" id="option2" autocomplete="off" />
					  <label class="btn btn-secondary" for="option2">Inactive</label>
					</div>
				  </p>
					<!--<p class="text-primary h2 fw-bold">Shopify</p>
                    <p class="text-xs text-gray-600 mb-0">Lorem ipsum dolor sit amet, consectetur adipisicing elit sed do.</p>-->
                </div>
              </div>
            </div>
			@endforeach
		</div>	
	  </div>
      </section>
@endsection