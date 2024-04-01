@extends('common-app/master')
@section('title', 'Rule Allocation')
@section('content')
<!-- <header class="py-4">
  <div class="container-fluid py-2">
    <h1 class="h3 fw-normal mb-0">Client details</h1>
  </div>
</header> -->
<style>
  .nav-tabs {
    border-bottom: 1px solid #e3ebf6;
}
.list-group {
    display: flex;
    flex-direction: column;
    margin-bottom: 0;
    padding-left: 0;
}
.list-group-item {
    position: relative;
    display: block;
    margin-bottom: -1px;
    padding: 0.75rem 1.25rem;
    border: 1px solid #dce4ec;
    background-color: #fff;
}
.list-group-item-action {
    width: 100%;
    text-align: inherit;
    color: #12263f;
}
.p-all-10 {
    padding: 10px;
}

.m-b-10 {
    margin-bottom: 10px;
}
.border-bottom {
    border-bottom: 1px solid #dce4ec!important;
}

element.style {
}
.card {
    transition: box-shadow ease .2s;
    box-shadow: 0 25px 50px rgba(8,21,66,.06);
}

.card {
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    border: 0 solid rgba(0,0,0,.125);
    border-radius: 0.25rem;
    background-color: #fff;
    background-clip: border-box;
}
.card .card-header {
    border-bottom: 0;
}
.bg-gray-300 {
    background-color: #e3ebf6;
}
.card-header {
    margin-bottom: 0;
    padding: 0.75rem 1.25rem;
    border-bottom: 0 solid rgba(0,0,0,.125);
    background-color: transparent;
}
.card .card-body {
    padding: 0.75rem 1.25rem;
}
.card-body {
    padding: 1.25rem;
    flex: 1 1 auto;
}
.form-row>.col, .form-row>[class*=col-] {
    padding-right: 5px;
    padding-left: 5px;
}

.custom-control {
    position: relative;
    display: block;
    min-height: 1.35rem;
    padding-left: 1.5rem;
}
input[type=checkbox], input[type=radio] {
    box-sizing: border-box;
    padding: 0;
}

.custom-control-input {
    position: absolute;
    z-index: -1;
    opacity: 0;
}
.custom-control-input:disabled~.custom-control-label {
    color: #95aac9;
}
.custom-control-label {
    position: relative;
    margin-bottom: 0;
    vertical-align: top;
}
label {
    display: inline-block;
    margin-bottom: 0.5rem;
}


.m-t-10 {
    margin-top: 10px;
}

.border-top {
    border-top: 1px solid #dce4ec!important;
}
.p-l-30 {
    padding-left: 30px;
}

.p-t-20 {
    padding-top: 20px;
}
.border-top {
    border-top: 1px solid #dce4ec!important;
}
.form-row {
    display: flex;
    margin-right: -5px;
    margin-left: -5px;
    flex-wrap: wrap;
}
.bg-gray-300 {
    background-color: #e3ebf6;
}

.m-b-10 {
    margin-bottom: 10px;
}
.form-group {
    margin-bottom: 1rem;
}
.custom-select, .form-control {
    background-color: #fff;
}
.custom-control-label::before, .custom-file-label, .custom-select {
    transition: background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
}
.custom-select {
    font-size: .9rem;
    font-weight: 400;
    line-height: 1.5;
    display: inline-block;
    width: 100%;
    height: calc(1.5em + 0.75rem + 2px);
    padding: 0.375rem 1.75rem 0.375rem 0.75rem;
    vertical-align: middle;
    color: #2e384d;
    border: 1px solid #dce4ec;
    border-radius: 0.25rem;
    background: url();
    background-color: #fff;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
}
.mdi-plus:before {
    content: "\F415";
}
.mdi:before, .mdi-set {
    display: inline-block;
    font: normal normal normal 24px/1 "Material Design Icons";
    font-size: inherit;
    text-rendering: auto;
    line-height: inherit;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}
.justify-content-between {
    justify-content: space-between!important;
}
.d-flex {
    display: flex!important;
}
.cstm-switch {
    display: inline-flex;
    margin: 0;
    cursor: default;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    align-items: center;
}
label {
    display: inline-block;
    margin-bottom: 0.5rem;
}
.cstm-switch .cstm-switch-input {
    position: absolute;
    z-index: -1;
    opacity: 0;
}
.cstm-switch .cstm-switch-input:checked~.cstm-switch-indicator {
  background: #4c66fb;
}
.cstm-switch .cstm-switch-indicator {
  position: relative;
  display: inline-block;
  width: 2.25rem;
  height: 1.25rem;
  transition: .3s border-color,.3s background-color;
  vertical-align: bottom;
  border: 1px solid #dce4ec;
  border-radius: 50px;
}
.cstm-switch .cstm-switch-input:checked~.cstm-switch-indicator:before {
    left: calc(1rem + 1px);
}
.cstm-switch .cstm-switch-indicator:before {
    position: absolute;
    top: 1px;
    left: 1px;
    width: calc(1.25rem - 4px);
    height: calc(1.25rem - 4px);
    content: '';
    transition: .3s left;
    border-radius: 50%;
    background: #fff;
    box-shadow: 0 1px 2px 0 rgba(0,0,0,.4);
}
.text-right {
  text-align: right !important;
}
input[type=checkbox], input[type=radio] {
    box-sizing: border-box;
    padding: 0;
}
.bg-success {
    background-color: #0c9!important;
}
*, ::after, ::before {
    box-sizing: border-box;
}
.w-100 {
    width: 100%!important;
}
.courier_div {
    padding-bottom: 5px;
}

.corlength {
    align-items: end;
}
.text-secondary {
    color: #4c66fb!important;
}
.small, small {
    font-size: 80%;
    font-weight: 400;
}
.fa-minus:before {
    content: "\f068";
    border: 1px solid #33b35a;
    padding: 8px;
    border-radius: 3px;
}
.bg-gray-400 {
    background-color: #d2ddec;
}
.p-all-15 {
    padding: 15px;
}
.lbl-sz{
  color: black;
  font-weight:600;
}
/* .table th{
  font-size: 12px;
}
.table td{
  font-size: 12px;
} */

