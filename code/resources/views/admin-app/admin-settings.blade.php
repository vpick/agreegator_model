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
                <a style="color: #000 !important" href="{{ route('company.index') }}"> 
                <!-- Icon, Title and Description Card -->               
                <div class="card pmd-card text-center">
                    <div class="card-body">
                        <div class="pmd-card-icon">
                            <img src="https://pro.propeller.in/assets/images/icon.svg" />
                        </div>
                        <h2 class="card-title">Brand Profile </h2>
                        <p class="card-text">As mobile web usage skyrockets, we make sure we code best practice HTML for all types of devices and screen sizes.</p>	
                    </div>
                </div>
                </a>
            </div>           
            <div class="col-xl-3 col-md-4 col-6 gy-4 gy-xl-0">
                <a style="color: #000 !important" href="{{ route('app-client.index') }}"> 
                <!-- Icon, Title and Description Card -->
                <div class="card pmd-card text-center">
                    <div class="card-body">
                        <div class="pmd-card-icon">
                            <img src="https://pro.propeller.in/assets/images/icon.svg" />
                        </div>
                        <h2 class="card-title">Client</h2>
                        <p class="card-text">As mobile web usage skyrockets, we make sure we code best practice HTML for all types of devices and screen sizes.</p>	
                    </div>
                </div>
                </a>
            </div>
            <div class="col-xl-3 col-md-4 col-6 gy-4 gy-xl-0">
                <a style='color: #000 !important;' href="{{ route('app-warehouse.index') }}"> 
                <!-- Icon, Title and Description Card -->
                <div class="card pmd-card text-center">
                    <div class="card-body">
                        <div class="pmd-card-icon">
                            <img src="https://pro.propeller.in/assets/images/icon.svg" />
                        </div>
                        <h2 class="card-title">Warehouse</h2>
                        <p class="card-text">As mobile web usage skyrockets, we make sure we code best practice HTML for all types of devices and screen sizes.</p>	
                    </div>
                </div>
                </a>
            </div>
            <div class="col-xl-3 col-md-4 col-6 gy-4 gy-xl-0">
                <a style='color: #000 !important;' href="{{ route('app-user.index') }}">
                <!-- Icon, Title and Description Card -->
                <div class="card pmd-card text-center">
                    <div class="card-body">
                        <div class="pmd-card-icon">
                            <img src="https://pro.propeller.in/assets/images/icon.svg" />
                        </div>
                        <h2 class="card-title">Users</h2>
                        <p class="card-text">As mobile web usage skyrockets, we make sure we code best practice HTML for all types of devices and screen sizes.</p>	
                    </div>
                </div>
                </a>
            </div>
        	<div class="col-xl-3 col-md-4 col-6 gy-4 gy-xl-0">
        	   <a style='color: #000 !important;' href="/app-chanels">
        		<!-- Icon, Title and Description Card -->
        		<div class="card pmd-card text-center">
        			<div class="card-body">
        				<div class="pmd-card-icon">
        					<img src="https://pro.propeller.in/assets/images/icon.svg" />
        				</div>
        				<h2 class="card-title">Channel</h2>
        				<p class="card-text">As mobile web usage skyrockets, we make sure we code best practice HTML for all types of devices and screen sizes.</p>	
        			</div>
        		</div>
        		</a>	
            </div>
        	<div class="col-xl-3 col-md-4 col-6 gy-4 gy-xl-0">
        		<a style='color: #000 !important;' href="/app-partners">
        		<!-- Icon, Title and Description Card -->
        		<div class="card pmd-card text-center">
        			<div class="card-body">
        				<div class="pmd-card-icon">
        					<img src="https://pro.propeller.in/assets/images/icon.svg" />
        				</div>
        				<h2 class="card-title">Logistics Partners</h2>
        				<p class="card-text">As mobile web usage skyrockets, we make sure we code best practice HTML for all types of devices and screen sizes.</p>	
        			</div>
        		</div>
        		</a>
            </div>
        	<div class="col-xl-3 col-md-4 col-6 gy-4 gy-xl-0">
        		<a style='color: #000 !important;' href="/app-aggrigators">
        		<!-- Icon, Title and Description Card -->
        		<div class="card pmd-card text-center">
        			<div class="card-body">
        				<div class="pmd-card-icon">
        					<img src="https://pro.propeller.in/assets/images/icon.svg" />
        				</div>
        				<h2 class="card-title">Aggrigators Partners</h2>
        				<p class="card-text">As mobile web usage skyrockets, we make sure we code best practice HTML for all types of devices and screen sizes.</p>	
        			</div>
        		</div>
        		</a>
            </div>
        	<div class="col-xl-3 col-md-4 col-6 gy-4 gy-xl-0">
        		<!-- Icon, Title and Description Card -->
        		<div class="card pmd-card text-center">
        			<div class="card-body">
        				<div class="pmd-card-icon">
        					<svg class="svg-icon svg-icon-big theme-solid-2 text-secondary"><use xlink:href="#iphone-1"></use></svg>
        				</div>
        				<h2 class="card-title">Api</h2>
        				<p class="card-text">As mobile web usage skyrockets, we make sure we code best practice HTML for all types of devices and screen sizes.</p>	
        			</div>
        		</div>
            </div>
        	<div class="col-xl-3 col-md-4 col-6 gy-4 gy-xl-0">
        		<!-- Icon, Title and Description Card -->
        		<div class="card pmd-card text-center">
        			<div class="card-body">
        				<div class="pmd-card-icon">
        					<img src="https://pro.propeller.in/assets/images/icon.svg" />
        				</div>
        				<h2 class="card-title">Webhooks</h2>
        				<p class="card-text">As mobile web usage skyrockets, we make sure we code best practice HTML for all types of devices and screen sizes.</p>	
        			</div>
        		</div>
            </div>
        	<div class="col-xl-3 col-md-4 col-6 gy-4 gy-xl-0">
        		<!-- Icon, Title and Description Card -->
        		<div class="card pmd-card text-center">
        			<div class="card-body">
        				<div class="pmd-card-icon">
        					<img src="https://pro.propeller.in/assets/images/icon.svg" />
        				</div>
        				<h2 class="card-title">Label Settings</h2>
        				<p class="card-text">As mobile web usage skyrockets, we make sure we code best practice HTML for all types of devices and screen sizes.</p>	
        			</div>
        		</div>
            </div>
        	<div class="col-xl-3 col-md-4 col-6 gy-4 gy-xl-0">
        		<!-- Icon, Title and Description Card -->
        		<div class="card pmd-card text-center">
        			<div class="card-body">
        				<div class="pmd-card-icon">
        					<img src="https://pro.propeller.in/assets/images/icon.svg" />
        				</div>
        				<h2 class="card-title">Invoice Settings</h2>
        				<p class="card-text">As mobile web usage skyrockets, we make sure we code best practice HTML for all types of devices and screen sizes.</p>	
        			</div>
        		</div>
            </div>
        	<div class="col-xl-3 col-md-4 col-6 gy-4 gy-xl-0">
        		<!-- Icon, Title and Description Card -->
        		<div class="card pmd-card text-center">
        			<div class="card-body">
        				<div class="pmd-card-icon">
        					<img src="https://pro.propeller.in/assets/images/icon.svg" />
        				</div>
        				<h2 class="card-title">Account Settings</h2>
        				<p class="card-text">As mobile web usage skyrockets, we make sure we code best practice HTML for all types of devices and screen sizes.</p>	
        			</div>
        		</div>
            </div>
            <div class="col-xl-3 col-md-4 col-6 gy-4 gy-xl-0">
                <a style='color: #000 !important;' href="{{ route('app-erp.index') }}">
                <!-- Icon, Title and Description Card -->
                <div class="card pmd-card text-center">
                    <div class="card-body">
                        <div class="pmd-card-icon">
                            <img src="https://pro.propeller.in/assets/images/icon.svg" />
                        </div>
                        <h2 class="card-title">WMS</h2>
                        <p class="card-text">As mobile web usage skyrockets, we make sure we code best practice HTML for all types of devices and screen sizes.</p>	
                    </div>
                </div>
                </a>
            </div>
            <div class="col-xl-3 col-md-4 col-6 gy-4 gy-xl-0">
                <a style='color: #000 !important;' href="{{ route('order-status.index') }}">
                <!-- Icon, Title and Description Card -->
                <div class="card pmd-card text-center">
                    <div class="card-body">
                        <div class="pmd-card-icon">
                            <img src="https://pro.propeller.in/assets/images/icon.svg" />
                        </div>
                        <h2 class="card-title">Status Master</h2>
                        <p class="card-text">As mobile web usage skyrockets, we make sure we code best practice HTML for all types of devices and screen sizes.</p>	
                    </div>
                </div>
                </a>
            </div>
            <div class="col-xl-3 col-md-4 col-6 gy-4 gy-xl-0">
                <a style="color: #000 !important" href="{{ route('weight-range.index') }}"> 
                <!-- Icon, Title and Description Card -->
                <div class="card pmd-card text-center">
                    <div class="card-body">
                        <div class="pmd-card-icon">
                            <img src="https://pro.propeller.in/assets/images/icon.svg" />
                        </div>
                        <h2 class="card-title">Weight Master</h2>
                        <p class="card-text">As mobile web usage skyrockets, we make sure we code best practice HTML for all types of devices and screen sizes.</p>	
                    </div>
                </div>
                </a>
            </div>
        </div> 
    </div>
</section>
@endsection  