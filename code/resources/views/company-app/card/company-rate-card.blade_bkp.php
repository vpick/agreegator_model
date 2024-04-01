@extends('common-app/master')
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
                            <form  class='g-3 align-items-center'  method="post" action="{{ route('rate-card.update', $data->id) }}">
                            @method('PUT')
                        @else
                            <form class='row g-3 align-items-center'  method="post" action="{{ route('rate-card.store') }}">
                        @endif
                        @csrf
                        
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="row mb-3">
                                    <div class="col-lg-2 mb-2">
                                        <label class="form-label" for="logistics_type">Logistics Type *</label>
                                        <select class="form-control" id="logistics_type" name="logistics_type" required>
                                            <option value="">Select Type</option>
                                            @foreach($types as $type)
                                            <option value="{{ $type->logistics_type }}" {{ $data ? ($data->logistics_type == $type->logistics_type ? 'selected' : '') : ''}}>{{ $type->logistics_type }}</option>
                                            @endforeach
                                        </select>
                                        @error('courier')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-lg-2 mb-2" id="logistics_partner_div">
                                        <label class="form-label" for="logistics_partner">Aggregators *</label>
                                        <select class="form-control" id="logistics_partner" name="logistics_partner">
                                            @if($data)
                                                @foreach($aggregators as $aggr)
                                                    <option value="{{ $aggr->logistics_name }}" {{ $data ? ($data->logistics_name == $aggr->logistics_name ? 'selected' : '') : ''}}>{{ $aggr->logistics_name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('courier')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-lg-2 mb-2"  id="courier_id_div" >
                                        <label class="form-label" for="courier_id">Couriers *</label>
                                        <select class="form-control" id="courier_id" name="courier_id" required>
                                            
                                            @if($data)
                                                @foreach($couriers as $cour)
                                                    <option value="{{ $cour->logistics_name }}" {{ $data ? ($data->courier == $cour->logistics_name ? 'selected' : '') : ''}}>{{ $cour->logistics_name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('courier')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                   <div class="col-lg-2 mb-2">
                                        <label class="form-label" for="courier_name">Courier Name *</label>
                                        <input type="text" class="form-control" value="{{ $data ? $data->courier_name : '' }}" name="courier_name" required>
                                        @error('courier')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-2 mb-2">
                                        <label class="form-label" for="shipment_type">Shipment Mode *</label>
                                       
                                        <select class="form-control" id="shipment_type" name="shipment_type" required >
                                            <option value="">Select Mode</option>
                                            @foreach($shipmentTypes as $shipmentType)
                                                <option value="{{ $shipmentType->shipment_type }}" {{ $data ? ($data->shipment_mode == $shipmentType->shipment_type ? 'selected' : '') : ''}}>{{ $shipmentType->shipment_type }}</option>
                                            @endforeach
                                        </select>
                                        @error('shipment_type')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-2 mb-2">
                                        <label class="form-label" for="min_weight">Least weight (Kg)*</label>
                                        <input type="text" class="form-control" value="{{ $data ? $data->min_weight : 0.00 }}" name="min_weight" required>
                                        @error('min_weight')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-2 mb-2">
                                        <label class="form-label" for="additional_weight">Additional weight (Kg)*</label>
                                        <input type="text" class="form-control" value="{{ $data ? $data->additional_weight : 0.00 }}" name="additional_weight" required>
                                        @error('additional_weight')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-lg-2 mb-2">
                                        <label class="form-label" for="cod_charge">COD Charge*</label>
                                        <input type="text" class="form-control" value="{{ $data ? $data->cod : 0.00 }}" name="cod_charge" required>
                                        @error('min_weight')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-2 mb-2">
                                        <label class="form-label" for="cod_percent">Cod %*</label>
                                        <input type="text" class="form-control" value="{{ $data ? $data->cod_percent : 0.00 }}" name="cod_percent" required>
                                        @error('min_weight')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div> 
                            </div>
                            <div class="col-lg-12">                 
                                <div class="row py-3">
                                    <div class="table-responsive" style="border: 1px solid #e4e4e4;">                  
                                        <table class="table" style="margin-bottom: 5px!important;">
                                            <thead class="thead-dark">
                                            <th class="text-center" style="width:2%!important">#<br><span class="fs" style="color:transparent">#</span></th>
                                            @foreach($zones as $zone)
                                            <th class="text-center">
                                                <p class="p-css">{{ strtoupper($zone->zone_code) }}</p>
                                                <span class="fs">{{ $zone->description }}</span>
                                            </th>
                                            @endforeach   
                                                <!-- <th class="text-center"><p class="p-css">COD </p><span class="fs">Charges</span></th>
                                                <th class="text-center"><p class="p-css">COD %</p><span class="fs"></span></th>                -->
                                            </thead>
                                            <tbody>
                                                @php  
                                                $jsonData = $data->forward ?? '';
                                                $dataArray = json_decode($jsonData, true) ?? '';
                                                $forward_additionalData = $data->forward_additional ?? '';
                                                $forward_additionalArray = json_decode($forward_additionalData, true);
                                                $reverseData = $data->reverse ?? '';
                                                $reverseArray = json_decode($reverseData, true);
                                                $dtoData = $data->dto ?? '';
                                                $dtoArray = json_decode($dtoData, true);
                                                @endphp
                                                <tr>
                                                    <td><p class="bg-css mb-2">Forward Rate</p></td>
                                                    @if($dataArray)
                                                        @foreach ($dataArray['forward'] as $key => $value)
                                                        <td class="text-center">
                                                            <input type="text" class="form-control mb-2" id="forward_rate" value="{{ $value ?? '0.00' }}" name="forward_rate[]">
                                                        </td> 
                                                        @endforeach
                                                    @else
                                                        @foreach($zones as $zone)
                                                        <td class="text-center">
                                                            <input type="text" class="form-control mb-2" id="forward_rate" value="{{ '0.00' }}" name="forward_rate[]">
                                                        </td> 
                                                    @endforeach
                                                    @endif
                                                <tr>
                                                    <td><p class="bg-css mb-2">FWD - Additional Rate</p></td>
                                                    @if($dataArray)
                                                        @foreach($forward_additionalArray['forward_additional']  as $key => $value)													
                                                            <td class="text-center">
                                                                <input type="text" class="form-control mb-2" id="forward_additional_rate" value="{{ $value ?? '0.00' }}" name="forward_additional_rate[]">
                                                            </td>					
                                                        @endforeach 
                                                    @else
                                                    @foreach($zones as $zone)
                                                        <td class="text-center">
                                                            <input type="text" class="form-control mb-2" id="forward_additional_rate" value="{{'0.00' }}" name="forward_additional_rate[]">
                                                        </td>
                                                        @endforeach
                                                    @endif
                                                <tr>
                                                    <td><p class="bg-css mb-2">Reverse Rate</p></td>
                                                    @if($reverseArray)
                                                        @foreach($reverseArray['reverse']  as $key => $value)			                                                                               
                                                            <td class="text-center">
                                                                <input type="text" class="form-control mb-2" id="reverse_rate" value="{{ $value ?? '0.00' }}" name="reverse_rate[]">
                                                            </td>						
                                                        @endforeach 
                                                    @else
                                                    @foreach($zones as $zone)
                                                        <td class="text-center">
                                                            <input type="text" class="form-control mb-2" id="reverse_rate" value="{{ '0.00' }}" name="reverse_rate[]">
                                                        </td>
                                                        @endforeach  
                                                    @endif
                                                </tr>  
                                                <tr>
                                                    <td><p class="bg-css mb-2">DTO Rate</p></td>
                                                    @if($reverseArray)
                                                        @foreach($dtoArray['dto']  as $key => $value)			                                                                               
                                                            <td class="text-center">
                                                                <input type="text" class="form-control mb-2" id="dto_rate" value="{{ $value ?? '0.00' }}" name="dto_rate[]">
                                                            </td>						
                                                        @endforeach 
                                                    @else
                                                    @foreach($zones as $zone)
                                                        <td class="text-center">
                                                            <input type="text" class="form-control mb-2" id="dto_rate" value="{{ '0.00' }}" name="dto_rate[]">
                                                        </td>
                                                        @endforeach  
                                                    @endif
                                                </tr>  
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <button class="btn btn-primary" type="submit">Submit</button>
                                <a href="{{ route('rate-card.index') }}" class="btn btn-danger">Cancel</a>
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
        $('#courier_id_div').hide(); 
        $('#logistics_partner_div').hide();  

        // Function to update the visibility of elements based on the selected logistics type
        function updateVisibility(logisticsType) {
            if (logisticsType == 'Aggrigator') {
                $('#logistics_partner_div').show();       
                $('#courier_id_div').show(); 
            } else {
                $('#logistics_partner').val('');   
                $('#logistics_partner_div').hide();
                $('#courier_id_div').show(); 
            }
        }

        // Initial visibility based on the selected logistics type on page load
        updateVisibility($('#logistics_type').val());
       
        $('#logistics_type').on('change', function() {
            var parentID = $(this).val();
            if (parentID) {             
                $.ajax({
                    url: '/load/logistics_partner/' + parentID,
                    type: 'GET',
                    success: function(res) { 
                        console.log(res.datagr);   
                        $('#logistics_partner').empty();    
                        var content = '';
                        if (parentID == 'Aggrigator') {
                            content += `<option value="">Select Aggregator</option>`;
                            $.each(res.data.aggr, function(index, val) {                        
                                content += `<option value="${val['logistics_name']}">${val['logistics_name']}</option>`;
                            });
                            $('#logistics_partner_div').show();
                            $('#courier_id_div').show();  
                        } else {
                            content += `<option value="">Select Courier</option>`;
                            $('#logistics_partner_div').hide();
                        }
                        $('#logistics_partner').append(content);

                        // Update the visibility based on the selected logistics type
                        updateVisibility(parentID);

                        // Populate courier options
                        $('#courier_id').empty();    
                        var cont = `<option value="">Select Courier</option>`;
                        $.each(res.data.cour, function(index, val) {                        
                            cont += `<option value="${val['logistics_name']}">${val['logistics_name']}</option>`;
                        });
                        $('#courier_id').append(cont);
                    },
                    error: function(res) {
                        console.log(res.error);
                    }
                });    
            }    
        });
        // $('#courier_id').on('change', function() {
        //     var parentID = $(this).val();
        //     console.log(parentID);
        //     if (parentID) {             
        //         $.ajax({
        //             url: '/load/dsp-zone/' + parentID,
        //             type: 'GET',
        //             success: function(res) { 
        //                 console.log(res.data);   
        //                  var content = '';
                        
                        
        //                 // Populate courier options
        //                 $('#zone_list').empty();    
                        
        //                 $.each(res.data, function(index, val) {                        
        //                     content += `<th class="text-center"><p class="p-css">${val['zone_code']}</p><span class="fs">${val['description']}</span></th>`;
        //                 });
                        
        //                 //$('#zone_list').after(content)
                        
        //             },
        //             error: function(res) {
        //                 console.log(res.error);
        //             }
        //         });    
        //     }    
        // });
    });
</script>

@endsection


