@extends('admin-app.admin-master')
@section('title', 'Warehouse Card')
@section('content')
@php
    if(session()->has('company') && !session()->has('client') && !session()->has('warehouse')){
          echo '<script>window.location.href = "/company_list";</script>';
    }
    else if(session()->has('company') && session()->has('client') && !session()->has('warehouse')){
          echo '<script>window.location.href = "/company_list";</script>';
    }
    else{
         
    }
@endphp
<header class="py-4">
        <div class="container-fluid py-2">
          <h1 class="h3 fw-normal mb-0">Warehouse details</h1>
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
                  <form  class='row g-3 align-items-center'  method="post" action="{{ route('app-warehouse.update', $data->id) }}">
                  @method('PUT')
                @else
                  <form class='row g-3 align-items-center'  method="post" action="{{ route('app-warehouse.store') }}">
                @endif
                @csrf
                <div class="col-lg-3">
                    <label class="form-label" for="company_id">Company *</label>
                    <select class="form-control" id="company_id" name="company_id" required {{  $data ? ($data->company_id ? 'disabled' : '') : '' }}>
                      <option value="">Select company</option>
                      @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ $data ? ($data->company_id == $company->id ? 'selected' : '') :  ($companyId == $company->id ? 'selected':'')}}>{{ $company->name }}</option>
                      @endforeach
                    </select>
                    @error('company_id')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                  <div class="col-lg-3">
                    <label class="form-label" for="client_id">Client *</label>
                    <select class="form-control" id="client_id" name="client_id" required {{  $data ? ($data->client_id ? 'disabled' : '') : '' }}>
                  
                      <option value="">Select client</option>
                      @if(!empty($clientId))
                        @foreach($clients as $client)
                          <option value="{{ $client->id }}" {{ $data  ? ($data->client_id == $client->id ? 'selected' : '') : ($clientId == $client->id ? 'selected' : '' )}}>{{ $client->name }}</option>
                        @endforeach
                      @endif
                    </select>
                    @error('client_id')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                  
                  <div class="col-lg-3">
                    <label class="form-label" for="warehouse_code">Warehouse Code *</label>
                    
                    <input class="form-control" value="{{ $data->warehouse_code ?? '' }}" id="warehouse_code" type="text" name="warehouse_code" {{  $data ? ($data->warehouse_code ? 'disabled' : '') : '' }}>
                    @error('warehouse_code')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                 
                  <div class="col-lg-3">
                    <label class="form-label" for="warehouse_name">Warehouse Name *</label>
                    <input class="form-control" id="warehouse_name" name="warehouse_name" value="{{ $data ? $data->warehouse_name : old('warehouse_name') }}" type="text" required>
                    @error('warehouse_name')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                  <div class="col-lg-3">
                    <label class="form-label" for="contact_name">Contact Name *</label>
                    <input class="form-control" id="contact_name" name="contact_name" value="{{ $data ? $data->contact_name : old('contact_name') }}" type="text" required>
                    @error('contact_name')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                  <div class="col-lg-3">
                    <label class="form-label" for="phone">Contact no. *</label>
                    <input class="form-control" id="phone" type="text"  name="phone" required value="{{ $data ? $data->phone : old('phone') }}" required>
                    @error('phone')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                  
                  <div class="col-lg-3">
                    <label class="form-label" for="gst_no">Gst no </label>
                    <input class="form-control" id="gst_no" name="gst_no" type="text" required value="{{ $data ? $data->gst_no : old('gst_no') }}" >
                    @error('gst_no')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                  <div class="col-lg-3">
                    <label class="form-label" for="support_email">Support Email id *</label>
                    <input class="form-control" id="support_email" type="email" name="support_email" value="{{ $data ? $data->support_email : old('support_email') }}" required>
                    @error('support_email')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                  <div class="col-lg-3">
                    <label class="form-label" for="support_phone">Support Phone No. *</label>
                    <input class="form-control" id="support_phone" type="text" name="support_phone" required value="{{ $data ? $data->support_phone : old('support_phone') }}">
                    @error('support_phone')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                  
                  <div class="col-lg-3">
                    <label class="form-label" for="state">State *</label>
                    <select class="form-control" id="state" name="state_id" required>
                      <option value="">Select state</option>
                      @foreach($states as $state)
                        <option value="{{ $state->id }}" {{ $data ? ($data->state_id == $state->id ? 'selected' : '') : ''}}>{{ $state->state_name }}</option>
                      @endforeach
                    </select>
                    @error('state_id')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                  <div class="col-lg-3">
                    <label class="form-label" for="city">City *</label>
                    <input class="form-control" id="city" name="city" type="text" required value="{{ $data ? $data->city : old('city') }}">
                    @error('city')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                  <div class="col-lg-3">
                    <label class="form-label" for="pincode">Pin Code *</label>
                    <input class="form-control" id="pincode" name="pincode" type="text" required value="{{ $data ? $data->pincode : old('pincode') }}">
                    @error('pincode')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                  <div class="col-lg-3">
                    <label class="form-label" for="address1">Address1 *</label>
                    <input class="form-control" id="address1" name="address1" type="text" required value="{{ $data ? $data->address1 : old('address1') }}">
                    @error('address1')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                  <div class="col-lg-3">
                    <label class="form-label" for="address2">Address2 *</label>
                    <input class="form-control" id="address2" name="address2" type="text" required value="{{ $data ? $data->address2 : old('address2') }}">
                    @error('address2')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                  <div class="col-lg-4">
                    <div class="pt-2 mt-3">
                      <button class="btn btn-primary" type="submit">Submit</button>
                      <a href="{{ route('app-warehouse.index') }}" class="btn btn-danger">Cancel</a>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        
      //get client
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#company_id').on('change',function(){
            var parentID = $(this).val();
            console.log('parentID');
            if(parentID){
            $.ajax({
                url:'/load/client/'+parentID,
                type:'GET',
                success:function(res){ 
                  console.log(res.data);                        
                    $('#client_id').empty();    
                    var content = '';
                    content = `<option value="">Select Client</option>`
                    $.each(res.data, function(index, val) {                        
                        content += `<option value="${val['id'] }"> ${val['name'] }</option>`
                    });
                    $('#client_id').append(content);     
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
