@extends('common-app/master')
@section('title', 'Api Documents')
@section('content')
@php

$clientId ='';

if(Session::has('client')){
    $clientId = session('client.id');
     $clientName = session('client.name');
}
else{
    $clientId = Auth::user()->client->id;
    $clientName = Auth::user()->client->name;
}
@endphp
<section class="py-2"> 
    <div class="col-md-12 text-end" style="margin-left: -2rem !important">
        <input type="hidden" id="client_id" value="{{ $clientId }}">
        @if($enableUser)
            @if(!empty($userP) && $userP->update != '1')
                <button type="button" class="btn btn-sm btn-primary" onclick="checkPermission()">Re-generate</button>
            @else
		        <button type="button" class="btn btn-sm btn-primary" onclick="reGenerate({{ $enableUser->id}})">Re-generate</button>
		    @endif
        @else
            @if(!empty($userP) && $userP->write != '1')
                <a href="#" class="btn btn-sm btn-info" onclick="checkPermission()"> Generate Api User Credential</a>
            @else
                <a href="{{ route('api-user.show',(\Crypt::encrypt($clientId))) }}" class="btn btn-sm btn-info"> Generate Api User Credential</a>
            @endif
        @endif
        @if(!empty($userP) && $userP->download != '1')
            <a class="btn btn-sm btn-primary" href='#' onclick="checkPermission()">Download API Sample</a>
        @else
            <a target='_blank' class="btn btn-sm btn-primary" href='/apidoc/OmneelabLogisticsApi.pdf'>Download API Sample</a>
        @endif
    </div>
</section>
<section class="pb-5"> 
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header border-bottom">
                    <h3 class="h4 mb-0">Api User Credentials</h3>
                    </div>
                    <div class="card-body">                   
                    <table class="table align-middle mb-0 bg-white">
                        <thead class="bg-light">
                            <tr>
                                <th>#</th>
                                <th>User Name</th>
                                <th>Password</th>
                                <th>Access Token</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($enableUser)
                            <tr style='border-radius: 5px 5px 4px 5px;box-shadow: 1px 1px 1px 0px aqua;'>
                                <td>1
                                </td>
                                <td><p class="fw-normal mb-1">{{ $enableUser->username  }}</p>
                                </td>
                                <td><p class="fw-normal mb-1"><input type="password" id="password" value="{{ $enableUser->show_password }}" style="border:none"><span><i id="toggler" class="fa fa-eye-slash"></i></span></p>
                                </td>   
                                <td>
                                    @if(!empty($enableUser->access_token))
                                        <input type="text" value="{{ $enableUser->access_token }}" id="myInput" style="display:none">
                                        <button onclick="myFunction()" class="btn btn-xs btn-info">access_token</button>
                                    @endif
                                </td>  
                                <td>
                                        @if(!empty($userP) && $userP->update != '1')
                                            @if($enableUser->is_active == '1')
        									    
        										<button type="button" class="btn btn-xs btn-primary" onclick="checkPermission()">Enable</button>
        									@else
        									
        										<button type="button" class="btn btn-xs btn-danger" onclick="checkPermission()">Disable</button>
        									@endif
                                            
                                        @else
                                 
        									@if($enableUser->is_active == '1')
        									    
        										<button type="button" class="btn btn-xs btn-primary" onclick="status({{ $enableUser->id}})">Enable</button>
        									@else
        									
        										<button type="button" class="btn btn-xs btn-danger" onclick="status({{ $enableUser->id}})">Disable</button>
        									@endif
        								@endif
    							
								</td>
                            </tr>
                           @endif
                        </tbody>
			        </table>
                        
                    </div>
                </div>
                <a href="{{ url()->previous() }}" class="btn btn-warning">Back</a>
            </div>      
        </div>         
    </div>
</section>
<script>
  var password = document.getElementById('password');
  var toggler = document.getElementById('toggler');
  showHidePassword = () => {
    if (password.type == 'password') {
      password.setAttribute('type', 'text');
      toggler.classList.add('fa-eye');
      toggler.classList.remove('fa-eye-slash');
    } 
    else {
      toggler.classList.add('fa-eye-slash');
      toggler.classList.remove('fa-eye');
      password.setAttribute('type', 'password');
    }
  };
  toggler.addEventListener('click', showHidePassword);
</script>
<script>
    function myFunction() {
      // Get the text field
      var copyText = document.getElementById("myInput");
    
      // Select the text field
      copyText.select();
      copyText.setSelectionRange(0, 99999); // For mobile devices
    
       // Copy the text inside the text field
      navigator.clipboard.writeText(copyText.value);
    
      // Alert the copied text
      alert("Copied!");
      
    }
</script>
<script>
        function reGenerate(n) {
            var url = "{{ route('api-user.edit', ':userId') }}".replace(':userId', n);
            if (n) {
              swal.fire({
                title: "Warning",
                text: "Are you sure? if you do, api will not work without updating credential in your system.",
                type: "warning",
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
</script>
<script>
        function status(n) {
            var url = "{{ route('key.change', ':userId') }}".replace(':userId', n);
            if (n) {
              swal.fire({
                title: "Warning",
                text: "Are you sure? if you do, api will not work. ? ",
                type: "warning",
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
</script>
@endsection