.svg-icon-sm {
    width: 18px;
    height: 18px;
}
  </style>
<!-- Forms Section-->
<section class="pb-5"> 
    <div class="container-fluid">
        <div class="row">
            <!-- Basic Form-->
            <div class="col-lg-12">
                <div class="card m-b-30">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-6">
                                <h4><i class="mdi mdi-checkbox-intermediate"></i> Order Allocation Engine</h4>
                            </div>
                            <div class="col-sm-6 text-right"></div>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Shipping Rules</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Rule Testing</a>
                        </li>
                    </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <div class="row">
                                    <div class="col-sm-12">
                                        @if (!empty($data))
                                            <form  class='create_new_order_filter_form'  method="post" action="{{ route('rule-allocation.update', $data->id) }}">
                                            @method('PUT')
                                        @else
                                            <form class='create_new_order_filter_form'  method="post" action="{{ route('rule-allocation.store') }}">
                                        @endif
                                            @csrf                  
                                                <div class="card">
                                                    <div class="card-header bg-gray-300 ">
                                                        <h5>Add New Shipping Rule</h5>
                                                    </div>
                                                    <div class="form-row m-t-10 border-top">
                                                        <div class="card-body col-sm-8">  
                                                        <div class="row">
                                                            <div class="form-group col-sm-4">
                                                                <label for="rule_name" class="col-form-label lbl-sz">Rule Name</label>
                                                                <input type="text" class="form-control" required="" id="rule_name" name="rule_name" value="{{ $data ? $data->rule_name : '' }}">
                                                            </div>
                                                            <div class="form-group col-sm-4">
                                                                <label for="rule_priority" class="col-form-label lbl-sz">Set Priority</label>
                                                                <input type="text" class="form-control" required="" id="rule_priority" name="rule_priority" value="{{ $data ? $data->rule_priority : '' }}" onkeypress="return validateNumberKeyPress(this,event.key)">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group col-sm-4">
                                                                <label class="col-form-label lbl-sz" for="order_type">Order Type</label>
                                                                <select class="form-control" name="order_type" id="order_type" required="">
                                                                    <option value="">Choose</option>
                                                                    <option value="All" {{ $data ? (($data->order_type == 'All') ? 'selected' : '') : '' }}>All</option>
                                                                    <option value="B2C" {{ $data ? (($data->order_type == 'B2C') ? 'selected' : '') : '' }}>B2C</option>
                                                                    <option value="B2B" {{ $data ? (($data->order_type == 'B2B') ? 'selected' : '') : '' }}>B2B</option>
                                                                </select>
                                                            </div> 
                                                            <div class="form-group col-sm-4">
                                                                <label for="shipment_mode" class="col-form-label lbl-sz">Shipment Type</label>
                                                                <select class="form-control" id="shipment_mode" name="shipment_mode">
                                                                    <option value="">Choose</option>
                                                                    <option value="All" {{ $data ? (($data->shipment_mode == 'All') ? 'selected' : '') : '' }}>All</option>
                                                                  @foreach($shipment_modes as $shipment_mode)
                                                                    @php
                                                                      $isSelected = $data ? (($data->shipment_mode == $shipment_mode->shipment_type) ? 'selected' : '') : '';
                                                                    @endphp
                                                                    <option value="{{ $shipment_mode->shipment_type }}" {{ $isSelected }}>{{ $shipment_mode->shipment_type }}</option>
                                                                  @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="form-group col-sm-4">
                                                                <label class="col-form-label lbl-sz" for="payment_mode">Payment Mode</label>
                                                                <select class="form-control" name="payment_mode" id="payment_mode" >
                                                                  <option value="">Choose</option>
                                                                   <option value="All" {{ $data ? (($data->payment_mode == 'All') ? 'selected' : '') : '' }}>All</option>
                                                                  <option value="COD" {{ $data ? (($data->payment_mode == 'COD') ? 'selected' : '') : ''}}>COD</option>
                                                                  <option value="Prepaid" {{ $data ? (($data->payment_mode == 'Prepaid') ? 'selected' : '') : ''}}>Prepaid</option>
                                                                </select>
                                                            </div> 
                                                            <div class="form-group col-sm-4">
                                                                <label for="weight" class="col-form-label lbl-sz">Weight Range</label>
                                                                <select class="form-control" id="weight" name="weight" >
                                                                  <option value="">Choose</option>
                                                                  <option value="All" {{ $data ? (($data->weight == 'All') ? 'selected' : '') : '' }}>All</option>
                                                                  @foreach($weights as $weight)
                                                                  <option value="{{ $weight->weight_range }}" {{ $data ? (($data->weight == $weight->weight_range) ? 'selected' : '') : ''}}> {{ $weight->description.' '.'( '.$weight->weight_range.'  Kg'.' )' }}</option>                                  
                                                                  @endforeach
                                                                </select>
                                                            </div>  
                                                            <div class="form-group col-sm-4">
                                                                <label for="zone" class="col-form-label lbl-sz">Zone</label>
                                                                <select class="form-control" id="zone" name="zone">
                                                                  <option value="">Choose</option>
                                                                  <option value="All" {{ $data ? (($data->zone == 'All') ? 'selected' : '') : '' }}>All</option>
                                                                  @foreach($zones as $zone)
                                                                  <option value="{{ $zone->zone_code }}" {{ $data ? (($data->zone == $zone->zone_code) ? 'selected' : '') : ''}}>{{ $zone->zone_code }}</option>                                  
                                                                  @endforeach
                                                                </select>
                                                            </div>  
                                                            <div class="form-group col-sm-4">
                                                                <input type="reset" class="btn btn-info" style="margin-top: 38px;border-radius: 10px;">
                                                            </div>  
                                                        </div>
                                                    </div>                            
                                                        <div class="card-body col-sm-4">
                                                        <div class="row m-b-10">
                                                            <div class="col-sm-12">
                                                              <h5>Courier Priority</h5>
                                                            </div>
                                                        </div>
                                                        <div class="card-body  courier_diva">
                                                            <div class="row bg-gray-300 courier_div ">
                                                                @if($data)
                                                                    @php  
                                                                        $prDetail = $data->courier_priority;
                                                                        $prDetailArray = json_decode($prDetail, true);
                                                                    @endphp
                                                                    <input type="hidden" value="{{ $prDetail }}" id="cor_arr">
                                                                      @php $k=1; @endphp
                                                                    @foreach($prDetailArray['courier_priority'] as  $prd)
                                                                        <div class="form-group col-sm-6 corlength">    
                                                                          
                                                                            @foreach ($prd as $keyy => $valuee)                              
                                                                                <label for="inputPassword" class="col-form-label">{{ $keyy }}</label>
                                                                                <select name="courier_priority[]" @if($keyy == 'Priority 1') required="" @endif class="custom-select courier_priority" id="courierPriority-{{$k}}">
                                                                                    <option value="">Select</option>  
                                                                                    @foreach($logistics as $logistic)                                
                                                                                    <option value="{{ $logistic->logistics_name }}" {{ ($valuee == $logistic->logistics_name) ? 'selected' : ''}}>{{ $logistic->logistics_name }}</option>                                        
                                                                                    @endforeach
                                                                                </select>  
                                                                            @endforeach   
                                                                            @php $k++; @endphp                             
                                                                        </div>
                                                                    @endforeach
                                                                @else
                                                                    @for($i=1; $i <= 4; $i++)
                                                                    <div class="form-group col-sm-6 corlength">                                    
                                                                      <label for="courierPriority" class="col-form-label">Priority {{ $i }}</label>
                                                                      <select name="courier_priority[]" @if($i == 1) required="" @endif class="custom-select courier_priority" id="courierPriority-{{$i}}">
                                                                        <option value="">Select</option>  
                                                                        @foreach($logistics as $logistic)                                
                                                                          <option value="{{ $logistic->logistics_name }}">{{ $logistic->logistics_name }}</option>                                        
                                                                        @endforeach
                                                                      </select>                                    
                                                                    </div>
                                                                  @endfor    
                                                                @endif                       
                                                            </div>
                                                        </div>
                                                        <div class="form-row border-bottom">
                                                            <div class="form-group col-sm-12 text-center">
                                                            <button type="button" class="btn-outline-info btn-rounded-circle btn-sm btn add_new_courier_prt"><i class="fa fa-plus"></i></button>
                                                        </div>
                                                    </div>
                                                    </div>
                                                        <div class="card-footer">
                                                            <button type="submit" class="btn btn-primary">Save</button>
                                                            <a href="{{ route('rule-allocation.index') }}" class="btn btn-secondary">Cancel</a>
                                                        </div>
                                                    </div>
                                                
                                            </div>
                                        </form>
                                    </div>                      
                                </div>
                            </div>
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="row">
                                <div class="col-sm-6">
                                    <form method="post" class="rule_testing_form" action="#">
                                        <div class="form-group text-center">
                                            <b>Enter details for rule testing</b>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-row">
                                                <div class="form-group col-sm-6">
                                                    <label for="inputPassword" class="col-form-label">Order ID</label>
                                                    <input type="text" class="form-control" required="" name="order_id" value="">
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label for="inputPassword" class=" text-right col-form-label">Pickup Warehouse</label>
                                                    <div class="">
                                                        <select class="form-control js-select2 select2-hidden-accessible" required="" name="warehouse_id" data-select2-id="1" tabindex="-1" aria-hidden="true">
                                                          <option value="" data-select2-id="3">Select Warehouse</option>
                                                          <option value="166572">BIL</option>
                                                          <option value="161199">BIL</option>                                             
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group bg-gray-400 p-all-15 text-black" id="matched_rule_name" style="display:none;"></div>
                                            <div class="form-group text-right">
                                                <button type="button" class="btn btn-primary">Make Test</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>             
                        </div>
                    </div>
                </div>
            </div>
        </div>  
    </div>
