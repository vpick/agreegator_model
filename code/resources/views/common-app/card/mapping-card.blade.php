@extends('common-app/master')
@section('title', 'Mapping Card')
@section('content')
<header class="py-4">
  <div class="container-fluid py-2">
    <h1 class="h3 fw-normal mb-0">User Warehouse Mapping</h1>
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

                <div class="alert alert-success" id="status"> </div>
            </div>
    <div class="col-lg-12"> 
        <div class="card">
            <div class="card-body">
                <!-- Updates Section -->
                <section class="mb-5">
                    <div class="container-fluid">
                        <div class="row">  
                            <input type="hidden"  id="user_id" value="{{ $user->id }}">                                                 
                            @foreach($clients as $client)       
                                <div class="col-lg-4 col-md-12" >
                                    <!-- Recent Updates Widget  -->
                                    <div class="card">
                                        <div class="card-header border-bottom">     
                                            <h2 class="h5 fw-normal mb-0 bg-primary p-2">
                                                <strong><a class="card-collapse-link text-dark d-block client_head" data-bs-toggle="collapse" 
                                                href="#updates-box{{$client->id}}" aria-expanded="true"> 
                                                {{ $client->name }}</a></strong>
                                                <input type="hidden" class="client" id="client:{{ $client->id }}" value="{{ $client->id }}">
                                            </h2>                                                
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="collapse show" id="updates-box{{$client->id}}" role="tabpanel">
                                                <div class="list-unstyled">                                                                                                            
                                                   @foreach ($client->warehouse as $w)                                                   
                                                    @php 
                                                        $arr = explode(',',$user->warehouse_map);
                                                        $sel = in_array($w->id, $arr) ? 'checked' : '';    
                                                    @endphp     
                                                        @if($user->multi_location == '1')                                                                                                              
                                                            <div class="p-3 d-flex justify-content-between">
                                                                <div class="d-flex">
                                                                    <div class="form-check">     
                                                                        <input type="checkbox" class="form-check-input warehouse" id="warehouse:{{ $w->id.'-'.$client->id }}" 
                                                                         value="{{ $w->id }}" {{ $sel }} {{ $user->warehouse_id == $w->id ? 'disabled' : '' }} >   
                                                                    </div>
                                                                    <div class="ms-3">
                                                                        <h5 class="fw-normal text-gray-600 mb-1">{{ $w->warehouse_name }} <span>{{ $user->warehouse_id == $w->id ? '(Default warehouse)' : '' }}</span></h5>
                                                                    </div>
                                                                </div>                                
                                                            </div> 
                                                        @else                                    
                                                            <div class="p-3 d-flex justify-content-between">
                                                                <div class="d-flex">
                                                                    <div class="form-check">
                                                                        <input type="checkbox" class="form-check-input warehouse" name="warehouse{{$client->id}}" 
                                                                        id="warehouse:{{ $w->id.'-'.$client->id }}" value="{{ $w->id }}" {{ $sel }} {{ $user->warehouse_id == $w->id ? 'disabled' : '' }}>
                                                                    </div>
                                                                    <div class="ms-3">
                                                                        <h5 class="fw-normal text-gray-600 mb-1">{{ $w->warehouse_name }}</h5>
                                                                    </div>
                                                                </div>                                
                                                            </div> 
                                                        @endif
                                                    @endforeach  
                                                <!-- Item-->  
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Recent Updates Widget End-->
                                </div>                              
                            @endforeach    
                                                   
                        </div>
                    </div>   
                </section>
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
      $('#status').hide();
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).on('change', '.warehouse', function() {
            debugger
            var uid = $('#user_id').val();
            var wareID = $(this).attr('id');
            var element = document.getElementById(wareID);
            var inputType = element.type; 
            var str=wareID.split(':');
            var num = str[1];
            var rel = num.split('-');           
            var wid =rel[0];
            var cid =rel[1];           
            if( $(this).is(':checked') ){
                var act = 'add';
            }
            else{
                var act = 'remove';
            }
            console.log(uid,cid,wid,act,inputType);
            if(wareID){
            $.ajax({
                url:'/load/map',               
                type:'POST', 
                data:{client_id:cid,warehouse_id:wid,user_id:uid,action:act,inputType:inputType,_token:'{{ csrf_token() }}'},             
                success:function(res){      
                    console.log(res);   
                    if(res.data === 'add'){
                        
                        $('#status').show();
                        $('#status').html('Mapping added!');
                    }   
                    else{
                        
                        $('#status').show();
                        $('#status').html('Mapping removed!');
                    }                
                },
                error:function(res) {
                    console.log(res.error);
                }
            });
          }    
        });
    });
  
    
</script>

