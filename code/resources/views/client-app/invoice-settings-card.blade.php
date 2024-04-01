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
                                    <i class="mdi mdi-24px mdi-file-document"></i> Invoice Settings
                                </h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="invoice-form-sec mt-4">
                            <div class="row">
                                <div class="col-md-6 col-sm-6 border-right ">
                                    <form method="post" action="{{ route('invoice-settings.store') }}" enctype="multipart/form-data" >
                                        @csrf
                                        <div class="form-group">
                                            <h5>Show/hide Your Company Name</h5>
                                        </div>
                                       
                                        <div class="form-group border-bottom pb-4">
                                            <label class="cstm-switch">
                                                <span class="cstm-switch-description ml-0 h6 mr-2" style="font-weight: 500;color: #515151;">Hide Company Name On Invoice </span>
                                                <input type="checkbox"  id="company_id"  class="js-switch"  onclick="toggleSwitch()" 
                                                value="{{ $data ? (($data->company_name_toggle == '1') ? '1' : '0'): '0' }}" 
                                                {{ $data ? (($data->company_name_toggle == '1') ? 'checked' : ''): '' }}>
                                                <input type="hidden" id="comId" name="company_name" value="{{ $data ? (($data->company_name_toggle == '1') ? '1' : '0'): '0' }}">
                                            </label>
                                        </div>
                                        <div class="form-group mt-4 pb-4 border-bottom">
                                            <h5>Set Prefix For Your Invoice No.</h5>
                                            <label for="invoice_prefix">This prefix will shown on your invoice along with the invoice number for Ex. NP001</label>
                                            <input type="text" name="invoice_prefix" maxlength="10" class="form-control" id="invoice_prefix" placeholder="Invoice Prefix" value="{{ $data ? $data->invoice_prefix :old('invoice_prefix') }}">
                                        </div>
                                        <div class="mt-4 border-bottom">
                                            <div class="form-group mb-4">
                                                <h5 class="mb-0">Set Logo  <span data-toggle="tooltip" data-placement="top" title="" data-original-title="Avoid blurry or pixelated images by uploading your file in the required size and aspect ratio."><i class="fa fa-alert-circle"></i></span>
                                                </h5>
                                                <!--  <label for="inputAddress">Required Image Size : 800x600-->
                                                <div class="input-group my-2">
                                                    <div class="custom-file">
                                                        <input type="file" onchange="previewlogoImage(this);" class="form-control" id="inputGroupFile02" name="picture" accept="image/png, image/jpg, image/jpeg" >
                                                        <input class="form-control" value="{{ $data ? $data->logo : '' }}" name='company_logo' id='company_logo' type="hidden" readonly>
                                                    </div>
                                                    
                                                </div>
                                                <div class="image-load ">
                                                    <img id="logo_preview" src="{{ $data ? $data->logo : url('preview.jpg') }}" width="100px" height="100px">
                                                    <label class="cstm-switch">
                                                        <span class="cstm-switch-description ml-0 h6 mr-2" style="font-weight: 500;color: #515151;">Hide Company Logo on Invoice </span>
                                                        <input type="checkbox"  id="logo_id"  class="js-switch"  onclick="logoToggleSwitch()" 
                                                        value="{{ $data ? (($data->logo_toggle == '1') ? '1' : '0'): '0' }}" 
                                                        {{ $data ? (($data->logo_toggle == '1') ? 'checked' : ''): '' }}>
                                                        <input type="hidden" id="logoId" name="logo_toggle" value="{{ $data ? (($data->logo_toggle == '1') ? '1' : '0'): '0' }}">
                                                    </label>
                                                </div>

                                            </div>
                                            <div class="form-group">
                                                <h5 class="" mb-0="">Set Signature   <span data-toggle="tooltip" data-placement="top" title="" data-original-title="Avoid blurry or pixelated images by uploading your file in the required size and aspect ratio.">
                                                        <i class="mdi mdi-24px mdi-alert"></i>
                                                    </span>
                                                </h5>
                                                <!-- <label for="inputAddress">Required Image Size : 800x600 -->
                                                <div class="fileinput fileinput-new" data-provides="fileinput"></div>
                                                <div class="input-group my-2">
                                                    <div class="custom-file">
                                                        <input type="file" class="form-control" id="inputGroupFile02"  onchange="previewImage(this);" name="signatureimg" accept="image/png, image/jpg, image/jpeg">
                                                        <input class="form-control" value="{{ $data ? $data->signature : '' }}" name='signature' id='signature' type="hidden" readonly>
                                                    </div>
                                                </div>
                                                <div class="image-load ">
                                                    <img src="{{ $data ? $data->signature : url('preview.jpg') }}" id="signature_preview" width="100px" height="100px">
                                                    <label class="cstm-switch">
                                                        <span class="cstm-switch-description ml-0 h6 mr-2" style="font-weight: 500;color: #515151;">Hide Signature on Invoice </span>
                                                        <input type="checkbox"  id="signature_id"  class="js-switch"  onclick="signatureToggleSwitch()" 
                                                        value="{{ $data ? (($data->signature_toggle == '1') ? '1' : '0'): '0' }}" 
                                                        {{ $data ? (($data->signature_toggle == '1') ? 'checked' : ''): '' }}>
                                                        <input type="hidden" id="signatureId" name="signature_toggle" value="{{ $data ? (($data->signature_toggle == '1') ? '1' : '0'): '0' }}">
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group mt-4 pb-4 border-bottom">
                                            <h5>Set Page Size.</h5> 
                                            <select name="page_size" class="form-control" id="page_size"> 
                                                <option value="A4" {{ $data ? ($data->page_size == 'A4' ? 'selected' : '') : '' }}>A4</option>
                                                <option value="1/2 A4" {{ $data ? ($data->page_size == '1/2 A4' ? 'selected' : '') : '' }}>1/2 A4</option>
                                                <option value="1/3 A4" {{ $data ? ($data->page_size == '1/3 A4' ? 'selected' : '') : '' }}>1/3 A4</option>
                                                <option value="1/4 A4" {{ $data ? ($data->page_size == '1/4 A4' ? 'selected' : '') : '' }}>1/4 A4</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <div class="table-sections mt-4">
                                                <h5>Customize Field</h5>
                                                <p>This option enables you to add the field along with value you want to show/print on your invoice</p>
                                                <div class="table-responsive mt-4">
                                                    <table class="table table-bordered ">
                                                        <thead>
                                                            <tr>
                                                                <th><span class="text-dark">Column Name </span></th>
                                                                <th colspan="2"><span class="text-dark">Value</span></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="field_wrapperb2c" class="customize_field">
                                                            @if($data)
                                                            @php  
                                                                $jsonData = $data->customize_field;
                                                                $dataArray = json_decode($jsonData, true);
                                                            @endphp
                                                            @foreach($dataArray['customize_fields'] as $field)
                                                                <tr id="customerfield0" class="custom_filds">
                                                                    <td>
                                                                        <input class="form-control nameval" maxlength="50" name="column_name[]" type="text" value="{{ $field['column_name'] }}">
                                                                    </td>
                                                                    <td>
                                                                        <input class="form-control nameval" maxlength="50" name="column_value[]" type="text" value="{{ $field['column_value'] }}">
                                                                    </td>
                                                                    @if ($loop->last)
                                                                    <td>
                                                                        <a class="btn btn-primary btn-sm" id="addmorefieldsb2c" href="javascript:void(0);" title="Add Field"><i class="fa fa-plus"></i></a>
                                                                    </td>
                                                                    @endif
                                                                </tr>
                                                            @endforeach
                                                            @else
                                                            <tr id="customerfield0" class="custom_filds">
                                                                    <td>
                                                                        <input class="form-control nameval" maxlength="50" name="column_name[]" type="text" value="">
                                                                    </td>
                                                                    <td>
                                                                        <input class="form-control nameval" maxlength="50" name="column_value[]" type="text" value="">
                                                                    </td>
                                                                    
                                                                    <td>
                                                                        <a class="btn btn-primary btn-sm" id="addmorefieldsb2c" href="javascript:void(0);" title="Add Field"><i class="fa fa-plus"></i></a>
                                                                    </td>
                                                                    
                                                                </tr>
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group text-center mb-4">
                                            <button type="submit" class="btn btn-sm btn-primary save_changs">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-6 col-sm-6 border-sz" >
                                    <div class="invoice_exa " style="box-shadow: 5px 10px 5px 10px #888888;">
                                        <img src="{{ url('inv.png') }}" alt="" class="img-fluid">
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
<script type="text/javascript">
    $(document).ready(function() {
        var addButton = $('#addmorefieldsb2c');
        var wrapper = $('#field_wrapperb2c');
        var x = 1;
        $(addButton).click(function() {
            if($('.custom_filds').length >=5)
    {
        alert("Can't add more than 5 columns");
        return false;
    }
            var fieldHTML = '<tr id="customerfield' + x + '" class="custom_filds">\
    <td>\
    <input type="text" class="form-control nameval" onkeypress name="column_name[]" maxlength="50"  required placeholder="">\
    </td>\
    <td>\
    <input type="text" class="form-control nameval" name="column_value[]" maxlength="50"  required placeholder="">\
    </td>\
    <td>\
    <a href="javascript:void(0);" class="btn btn-danger btn-sm" id="remove_button" href="javascript:void(0);" onclick="removedivB2C(' + x + ');"  title="Remove Field" ><i class="fa fa-minus"></i></a></td>\
    </tr>';
            x++;
            $(wrapper).append(fieldHTML);
            $('.js-example-data-array').select2();
        });
    });

    function removedivB2C(id) {
        var element = document.getElementById("customerfield" + id);
        element.parentNode.removeChild(element);
    }