</section>

<script>

    $('#myTab a').on('click', function (e) {
      e.preventDefault()
      var href = $(this).attr('href');
      $(this).tab('show');
      if(href == '#home'){
        window.location.href = ""; 
      }
       
    })

    $('input[type=radio][name=order_type]').change(function() {
        window.location.replace("#" + this.value);
    });
   
    $('.add_new_courier_prt').click(function() {
      debugger
        var lem = $(".corlength").length;
        var p = lem + 1;
        var courfieldHTML = '<div class="form-row col-sm-6 corlength"><div class="col-sm-10"><label for="courier_priority" class="col-form-label">Priority <span class="count_cour">' + p + '</span></label><select name="courier_priority[]" class="custom-select courier_priority" id="courierPriority-'+ p +'"><option value="">Select</option>@foreach($logistics as $logistic)<option value="{{ $logistic->logistics_name }}">{{ $logistic->logistics_name }}</option>@endforeach</select> </div> <div class="col-sm-2 text-right" >\<button type="button" class="btn btn-link btn-sm count_cour1"  onclick="deleteCourRow1(this);"><i class="fa fa-minus"></i></button>\</div> </div>';
        p++;
        $('.courier_div').append(courfieldHTML);
    });
    
    function deleteCourRow1(id) {
      $(id).parent('div').parent('div').remove();
      var len = $(".corlength").length;
      var ln = 1;
      $('.corlength').each(function() {
          $(this).find('.count_cour').each(function(el) {
              $(this).text(ln);
          });
          $(this).find('.count_cour1').each(function(el) {
              $(this).val(ln);
          });
          ln++
      });
    }

    var x = 1;
    $('.add_new_condition').click(function() {
      
        var fieldHTML = '<div class="row bg-gray-300 p-t-20 m-b-10 border-top border-light" id="filter_number_' + x + '"><div class="col-sm-6"><div class="form-group"><select name="filter_field[]" onchange="on_field_change(' + x + ',this.value)" required class="custom-select"><option value="">Select</option><option value="payment_type">Payment Mode</option><option value="order_amount">Order Amount</option><option value="pickup_pincode">Pickup Pincode</option><option value="delivery_pincode">Delivery Pincode</option><option value="zone">Zone</option><option value="product_name">Product Name</option><option value="product_sku">Product SKU</option>\
                            </select>\
                        </div>\
                    </div>\
                    <div class="col-sm-6">\
                        <div class="form-group">\
                          <select name="filter_condition[]" id="filter_' + x + '_conditions" required class="custom-select">\
                            </select>\
                        </div>\
                    </div>\
                    <div class="col-sm-10">\
                        <div class="form-group" id="filter_' + x + '_value">\
                            <textarea class="form-control" rows="1" required="" name="filter_value[]" placeholder=""></textarea>\
                                <small  id="filter_' + x + '_help_text" class="form-text text-muted"></small>\
                        </div>\
                    </div>\
                    <div class="col-sm-2 text-right">\
                        <button type="button" class="btn btn-link btn-sm" onclick="deleteFilterRow(' + x + ');"><i class="fa fa-minus"></i></button>\
                    </div></div></div>';
        x++;
        $('.filter-body').append(fieldHTML);
    });
    
    function on_field_change(row = false, value = false) {
      
        var options = '';

        var values_options = '<textarea class="form-control" rows="1" required="" name="filter_value[]" placeholder=""></textarea>';
        document.getElementById("filter_" + row + "_value").innerHTML = values_options;

        switch (value) {
            case 'payment_type':
                options = '<option value="is">Is</option><option value="is_not">Is not</option>';
                var values_options = '<select name="filter_value[]" id="filter_value" class="custom-select" required=""><option value="">Select</option><option value="cod">COD</option><option value="prepaid">Prepaid</option><option value="reverse_qc">Reverse With Qc</option><option value="reverse">Reverse Without Qc</option></select>';
                document.getElementById("filter_" + row + "_value").innerHTML = values_options;
                break;

            case 'order_amount':
                options = '<option value="greater_than">Greater than</option><option value="less_than">Less than</option>';
                break;
            case 'pickup_pincode':
                options = '<option value="is">Is</option><option value="is_not">Is not</option><option value="any_of">Any of (Comma Separated)</option>';
                break;
            case 'delivery_pincode':
                options = '<option value="is">Is</option><option value="is_not">Is not</option><option value="starts_with">Starts with</option><option value="any_of">Any of (Comma Separated)</option>';
                break;
            case 'state':
                options = '<option value="is">Is</option><option value="is_not">Is not</option>';
                break;
            case 'zone':
                options = '<option value="is">Is</option><option value="is_not">Is not</option>';
                var values_options = '<select name="filter_value[]" class="custom-select" required="" id="filter_value"><option value="">Select</option>@foreach($zones as $zone)<option value="{{ $zone->zone_code }}">{{ $zone->zone_code.' ( '.$zone->description.' )' }} </option>@endforeach</select>';
                document.getElementById("filter_" + row + "_value").innerHTML = values_options;

                break;
            case 'weight':
                options = '<option value="greater_than">Greater than</option><option value="less_than">Less than</option>';
                break;
            case 'product_name':
                options = '<option value="is">Is</option><option value="is_not">Is not</option><option value="starts_with">Starts with</option><option value="contain">Contain word</option><option value="any_of">Any of (Comma Separated)</option>';
                break;
            case 'product_sku':
                options = '<option value="is">Is</option><option value="is_not">Is not</option><option value="starts_with">Starts with</option><option value="contain">Contain word</option><option value="any_of">Any of (Comma Separated)</option>';
                break;
            default:
                options = '<option value="is">Is</option><option value="is_not">Is not</option><option value="starts_with">Starts with</option><option value="greater_than">Greater than</option><option value="less_than">Less than</option>';
        }

        document.getElementById("filter_" + row + "_conditions").innerHTML = options;
    }
    
    function status(n) { 
      var url = "{{ route('rule-allocation.show', ':ruleId') }}".replace(':ruleId', n);
      if (n) {
        swal.fire({
          title: "Warning",
          text: "Are you sure?",
          type: "Warning",
          showCancelButton: true,
          confirmButtonText: "Confirm",
          cancelButtonText: "Cancel",
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = url;
          }
        });
      }
    }

    function viewRule(n) {
      var url = "{{ route('rule-allocation.edit', ':ruleId') }}".replace(':ruleId', n);
      if (n) {        
        $('#bg'+n).css({'background':'#393836','color':'white','cursor':'pointer'});
        window.location.href = url;     
      }
      else{
        $('#bg'+n).css({'background':'white','color':'#12263f','cursor':'pointer'});
      }
    }
    var courier = [];
