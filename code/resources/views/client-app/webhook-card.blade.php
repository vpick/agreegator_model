@extends('common-app/master')
@section('title', 'Invoice Setting')
@section('content')
<!-- <header class="py-4">
	<div class="container-fluid py-2">
	  <h1 class="h3 fw-normal mb-0">Order details</h1>
	</div>
</header> -->
<style>
    .m-t-50{
        margin-top: 50px;
    }
    .fs-14{
        font-size: 14px;
    }
    .fs-13{
        font-size: 13px;
    color: darkslategray;
    }
    .fw-5{
        font-weight: 500;
    }
    .m-b-10 {
    margin-bottom: 10px;
}
.pb-4, .py-4 {
    padding-bottom: 1.5rem!important;
}
.border-bottom {
    border-bottom: 1px solid #dce4ec!important;
}
.invoice-form-sec {
    border-radius: 5px;
    padding: 20px 30px;
}
.mt-4, .my-4 {
    margin-top: 1.5rem!important;
}

.form-group {
    margin-bottom: 1rem;
}
.table thead th {
    font-size: .7875rem;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: #0f4588;
    border-bottom-width: 1px;
    background-color: #fff;
}

element.style {
}
.table tfoot th, .table thead th {
    font-size: .7875rem;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: #0f4588;
    border-bottom-width: 1px;
    background-color: #fff;
}
.table-bordered thead td, .table-bordered thead th {
    border-bottom-width: 2px;
}
.table thead th {
    vertical-align: bottom;
    border-bottom: 2px solid #edf2f9;
}
.table-bordered td, .table-bordered th {
    border: 1px solid #edf2f9;
}
.table td, .table th {
    padding: 0.9375rem;
    vertical-align: top;
    border-top: 1px solid #edf2f9;
}
.input-group>.custom-file {
    display: flex;
    align-items: center;
}
.input-group {
    position: relative;
    display: flex;
    width: 100%;
    flex-wrap: wrap;
    align-items: stretch;
}
.input-group>.custom-file, .input-group>.custom-select, .input-group>.form-control, .input-group>.form-control-plaintext {
    position: relative;
    width: 1%;
    margin-bottom: 0;
    flex: 1 1 auto;
}
.custom-file {
    position: relative;
    display: inline-block;
    width: 100%;
    height: calc(1.5em + 0.75rem + 2px);
    margin-bottom: 0;
}
.custom-file-input {
    position: relative;
    z-index: 2;
    width: 100%;
    height: calc(1.5em + 0.75rem + 2px);
    margin: 0;
    opacity: 0;
}
label {
    display: inline-block;
    margin-bottom: 0.5rem;
}
.border-sz{
    border-left: 1px solid #c5c5c5;
    padding-top: 20px;
}
.border-right{
    border-right:1px solid #c5c5c5;
}
</style>
<!-- Forms Section-->
<section class="pb-5"> 
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card mt-4 m-b-10">
                    <div class="card-header border-bottom">
                        <div class="row">
                            <div class="col-md-4">
                                <h4 class="m-b-0">
                                    <i class="mdi mdi-24px mdi-file-document"></i> Create Webhook
                                </h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="invoice-form-sec mt-4">
                            <div class="row">
                                <div class="col-md-6 col-sm-6 border-right " >
                                @if (!empty($data))
                                    <form  class='row g-3 align-items-center'  method="post" action="{{ route('webhook.update', $data->id) }}">
                                    @method('PUT')
                                    @else
                                    <form class='row g-3 align-items-center'  method="post" action="{{ route('webhook.store') }}">
                                    @endif
                                    @csrf
                                        <div class="form-group mt-4 ">
                                            <label for="webhook_name">WebHook Name</label>
                                            <input type="text" name="webhook_name" value="{{ $data ? $data->webhook_name : old('webhook_name') }}" maxlength="10" class="form-control" id="webhook_name" placeholder="Webhook name">
                                            @if($errors->has('webhook_name'))
                    							<div class="error">{{ $errors->first('webhook_name') }}</div>
                    						@endif
                                        </div>
                                        
                                        <div class="form-group mt-4 ">
                                            <label for="webhook_url">Delivery Url</label>
                                            <input type="url" name="webhook_url" value="{{ $data ? $data->webhook_url : old('webhook_url') }}" class="form-control" id="webhook_url" placeholder="Webhook url">
                                            @if($errors->has('webhook_url'))
                    							<div class="error">{{ $errors->first('webhook_url') }}</div>
                    						@endif
                                        </div>
                                        <div class="form-group mt-4 ">
                                            <label for="webhook_secret">Secret</label>
                                            <input type="text" name="webhook_secret" value="{{ $data ? $data->webhook_secret : old('webhook_secret') }}" maxlength="10" class="form-control" id="webhook_secret" placeholder="Webhook secret">
                                            @if($errors->has('webhook_secret'))
                    							<div class="error">{{ $errors->first('webhook_secret') }}</div>
                    						@endif
                                        </div>
                                        <div class="form-group mt-4 ">
                                            <label for="webhook_status">status</label>
                                            <select name="webhook_status"class="form-control" id="webhook_status">
                                                <option value="Active" {{ $data ? ($data->webhook_status =='Active' ? 'selected' : '') : ''}}>Active</option>
                                                <option value="Pause" {{ $data ? ($data->webhook_status == 'Pause' ? 'selected' : '') : ''}}>Pause</option>
                                                <option value="Disable" {{ $data ? ($data->webhook_status == 'Disable' ? 'selected' : '') : ''}}>Disable</option>
                                            </select>
                                            @if($errors->has('webhook_status'))
                    							<div class="error">{{ $errors->first('webhook_status') }}</div>
                    						@endif
                                        </div>
                                        <div class="form-group text-center mb-4">
                                            <button type="submit" class="btn btn-sm btn-primary save_changs">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-6 col-sm-6 " >                                    
                                    <div class="card m-b-30">
                                        <div class="card-header">
                                            <h5 class="m-b-0">
                                                Instructions
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <p><b>Webhooks are event notifications sent to URLs of your choice. Use these to receive real time shipment updates in your system.</b></p>
                                            <p><b>Name:</b> Used to easily identify webhook.</p>
                                            <p><b>Delivery URL:</b> URL where the webhook payload is delivered.</p>
                                            <p><b>Secret:</b> The Secret Key generates a hash of the delivered webhook and is provided in the request headers. No hash generated if left blank.</p>
                                            <p><b>Status:</b> Set to Active (delivers payload), Paused (does not deliver), or Disabled (does not deliver due delivery failures).</p>
                                            <p></p>
                                            <p><b>Timeout:</b> System has a five second timeout period. It waits five seconds for a response to each request to a webhook. If there is no response, or an error is returned, then request is marked as failed. If there are 100 consecutive failures, then the webhook automatically get disabled.</p>
                                            <p><b>Webhook Response:</b> Your webhook acknowledges that it received data by sending a 200 OK response. Any response outside of the 200 range, including 3XX HTTP redirection codes, indicates that you did not receive the webhook. System does not follow redirects for webhook notifications and considers them to be an error response.</p>

                                            <p><b>Verifying Webhooks: </b> Webhooks data can be verified by calculating a digital signature. Each webhook request includes a base64-encoded X-Hmac-SHA256 header, which is generated using the secret key along with the data sent in the request. Here is sample hash generation method:
                                                <br>
                                                <code>$hash = base64_encode(hash_hmac('sha256', 'webhook_data_here', 'your_secret_here', true));</code>
                                            </p>

                                            <p><b>Sample Payload:</b><br>
                                                <code>
                                                    {
                                                    "awb_number": "4152912381315",
                                                    "status": "in transit",
                                                    "event_time": "2021-02-26 16:19:59",
                                                    "location": "Delhi",
                                                    "message": "Reached at nearest hub",
                                                    "rto_awb": ""
                                                    }
                                                </code>
                                            </p>


                                        </div>
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