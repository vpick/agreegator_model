@extends('common-app/master')
@section('title', 'Rule Allocation')
@section('content')
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
        padding-bottom: 30px;
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
  <!-- Counts Section -->
<section class="py-filter">
    <div class="col-md-12 text-end">
		<button type="button" class="btn btn-sm btn-outline-dark refresh_orders_button"><i class="mdi mdi-refresh"></i> Refresh</button>
		 @if(!empty($userP) && $userP->write != '1')
		    <a href="#" onclick="checkPermission()" class="btn btn-outline-dark btn-sm ">Create Rule</a>
		 @else
		    <a href="{{ route('rule-allocation.create') }}" class="btn btn-outline-dark btn-sm ">Create Rule</a>
		@endif
        <button type="button" class="btn btn-outline-dark show_hide_filter btn-sm" style="display:none;"><i class="icon-placeholder mdi mdi-close"></i> Close</button>
	</div>
	<hr/>
</section> 
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
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="table-responsive">
                                            <table class="table align-middle mb-0 bg-white">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th>#</th> 
                                                        <th>Rule No.</th>  
                                                        <th>Rule Description</th>  
                                                        <th>Rule Priority</th>  
                                                        <th>Order Type</th>
                                                        <th>Shipment Mode</th>
                                                        <th>Payment Mode</th>
                                                        <th>Weight (Kg)</th>
                                                        <th>Zone</th>
                                                        <th>Courier Priority</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($ruleList as $k => $ruleData)
                                                        @php
                                                          $jsonData = $ruleData->rule_condition;
                                                          $dataArray = json_decode($jsonData, true);
                                                          $priorityData = $ruleData->courier_priority;
                                                          $priorityArray = json_decode($priorityData, true);  
                                                        @endphp
                                                        <tr style='border-radius: 5px 5px 4px 5px;box-shadow: 1px 1px 1px 0px aqua;'>      
                                                        @if(!empty($userP) && $userP->update != '1')
                                                            <td onclick="checkPermission()"> 
                                                                <svg class="svg-icon svg-icon-sm svg-icon-heavy me-2" style="color:green;"><use xlink:href="#survey-1"> </use></svg>
                                                            </td>
                                                         @else
                                                            <td onclick="viewRule({{ $ruleData->id}})"  style="cursor:pointer"> 
                                                                <svg class="svg-icon svg-icon-sm svg-icon-heavy me-2" style="color:green;"><use xlink:href="#survey-1"> </use></svg>
                                                            </td>       
                                                        @endif
                                                            <td>{{ $k+1 }} </td>
                                                            <td>{{ $ruleData->rule_name }} </td>
                                                            <td>{{ ' # '.$ruleData->rule_priority }}</td>
                                                            <td>{{ $ruleData->order_type }} </td>
                                                            <td>{{ $ruleData->shipment_mode }} </td>
                                                            <td>{{ $ruleData->payment_mode }} </td>
                                                            <td>{{ $ruleData->weight  }} </td>
                                                            <td>{{ $ruleData->zone }}</td>                                
                                                            <td style="font-size: 14px;">
                                                                @foreach ($priorityArray['courier_priority'] as $priority)
                                                                  @foreach ($priority as $key => $value)
                                                                    @if ($value !== null)
                                                                      <b>{{ $key }}:</b> {{ $value }}<br>
                                                                    @endif
                                                                  @endforeach
                                                                @endforeach
                                                            </td>
                                                            <td>
                                                                @if(!empty($userP) && $userP->update != '1')
                                                                    @if($ruleData->rule_status == '1')
                                                                      <button type="button" class="btn btn-sm btn-primary" onclick="checkPermission()">Active</button>
                                                                    @else
                                                                      <button type="button" class="btn btn-sm btn-danger" onclick="checkPermission()">Disable</button>
                                                                    @endif
                                                                @else
                                                                    @if($ruleData->rule_status == '1')
                                                                      <button type="button" class="btn btn-sm btn-primary" onclick="status({{ $ruleData->id}})">Active</button>
                                                                    @else
                                                                      <button type="button" class="btn btn-sm btn-danger" onclick="status({{ $ruleData->id}})">Disable</button>
                                                                    @endif
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
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
        function status(n) 
        { 
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

    function viewRule(n) 
    {
      var url = "{{ route('rule-allocation.edit', ':ruleId') }}".replace(':ruleId', n);
      if (n) {        
        $('#bg'+n).css({'background':'#393836','color':'white','cursor':'pointer'});
        window.location.href = url;     
      }
      else{
        $('#bg'+n).css({'background':'white','color':'#12263f','cursor':'pointer'});
      }
    }
</script>
@endsection