</script>
<script>  
$(document).ready(function () {  
  
    
    var elements = document.querySelectorAll(".courier_priority");
    var values = [];
    
    for (var i = 0; i < elements.length; i++) {
        var value = elements[i].value.trim(); // Trim the value to remove leading/trailing white spaces
        if (value !== "") {
            courier.push(value);
        }
    }

console.log(courier);

  $(document).on('change', '.courier_priority', function(event) {
    event.preventDefault();
    var id = $(this).attr('id');      
    var data = id.split('-');
    var no = data[1];
    var cp = $('#courierPriority-' + no).val();
    // Check if the value already exists in the courier object
  
      courier[id] = cp;
      console.log(courier);
    
  });

  function getKeyByValue(object, value) {
    for (var key in object) {
      if (object.hasOwnProperty(key) && object[key] === value) {
        return key;
      }
    }
    return null;
  }

});

</script> 
<script>
  $(document).ready(function () {  
    $(document).on('change', '#shipment_mode', function(event) {
        var shipment_mode = $(this).val();
        var order_type = $('#order_type').val();
        var payment_mode = $('#payment_mode').val();
        var weight = $('#weight').val();
        var zone = $('#zone').val();
        if(order_type!='' && shipment_mode!='' &&  payment_mode == '' && weight =='' && zone ==''){
          var payment_mode_length = $('#payment_mode').find('option:not(:empty):not([value=""])').length;    
          var order_type_length = $('#order_type').find('option:not(:empty):not([value=""])').length;
          var shipment_mode_length = $('#shipment_mode').find('option:not(:empty):not([value=""])').length; 
          var weight_length = $('#weight').find('option:not(:empty):not([value=""])').length;  
          var zone_length = $('#zone').find('option:not(:empty):not([value=""])').length;     
          var rule =  order_type_length * shipment_mode_length;
          swal.fire({
            title: "Info",
            html:
             "Maximum possible rules are " + rule + " on combination of <br> Order type and Shipment mode",
            type: "warning",
            icon: 'info',
          });
        }
        if(order_type!='' && shipment_mode!='' &&  payment_mode != '' && weight =='' && zone ==''){
          var payment_mode_length = $('#payment_mode').find('option:not(:empty):not([value=""])').length;    
          var order_type_length = $('#order_type').find('option:not(:empty):not([value=""])').length;
          var shipment_mode_length = $('#shipment_mode').find('option:not(:empty):not([value=""])').length; 
          var weight_length = $('#weight').find('option:not(:empty):not([value=""])').length;  
          var zone_length = $('#zone').find('option:not(:empty):not([value=""])').length; 
          var rule = shipment_mode_length * payment_mode_length * order_type_length;
          swal.fire({
            title: "Info",
            html:
             "Maximum possible rules are " + rule + " on combination of <br>Order type, Shipment mode, Payment mode",
            type: "warning",
            icon: 'info',
          });
        }
        if(order_type!='' && shipment_mode!='' &&  payment_mode != '' && weight !='' && zone !=''){
          var payment_mode_length = $('#payment_mode').find('option:not(:empty):not([value=""])').length;    
          var order_type_length = $('#order_type').find('option:not(:empty):not([value=""])').length;
          var shipment_mode_length = $('#shipment_mode').find('option:not(:empty):not([value=""])').length; 
          var weight_length = $('#weight').find('option:not(:empty):not([value=""])').length;  
          var zone_length = $('#zone').find('option:not(:empty):not([value=""])').length; 
          var rule = shipment_mode_length * payment_mode_length * order_type_length * weight_length*zone_length;
          swal.fire({
            title: "Info",
            html:
             "Maximum possible rules are " + rule + " on combination of <br>Order type, Shipment mode, Payment mode, weight and zone",
            type: "warning",
            icon: 'info',
          });
        }
        if(order_type!='' && shipment_mode!='' &&  payment_mode != '' && weight !='' && zone ==''){
          var payment_mode_length = $('#payment_mode').find('option:not(:empty):not([value=""])').length;    
          var order_type_length = $('#order_type').find('option:not(:empty):not([value=""])').length;
          var shipment_mode_length = $('#shipment_mode').find('option:not(:empty):not([value=""])').length; 
          var weight_length = $('#weight').find('option:not(:empty):not([value=""])').length;  
          var zone_length = $('#zone').find('option:not(:empty):not([value=""])').length; 
          var rule = shipment_mode_length * payment_mode_length * order_type_length * weight_length;
          swal.fire({
            title: "Info",
            html:
             "Maximum possible rules are " + rule + " on combination of <br>Order type, Shipment mode, Payment mode, and weight",
            type: "warning",
            icon: 'info',
          });
        }
        if(order_type!='' && shipment_mode!='' &&  payment_mode != '' && weight =='' && zone ==''){
          var payment_mode_length = $('#payment_mode').find('option:not(:empty):not([value=""])').length;    
          var order_type_length = $('#order_type').find('option:not(:empty):not([value=""])').length;
          var shipment_mode_length = $('#shipment_mode').find('option:not(:empty):not([value=""])').length; 
          var weight_length = $('#weight').find('option:not(:empty):not([value=""])').length;  
          var zone_length = $('#zone').find('option:not(:empty):not([value=""])').length; 
          var rule = shipment_mode_length * payment_mode_length * order_type_length ;
          swal.fire({
            title: "Info",
            html:
             "Maximum possible rules are " + rule + " on combination of <br>Order type, Shipment mode and Payment mode",
            type: "warning",
            icon: 'info',
          });
        }
        
        if(order_type!='' && shipment_mode!='' &&  payment_mode == '' && weight =='' && zone !=''){
          var payment_mode_length = $('#payment_mode').find('option:not(:empty):not([value=""])').length;    
          var order_type_length = $('#order_type').find('option:not(:empty):not([value=""])').length;
          var shipment_mode_length = $('#shipment_mode').find('option:not(:empty):not([value=""])').length; 
          var weight_length = $('#weight').find('option:not(:empty):not([value=""])').length;  
          var zone_length = $('#zone').find('option:not(:empty):not([value=""])').length; 
          var rule = order_type_length * shipment_mode_length *zone_length;
          swal.fire({
            title: "Info",
            html:
             "Maximum possible rules are " + rule + " on combination of <br>Order type and zone",
            type: "warning",
            icon: 'info',
          });
        }
        if(order_type!='' && shipment_mode!='' &&  payment_mode == '' && weight !='' && zone ==''){
          var payment_mode_length = $('#payment_mode').find('option:not(:empty):not([value=""])').length;    
          var order_type_length = $('#order_type').find('option:not(:empty):not([value=""])').length;
          var shipment_mode_length = $('#shipment_mode').find('option:not(:empty):not([value=""])').length; 
          var weight_length = $('#weight').find('option:not(:empty):not([value=""])').length;  
          var zone_length = $('#zone').find('option:not(:empty):not([value=""])').length; 
          var rule = order_type_length * shipment_mode_length * weight_length;
          swal.fire({
            title: "Info",
            html:
             "Maximum possible rules are " + rule + " on combination of <br>Order type, Shipment mode and weight",
            type: "warning",
            icon: 'info',
          });
        }
    });
    $(document).on('change', '#payment_mode', function(event) {
        var shipment_mode = $('#shipment_mode').val();
        var order_type = $('#order_type').val();
        var payment_mode = $('#payment_mode').val();
        var weight = $('#weight').val();
        var zone = $('#zone').val();
        if(order_type!='' && shipment_mode =='' && payment_mode != '' && weight =='' && zone ==''){
          var payment_mode_length = $('#payment_mode').find('option:not(:empty):not([value=""])').length;    
          var order_type_length = $('#order_type').find('option:not(:empty):not([value=""])').length;
          var shipment_mode_length = $('#shipment_mode').find('option:not(:empty):not([value=""])').length; 
          var weight_length = $('#weight').find('option:not(:empty):not([value=""])').length;  
          var zone_length = $('#zone').find('option:not(:empty):not([value=""])').length;    
          var rule =  order_type_length * payment_mode_length;
          swal.fire({
            title: "Info",
            html:
             "Maximum possible rules are " + rule + " on combination of <br>Order type and Payment mode",
            type: "warning",
            icon: 'info',
          });
        }
        if(order_type!='' && shipment_mode !='' && payment_mode != '' && weight =='' && zone ==''){
          var payment_mode_length = $('#payment_mode').find('option:not(:empty):not([value=""])').length;    
          var order_type_length = $('#order_type').find('option:not(:empty):not([value=""])').length;
          var shipment_mode_length = $('#shipment_mode').find('option:not(:empty):not([value=""])').length; 
          var weight_length = $('#weight').find('option:not(:empty):not([value=""])').length;  
          var zone_length = $('#zone').find('option:not(:empty):not([value=""])').length;    
          var rule =  order_type_length * shipment_mode_length *payment_mode_length;
          swal.fire({
            title: "Info",
            html:
             "Maximum possible rules are " + rule + " on combination of <br>Order type, Shipment mode and Payment mode",
            type: "warning",
            icon: 'info',
          });
        }
        if(order_type!='' && shipment_mode!='' &&  payment_mode != '' && weight !='' && zone ==''){  
          var shipment_mode_length = $('#shipment_mode').find('option:not(:empty):not([value=""])').length;  
          var payment_mode_length = $('#payment_mode').find('option:not(:empty):not([value=""])').length;    
          var order_type_length = $('#order_type').find('option:not(:empty):not([value=""])').length;  
          var weight_length = $('#weight').find('option:not(:empty):not([value=""])').length;  
          var zone_length = $('#zone').find('option:not(:empty):not([value=""])').length;
          var rule = order_type_length * shipment_mode_length * payment_mode_length * weight_length; 
          swal.fire({
            title: "Info",
            html:
             "Maximum possible rules are " + rule + " on combination of <br>Order type, Shipment mode, Payment mode and weight",
            type: "warning",
            icon: 'info',
          });
        }
        if(order_type!='' && shipment_mode!='' &&  payment_mode !== '' && weight !='' && zone!=''){  
          var shipment_mode_length = $('#shipment_mode').find('option:not(:empty):not([value=""])').length;  
          var payment_mode_length = $('#payment_mode').find('option:not(:empty):not([value=""])').length;    
          var order_type_length = $('#order_type').find('option:not(:empty):not([value=""])').length;  
          var weight_length = $('#weight').find('option:not(:empty):not([value=""])').length;  
          var zone_length = $('#zone').find('option:not(:empty):not([value=""])').length;
          var rule = order_type_length * shipment_mode_length * payment_mode_length * weight_length * zone_length; 
          swal.fire({
            title: "Info",
            html:
             "Maximum possible rules are " + rule + " on combination of <br>Order type, Shipment mode, Payment mode, weight and zone",
            type: "warning",
            icon: 'info',
          });
        }
        if(order_type!='' && shipment_mode=='' &&  payment_mode !== '' && weight !='' && zone==''){  
          var shipment_mode_length = $('#shipment_mode').find('option:not(:empty):not([value=""])').length;  
          var payment_mode_length = $('#payment_mode').find('option:not(:empty):not([value=""])').length;    
          var order_type_length = $('#order_type').find('option:not(:empty):not([value=""])').length;  
          var weight_length = $('#weight').find('option:not(:empty):not([value=""])').length;  
          var zone_length = $('#zone').find('option:not(:empty):not([value=""])').length;
          var rule = order_type_length * payment_mode_length * weight_length; 
          swal.fire({
            title: "Info",
            html:
             "Maximum possible rules are " + rule + " on combination of <br>Order type, Payment mode and weight",
            type: "warning",
            icon: 'info',
          });
        }
       
    });
    $(document).on('change', '#weight', function(event) {
        var weight = $('#weight').val();
        var shipment_mode = $('#shipment_mode').val();
        var order_type = $('#order_type').val();
        var payment_mode = $('#payment_mode').val();
        var weight = $('#weight').val();
        var zone = $('#zone').val();
        if(order_type!='' && shipment_mode !='' && payment_mode != '' && weight !='' && zone ==''){
          var order_type_length = $('#order_type').find('option:not(:empty):not([value=""])').length;   
          var shipment_mode_length = $('#shipment_mode').find('option:not(:empty):not([value=""])').length;  
          var payment_mode_length = $('#payment_mode').find('option:not(:empty):not([value=""])').length;               
          var weight_length = $('#weight').find('option:not(:empty):not([value=""])').length;
          var zone_length = $('#zone').find('option:not(:empty):not([value=""])').length;
          var rule = order_type_length * shipment_mode_length * payment_mode_length * weight_length;
          swal.fire({
            title: "Info",
            html:
             "Maximum possible rules are " + rule + " on combination of <br>Order type, Shipment mode, Payment mode, weight",
            type: "warning",
            icon: 'info',
          });
        }
        if(order_type!='' && shipment_mode !='' && payment_mode != '' && weight !='' && zone !=''){
          var order_type_length = $('#order_type').find('option:not(:empty):not([value=""])').length;   
          var shipment_mode_length = $('#shipment_mode').find('option:not(:empty):not([value=""])').length;  
          var payment_mode_length = $('#payment_mode').find('option:not(:empty):not([value=""])').length;               
          var weight_length = $('#weight').find('option:not(:empty):not([value=""])').length;
          var zone_length = $('#zone').find('option:not(:empty):not([value=""])').length;
          var rule = order_type_length * shipment_mode_length * payment_mode_length * weight_length * zone_length;
          swal.fire({
            title: "Info",
            html:
             "Maximum possible rules are " + rule + " on combination of <br>Order type, Shipment mode, Payment mode, weight, zone",
            type: "warning",
            icon: 'info',
          });
        }
        if(order_type!='' && shipment_mode =='' && payment_mode != '' && weight !='' && zone ==''){         
          var order_type_length = $('#order_type').find('option:not(:empty):not([value=""])').length;    
          var shipment_mode_length = $('#shipment_mode').find('option:not(:empty):not([value=""])').length;  
          var payment_mode_length = $('#payment_mode').find('option:not(:empty):not([value=""])').length;    
          var weight_length = $('#weight').find('option:not(:empty):not([value=""])').length;
          var zone_length = $('#zone').find('option:not(:empty):not([value=""])').length;
          var rule = order_type_length *  payment_mode_length * weight_length;
          swal.fire({
            title: "Info",
            html:
             "Maximum possible rules are " + rule + " on combination of <br>Order type, Payment mode, weight",
            type: "warning",
            icon: 'info',
          });
        }
        if(order_type!='' && shipment_mode !='' && payment_mode == '' && weight !='' && zone ==''){         
          var order_type_length = $('#order_type').find('option:not(:empty):not([value=""])').length;    
          var shipment_mode_length = $('#shipment_mode').find('option:not(:empty):not([value=""])').length;  
          var payment_mode_length = $('#payment_mode').find('option:not(:empty):not([value=""])').length;    
          var weight_length = $('#weight').find('option:not(:empty):not([value=""])').length;
          var zone_length = $('#zone').find('option:not(:empty):not([value=""])').length;
          var rule = order_type_length *  shipment_mode_length * weight_length;
          swal.fire({
            title: "Info",
            html:
             "Maximum possible rules are " + rule + " on combination of <br>Order type, Shipment mode, weight",
            type: "warning",
            icon: 'info',
          });
        }
        if(order_type!='' && shipment_mode =='' && payment_mode == '' && weight !='' && zone ==''){         
          var order_type_length = $('#order_type').find('option:not(:empty):not([value=""])').length;    
          var shipment_mode_length = $('#shipment_mode').find('option:not(:empty):not([value=""])').length;  
          var payment_mode_length = $('#payment_mode').find('option:not(:empty):not([value=""])').length;    
          var weight_length = $('#weight').find('option:not(:empty):not([value=""])').length;
          var zone_length = $('#zone').find('option:not(:empty):not([value=""])').length;
          var rule = order_type_length * weight_length;
          swal.fire({
            title: "Info",
            html:
             "Maximum possible rules are " + rule + " on combination of <br>Order type, weight",
            type: "warning",
            icon: 'info',
          });
        }
        if(order_type!='' && shipment_mode =='' && payment_mode == '' && weight !='' && zone !=''){         
          var order_type_length = $('#order_type').find('option:not(:empty):not([value=""])').length;    
          var shipment_mode_length = $('#shipment_mode').find('option:not(:empty):not([value=""])').length;  
          var payment_mode_length = $('#payment_mode').find('option:not(:empty):not([value=""])').length;    
          var weight_length = $('#weight').find('option:not(:empty):not([value=""])').length;
          var zone_length = $('#zone').find('option:not(:empty):not([value=""])').length;
          var rule = order_type_length * weight_length * zone_length;
          swal.fire({
            title: "Info",
            html:
             "Maximum possible rules are " + rule + " on combination of <br>Order type, weight, zone",
            type: "warning",
            icon: 'info',
          });
        }
        if(order_type!='' && shipment_mode =='' && payment_mode != '' && weight !='' && zone !=''){         
          var order_type_length = $('#order_type').find('option:not(:empty):not([value=""])').length;    
          var shipment_mode_length = $('#shipment_mode').find('option:not(:empty):not([value=""])').length;  
          var payment_mode_length = $('#payment_mode').find('option:not(:empty):not([value=""])').length;    
          var weight_length = $('#weight').find('option:not(:empty):not([value=""])').length;
          var zone_length = $('#zone').find('option:not(:empty):not([value=""])').length;
          var rule = order_type_length * payment_mode_length * weight_length * zone_length;
          swal.fire({
            title: "Info",
            html:
             "Maximum possible rules are " + rule + " on combination of <br>Order type, Payment mode, weight, zone",
            type: "warning",
            icon: 'info',
          });
        }
    });
    $(document).on('change', '#zone', function(event) {
        var weight = $('#weight').val();
        var shipment_mode = $('#shipment_mode').val();
        var order_type = $('#order_type').val();
        var payment_mode = $('#payment_mode').val();
        var weight = $('#weight').val();
        var zone = $('#zone').val();
        if(order_type!='' && shipment_mode !='' && payment_mode != '' && weight !='' && zone !=''){
          var order_type_length = $('#order_type').find('option:not(:empty):not([value=""])').length;   
          var shipment_mode_length = $('#shipment_mode').find('option:not(:empty):not([value=""])').length;  
          var payment_mode_length = $('#payment_mode').find('option:not(:empty):not([value=""])').length;               
          var weight_length = $('#weight').find('option:not(:empty):not([value=""])').length;
          var zone_length = $('#zone').find('option:not(:empty):not([value=""])').length;
          var rule = order_type_length * shipment_mode_length * payment_mode_length * weight_length * zone_length;
          swal.fire({
            title: "Info",
            html:
             "Maximum possible rules are " + rule + " on combination of <br>Order type, Shipment mode, Payment mode, weight, zone",
            type: "warning",
            icon: 'info',
          });
        }
        if(order_type!='' && shipment_mode =='' && payment_mode != '' && weight !='' && zone !=''){         
          var order_type_length = $('#order_type').find('option:not(:empty):not([value=""])').length;    
          var shipment_mode_length = $('#shipment_mode').find('option:not(:empty):not([value=""])').length;  
          var payment_mode_length = $('#payment_mode').find('option:not(:empty):not([value=""])').length;    
          var weight_length = $('#weight').find('option:not(:empty):not([value=""])').length;
          var zone_length = $('#zone').find('option:not(:empty):not([value=""])').length;
          var rule = order_type_length *  payment_mode_length * weight_length * zone_length;
          
          swal.fire({
            title: "Info",
            html:
             "Maximum possible rules are " + rule + " on combination of <br>Order type,Payment mode, weight, zone",
            type: "warning",
            icon: 'info',
          });
        }
        if(order_type!='' && shipment_mode !='' && payment_mode == '' && weight !='' && zone !=''){         
          var order_type_length = $('#order_type').find('option:not(:empty):not([value=""])').length;    
          var shipment_mode_length = $('#shipment_mode').find('option:not(:empty):not([value=""])').length;  
          var payment_mode_length = $('#payment_mode').find('option:not(:empty):not([value=""])').length;    
          var weight_length = $('#weight').find('option:not(:empty):not([value=""])').length;
          var zone_length = $('#zone').find('option:not(:empty):not([value=""])').length;
          var rule = order_type_length *  shipment_mode_length * weight_length * zone_length;
          swal.fire({
            title: "Info",
            html:
             "Maximum possible rules are " + rule + " on combination of <br>Order type, Shipment mode, weight, zone",
            type: "warning",
            icon: 'info',
          });
        }
        if(order_type!='' && shipment_mode !='' && payment_mode != '' && weight =='' && zone !=''){         
          var order_type_length = $('#order_type').find('option:not(:empty):not([value=""])').length;    
          var shipment_mode_length = $('#shipment_mode').find('option:not(:empty):not([value=""])').length;  
          var payment_mode_length = $('#payment_mode').find('option:not(:empty):not([value=""])').length;    
          var weight_length = $('#weight').find('option:not(:empty):not([value=""])').length;
          var zone_length = $('#zone').find('option:not(:empty):not([value=""])').length;
          var rule = order_type_length *  shipment_mode_length * payment_mode_length * zone_length;
          swal.fire({
            title: "Info",
            html:
             "Maximum possible rules are " + rule + " on combination of <br>Order type, Shipment mode, Payment mode, zone",
            type: "warning",
            icon: 'info',
          });
        }
        if(order_type!='' && shipment_mode !='' && payment_mode == '' && weight =='' && zone !=''){         
          var order_type_length = $('#order_type').find('option:not(:empty):not([value=""])').length;    
          var shipment_mode_length = $('#shipment_mode').find('option:not(:empty):not([value=""])').length;  
          var payment_mode_length = $('#payment_mode').find('option:not(:empty):not([value=""])').length;    
          var weight_length = $('#weight').find('option:not(:empty):not([value=""])').length;
          var zone_length = $('#zone').find('option:not(:empty):not([value=""])').length;
          var rule = order_type_length *  shipment_mode_length *  zone_length;
          swal.fire({
            title: "Info",
            html:
             "Maximum possible rules are " + rule + " on combination of <br>Order type, Shipment mode, zone",
            type: "warning",
            icon: 'info',
          });
        }
        if(order_type!='' && shipment_mode =='' && payment_mode != '' && weight =='' && zone !=''){         
          var order_type_length = $('#order_type').find('option:not(:empty):not([value=""])').length;    
          var shipment_mode_length = $('#shipment_mode').find('option:not(:empty):not([value=""])').length;  
          var payment_mode_length = $('#payment_mode').find('option:not(:empty):not([value=""])').length;    
          var weight_length = $('#weight').find('option:not(:empty):not([value=""])').length;
          var zone_length = $('#zone').find('option:not(:empty):not([value=""])').length;
          var rule = order_type_length *  payment_mode_length *  zone_length;
          swal.fire({
            title: "Info",
            html:
             "Maximum possible rules are " + rule + " on combination of <br>Order type, Payment mode, zone",
            type: "warning",
            icon: 'info',
          });
        }
        if(order_type!='' && shipment_mode =='' && payment_mode == '' && weight =='' && zone !=''){         
          var order_type_length = $('#order_type').find('option:not(:empty):not([value=""])').length;    
          var shipment_mode_length = $('#shipment_mode').find('option:not(:empty):not([value=""])').length;  
          var payment_mode_length = $('#payment_mode').find('option:not(:empty):not([value=""])').length;    
          var weight_length = $('#weight').find('option:not(:empty):not([value=""])').length;
          var zone_length = $('#zone').find('option:not(:empty):not([value=""])').length;
          var rule = order_type_length * zone_length;
          swal.fire({
            title: "Info",
            html:
             "Maximum possible rules are " + rule + " on combination of <br>Order type, zone",
            type: "warning",
            icon: 'info',
          });
        }
    });
  })
</script>        
<script>
    function validateNumberKeyPress(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode;
        // Allow only numeric digits (0-9)
        if (charCode < 48 || charCode > 57) {
            return false;
        }
        return true;
    }
</script>
@endsection