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
            <form  class='g-3 align-items-center'  method="post" action="{{ route('rate-card.update', $data->id) }}">
            @method('PUT')
          @else
            <form class='row g-3 align-items-center'  method="post" action="{{ route('rate-card.store') }}">
          @endif
          @csrf
          <div class="row">
            <div class="col-lg-12">
              <div class="row mb-3">
                <div class="col-lg-3 mb-2">
                  <label class="form-label" for="courier">Courier *</label>
                  <select class="form-control" id="courier" name="courier_name" required {{  $data ? ($data->courier_name ? 'disabled' : '') : '' }}>
                    <option value="">Select Courier</option>
                    @foreach($couriers as $courier)
                      <option value="{{ $courier->logistics_name }}" {{ $data ? ($data->courier_name == $courier->logistics_name ? 'selected' : '') : ''}}>{{ $courier->logistics_name }}</option>
                    @endforeach
                  </select>
                  @error('courier')
                    <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div>
                <div class="col-lg-3 mb-2">
                  <label class="form-label" for="courier_code">Courier Code*</label>
                  <input type="text" name="courier_code" id="courier_code" value="{{ $data ? $data->courier_code : '' }}" class="form-control" placeholder="Courier Code">
                  @error('courier_code')
                    <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div>
                <div class="col-lg-3 mb-2">
                  <label class="form-label" for="shipment_type">Shipment Mode *</label>
                  <select class="form-control" id="shipment_type" name="shipment_type" required {{  $data ? ($data->courier_name ? 'disabled' : '') : '' }}>
                    <option value="">Select Mode</option>
                      @foreach($shipmentTypes as $shipmentType)
                        <option value="{{ $shipmentType->shipment_type }}" {{ $data ? ($data->shipment_type == $shipmentType->shipment_type ? 'selected' : '') : ''}}>{{ $shipmentType->shipment_type }}</option>
                      @endforeach
                  </select>
                  @error('client_id')
                    <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div>
                <div class="col-lg-3 mb-2">
                  <label class="form-label" for="min_weight">Min weight (Kg)*</label>
                  <input type="text" class="form-control" value="{{ $data ? $data->min_weight : 0.5 }}" name="min_weight" readonly required>
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
                      <th class="text-center" style="width:5%!important">#<br><span class="fs" style="color:transparent">#</span></th>
                      @foreach($zones as $zone)
                      <th class="text-center">
                        <p class="p-css">{{ strtoupper($zone->zone_code) }}</p>
                        <span class="fs">{{ $zone->description }}</span>
                      </th>
                      @endforeach   
                        <th class="text-center"><p class="p-css">COD </p><span class="fs">Charges</span></th>
                        <th class="text-center"><p class="p-css">COD %</p><span class="fs"></span></th>
                        <th class="text-center"><p class="p-css">FSC % </p> <span class="fs">(Fuel Surcharge)</span></th>
                        <th class="text-center"><p class="p-css">Other Charges</p><span class="fs"></span></th>                   
                    </thead>
                    <tbody>
                   
                        @php  
                          $jsonData = $data->forward ?? '';
                          $dataArray = json_decode($jsonData, true);
                          $forward_additionalData = $data->forward_additional ?? '';
                          $forward_additionalArray = json_decode($forward_additionalData, true);
                          $reverseData = $data->reverse ?? '';
                          $reverseArray = json_decode($reverseData, true);
                        @endphp

                      
                        
                        
                        @if ($data)
                          @if($mode == 'forward')	
                          <tr>
                            <td><p class="bg-css mb-2">Forward Rate</p></td>
                            @foreach ($dataArray['forward'] as $key => $value)
                              @if ($key !== 'cod_charge' && $key !== 'cod_percent' && $key !== 'fsc_percent' && $key !== 'other_charge')
                                <td class="text-center">
                                    <input type="number" class="form-control mb-2" id="rate" value="{{ $value ?? '0.00' }}" name="forward_rate[]">
                                </td>
                              @endif
                            @endforeach
                            <td>
                              <input type="number" class="form-control mb-2" value="{{ $dataArray['forward']['cod_charge'] ?? '0.00' }}" name="cod_charge[0]" required>
                            </td>
                            <td> 
                              <input type="number" class="form-control mb-2" value="{{ $dataArray['forward']['cod_percent'] ?? '0.00' }}" name="cod_percent[0]" required>
                            </td>
                            <td> 
                              <input type="number" class="form-control mb-2" value="{{ $dataArray['forward']['fsc_percent'] ?? '0.00' }}" name="fsc_percent[0]" required>
                            </td>
                            <td>
                              <input type="number" class="form-control mb-2" value="{{ $dataArray['forward']['other_charge'] ?? '0.00' }}" name="other_charge[0]" required>
                            </td>
                          </tr>
                          @endif
                        @else
                        <tr>
                          <td><p class="bg-css mb-2">Forward Rate</p></td>
                          @foreach ($zones as $zone)
                            <td class="text-center">
                              <input type="number" class="form-control mb-2" id="rate" value="0.00" name="forward_rate[]">																		                              
                            </td>	  
                          @endforeach                           
                          <td>
                            <input type="number" class="form-control mb-2" value="0.00" name="cod_charge[0]" required>
                          </td>
                          <td> 
                            <input type="number" class="form-control mb-2" value="0.00" name="cod_percent[0]" required>
                          </td>
                          <td> 
                            <input type="number" class="form-control mb-2" value="0.00" name="fsc_percent[0]" required>
                          </td>
                          <td>
                            <input type="number" class="form-control mb-2" value="0.00" name="other_charge[0]" required>
                          </td>  
                          </tr> 
                        @endif
                        @if($data)	
                          @if($mode == 'forward_additional')	
                          <tr>
                            <td><p class="bg-css mb-2">FWD - Additional Rate</p></td>
                            @foreach($forward_additionalArray['forward_additional']  as $key => $value)		
                            @if ($key !== 'cod_charge' && $key !== 'cod_percent' && $key !== 'fsc_percent' && $key !== 'other_charge')														
                              <td class="text-center">
                                <input type="number" class="form-control mb-2" id="rate" value="{{ $value ?? '0.00' }}" name="forward_additional_rate[]">
                              </td>					
                              @endif	
                            @endforeach 
                            <td>
                              <input type="number" class="form-control mb-2" value="{{ $forward_additionalArray['forward_additional']['cod_charge'] ?? '0.00' }}" name="cod_charge[1]" required>
                            </td>
                            <td> 
                              <input type="number" class="form-control mb-2" value="{{ $forward_additionalArray['forward_additional']['cod_percent'] ?? '0.00' }}" name="cod_percent[1]" required>
                            </td>
                            <td> 
                              <input type="number" class="form-control mb-2" value="{{ $forward_additionalArray['forward_additional']['fsc_percent'] ?? '0.00' }}" name="fsc_percent[1]" required>
                            </td>
                            <td>
                              <input type="number" class="form-control mb-2" value="{{ $forward_additionalArray['forward_additional']['other_charge'] ?? '0.00' }}" name="other_charge[1]" required>
                            </td>  	
                          
                          </tr>
                        @endif  
                        @else			
                          <tr>
                            <td><p class="bg-css mb-2">FWD - Additional Rate</p></td>								
                            @foreach($zones as $zone)	                        								
                              <td class="text-center">
                                <input type="number" class="form-control mb-2" id="rate" value="0.00" name="forward_additional_rate[]">																		                              
                              </td>	                        		
                            @endforeach  
                          <td>
                            <input type="number" class="form-control mb-2" value="0.00" name="cod_charge[1]" required>
                          </td>
                          <td> 
                            <input type="number" class="form-control mb-2" value="0.00" name="cod_percent[1]" required>
                          </td>
                          <td> 
                            <input type="number" class="form-control mb-2" value="0.00" name="fsc_percent[1]" required>
                          </td>
                          <td>
                            <input type="number" class="form-control mb-2" value="0.00" name="other_charge[1]" required>
                          </td> 
                        </tr>  	
                        @endif	
                        @if($data)	
                          @if($mode == 'reverse')	
                        <tr>
                          <td><p class="bg-css mb-2">Reverse Rate</p></td>
                            @foreach($reverseArray['reverse']  as $key => $value)			
                              @if ($key !== 'cod_charge' && $key !== 'cod_percent' && $key !== 'fsc_percent' && $key !== 'other_charge')													
                                <td class="text-center">
                                  <input type="number" class="form-control mb-2" id="rate" value="{{ $value ?? '0.00' }}" name="reverse_rate[]">
                                </td>						
                              @endif
                            @endforeach 
                          <td>
                            <input type="number" class="form-control mb-2" value="{{ $reverseArray['reverse']['cod_charge'] ?? '0.00' }}" name="cod_charge[2]" required>
                          </td>
                          <td> 
                            <input type="number" class="form-control mb-2" value="{{ $reverseArray['reverse']['cod_percent'] ?? '0.00' }}" name="cod_percent[2]" required>
                          </td>
                          <td> 
                            <input type="number" class="form-control mb-2" value="{{ $reverseArray['reverse']['fsc_percent'] ?? '0.00' }}" name="fsc_percent[2]" required>
                          </td>
                          <td>
                            <input type="number" class="form-control mb-2" value="{{ $reverseArray['reverse']['other_charge'] ?? '0.00' }}" name="other_charge[2]" required>
                          </td>  
                        </tr>
                        @endif
                        @else		
                          <tr>
                            <td><p class="bg-css mb-2">FWD - Additional Rate</p></td>								
                              @foreach($zones as $zone)	                       									
                                <td class="text-center">
                                  <input type="number" class="form-control mb-2" id="rate" value="0.00" name="reverse_rate[]">																		                              
                                </td>	  	
                              @endforeach  
                            <td>
                              <input type="number" class="form-control mb-2" value="0.00" name="cod_charge[2]" required>
                            </td>
                            <td> 
                              <input type="number" class="form-control mb-2" value="0.00" name="cod_percent[2]" required>
                            </td>
                            <td> 
                              <input type="number" class="form-control mb-2" value="0.00" name="fsc_percent[2]" required>
                            </td>
                            <td>
                              <input type="number" class="form-control mb-2" value="0.00" name="other_charge[2]" required>
                            </td>  
                          </tr> 	
                        @endif	 
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
</div>
</section>

@endsection


