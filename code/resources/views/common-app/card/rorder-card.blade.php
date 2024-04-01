@extends('common-app/master')
@section('title', 'Order Card')
@section('content')
<header class="py-4">
	<div class="container-fluid py-2">
	  <h1 class="h3 fw-normal mb-0">Order details</h1>
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
    		            <form class='row g-3 align-items-center' method="post" action="{{url('store-rorder')}}">
    		                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    		<div class="row">
                    			<div class="col-lg-12">
                    			    <div class="card m-b-30">
                    					<div class="card-header">
                    						<h5 class="m-b-0">
                    							Order Information create
                    						</h5>
                    						<!--<button class="btn btn-sm btn-success" style="background:#12263f!important;float: right;margin-top: -26px;margin-bottom: 5px;border: 1px solid #12263F!important;" title="How to Create Order" data-toggle="modal" data-target="#exampleModalCenter"><i class="mdi mdi-comment-question"></i></button>-->
                    					</div>
                    					<div class="card-body">
                    						<div class="row">
                    							<div class="form-group col-lg-3">
                    								<label>Order ID*</label>
                    								<input type="text" required="" class="form-control" name="order_id" placeholder="Order Id" value="{{ old('order_id') }}">
                    								<span class="errormsg" id="errormsg" style="color: #8b0001;font-weight: bold;">
                    								@if($errors->has('order_id'))
                    									<div class="error">{{ $errors->first('order_id') }}</div>
                    								@endif
                    								</span>
                    							</div>
                    							<div class="form-group col-lg-3">
                    								<label>Order Type*</label>
                    								<select id="order_type" required="" name="order_type" class="form-control">
                    									<option value="B2C">B2C</option>
                    									<option value="B2B">B2B</option>
                    								</select>
                    								<span class="errormsg" id="errormsg" style="color: #8b0001;font-weight: bold;">
                    								@if($errors->has('order_type'))
                    									<div class="error">{{ $errors->first('order_type') }}</div>
                    								@endif
                    								</span>
                    							</div>
                    							
                    							<div class="form-group col-lg-3">
                    								<label>Payment Mode*</label>
                    								<select id="payment_mode" required="" name="payment_mode" id="payment_mode" class="form-control">
                    									<option value="COD">Cash on Delivery</option>
                    									<option value="prepaid">Prepaid</option>
                    								</select>
                    								<span class="errormsg" id="errormsg" style="color: #8b0001;font-weight: bold;">
                    								@if($errors->has('payment_mode'))
                    									<div class="error">{{ $errors->first('payment_mode') }}</div>
                    								@endif
                    								</span>
                    							</div>
                    						</div>
                    					</div>
                    				</div>
                    			</div>
                    			<div class="col-lg-6">
                    				<div class="card m-b-30">
                    					<div class="card-header">
                    						<h5 class="m-b-0">
                    							Shipping Information
                    						</h5>
                    					</div>
                    					<div class="card-body ">
                    						<div class="row">
                    							<div class="form-group col-sm-6">
                    								<label>First Name*</label>
                    								<input type="text" name="shipping_first_name" id="shipping_name" autocomplete="nope" required="" class="form-control" placeholder="First Name" value="{{ old('shipping_first_name') }}">
                    								<span class="errormsg" id="errormsg" style="color: #8b0001;font-weight: bold;">
                    								@if($errors->has('shipping_first_name'))
                    									<div class="error">{{ $errors->first('shipping_first_name') }}</div>
                    								@endif
                    								</span>
                    							</div>
                    							<div class="form-group col-sm-6">
                    								<label>Last Name</label>
                    								<input type="text" name="shipping_last_name" autocomplete="nope" id="shipping_lname" class="form-control" placeholder="Last Name" value="{{ old('shipping_last_name') }}">
                    							</div>
                    						</div>
                    						<div class="row">
                    							<div class="form-group col-sm-6">
                    								<label>Company Name</label>
                    								<input type="text" name="shipping_company_name" id="shipping_company_name" autocomplete="nope" class="form-control" placeholder="Company Name" value="{{ old('shipping_company_name') }}">
                    								<span class="errormsg" id="errormsg" style="color: #8b0001;font-weight: bold;"></span>
                    							</div>
                    							<div class="form-group col-sm-6">
                    								<label>Email</label>
                    								<input type="email" name="shipping_email" id="shipping_email" autocomplete="nope" class="form-control" placeholder="Email" value="{{ old('shipping_email') }}">
                    								<span class="errormsg" id="errormsg" style="color: #8b0001;font-weight: bold;"></span>
                    							</div>
                    						</div>
                    						<div class="row">
                    							<div class="form-group col-sm-6">
                    								<label>Address*</label>
                    								<textarea class="form-control" id="shipping_address_1" autocomplete="nope" required="" name="shipping_address_1" placeholder="Shipping Address">{{ old('shipping_address_1') }}</textarea>
                    								<span class="errormsg" id="errormsg" style="color: #8b0001;font-weight: bold;">
                    								@if($errors->has('shipping_address_1'))
                    									<div class="error">{{ $errors->first('shipping_address_1') }}</div>
                    								@endif
                    								</span>
                    							</div>
                    							<div class="form-group col-sm-6">
                    								<label>Address 2 (Optional)</label>
                    								<textarea class="form-control" autocomplete="nope" name="shipping_address_2" id="shipping_address_2" placeholder="Address 2">{{ old('shipping_address_2') }}</textarea>
                    							</div>
                    						</div>
                    						<div class="row">
                    							<div class="form-group col-sm-6">
                    								<label>Pin Code*</label>
                    								<input type="text" onkeypress="return validatePincodeKeyPress(this,event.key)" maxlength="6" minlength="6"  name="shipping_pincode" id="shipping_pincode" autocomplete="nope" required="" class="form-control" placeholder="Pin Code" value="{{ old('shipping_pincode') }}">
                    								<span class="errormsg" id="errormsg" style="color: #8b0001;font-weight: bold;">
                    								@if($errors->has('shipping_pincode'))
                    									<div class="error">{{ $errors->first('shipping_pincode') }}</div>
                    								@endif
                    								</span>
                    							</div>
                    							
                    							<div class="form-group col-sm-6">
                    								<label>City*</label>
                    								<input type="text" name="shipping_city" autocomplete="nope" id="shipping_getcity" required="" class="form-control" placeholder="City" value="{{ old('shipping_city') }}">
                    								<span class="errormsg" id="errormsg" style="color: #8b0001;font-weight: bold;">
                    								@if($errors->has('shipping_city'))
                    									<div class="error">{{ $errors->first('shipping_city') }}</div>
                    								@endif
                    								</span>
                    							</div>
                    						</div>
                    						<div class="row">
                    							
                    							<div class="form-group col-sm-6">
                    								<label>State*</label>
                    								<input type="text" name="shipping_state" autocomplete="nope" id="shipping_getstate" required="" class="form-control" placeholder="State" value="{{ old('shipping_state') }}">
                    								<span class="errormsg" id="errormsg" style="color: #8b0001;font-weight: bold;">
                    								@if($errors->has('shipping_state'))
                    									<div class="error">{{ $errors->first('shipping_state') }}</div>
                    								@endif
                    								</span>
                    							</div>
                    							<div class="form-group col-sm-6">
                    								<label>Country*</label>
                    								<input type="text" name="shipping_country" autocomplete="nope" id="shipping_country" required="" class="form-control" placeholder="Country" value="{{ old('shipping_country') }}">
                    								<span class="errormsg" id="errormsg" style="color: #8b0001;font-weight: bold;">
                    								@if($errors->has('shipping_country'))
                    									<div class="error">{{ $errors->first('shipping_country') }}</div>
                    								@endif
                    								</span>
                    							</div>
                    						</div>
                    						<div class="row">
                    							<div class="form-group col-sm-6">
                    								<label>Phone*</label>
                    								<input type="text" name="shipping_phone_number" onkeypress="return validateFloatKeyPress(this,event.key)" id="shipping_phone" autocomplete="nope" required="" class="form-control" placeholder="Phone" value="{{ old('shipping_phone') }}">
                    								<span class="errormsg" id="errormsg" style="color: #8b0001;font-weight: bold;">
                    								@if($errors->has('shipping_phone_number'))
                    									<div class="error">{{ $errors->first('shipping_phone_number') }}</div>
                    								@endif
                    								</span>
                    							</div>
                    							<div class="form-group col-sm-6">
                    								<label>Alternate Phone</label>
                    								<input type="text" name="shipping_alternate_phone" onkeypress="return validateFloatKeyPress(this,event.key)" id="shipping_phone_alternate" autocomplete="nope" class="form-control" placeholder="Phone" value="{{ old('shipping_phone_alternate') }}">
                    								<span class="errormsg" id="errormsg" style="color: #8b0001;font-weight: bold;"></span>
                    							</div>
                    						</div>
                    						<div class="row">
                    							<div class="form-group col-sm-12">
                    								<input class="hyperlocal_check" type="checkbox" name="hyperlocal_check" value="1">
                    								<label for="hyperlocal">
                    									<span class="text-15">Select For Hyperlocal Shipment</span>
                    								</label>
                    								<span id="error" style="display: none;"></span>
                    							</div>
                    						</div>
                    						<div id="hyperlocal" class="hyperlocal" style="display: none;">
                    							<div class="pac-card" id="pac-card">
                    								<div>
                    									<div id="label">
                    										<input id="pac-input" type="text" placeholder="Enter a location" class="pac-target-input" autocomplete="off">
                    										<div id="location-error"></div>
                    									</div>
                    								</div>
                    								<div id="pac-container">
                    								</div>
                    							</div>
                    							<div class="map" id="map" style="width: 100%; height: 300px;">
                    							<div style="height: 100%; width: 100%;">
                    							<div style="overflow: hidden;"></div>
                    							</div>
                    							</div>
                    						</div>
                    					</div>
                    				</div>
                    			</div>
                    			<div class="col-lg-6">
                    				<div class="card m-b-30">
                    					<div class="card-header">
                    						<h5 class="m-b-0">Billing Information <input type="checkbox" id="toshipping_checkbox">
                    						<em>Mark same as shipping address</em></h5>
                    					</div>
                    					<div class="card-body ">
                    						<div class="row">
                    							<div class="form-group col-sm-6">
                    								<label>First Name</label>
                    								<input type="text" name="billing_first_name" id="billing_shipping_name" autocomplete="nope" class="form-control" placeholder="First Name" value="{{ old('billing_first_name') }}">
                    								<span class="errormsg" id="errormsg" style="color: #8b0001;font-weight: bold;"></span>
                    							</div>
                    							<div class="form-group col-sm-6">
                    								<label>Last Name</label>
                    								<input type="text" name="billing_last_name" autocomplete="nope" id="billing_shipping_lname" class="form-control" placeholder="Last Name" value="{{ old('billing_last_name') }}">
                    							</div>
                    						</div>
                    						<div class="row">
                    							<div class="form-group col-sm-6">
                    								<label>Company Name</label>
                    								<input type="text" name="billing_company_name" id="billing_shipping_company_name" autocomplete="nope" class="form-control" placeholder="Company Name" value="{{ old('billing_company_name') }}">
                    								<span class="errormsg" id="errormsg" style="color: #8b0001;font-weight: bold;"></span>
                    							</div>
                    							<div class="form-group col-sm-6">
                    								<label>Email</label>
                    								<input type="email" name="billing_email" id="billing_shipping_email" autocomplete="nope" class="form-control" placeholder="Email" value="{{ old('billing_email') }}">
                    								<span class="errormsg" id="errormsg" style="color: #8b0001;font-weight: bold;"></span>
                    							</div>
                    						</div>
                    						<div class="row">
                    							<div class="form-group col-sm-6">
                    								<label>Address</label>
                    								<textarea class="form-control" autocomplete="nope" name="billing_address_1" id="billing_shipping_address_1" placeholder="Billing Address">{{ old('billing_address_1') }}</textarea>
                    								<span class="errormsg" id="errormsg" style="color: #8b0001;font-weight: bold;"></span>
                    							</div>
                    							<div class="form-group col-sm-6">
                    								<label>Address 2 (Optional)</label>
                    								<textarea class="form-control" autocomplete="nope" name="billing_address_2" id="billing_shipping_address_2" placeholder="Address 2">{{ old('billing_address_2') }}</textarea>
                    							</div>
                    						</div>
                    						<div class="row">
                    							<div class="form-group col-sm-6">
                    								<label>Pin Code</label>
                    								<input type="text" onkeypress="return validatePincodeKeyPress(this,event.key)" maxlength="6" minlength="6" name="billing_pincode" id="billing_shipping_pincode" autocomplete="nope" class="form-control" placeholder="Pin Code" value="{{ old('billing_pincode') }}">
                    								<span class="errormsg" id="errormsg" style="color: #8b0001;font-weight: bold;"></span>
                    							</div>
                    							
                    							<div class="form-group col-sm-6">
                    								<label>City</label>
                    								<input type="text" name="billing_city" autocomplete="nope" id="billing_shipping_getcity" class="form-control" placeholder="City" value="{{ old('billing_city') }}">
                    							</div>
                    						</div>
                    						<div class="row">
                    							
                    							<div class="form-group col-sm-6">
                    								<label>State</label>
                    								<input type="text" name="billing_state" autocomplete="nope" id="billing_shipping_getstate" class="form-control" placeholder="State" value="{{ old('billing_state') }}">
                    								<span class="errormsg" id="errormsg" style="color: #8b0001;font-weight: bold;"></span>
                    							</div>
                    							<div class="form-group col-sm-6">
                    								<label>Country</label>
                    								<input type="text" name="billing_country" autocomplete="nope" id="billing_shipping_country" class="form-control" placeholder="Country" value="{{ old('billing_country') }}">
                    								<span class="errormsg" id="errormsg" style="color: #8b0001;font-weight: bold;"></span>
                    							</div>
                    						</div>
                    						<div class="row">
                    							<div class="form-group col-sm-6">
                    								<label>Phone</label>
                    								<input type="text" onkeypress="return validateFloatKeyPress(this,event.key)" maxlength="10" minlength="10" name="billing_phone_number" id="billing_shipping_phone" autocomplete="nope" class="form-control" placeholder="Phone" value="{{ old('billing_phone_number') }}">
                    							</div>
                    							<div class="form-group col-sm-6">
                    								<label>Alternate Phone</label>
                    								<input type="text" onkeypress="return validateFloatKeyPress(this,event.key)" maxlength="10" minlength="10" name="billing_alternate_phone" id="billing_shipping_phone_alternate" autocomplete="nope" class="form-control" placeholder="Phone" value="{{ old('billing_alternate_phone') }}">
                    							</div>
                    						</div>
                    						<div class="row">
                    							<div class="form-group col-sm-12">
                    								<label>GST Number</label>
                    								<input type="text" name="gst_no" maxlength="15" id="billings_gst_number" autocomplete="nope" class="form-control" placeholder="GST Number" value="{{ old('gst_no') }}">
                    							</div>
                    						</div>
                    					</div>
                    				</div>
                    			</div>
                    		  </div>
                    		<div class="row">
                    			<div class="col-lg-12">
                    				<div class="card m-b-30">
                    					<div class="card-body">
                    						<div class="table-responsive">
                    							<table class="table invoice-detail-table create-invoice com-create-sales-table product_det_table">
                    								<thead>
                    									<tr>
                    										<th>Product*</th>
                    										<th>Quantity*</th>
                    										<th>Amount(Rs)*</th>
                    										<th>SKU *</th>
                    										<th>Add More</th>
                    									</tr>
                    								</thead>
                    								<tbody id="field_wrapper" class="product_table_tbody">
                    									<tr id="customerfield0">
                    										<td>
                    											<input type="text" autocomplete="nope" id="perproduct_ids0" name="products[0][product_name]"  required="" class="form-control" placeholder="Product Name" value="{{ old('product_name') }}">
                    										</td>
                    										<td>
                    											<input type="text" onkeypress="return validateFloatKeyPress(this,event.key)" autocomplete="nope" name="products[0][product_qty]" id="basic_unit0" class="form-control" required="" value="{{ old('product_qty') }}">
                    										</td>
                    										<td>
                    											<input type="text" onkeypress="return validateFloatKeyPress(this,event.key)"  autocomplete="nope" id="productrate0" name="products[0][product_price]" required="" class="form-control" placeholder="Amount" value="{{ old('product_price') }}">
                    										</td>
                    										<td>
                    											<input type="text" autocomplete="nope" id="productsku0" name="products[0][product_sku]" class="form-control" placeholder="SKU" value="{{ old('product_sku') }}">
                    											<input type="hidden" id="product_id0" name="products[0][product_id]" class="form-control" value="{{ old('product_id') }}">
                    										</td>
                    										<td>
                    											<a class="btn btn-primary btn-sm" id="addmorefields" href="javascript:void(0);" title="Product">+</a>
                    										</td>
                    									</tr>
                    								</tbody>
                    							</table>
                    						</div>
                    					</div>
                    				</div>
                    			</div>
                    		  </div>
                    		<div class="row">
                    			<div class="col-lg-6">
                    				<div class="card m-b-30">
                    					<div class="card-body">
                    						<table class="table-responsive">
                    							<tbody>
                    								<tr>
                    									<th style="margin-top: 7px;display: block;">Weight*</th>
                    									<td>
                    										<div class="input-group">
                    											<input type="text" autocomplete="nope" onkeypress="return validateFloatKeyPress(this,event.key)" name="weight" id="weight" class="form-control" placeholder="in grams" value="{{ old('weight') }}">
                    										</div>
                    										<p>eg: 500, 300 (in grams)</p>
                    									</td>
                    								</tr>
                    								<tr>
                    									<th style="margin-top: 7px;display: block;">Dimensions (cm)*</th>
                    									<td>
                    										<div class="input-group">
                    											<input type="text" onkeypress="return validateFloatKeyPress(this,event.key)" id="length" name="length" autocomplete="nope" class="form-control calculate_vol_weight" placeholder="CM" value="{{ old('length') }}">
                    											<input type="text" onkeypress="return validateFloatKeyPress(this,event.key)" id="breadth" name="breadth" autocomplete="nope" class="form-control calculate_vol_weight" placeholder="CM" value="{{ old('breadth') }}">
                    											<input type="text" onkeypress="return validateFloatKeyPress(this,event.key)" id="height" name="height" autocomplete="nope" class="form-control calculate_vol_weight" placeholder="CM" value="{{ old('height') }}">
                    										</div>
                    									</td>
                    								</tr>
                    								<tr>
                    									<th style="margin-top: 17px;display: block;">Volumetric Weight</th>
                    									<td>
                    										<div class="input-group" style="margin-top: 10px;">
                    											<input type="hidden" name="volumetric_weight" id="volumetric_weight" value="0">
                    											<input type="text" autocomplete="nope" name="vol_weight" id="vol_weight" class="form-control" placeholder="Volumetric Weight" value="{{ old('vol_weight') ? old('vol_weight') : 0 }}" readonly="">
                    											<span style="margin-top: 7px; margin-left: 6px;margin-right: 304px;" id="weight_in">Grams</span>
                    										</div>
                    										<br>
                    										<div id="errmsgbox" style="color: #c59605"></div>
                    									</td>
                    								</tr>
                    							</tbody>
                    						</table>
                    					</div>
                    				</div>
                    			</div>
                    			<div class="col-lg-6">
                    				<div class="card m-b-30">
                    					<div class="card-body">
                    						<div class="form-group col-sm-6">
                    							<label>Shipping Charges*</label>
                    							<input type="text" onkeypress="return validateFloatKeyPress(this,event.key)" autocomplete="nope" name="shipping_charges" required="" class="form-control" placeholder="Shipping Charges" value="{{ old('shipping_charges') ? old('shipping_charges') : 0 }}">
                    						</div>
                    						<div class="form-group col-sm-6">
                    							<label>COD Charges*</label>
                    							<input type="text" onkeypress="return validateFloatKeyPress(this,event.key)" autocomplete="nope" name="cod_charges" required="" class="form-control" placeholder="COD Charges" value="{{ old('cod_charges') ? old('cod_charges') : 0 }}">
                    						</div>
                    						<div class="form-group col-sm-6">
                    							<label>Tax Amount*</label>
                    							<input type="text" onkeypress="return validateFloatKeyPress(this,event.key)" autocomplete="nope" name="tax_amount" required="" class="form-control" placeholder="Tax Amount" value="{{ old('tax_amount') ? old('tax_amount') : 0 }}">
                    						</div>
                    						<div class="form-group col-sm-6">
                    							<label>Discount*</label>
                    							<input type="text" onkeypress="return validateFloatKeyPress(this,event.key)" autocomplete="nope" name="discount" required="" class="form-control" placeholder="Discount Applied" value="{{ old('discount') ? old('discount') : 0 }}">
                    						</div>
                    						<input type="hidden" id="lat" name="latitude" value="">
                    						<input type="hidden" id="lng" name="longitude" value="">
                    						<input type="hidden" id="hyperlocal_address" name="hyperlocal_address" value="">
                    						<input type="hidden" id="postal_code" name="postal_code" value="">
                    						<br />
                    						<button type="submit" name="submit" class="btn btn-primary">Save</button>&nbsp;
                    						<a href="/orders" name="cancel" class="btn btn-danger">Cancel</a>
                    					</div>
                    				</div>
                    			</div>
                    		  </div>
    		            </form>
    		        </div>
	            </div>
            </div>
	    </div>
    </div>
