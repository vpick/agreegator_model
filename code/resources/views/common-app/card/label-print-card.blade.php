@extends('common-app/master')
@section('title', 'Label Print Setting')
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
</style>
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
                        <div class="container-fluid">
                            <div class="row">                                
                                <div class="card">
                                    <div class="card-body p-sm-5">
                                        <div class="row">
                                            <div class="col-12">
                                                <!-- Table-->
                                                <div class="table-responsive">
                                                    <form action="{{ route('label.setting')}}" method="POST">
                                                        @csrf
                                                        <table class="table mt-4 mb-0">                                                       
                                                            <tr>
                                                                @foreach($labels as $label)
                                                                    <td><input type="radio" name="label_id" class="form-check-label me-1" value="{{ $label->id }}" {{ $data ? ($data->label_id == $label->id ? 'checked' :'') : '' }} required><strong>{{ $label->printer_name }} - {{ $label->size }}</strong>
                                                                        <br><small>({{ $label->description }})</small>
                                                                    </td>   
                                                                @endforeach 
                                                            </tr>             
                                                            <tr style="border-color: transparent!important;">
                                                            @foreach($labels as $label)
                                                                <td class="text-center"><img src="{{ url('label/' . $label->print_image) }}" style="height: 260px;"></td>    
                                                                @endforeach                                            
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2" class="text-center" >
                                                                    <button type="submit" class="btn btn-info">Submit</button>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </form>
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
	    </div>
    </div>
</section>

@endsection