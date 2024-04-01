@extends('admin-app/admin-master')
@section('title', 'Add erp')
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
	  <h1 class="h3 fw-normal mb-0">Add Fields</h1>
	</div>
</header>
<section class="pb-5"> 
	<div class="container-fluid">
	    <div class="row">
		    <!-- Basic Form-->
		    <div class="col-lg-12">
			    <div class="card">
                    
				    <div class="card-body">
				        <form class='row g-3 align-items-center'  method="post" action="{{ route('field_mapping') }}">
                            @csrf
                            <div class="col-lg-4">
                                <label class="<!--visually-hidden-->" for="erp_type">Logistic Name*</label>
                                <input type="hidden" value="{{ $logistic->id }}" class="form-control" name="partner_id">
                                <input type="text" value="{{ $logistic->logistics_name }}" class="form-control" readonly>  
                            </div>
                            <div class="col-lg-4" style="margin-top: 30px!important;">
    					        <label class="<!--visually-hidden-->" for="state">Map Field*</label>
                                <?php $selRec = $logistic ? $logistic->add_fields: '';
                                $rec = explode(',',$selRec);?>
                                <select class="multiple-select" id="field_name" name="field_name[]" multiple="multiple" placeholder="States">

                                    <?php
                                        $result = array_diff($columns, $pre_table_colomn);
                                        foreach ($result as $column) 
                                        {
                                        $selected = in_array($column, $rec) ? 'selected' : '';
                                    ?>
                                        <option value="<?php echo $column; ?>" <?php echo $selected; ?>><?php echo $column; ?></option>
                                    <?php } ?>
                                </select>
                                <p>
                                    @if($errors->has('field_name'))
                                        <div class="error">{{ $errors->first('field_name') }}</div>
                                    @endif
    						    </p>
    						</div>
                            <div class="col-lg-4" style="margin-top: 30px!important;">
    						    <span class="btn btn-danger" onclick="mapPartner({{ $logistic->id }})">+ Custom Field</span>
                            </div>
                               
    					
                            <div class="col-lg-3">
                                <button class="btn btn-primary" type="submit">Submit</button>
                                <a href="{{url('our-aggrigators')}}" class="btn btn-warning">Cancel</a>
                            </div>
				        </form>
				    </div>
			    </div>
		    </div>
		</div>
	</div>
  </section>
    <div class="modal fade text-start" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Add Custom Field</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class='row g-3 align-items-center' method="post" action="{{route('store_field')}}">
                @csrf
                <div class="modal-body"> 
                    <div class="row">
                        <div class="form-group col-lg-12">
                            <label class="form-label" for="field_name">Field Name *</label>
                            <input class="form-control" id="field_data" type="text" name="field_name" required>
                         <span id="error_message" class="text-danger" style="color:red"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">		
                    <button class="btn btn-primary" id="addBtn" type="submit">Add</button>
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                </div>
            </form> 
        </div>
    </div>
    <script>
  	function mapPartner()
	{
		$('#myModal').modal('show');
	}  
    
</script>
<script>
    $(document).ready(function () {
    $('#addBtn').prop('disabled', false);

    $('#field_data').on('keyup', function () {
        var inputValue = $(this).val();

        // Check if the input contains only letters and spaces
        if (!/^[a-zA-Z\s]+$/.test(inputValue)) {
            console.log('Invalid input');
            $('#error_message').text('Invalid input. Only letters and spaces are allowed.');
            $('#addBtn').prop('disabled', true);
        } else {
            console.log('Valid input');
            $('#error_message').text('');
            $('#addBtn').prop('disabled', false);
        }
    });
});

</script>
@endsection

