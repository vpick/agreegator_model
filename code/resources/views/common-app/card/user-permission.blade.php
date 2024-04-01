@extends('common-app/master')
@section('title', 'User Permission Card')
@section('content')
<style>
    .ms-1
    {
        
        padding:5px;
        margin-left:0px !important;
    }
</style>
<header class="py-4">
    <div class="container-fluid py-2">
        <h1 class="h3 fw-normal mb-0"></h1>
    </div>
</header>
<section>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-2"></div>
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header border-bottom">
                        <h2 class="h5 fw-normal mb-0"><a class="card-collapse-link text-dark d-block" data-bs-toggle="collapse" href="#feeds-box" aria-expanded="true">User Detail
                        </a></h2>
                    </div>
                    <div class="card-body-0">
                        <div class="collapse show" id="feeds-box" role="tabpanel">
                        <!-- Item-->
                        <div class="p-3 border-bottom border-gray-200">
                            <div class="d-flex justify-content-between">
                            <div class="d-flex">
                                <div class="ms-3">
                                    <h5 class="fw-normal upcase" >{{ $data['user']->username }}</h5>
                                    <input type="hidden" value="{{ $data['user']->id }}" id="userId">
                                    <p class="mb-0 text-xs text-gray-600 lh-1"><strong>Role : </strong><span >{{ $data['user']->role->role }}</span> User</p>
                                    
                                </div>
                            </div>
                            <!-- <div class="text-end"><small class="text-gray-500">5min ago</small></div> -->
                            </div>
                        </div>
                        
                        </div>
                    </div>
                </div>
            <div class="col-lg-2"></div>
        </div>
    </div>
</section>
<!-- Basic Form-->
<div class="col-md-12" >
    @if(\Request::old('success'))
        <div class="alert alert-success" > {{\Request::old('success')}} </div>
    @elseif(\Request::old('error'))
        <div class="alert alert-danger" > {{\Request::old('error')}} </div>
    @endif

    <div class="alert alert-success" id="status"> </div>
</div>
<section class="tables">   
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header border-bottom">
                        <h3 class="h4 mb-0">User Permission</h3>
                    </div>
                    
                    <div class="card-body">
                        <!--<div class="table-responsive">
                            <table class="table align-middle mb-0 bg-white">
                                <thead class="table-bg">
                                    <tr>
                                        <th>#</th>
                                        <th>Read</th>
                                        <th>Write</th>
                                        <th>Update</th>
                                        <th>Delete</th>				  
                                    </tr>
                                </thead>
                                <tbody>	
                                    @foreach($data['pages'] as $page)	
                                   
                                        <tr>
                                            <td class="page-cap"> {{ $page->pagename }}</td>
                                            <td><input type="checkbox" class="form-check-input permit" id="read:{{ $page->id }}" {{ $page->read == '1' ? 'checked' : ''}}></td>   
                                            <td><input type="checkbox" class="form-check-input permit" id="write:{{ $page->id }}" {{ $page->write == '1' ? 'checked' : ''}}></td>   
                                            <td><input type="checkbox" class="form-check-input permit" id="update:{{ $page->id }}" {{ $page->update == '1' ?'checked' : ''}}></td>   
                                            <td><input type="checkbox" class="form-check-input permit" id="delete:{{ $page->id }}" {{ $page->delete == '1' ?'checked' : ''}}></td>                                           
                                        </tr> 
                                    @endforeach
                                </tbody>
                            </table>
                        </div>-->
						<div class="row">
						@foreach($data['pages'] as $page)
						<div class="col-xl-4 col-md-4 col-6 gy-4 gy-xl-0">
                            <!-- Icon, Title and Description Card -->
                            <div class="card pmd-card">
                                    <div class="card-body" style='padding:1px !important'>
                                        <h2 class="card-title">{{ ucwords($page->pagename) }}</h2>
                                        <div class="d-flex justify-content-between">
                                            <div class="d-flex">
                                                <div class="ms-1">
                                                  <h3 class="h6 text-dark text-uppercase fw-normal">Read</h3>
                                                  <input type="checkbox" class="form-check-input permit js-switch" id="read:{{ $page->id }}" {{ $page->read == '1' ? 'checked' : ''}}>
                                                </div>
                                                <div class="ms-1">
                                                  <h3 class="h6 text-dark text-uppercase fw-normal">Write</h3>
                                                  <input type="checkbox" class="form-check-input permit js-switch" id="write:{{ $page->id }}" {{ $page->write == '1' ? 'checked' : ''}}>
                                                </div>
                                                <div class="ms-1">
                                                  <h3 class="h6 text-dark text-uppercase fw-normal">Delete</h3>
                                                  <input type="checkbox" class="form-check-input permit js-switch" id="delete:{{ $page->id }}" {{ $page->delete == '1' ?'checked' : ''}}>
                                                </div>
                                            <!--</div>
                                            </div>
                                            <hr />
                                            <div class="d-flex justify-content-between">
                                            <div class="d-flex">-->
                                                <div class="ms-1">
                                                  <h3 class="h6 text-dark text-uppercase fw-normal">Update</h3>
                                                  <input type="checkbox" class="form-check-input permit js-switch" id="update:{{ $page->id }}" {{ $page->update == '1' ?'checked' : ''}}>
                                                </div>
                                                <div class="ms-1">
                                                  <h3 class="h6 text-dark text-uppercase fw-normal">Download</h3>
                                                  <input type="checkbox" class="form-check-input permit js-switch" id="download:{{ $page->id }}" {{ $page->download == '1' ?'checked' : ''}}>
                                                </div>
                                                <div class="ms-1">
                                                  <h3 class="h6 text-dark text-uppercase fw-normal">Print</h3>
                                                  <input type="checkbox" class="form-check-input permit js-switch" id="print:{{ $page->id }}" {{ $page->print == '1' ?'checked' : ''}}>
                                                </div>
                                            </div>
                                            </div>
                                    </div>
                            </div>
                        </div>
						@endforeach
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
      $('#status').hide();
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).on('change', '.permit', function() {
            //debugger
            var uid = $('#userId').val();
            var pageId = $(this).attr('id');
            var str=pageId.split(':');
            var pid = str[1];     
            var pagename = str[0];   
            if( $(this).is(':checked') ){
                var permission = '1';
            }
            else{
                var permission = '0';
            }
            console.log(uid,pid,pagename,permission);
            if(pageId){
                $.ajax({
                    url:'/load/permission',               
                    type:'POST', 
                    data:{user_id:uid,page_id:pid,pagename:pagename,permission:permission,_token:'{{ csrf_token() }}'},             
                    success:function(res){      
                        console.log(res);   
                        if(res.data === 'add'){
                            //console.log('if');
                            //$('#status').show();
                            $('#status').html('Permission added!');
                        }   
                        else{
                            //console.log('else');
                            $('#status').show();
                            $('#status').html('Permission updated!');
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