</script>
<script>
    function previewImage(element)
    {
		debugger
		var file = element.files[0];
		var reader = new FileReader();
		reader.onloadend = function() 
		{
     //alert(reader.result);
			$("#signature").attr("value",reader.result);
			$('#signature_preview').attr('src', reader.result);
		}
		reader.readAsDataURL(file);
    }
</script>
<script>
    function previewlogoImage(element)
    {
		debugger
		var file = element.files[0];
		var reader = new FileReader();
		reader.onloadend = function() 
		{
     
			$("#company_logo").attr("value",reader.result);
			$('#logo_preview').attr('src', reader.result);
		}
		reader.readAsDataURL(file);
    }
</script>
<script>
    function toggleSwitch() {
        if ($('#company_id').is(':checked')) {
           
            $('#company_id').attr('checked', true);
            $('#company_id').val(1);
            $('#comId').val(1);
        } else {
           
            $('#company_id').attr('checked', false);
            $('#company_id').val(0);
            $('#comId').val(0);
        }
    }
    function logoToggleSwitch() {
        var company_logo = $('#company_logo').val();
        console.log(company_logo);
        if(company_logo!=''){
            if ($('#logo_id').is(':checked')) {
            
                $('#logo_id').attr('checked', true);
                $('#logo_id').val(1);
                $('#logoId').val(1);
            } else {
            
                $('#logo_id').attr('checked', false);
                $('#logo_id').val(0);
                $('#logoId').val(0);
            }
        }
        else{
            swal.fire({
                title: "Warning",
                text: "Upload logo first?",
                type: "warning",
                confirmButtonText: "OK",
            });
            $('#logo_id').prop('checked', false);
        }
    }
    function signatureToggleSwitch() {
        var signature = $('#signature').val();
        if(signature!=''){
            if ($('#signature_id').is(':checked')) {
            
                $('#signature_id').attr('checked', true);
                $('#signature_id').val(1);
                $('#signatureId').val(1);
            } else {
            
                $('#signature_id').attr('checked', false);
                $('#signature_id').val(0);
                $('#signatureId').val(0);
            }
        }
        else{
            swal.fire({
                title: "Warning",
                text: "Upload signature first?",
                type: "warning",
                confirmButtonText: "OK",
            });
            $('#signature_id').prop('checked', false);
        }
    }
</script>
@endsection