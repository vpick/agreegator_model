@extends('admin-app/admin-master')
@section('title', 'App Settings')
@section('content')
<!-- Counts Section -->
<!--<section class="py-5"></section>-->
<!-- Header Section-->
<section class="bg-white">
    <div class="container-fluid">
        <div class="row">
	        <div class="col-xl-3 col-md-4 col-6 gy-4 gy-xl-0">
                <a style="color: #000 !important" href="/pincode-master"> 
                <!-- Icon, Title and Description Card -->               
                <div class="card pmd-card text-center">
                    <div class="card-body">
                        <div class="pmd-card-icon">
                            <img src="https://pro.propeller.in/assets/images/icon.svg" />
                        </div>
                        <h2 class="card-title">Pincode</h2>
                        <p class="card-text">As mobile web usage skyrockets, we make sure we code best practice HTML for all types of devices and screen sizes.</p>	
                    </div>
                </div>
                </a>
            </div>           
            <div class="col-xl-3 col-md-4 col-6 gy-4 gy-xl-0">
                <a style="color: #000 !important" href="{{ route('zone.index') }}"> 
                <!-- Icon, Title and Description Card -->
                <div class="card pmd-card text-center">
                    <div class="card-body">
                        <div class="pmd-card-icon">
                            <img src="https://pro.propeller.in/assets/images/icon.svg" />
                        </div>
                        <h2 class="card-title">Zone</h2>
                        <p class="card-text">As mobile web usage skyrockets, we make sure we code best practice HTML for all types of devices and screen sizes.</p>	
                    </div>
                </div>
                </a>
            </div>
			<div class="col-xl-3 col-md-4 col-6 gy-4 gy-xl-0" style="display:none">
                <a style="color: #000 !important" href="{{ route('region.index') }}"> 
                <!-- Icon, Title and Description Card -->
                <div class="card pmd-card text-center">
                    <div class="card-body">
                        <div class="pmd-card-icon">
                            <img src="https://pro.propeller.in/assets/images/icon.svg" />
                        </div>
                        <h2 class="card-title">Region</h2>
                        <p class="card-text">As mobile web usage skyrockets, we make sure we code best practice HTML for all types of devices and screen sizes.</p>	
                    </div>
                </div>
                </a>
            </div>
            <div class="col-xl-3 col-md-4 col-6 gy-4 gy-xl-0">
                <a style="color: #000 !important" href="/shipmentType"> 
                <!-- Icon, Title and Description Card -->
                <div class="card pmd-card text-center">
                    <div class="card-body">
                        <div class="pmd-card-icon">
                            <img src="https://pro.propeller.in/assets/images/icon.svg" />
                        </div>
                        <h2 class="card-title">Shipment Mode</h2>
                        <p class="card-text">As mobile web usage skyrockets, we make sure we code best practice HTML for all types of devices and screen sizes.</p>	
                    </div>
                </div>
                </a>
            </div>
            <div class="col-xl-3 col-md-4 col-6 gy-4 gy-xl-0">
                <a style="color: #000 !important" href="{{ route('rate-card.index') }}"> 
                <!-- Icon, Title and Description Card -->
                <div class="card pmd-card text-center">
                    <div class="card-body">
                        <div class="pmd-card-icon">
                            <img src="https://pro.propeller.in/assets/images/icon.svg" />
                        </div>
                        <h2 class="card-title">Rate Card</h2>
                        <p class="card-text">As mobile web usage skyrockets, we make sure we code best practice HTML for all types of devices and screen sizes.</p>	
                    </div>
                </div>
                </a>
            </div>
        </div> 
    </div>
</section>
@endsection  