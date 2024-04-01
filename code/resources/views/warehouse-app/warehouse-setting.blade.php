@extends('common-app/master')
@section('title', 'Warehouse Settings')
@section('content')

<!-- Counts Section -->
<!--<section class="py-5"></section>-->
<!-- Header Section-->
<section class="bg-white">
    <section class="bg-white">
    <div class="container-fluid">
        <div class="row"> 
        
            <div class="col-xl-3 col-md-4 col-6 gy-4 gy-xl-0">
                 <a style='color: #000 !important;' href="{{ route('api-user.index') }}">
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
                </a>
            </div>
            <div class="col-xl-3 col-md-4 col-6 gy-4 gy-xl-0">
             
                <a href="{{ route('webhook.index') }}" style='color: #000 !important;'>
                    <div class="card pmd-card text-center">
                        <div class="card-body">
                            <div class="pmd-card-icon">
                                <img src="https://pro.propeller.in/assets/images/icon.svg" />
                            </div>
                            <h2 class="card-title">Webhooks</h2>
                            <p class="card-text">As mobile web usage skyrockets, we make sure we code best practice HTML for all types of devices and screen sizes.</p>	
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-md-4 col-6 gy-4 gy-xl-0">
                <a style='color: #000 !important;' href="{{ route('label.print') }}">
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
                </a>
            </div>
            <div class="col-xl-3 col-md-4 col-6 gy-4 gy-xl-0">
                <a style='color: #000 !important;' href="{{ route('invoice-settings.create') }}">
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
                </a>
            </div>
            <!--<div class="col-xl-3 col-md-4 col-6 gy-4 gy-xl-0">-->
            <!--    <a style='color: #000 !important;' href="{{ route('erp.get') }}"> -->
                <!-- Icon, Title and Description Card -->
            <!--    <div class="card pmd-card text-center">-->
            <!--        <div class="card-body">-->
            <!--            <div class="pmd-card-icon">-->
            <!--                <img src="https://pro.propeller.in/assets/images/icon.svg" />-->
            <!--            </div>-->
            <!--            <h2 class="card-title">WMS</h2>-->
            <!--            <p class="card-text">As mobile web usage skyrockets, we make sure we code best practice HTML for all types of devices and screen sizes.</p>	-->
            <!--        </div>-->
            <!--    </div>-->
            <!--    </a>-->
            <!--</div>-->
            
           
            <div class="col-xl-3 col-md-4 col-6 gy-4 gy-xl-0">
                <a style="color: #000 !important" href="{{ route('pincode.get') }}"> 
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
        </div> 
    </div>
</section>
</section>
@endsection  