</section>
<script>
 $(document).ready(function() {
    $('#cod_charges').prop('readonly', false);
        $(document).on('change', '#payment_mode', function(event) {
            var payment_mode = $('#payment_mode').val();
            console.log(payment_mode);
            if(payment_mode == 'COD'){
                $('#cod_charges').prop('readonly', false);
            }
            else{
                $('#cod_charges').prop('readonly', true);
            }
          });

        var addButton = $('#addmorefields');
        var wrapper = $('#field_wrapper');
        var x = 1;
        $(addButton).click(function() {
            var fieldHTML = '<tr id="customerfield' + x + '">\
			<td>\
			<input type="text" autocomplete="nope" id="perproduct_ids' + x + '"  name="products[' + x + '][product_name]" required="" class="form-control" placeholder="Product Name">\
			</td>\
			<td>\
			<input type="text" onkeypress="return validateFloatKeyPress(this,event.key)" autocomplete="nope" name="products[' + x + '][product_qty]" id="basic_unit' + x + '" class="form-control"  required="" value="1">\
			</td>\
			<td>\
			<input type="text" onkeypress="return validateFloatKeyPress(this,event.key)" autocomplete="nope" id="productrate' + x + '" name="products[' + x + '][product_price]" required="" class="form-control" placeholder="Amount">\
			</td>\
			<td>\
			<input type="text" autocomplete="nope" id="productsku' + x + '" name="products[' + x + '][product_sku]" class="form-control" placeholder="SKU">\
			<input type="hidden" id="products_id' + x + '" name="products[' + x + '][products_id]" class="form-control">\
			</td>\
			<td>\
			<a href="javascript:void(0);" class="btn btn-danger btn-sm" id="remove_button" href="javascript:void(0);" onclick="removediv(' + x + ');"  title="Remove Field" >X</a></td>\
			</tr>';
            x++;
            $(wrapper).append(fieldHTML);
        });
    });
    

    function removediv(id) 
    {
        var element = document.getElementById("customerfield" + id);
        element.parentNode.removeChild(element);
    }
    $("#toshipping_checkbox").on("click", function() {
        var biil = $(this).is(":checked");
        $("[id^='billing_']").each(function() {
            var tmpID = this.id.split('billing_')[1];
            $(this).val(biil ? $("#" + tmpID).val() : "");
        });
    });
    $('.calculate_vol_weight').bind("paste", function(e) {
        var text = e.originalEvent.clipboardData.getData('Text');
        if ($.isNumeric(text)) {
            if ((text.substring(text.indexOf('.')).length > 3) && (text.indexOf('.') > -1)) {
                e.preventDefault();
                $(this).val(text.substring(0, text.indexOf('.') + 3));
            }
        }
        else 
        {
            e.preventDefault();
        }
    });
    function validateFloatKeyPress(el, evt) 
    {
        var charCode = (evt.which) ? evt.which : event.keyCode;
        var number = el.value.split('.');
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        if(number.length>1 && charCode == 46){
            return false;
        }
        var caratPos = getSelectionStart(el);
        var dotPos = el.value.indexOf(".");
        if( caratPos > dotPos && dotPos>-1 && (number[1].length > 1)){
            return false;
        }
        return true;
    }
    function validatePincodeKeyPress(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode;
    
        // Allow only numeric digits (0-9)
        if (charCode < 48 || charCode > 57) {
            return false;
        }
    
        return true;
    }
    function getSelectionStart(o) {
        if (o.createTextRange) {
            var r = document.selection.createRange().duplicate()
            r.moveEnd('character', o.value.length)
            if (r.text == '') return o.value.length
            return o.value.lastIndexOf(r.text)
        } else return o.selectionStart
    }

    function alertFunction() {
        var total_weight = $("#weight").val();

        $('#errmsgbox').html('');

        if (total_weight >= 50001) {
            $("#errmsgbox").html("<b>Note:</b> Enter weight is greater then 50 kg please cross verify before order created");
        }

        var l = $("#length").val();
        var b = $("#breadth").val();
        var h = $("#height").val();

        len = l.replace(/\s/g, '');
        bre = b.replace(/\s/g, '');
        hei = h.replace(/\s/g, '');

        var sum = len * bre * hei;
        var totalsum = sum / 5000;
        var weight = (totalsum * 1000);

        if (weight >= 50001){
              $("#errmsgbox").html("<b>Note:</b> Enter weight is greater then 50 kg please cross verify before order created");
        }
         
    }

    $('.calculate_vol_weight').keyup(function() {
        $('#vol_weight').val('');
        var l = $("#length").val();
        var b = $("#breadth").val();
        var h = $("#height").val();
        var total_weight = $("#weight").val();

        len = l.replace(/\s/g, '');
        bre = b.replace(/\s/g, '');
        hei = h.replace(/\s/g, '');
     
            var sum = len * bre * hei;
           
            var totalsum = sum / 5000;
            var weight = (totalsum * 1000);

            $('#errmsgbox').html('');

            if (weight >= 50001)
                $("#errmsgbox").html("<b>Note:</b> Enter weight is greater then 50 kg please cross verify before order created");

            if (total_weight >= 50001)
                $("#errmsgbox").html("<b>Note:</b> Enter weight is greater then 50 kg please cross verify before order created");

            var bs = totalsum.toString().split(".")[0]; ///before  
            var as = totalsum.toString().split(".")[1]; ///after
           
            $('#volumetric_weight').val(Math.round(weight));
            
            if (bs > 0) {
                $('#vol_weight').val(totalsum.toFixed(2));
                $("#weight_in").html("Kg");
            } else { 
                $('#vol_weight').val(Math.round(weight));
                $("#weight_in").html("Grams");
            }

    });
    function volweightonload(){
    $('#vol_weight').val('');
        var l = $("#length").val();
        var b = $("#breadth").val();
        var h = $("#height").val();
        var total_weight = $("#weight").val();

        len = l.replace(/\s/g, '');
        bre = b.replace(/\s/g, '');
        hei = h.replace(/\s/g, '');
     
            var sum = len * bre * hei;
           
            var totalsum = sum / 5000;
            var weight = (totalsum * 1000);

            $('#errmsgbox').html('');

            if (weight >= 50001)
                $("#errmsgbox").html("<b>Note:</b> Enter weight is greater then 50 kg please cross verify before order created");

            if (total_weight >= 50001)
                $("#errmsgbox").html("<b>Note:</b> Enter weight is greater then 50 kg please cross verify before order created");

            var bs = totalsum.toString().split(".")[0]; ///before  
            var as = totalsum.toString().split(".")[1]; ///after
           
            $('#volumetric_weight').val(Math.round(weight));
            
            if (bs > 0) {
                $('#vol_weight').val(totalsum.toFixed(2));
                $("#weight_in").html("Kg");
            } else { 
                $('#vol_weight').val(Math.round(weight));
                $("#weight_in").html("Grams");
            }

}
    volweightonload();

    $("#shipping_pincode").change(function() {
        var pincode = $('#shipping_pincode').val();
        if (pincode == "") {
            $('#shipping_getcity').val('');
            $('#shipping_getstate').val('');
        } else {
            $.ajax({
                type: 'POST',
                url: "orders/getcitystate", //file which read zip code excel file
                data: {
                    'pincode': pincode
                },
                success: function(data) {
                    if (data == '') {
                        $('#shipping_getcity').val('');
                        $('#shipping_getstate').val('');
                        return false;
                    } else {
                        var getData = $.parseJSON(data);
                        $('#shipping_getcity').val(getData.city);
                        $('#shipping_getstate').val(getData.state);
                    }
                },
            });
        }
    });

    $("#billing_shipping_pincode").change(function() {
        var pincode = $('#billing_shipping_pincode').val();
        if (pincode == "") {
            $('#billing_shipping_getcity').val('');
            $('#billing_shipping_getstate').val('');
        } else {
            $.ajax({
                type: 'POST',
                url: "orders/getcitystate", //file which read zip code excel file
                data: {
                    'pincode': pincode
                },
                success: function(data) {
                    if (data == '') {
                        $('#billing_shipping_getcity').val('');
                        $('#billing_shipping_getstate').val('');
                        return false;
                    } else {
                        var getData = $.parseJSON(data);
                        $('#billing_shipping_getcity').val(getData.city);
                        $('#billing_shipping_getstate').val(getData.state);
                    }
                },
            });
        }
})
</script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDBhpIX1c9x4hPkae2ukLeNYm8_8CFmNiE&sensor=false&libraries=places&sensor=true"></script>
<script>
    function initialize() {
        var lat = 28.613907140713394;
        var lang = 77.22983646704098;
        var hyperlocal_address ='';
        var latlng = new google.maps.LatLng(lat, lang);
        var map = new google.maps.Map(document.getElementById('map'), {
            center: latlng,
            zoom: 17
        });

        var marker = new google.maps.Marker({
            map: map,
            position: latlng,
            draggable: true,
            anchorPoint: new google.maps.Point(0, -29)
        });

        if (lat != "" & lang != "" & hyperlocal_address != "") {
            GetAddress(map, marker, lat, lang, hyperlocal_address);
        }


        var card = document.getElementById('pac-card');
        var input = document.getElementById('pac-input');
        map.controls[google.maps.ControlPosition.TOP_RIGHT].push(card);

        var geocoder = new google.maps.Geocoder();
        var infowindow = new google.maps.InfoWindow();

        var autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.setComponentRestrictions({
            'country': ['in']
        });
        autocomplete.bindTo('bounds', map);
        autocomplete.addListener('place_changed', function() {
            infowindow.close();
            marker.setVisible(false);
            var place = autocomplete.getPlace();
            if (!place.geometry) {
                window.alert("Autocomplete's returned place contains no geometry");
                return;
            }

            // If the place has a geometry, then present it on a map.
            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(17);
            }

            marker.setPosition(place.geometry.location);
            marker.setVisible(true);
            var searchAddressComponents = place.address_components,
                searchPostalCode = "";
            $.each(searchAddressComponents, function() {
                if (this.types[0] == "postal_code") {
                    searchPostalCode = this.short_name;
                }
            });

            bindDataToForm(place.formatted_address, place.geometry.location.lat(), place.geometry.location.lng(), searchPostalCode);
            infowindow.setContent(place.formatted_address);
            infowindow.open(map, marker);

        });
        google.maps.event.addListener(marker, 'dragend', function() {
            geocoder.geocode({
                'latLng': marker.getPosition()
            }, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                        var searchAddressComponents = results[0].address_components,
                            searchPostalCode = "";
                        $.each(searchAddressComponents, function() {
                            if (this.types[0] == "postal_code") {
                                searchPostalCode = this.short_name;
                            }
                        });
                        bindDataToForm(results[0].formatted_address, marker.getPosition().lat(), marker.getPosition().lng(), searchPostalCode);
                        infowindow.setContent(results[0].formatted_address);
                        infowindow.open(map, marker);
                    }
                }
            });
        });
    }

    function bindDataToForm(address, lat, lng, searchPostalCode) {
        document.getElementById('pac-input').value = address;
        document.getElementById('postal_code').value = searchPostalCode;
        document.getElementById('hyperlocal_address').value = address;
        document.getElementById('lat').value = lat;
        document.getElementById('lng').value = lng;
        var zip = $('#shipping_pincode').val();;
     
    }


    function GetAddress(map, marker, lat, lng, hyperlocal_address) {
        var latlng = new google.maps.LatLng(lat, lng);
        var geocoder = geocoder = new google.maps.Geocoder();
        var infowindow = new google.maps.InfoWindow();
        geocoder.geocode({
            'latLng': latlng
        }, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[1]) {

                    var searchAddressComponents = results[1].address_components,
                        searchPostalCode = "";
                    $.each(searchAddressComponents, function() {
                        if (this.types[0] == "postal_code") {
                            searchPostalCode = this.short_name;
                        }
                    });
                    document.getElementById('pac-input').value = hyperlocal_address;
                    infowindow.setContent(hyperlocal_address);
                   // document.getElementById('postal_code').value = searchPostalCode;
                    infowindow.open(map, marker);
                    var zip = $('#shipping_pincode').val();;
                 
                }
            }
        });
    }
    google.maps.event.addDomListener(window, 'load', initialize);

    $(document).ready(function() {
        if ($('input[name="hyperlocal_check"]').is(':checked')) {
            $(".hyperlocal").show();

        } else {
            $(".hyperlocal").hide();
            $("#lat").val('');
            $("#lng").val('');
            $("#error").hide();
        }
        $(".hyperlocal_check").click(function() {
            if ($(this).is(":checked")) {
                $(".hyperlocal").show();
            } else {
                $(".hyperlocal").hide();
                $("#lat").val('');
                $("#lng").val('');
                $("#error").hide();
            }
        });
    });
</script> 
@endsection