@extends('admin-app.admin-master')
@section('title', 'Add Rate Card')
@section('content')
<style>
  .fs{
    font-size: 11px;
    color: #dbd9d9;
    font-weight: 400;
    padding-bottom: 5px;

  }
  .bg-css{
    background: cornflowerblue;
    color: white;
    padding: 10px 0px 10px 10px;
    font-size: 12px;
    text-transform: uppercase;
  }
  .table thead th { 
    width: 4%;
    font-size:12px
  }
  .thead-dark{
    background: black;
    color: white;
  }
  .p-css{
    margin-top: 8px;
    margin-bottom: 5px;
  }
  .table {
    border-collapse: separate;
    border-spacing: 0 20px;
}
.x-css{
  padding-top: 25px;
}
.table > :not(caption) > * > * {
    padding: 0.1rem 0.1rem;
    background-color: var(--bs-table-bg);
    border-bottom-width: 0px;
    -webkit-box-shadow: inset 0 0 0 9999px var(--bs-table-accent-bg);
    box-shadow: inset 0 0 0 9999px var(--bs-table-accent-bg);
}
</style>
<header class="py-4">
    <div class="container-fluid py-2">
        <h1 class="h3 fw-normal mb-0">Rate Card</h1>
    </div>
</header>
<!-- Forms Section-->
<section class="pb-5"> 
    <div class="container-fluid">
        <div class="row">
            <!-- Basic Form-->
            <div class="col-lg-12">
                <div class="card">
                    <!--<div class="card-header border-bottom">
                        <h3 class="h4 mb-0">Basic Form</h3>
                    </div>-->
                    <div class="card-body">
                        @if (!empty($data))
                            <form  class='g-3 align-items-center'  method="post" action="{{ route('update_b2b', $data->id) }}">
                            @method('PUT')
                        @else
                            <form class='row g-3 align-items-center'  method="post" action="{{ route('store_b2b') }}">
                         @endif 
                        @csrf
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="row mb-3">
                                    <div class="col-lg-3 mb-2">
                                        <label class="form-label" for="origin">Origin *</label>
                                        <select class="form-control" id="origin" name="origin" required>
                                            <option value="">Select Origin</option>
                                            @foreach($regions as $region)
                                            <option value="{{ $region->region }}" {{ $data ? ($data->region == $region->region ? 'selected' : '') : (old('origin') ? 'selected': '')}}>{{ $region->region }}</option>
                                            @endforeach
                                        </select>
                                        @error('origin')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-3 mb-2">
                                        <label class="form-label" for="region">Region *</label>
                                        <select class="form-control" id="region" name="region" required>
                                            <option value="">Select Region</option>
                                            @foreach($regions as $region)
                                            <option value="{{ $region->region }}" {{ $data ? ($data->region == $region->region ? 'selected' : '') : (old('region') ? 'selected': '')}}>{{ $region->region }}</option>
                                            @endforeach
                                        </select>
                                        @error('origin')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-3 mb-2">
                                        <label class="form-label" for="destination">Destinations *</label>
                                        <input type="text" class="form-control" value="{{ $data ? $data->destinations : old('destination')}}" name="destination" id="destination" required>
                                        @error('destination')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-lg-3 mb-2" >
                                        <label class="form-label" for="courier">Couriers *</label>
                                        <select class="form-control" id="courier" name="courier" required>
                                            <option value="">Select Courier</option>
                                                @foreach($couriers as $courier)
                                                <option value="{{ $courier->logistics_name }}" {{ $data ? ($data->courier == $courier->logistics_name ? 'selected' : '') : (old('courier') ? 'selected': '')}}>{{ $courier->logistics_name }}</option>
                                                @endforeach
                                            </select>
                                        @error('courier')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                   
                                    <div class="col-lg-3 mb-2">
                                        <label class="form-label" for="courier_charge">Courier Charge *</label>
                                        <input type="text" class="form-control" value="{{ $data ? $data->courier_charge : old('courier_charge') }}" name="courier_charge"  required>
                                        @error('courier_charge')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-3 mb-2">
                                        <label class="form-label" for="docket_charge">LR/Docket Charges(Rs) *</label>
                                        <input type="text" class="form-control" value="{{ $data ? $data->docket_charge :  old('docket_charge') }}" name="docket_charge" required>
                                        @error('docket_charge')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-3 mb-2">
                                        <label class="form-label" for="fuel_surcharge">Fuel Surcharge(%)*</label>
                                        <input type="text" class="form-control" value="{{ $data ? $data->fuel_surcharge :  old('fuel_surcharge') }}" name="fuel_surcharge" required>
                                        @error('fuel_surcharge')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-3 mb-2">
                                        <label class="form-label" for="fov">Fov-Owner RIsk*</label>
                                        @php
                                        $fov = $data->fov_owner_risk ?? '';
                                        $dt = json_decode($fov, true);
                                        if (isset($dt['fov_charge'][0]['fov_min_charge'])) {
                                            $fovMinCharge = $dt['fov_charge'][0]['fov_min_charge'];
                                            
                                        } else {
                                            
                                        }
                                        if (isset($dt['fov_charge'][0]['fov_percent'])) {
                                            $fovMinPercent = $dt['fov_charge'][0]['fov_percent'];
                                           
                                        } else {
                                            
                                        }
                                        @endphp
                                        <div class="row">
                                            <div class="col-lg-6 mb-2">                                               
                                                <input type="text" class="form-control" value="{{ $fovMinCharge ?? old('fov_min_charge') }}" name="fov_min_charge" placeholder="FOV min charge (Rs)" required>
                                            </div>
                                            <div class="col-lg-6 mb-2">
                                                <input type="text" class="form-control" value="{{ $fovMinPercent ?? old('fov_percent') }}" name="fov_percent" placeholder="FOV percent (%)" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 mb-2">
                                        <label class="form-label" for="min_chargable_weight">Min. Chargable Weight(Kg/LR)*</label>
                                        <input type="text" class="form-control" value="{{ $data ? $data->min_chargable_weight : old('min_chargable_weight') }}" name="min_chargable_weight" required>
                                        @error('min_chargable_weight')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-3 mb-2">
                                        <label class="form-label" for="min_chargable_amount">Min. Chargable Amount(per LR)*</label>
                                        <input type="text" class="form-control" value="{{ $data ? $data->min_chargable_amount : old('min_chargable_amount') }}" name="min_chargable_amount" required>
                                        @error('min_chargable_weight')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-3 mb-2">
                                        <label class="form-label" for="volumetric_weight">Volumetric Weight(cft)*</label>
                                        <input type="text" class="form-control" value="{{ $data ? $data->volumetric_weight : old('volumetric_weight') }}" name="volumetric_weight" required>
                                        @error('volumetric_weight')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div> 
                            </div>
                           
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <button class="btn btn-primary" type="submit">Submit</button>
                                <a href="{{ route('b2b_list') }}" class="btn btn-danger">Cancel</a>
                            </div> 
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</section>
<script type="text/javascript">
    $(document).ready(function() {
        
      //get client
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#region').on('change',function(){
            var parentID = $(this).val();
            if(parentID){             
                $.ajax({
                    url:'/load/destination/'+parentID,
                    type:'GET',
                    success:function(res){ 
                    console.log(res.data[0].destinations);                    
                        $('#destination').empty();    
                        $('#destination').val(res.data[0].destinations);     
                        $('#destination').attr('title',res.data[0].destinations);   
                    },
                    error:function(res) {
                        console.log(res.error);
                    }
                });    
            }    
        });
    });
    //get warehouse
   
</script>
@endsection


