@extends('admin-app.admin-master')
@section('title', 'Users Card')
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
    <h1 class="h3 fw-normal mb-0">Users details</h1>
  </div>
</header>
      <!-- Forms Section-->
      <section class="pb-5"> 
        <div class="container-fluid">
          <div class="row">
            <!-- Basic Form-->
            <div class="col-md-12" >
              @if(\Request::old('success'))
              <div class="alert alert-success" > {{\Request::old('success')}} </div>
              @elseif(\Request::old('error'))
              <div class="alert alert-danger" > {{\Request::old('error')}} </div>
              @endif
            </div>
            <div class="col-lg-12"> 
              <div class="card">
                <!--<div class="card-header border-bottom">
                  <h3 class="h4 mb-0">Basic Form</h3>
                </div>-->
                <div class="card-body">
                  <form class='row g-3 align-items-center' method="POST" action="{{ route('app-user.store') }}">
                    @csrf
                    <div class="col-lg-6">
                      <label class="form-label" for="username">User Name *</label>
                      <input class="form-control" id="username" type="text"  name="username" required value="{{ $data ? $data->username : old('username') }}" {{ $data ? 'disabled' : '' }}>   
                      @error('username')
                        <span class="text-danger">{{ $message }}</span>
                      @enderror                   
                    </div>
                    <div class="col-lg-6" {{ $data ? 'hidden' : '' }}>
                      <label class="form-label" for="password">Password *</label>
                      <input class="form-control" id="password" type="password" name="password" required value="{{ old('password') }}">
                      @error('password')
                        <span class="text-danger">{{ $message }}</span>
                      @enderror
                    </div>
                    <div class="col-lg-6">
                      <label class="form-label" for="phone">Mobile no.</label>
                      <input class="form-control" id="phone" type="text" name="phone" required value="{{ $data ? $data->mobile :old('phone') }}" {{ $data ? 'disabled' : '' }}>  
                      @error('phone')
                        <span class="text-danger">{{ $message }}</span>
                      @enderror
                    </div>
					          <div class="col-lg-6">
                      <label class="form-label" for="email">Email *</label>
                      <input class="form-control" id="email" type="email" name="email" required value="{{ $data ? $data->email :old('email') }}" {{ $data ? 'disabled' : '' }}> 
                      @error('email')
                        <span class="text-danger">{{ $message }}</span>
                      @enderror
                    </div>
					<div class="col-lg-6">
                      <label class="form-label" for="role_id">Role *</label>
                      <select class="form-control" id="role_id" name="role_id" required {{ $data ? 'disabled' : '' }}>
                        <option value="">Select type</option>
                        @foreach($roles as $role)
                        <option value="{{ $role->role }}" {{ $data ? ($data->role_id == $role->id ? 'selected' : '') : ''}}>{{ $role->role }}</option>   
                        @endforeach                    
                      </select>
                      @error('role_id')
                        <span class="text-danger">{{ $message }}</span>
                      @enderror
                    </div>
                    <div class="col-lg-6">
                      <label class="form-label" for="user_type">User Type *</label>
                        <input class="form-control" id="user_type" name="user_type" required {{ $data ? 'disabled' : '' }} value="{{ $data ? $data->user_type : '' }}">
                      <!--<select class="form-control" id="user_type" name="user_type" required {{ $data ? 'disabled' : '' }}>-->
                      <!--   <option value="">Select type</option>                       -->
                      <!--  <option value="isSystem" {{ $data ? ($data->user_type == 'isSystem' ? 'selected' : '') : ''}}>Is System</option> -->
                      <!--  <option value="isCompany" {{ $data ? ($data->user_type == 'isCompany' ? 'selected' : '') : ''}}>Is Company</option>   -->
                      <!--   <option value="isClient" {{ $data ? ($data->user_type == 'isClient' ? 'selected' : '') : ''}}>Is Client</option>   -->
                      <!--  <option value="isUser" {{ $data ? ($data->user_type == 'isUser' ? 'selected' : '') : ''}}>Is User</option>                 -->
                      <!--</select>-->
                      @error('user_type')
                        <span class="text-danger">{{ $message }}</span>
                      @enderror
                    </div>
                    <div class="col-lg-6">
                      <label class="form-label" for="company_id">Company *</label>
                      <select class="form-control" id="company_id" name="company_id" required {{ $data ? 'disabled' : '' }}>
                        <option value="">Select Company</option>
                        @foreach($companies as $company)
                          <option value="{{ $company->id }}" {{ $data ? ($data->company_id == $company->id ? 'selected' : '') : ($companyId == $company->id ? 'selected':'')}}>{{ $company->name }}</option>
                        @endforeach
                      </select>
                      @error('company_id')
                        <span class="text-danger">{{ $message }}</span>
                      @enderror
                    </div>
                 
                    <div class="col-lg-6">
                      <label class="form-label" for="client_id">Client *</label>
                      <select class="form-control" id="client_id" name="client_id" required {{ $data ? 'disabled' : '' }}>
                      <option value="">Select Client</option>
                      @foreach($clients as $client)
                          <option value="{{ $client->id }}" {{ $data ? ($data->client_id == $client->id ? 'selected' : '') : ($clientId == $client->id ? 'selected' : '' )}}>{{ $client->name }}</option>
                        @endforeach
                      </select>
                      @error('client_id')
                        <span class="text-danger">{{ $message }}</span>
                      @enderror
                    </div>
                    <div class="col-lg-6">
                      <label class="form-label" for="warehouse_id">Warehouse *</label>
                      <select class="form-control" id="warehouse_id" name="warehouse_id" required {{ $data ? 'disabled' : '' }}>
                      <option value="">Select Warehouse</option>
                      @foreach($warehouses as $warehouse)
                          <option value="{{ $warehouse->id }}" {{ $data ? ($data->warehouse_id == $warehouse->id ? 'selected' : '') : ($warehouseId == $warehouse->id ? 'selected' : '' )}}>{{ $warehouse->warehouse_name }}</option>
                        @endforeach
                      </select>
                      @error('warehouse_id')
                        <span class="text-danger">{{ $message }}</span>
                      @enderror
                    </div>
                  
                  
                     <div class="col-lg-6 multi_choice_client">
                      <label class="form-label" for="multi_client">Multiple Client *</label>
                      <input type="hidden" class="form-control" id="multi_client" name="multi_client" required {{ $data ? 'disabled' : 'readonly' }} >
                      <input type="text" class="form-control" id="multi_client_name"  required {{ $data ? 'disabled' : 'readonly' }} value="{{ $data ? (($data->multi_client ==1) ? 'Yes':'No') : ''}}">
                      <!--<select class="form-control" id="multi_client" name="multi_client" required {{ $data ? 'disabled' : '' }}>-->
                      <!--  <option value="">Select type</option>-->
                      <!--  <option value="1" {{ $data ? ($data->multi_client == '1' ? 'selected' : '') : ''}}>Yes</option>-->
                      <!--  <option value="0" {{ $data ? ($data->multi_client == '0' ? 'selected' : '') : ''}}>No</option>-->
                      <!--</select>-->
                    </div>
                    <div class="col-lg-6 multi_choice_warehouse">
                      <label class="form-label" for="multi_location">Multiple Location *</label>
                      <div id="multi_text">
                          <input type="hidden" class="form-control" id="multi_location" name="multi_location" required {{ $data ? 'disabled' : 'readonly' }}>
                          <input type="text" class="form-control" id="multi_location_name"  required {{ $data ? 'disabled' : 'readonly' }} value="{{ $data ? (($data->multi_location == 1) ? 'Yes':'No') : ''}}">
                      </div>
                       <div id="multi_select" style="display:none">
                          <select class="form-control" id="multi_location_id" name="multi_location" required {{ $data ? 'disabled' : '' }}>
                            <option value="">Select type</option>
                            <option value="1" {{ $data ? ($data->multi_location == '1' ? 'selected' : '') : ''}}>Yes</option>
                            <option value="0" {{ $data ? ($data->multi_location == '0' ? 'selected' : '') : ''}}>No</option>
                          </select>
                       </div>
                       @error('multi_location')
                        <span class="text-danger">{{ $message }}</span>
                      @enderror
                    </div>
                  
                  <div class="col-lg-3 pt-4">
                    @if(!$data)
                    <button class="btn btn-primary" type="submit">Submit</button>
                    <a href="{{ route('app-user.index') }}" class="btn btn-danger" {{ $data ? 'disabled' : '' }}>Cancel</a>
                    @endif
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
        
        $('#user_type').on('change',function(){
            var parentID = $(this).val();
            if(parentID == 'isSystem' && parentID == 'isCompany'){
              var cl_box = '<label class="form-label" for="multi_client">Multiple Client *</label><input type="text" value="Yes" class="form-control" id="multi_client" readonly><input type="hidden" value="1" name="multi_client">';
              var wr_box = '<label class="form-label" for="multi_location">Multiple Location *</label><input type="text" value="Yes" class="form-control" id="multi_location" readonly><input type="hidden" value="1" name="multi_location">';
            }
            else if(parentID == 'isClient'){
              var cl_box = '<label class="form-label" for="multi_client">Multiple Client *</label><input type="text" value="Yes" class="form-control" id="multi_client" readonly><input type="hidden" value="0" name="multi_client">';
              var wr_box = '<label class="form-label" for="multi_location">Multiple Location *</label><select class="form-control" id="multi_location" name="multi_location" required><option value="">Select type</option><option value="1">Yes</option><option value="0">No</option></select>';
                
            }
            else{
              var cl_box = '<label class="form-label" for="multi_client">Multiple Client *</label><input type="text" value="No" class="form-control" id="multi_client" readonly><input type="hidden" value="0" name="multi_client">';
              var wr_box = '<label class="form-label" for="multi_location">Multiple Location *</label><input type="text" value="No" class="form-control" id="multi_location" readonly><input type="hidden" value="0" name="multi_location">';
            }
            $('.multi_choice_client').html(cl_box);  
            $('.multi_choice_warehouse').html(wr_box); 
        });
        $('#company_id').on('change',function(){
            var parentID = $(this).val();
            console.log('parentID');
            if(parentID){
            $.ajax({
                url:'/load/app-client/'+parentID,
                type:'GET',
                success:function(res){                         
                    $('#client_id').empty();    
                    var content = '';
                    content = `<option value="">Select Client</option>`
                    $.each(res.data, function(index, val) {                        
                        content += `<option value="${val['id'] }" > ${val['name'] }</option>`
                    });
                    $('#client_id').append(content);     
                },
                error:function(res) {
                    console.log(res.error);
                }
            });
          }    
        });
        $('#role_id').on('change',function(){
            var parentID = $(this).val();
            console.log(parentID);
            if(parentID == 'System'){
                $('#user_type').val('isSystem');
                $('#multi_text').css('display','block');
                $('#multi_select').css('display','none');
                $('#multi_location').val('1');
                $('#multi_location_name').val('Yes');
                $('#multi_client').val('1');
                $('#multi_client_name').val('Yes');
                $('#multi_location_id').prop('disabled', true);
            }   
            else if(parentID == 'Company'){
                $('#user_type').val('isCompany');
                $('#multi_text').css('display','block');
                $('#multi_select').css('display','none');
                $('#multi_location').val('1');
                $('#multi_location_name').val('Yes');
                $('#multi_client').val('1');
                $('#multi_client_name').val('Yes');
               $('#multi_location_id').prop('disabled', true);
            }
            else if(parentID == 'Client'){
                $('#user_type').val('isClient');
                $('#multi_text').css('display','none');
                $('#multi_select').css('display','block');
                $('#multi_client').val('0');
                $('#multi_client_name').val('No');
                $('#multi_location_id').prop('disabled', false);
            }
            else if(parentID == 'Warehouse'){
                $('#user_type').val('isUser');
                $('#multi_text').css('display','block');
                $('#multi_select').css('display','none');
                $('#multi_location').val('0');
                $('#multi_location_name').val('No');
                $('#multi_client').val('0');
                $('#multi_client_name').val('No');
                $('#multi_location_id').prop('disabled', true);
            }
        });
      
    });
    //get warehouse
    $(document).ready(function() {   
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#client_id').on('change',function(){
            var parentID = $(this).val();
            console.log('parentID');
            if(parentID){
            $.ajax({
                url:'/load/app-warehouse/'+parentID,
                type:'GET',
                success:function(res){                         
                    $('#warehouse_id').empty();    
                    var content = '';
                    content = `<option value="">Select Warehouse</option>`
                    $.each(res.data, function(index, val) {                        
                        content += `<option value="${val['id'] }"> ${val['warehouse_name'] }</option>`
                    });
                    $('#warehouse_id').append(content);     
                },
                error:function(res) {
                    console.log(res.error);
                }
            });
          }    
        });
    });
</script>
@endsection
