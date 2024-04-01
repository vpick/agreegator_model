@extends('common-app/master')
@section('title', 'Users Card')
@section('content')
@php
if(Auth::user()->user_type == 'isCompany')
{
    if(session()->has('client') && !session()->has('warehouse')) 
    {
        echo '<script>window.location.href = "'.url('/get-client', [\Crypt::encrypt(Auth::user()->company->id)]).'";</script>';
    }
    else {
        // Your alternative logic if the condition is not met
    }
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
                    @if(!empty($data))
                        <form  class='row g-3 align-items-center'  method="post" action="{{ route('user.update', $data->id) }}">
                        @method('PUT')
                    @else
                      <form class='row g-3 align-items-center'  method="post" action="{{ route('user.store') }}">
                    @endif
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
                        <input class="form-control" id="password" type="password" name="password"  value="{{ old('password') }}">
                        @error('password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
					<div class="col-lg-6">
                        <label class="form-label" for="role_id">Role *</label>
                        <select class="form-control" id="role_id" name="role_id" required {{ $data ? 'disabled' : '' }}>
                            <option value="">Select Role</option>
                            @foreach($roles as $role)
                            <option value="{{ $role->role}}" {{ $data ? ($data->role_id == $role->id ? 'selected' : '') : ''}}>{{ $role->role }}</option>  
                            @endforeach                     
                        </select>
                        @error('role_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                  </div>
                 
                  <div class="col-lg-6">
                      <label class="form-label" for="user_type">User Type *</label>
                      <input class="form-control" id="user_type" name="user_type" required readonly {{ $data ? 'disabled' : '' }} value="{{ $data ? $data->user_type : '' }}">
                      <!--<select class="form-control" id="user_type" name="user_type" required {{ $data ? 'disabled' : '' }}>    -->
                      <!--  <option value="">Select type</option>-->
                      <!--  <option value="isClient" {{ $data ? ($data->user_type == 'isCompany' ? 'selected' : '') : ''}}>Is Company</option> -->
                      <!--  <option value="isClient" {{ $data ? ($data->user_type == 'isClient' ? 'selected' : '') : ''}}>Is Client</option> -->
                      <!--  <option value="isUser" {{ $data ? ($data->user_type == 'isUser' ? 'selected' : '') : ''}}>Is User</option>  -->
                      <!--</select>-->
                      </div>
                    <div class="col-lg-6">
                      <label class="form-label" for="company_id">Company *</label>
                      <input class="form-control" id="company_id" type="text" name="company_id" value="{{ Auth::user()->company->name }}" required {{ $data ? 'disabled' : '' }} readonly>
                      @error('company_id')
                        <span class="text-danger">{{ $message }}</span>
                      @enderror
                  </div>
                
                    <div class="col-lg-6">
                      <label class="form-label" for="client_id">Client *</label>
                     
                      <input class="form-control" type="text" value="{{ $clientName }}" required  readonly {{ $data ? 'disabled' : '' }}>
                      <input class="form-control" id="client_id" type="hidden" name="client_id" value="{{ $clientId }}" required  readonly {{ $data ? 'disabled' : '' }}>
                      
                      
                      @error('client_id')
                        <span class="text-danger">{{ $message }}</span>
                      @enderror
                  </div>
                 
                    <div class="col-lg-6">
                      <label class="form-label" for="warehouse_id">Warehouse *</label>
                      <input class="form-control" type="text" value="{{ $warehouseName }}" required  readonly {{ $data ? 'disabled' : '' }}>
                      <input class="form-control" id="warehouse_id" type="hidden" name="warehouse_id" value="{{ $warehouseId }}" required  readonly {{ $data ? 'disabled' : '' }}>
                      <!--<select class="form-control" id="warehouse_id" name="warehouse_id" required {{ $data ? 'disabled' : '' }}>-->
                      <!--<option value="">Select Warehouse</option>-->
                      <!--@foreach($warehouses as $warehouse)-->
                      <!--  <option value="{{ $warehouse->id }}" {{ $data ? ($data->warehouse_id == $warehouse->id ? 'selected' : '') : ''}}>{{ $warehouse->warehouse_name }}</option>-->
                      <!--  @endforeach-->
                      <!--</select>-->
                      @error('warehouse_id')
                        <span class="text-danger" >{{ $message }}</span>
                      @enderror
                  </div>
                 
                  
                    <div class="col-lg-6">
                      <label class="form-label" for="multi_client">Multiple Client *</label>
                      <input type="hidden" class="form-control" id="multi_client" name="multi_client" required {{ $data ? 'disabled' : 'readonly' }} >
                      <input type="text" class="form-control" id="multi_client_name"  required {{ $data ? 'disabled' : 'readonly' }} value="{{ $data ? (($data->multi_client ==1) ? 'Yes':'No') : ''}}">
                      <!--<select class="form-control" id="multi_client" name="multi_client" required {{ $data ? 'disabled' : '' }}>-->
                      <!--  <option value="">Select type</option>-->
                      <!--  <option value="1" {{ $data ? ($data->multi_client == '1' ? 'selected' : '') : ''}}>Yes</option>-->
                      <!--  <option value="0" {{ $data ? ($data->multi_client == '0' ? 'selected' : '') : ''}}>No</option>-->
                      <!--</select>-->
                      @error('multi_client')
                        <span class="text-danger">{{ $message }}</span>
                      @enderror
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
                    
                     <div class="col-lg-6">
                      <label class="form-label" for="phone">Mobile no. *</label>
                      <input class="form-control" id="phone" type="text" name="phone" required value="{{ $data ? $data->mobile :old('phone') }}">  
                      @error('phone')
                        <span class="text-danger">{{ $message }}</span>
                      @enderror
                    </div>
					          <div class="col-lg-6">
                      <label class="form-label" for="email">Email *</label>
                      <input class="form-control" id="email" type="email" name="email" required value="{{ $data ? $data->email :old('email') }}"> 
                      @error('email')
                        <span class="text-danger">{{ $message }}</span>
                      @enderror
                    </div>
                  <div class="col-lg-3 pt-4">
                    <button class="btn btn-primary" type="submit">Submit</button>
                    <a href="{{ route('user.index') }}" class="btn btn-danger">Cancel</a>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            </div>
            <div class="row" style="display:none">
            <!-- Modal Form-->
            <div class="col-lg-6">
              <div class="card">
                <div class="card-header border-bottom">
                  <h3 class="mb-0">Signin Modal</h3>
                </div>
                <div class="card-body text-center">
                  <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#myModal">Form in simple modal </button>
                  <!-- Modal-->
                  <div class="modal fade text-start" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="myModalLabel">Modal Form</h5>
                          <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          <p>Lorem ipsum dolor sit amet consectetur.</p>
                          <form>
                            <div class="mb-3">
                              <label class="form-label" for="modalInputEmail1">Email address</label>
                              <input class="form-control" id="modalInputEmail1" type="email" aria-describedby="emailHelp">
                              <div class="form-text" id="emailHelp">We'll never share your email with anyone else.</div>
                            </div>
                            <div class="mb-3">
                              <label class="form-label" for="modalInputPassword1">Password</label>
                              <input class="form-control" id="modalInputPassword1" type="password">
                            </div>
                          </form>
                        </div>
                        <div class="modal-footer">
                          <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                          <button class="btn btn-primary" type="button">Save changes</button>
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
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {   
      //get client
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
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
                url:'/load/warehouse/'+parentID,
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

