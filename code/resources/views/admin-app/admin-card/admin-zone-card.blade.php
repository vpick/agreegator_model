@extends('admin-app/admin-master')
@section('title', 'Add Zone')
@section('content')
<style>
	.ms-choice {
        display: block;
        width: 450px!important;
        height: 36px!important;
        padding: 0;
        overflow: hidden;
        cursor: pointer;
        border: 1px solid #ced4da!important;
        text-align: left;
        white-space: nowrap;
        line-height: 36px!important;
        color: #444;
        text-decoration: none;
        border-radius: 4px;
        background-color: #fff;
    }
    .ms-choice>span.placeholder {
        color: transparent!important;
    }
    .ms-choice>div.icon-caret {
    	display: none!important;
    }
	select {
      width: 100%;
    }
    .ms-choice>span.placeholder {
        color: transparent!important;
        display: none!important;
    }
</style>
<header class="py-4">
	<div class="container-fluid py-2">
	  <h1 class="h3 fw-normal mb-0">Zone</h1>
	</div>
</header>

<section class="pb-5"> 
	<div class="container-fluid">
	    <div class="row">
		    <!-- Basic Form-->
		    <div class="col-lg-12">
			    <div class="card">
    				<!--<div class="card-header border-bottom">
    				  <h3 class="h4 mb-0">Inline Form</h3>
    				</div>-->			
    				<div class="card-body">
        				@if (!empty($data))
                            <form  class='g-3 align-items-center '  method="post" action="{{ route('zone.update', $data->id) }}">
                      	@method('PUT')
        				@else
        				    <form class='row g-3 align-items-center'  method="post" action="{{ route('zone.store') }}">
        				@endif
    				    @csrf
    				    <div class="col-lg-4" style="margin-top: 5px;">
    				        <label class="<!--visually-hidden-->" for="dsp">DSP*</label>
    				        <select class="form-control" id="dsp" name="dsp"  placeholder="DSp">
    				            <option value="">Select DSP</option>
    							@foreach($couriers as $courier)
    								<option value="{{ $courier->id }}" {{ $data ? (($courier->id == $data->dsp) ? 'selected': '') : ''}} >{{ $courier->logistics_name }}</option>
    							@endforeach
    						</select>
    					</div>
    					<div class="col-lg-4">
    					    <label class="<!--visually-hidden-->" for="zone_code">Zone Code*</label>
    					    <input class="form-control" id="zone_code" name='zone_code' type="text" placeholder="Zone Code" required value="{{ $data ? $data->zone_code : old('zone_code') }}"> 
    						<p>
    						@if($errors->has('zone'))
    							<div class="error">{{ $errors->first('zone') }}</div>
    						@endif
    						</p>
    					</div>
                        <div class="col-lg-4">
    					    <label class="<!--visually-hidden-->" for="description">Description*</label>
    					    <input class="form-control" id="description" name='description' type="text" placeholder="Description" required value="{{ $data ? $data->description : old('description') }}"> 
    						<p>
    						@if($errors->has('description'))
    							<div class="error">{{ $errors->first('description') }}</div>
    						@endif
    						</p>
    					</div>
    					<div class="col-lg-4">
    					    <label class="<!--visually-hidden-->" for="state">States*</label>
    						@if(!empty($data))
    						@php
    							$selectedState =  explode(',',$data->zone_mapping);
    						@endphp
    					    <select class="multiple-select" id="state" name="state[]" multiple="multiple" placeholder="States">
    							@foreach($states as $key => $value)		
    								<option value="{{ $value }}" {{ $selectedState ? ((in_array($value, $selectedState)) ? 'selected' : '') : '' }}>{{$value}}</option>
    							@endforeach
    						</select>
    						@else
    						<div id="state_list">
    						    <select class="multiple-select" id="state" name="state[]" multiple="multiple" placeholder="States">
    						    </select>
    						</div>
    						@endif
    						<span id="state_error" style="color:red"></span>
    						<p>
    						    
    						@if($errors->has('state'))
    							<div class="error">{{ $errors->first('state') }}</div>
    						@endif
    						</p>
    					</div>
    					<div class="col-lg-3">
    					    <button class="btn btn-primary" type="submit">Submit</button>
    					    <a href="{{ route('zone.index') }}" class="btn btn-warning">Cancel</a>
    					</div>
			        </form>
				</div>
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
        $('#dsp').on('change',function(){
            var parentID = $(this).val();
            if(parentID){
            $.ajax({
                url:'/load/zone-state/'+parentID,
                type:'GET',
                success:function(res){ 
                  $('#state_error').empty();
                   $('#state_list').empty();  
                   $('#state').empty();   
                  if(res.data)
                  {
                      console.log(res.data);  
                        var content = '';
                        content = `<select class="multiple-select" id="state" name="state[]" multiple="multiple" placeholder="States">`
                        $.each(res.data, function(index, val) {                        
                            content += `<option value="${val}"> ${val}</option>`
                        });
                        content += `</select>`;
                       
                        $('#state_list').append(content);    
                  }
                  else
                  {
                      $('#state_error').text(res.error);
                  }
                },
                error:function(res) {
                    console.log(res);
                    
                }
            });
          }    
        });
    });
    //get warehouse
   
</script>
@endsection

