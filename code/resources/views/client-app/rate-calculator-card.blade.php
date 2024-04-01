@extends('common-app/master')
@section('title', 'Rate Calculator')
@section('content')
<!-- <header class="py-4">
  <div class="container-fluid py-2">
    <h1 class="h3 fw-normal mb-0">Client details</h1>
  </div>
</header> -->

<style>
.table-responsive {
    overflow-x: clip !important;
}
    .table_highlight .table-bordered td,
    .table_highlight .table-bordered th {
        border: 1px solid #8a8b8d !important;
        vertical-align: middle;
    }

    .table_highlight .table.table-bordered td {
        padding: 0px;
    }

    .table_highlight .table.table-bordered td p {
        margin-bottom: 0;
        line-height: 28px;
        padding: 2px 0px;
    }

    .table_highlight .table.table-bordered td p span {
        display: block;
        border-top: 1px solid #ddd;
    }
    .table-responsive {
        overflow-x: clip !important;
    }
    .card-header {
    margin-bottom: 0;
    padding: 0.75rem 1.25rem;
    border-bottom: 0 solid rgba(0,0,0,.125);
    background-color: transparent;
}
.bg-dark {
    background-color: #12263f!important;
}
.text-white {
    color: #fff!important;
}
.m-t-10 {
    margin-top: 10px;
}
.m-t-20 {
    margin-top: 20px;
}
.table td{
    font-size: 13px;
}
.table th{
    font-size: 13px;
}
.tab-line .nav-item .nav-link.active {
    color: #4c66fb;
    border-top: none;
    border-right: none;
    border-bottom: 2px solid #4c66fb;
    border-left: none;
}
.input-group-prepend {
    margin-right: -1px;
}
.mb-2, .my-2 {
    margin-bottom: 0.5rem!important;
}

.input-group {
    position: relative;
    display: flex;
    width: 100%;
    flex-wrap: wrap;
    align-items: stretch;
}
.input-group-text {
    font-size: .9rem;
    font-weight: 400;
    line-height: 1.5;
    display: flex;
    margin-bottom: 0;
    padding: 0.375rem 0.75rem;
    text-align: center;
    white-space: nowrap;
    color: #2e384d;
    border: 1px solid #dce4ec;
    border-radius: 0.25rem;
    background-color: #fff;
    align-items: center;
}
.input-group-append, .input-group-prepend {
    display: flex;
}
.i-map-marker:before {
    content: "\F34E";
}

.i:before, .mdi-set {
    display: inline-block;
    font: normal normal normal 24px/1 "Material Design Icons";
    font-size: inherit;
    text-rendering: auto;
    line-height: inherit;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}
.p-all-15 {
    padding: 15px;
}
.m-t-15 {
    margin-top: 15px;
}
.border-top {
    border-top: 1px solid #dce4ec!important;
}
.table_highlight .table-bordered td, .table_highlight .table-bordered th {
    border: 1px solid #8a8b8d !important;
    vertical-align: middle;
}

.table-sm thead th {
    font-size: .7rem;
}
.table_highlight .table.table-bordered td p {
    margin-bottom: 0;
    line-height: 28px;
    padding: 2px 0px;
}
.table1 td p{
    font-size: 13px!important;
}
</style>
<!-- Forms Section-->
<section class="pb-5"> 
    <div class="container-fluid">
        <div class="row">
            <!-- Basic Form-->
            <div class="col-lg-12">
                <div class="card m-b-30">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-6">
                                <h4>Billing - Price Calculator</h4>
                            </div>
                            <!--<div class="col-sm-6 text-right">-->
                            <!--    <button class="btn btn-sm btn-dark" title="An Overview of The Billing Module" data-toggle="modal" data-target="#exampleModalCenter"><i class="mdi mdi-comment-question"></i></button>-->
                            <!--</div>-->
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs m-b-15">
                            <li class="nav-item">
                                <a class="nav-link active " href="#"><i class="mdi mdi-calculator"></i> Price Calculator</a>
                            </li>
                            <!--<li class="nav-item">-->
                            <!--    <a class="nav-link  " href="#"><i class="mdi mdi-cash"></i> COD Remittance</a>-->
                            <!--</li>-->
                            <!--<li class="nav-item">-->
                            <!--    <a class="nav-link " href="#"><i class="mdi mdi-wallet"></i> Wallet Transactions</a>-->
                            <!--</li>-->
                            <!--<li class="nav-item">-->
                            <!--    <a class="nav-link " href="#"><i class="mdi mdi-truck-fast"></i> Shipping Charges</a>-->
                            <!--</li>-->
                            <!--<li class="nav-item">-->
                            <!--    <a class="nav-link " href="#"><i class="mdi mdi-file-pdf"></i> Invoice</a>-->
                            <!--</li>-->
                            <!--<li class="nav-item">-->
                            <!--    <a class="nav-link " href="#"><i class="mdi mdi-file-pdf"></i> Credit Notes</a>-->
                            <!--</li>-->
                            <!--<li class="nav-item">
                                <a class="nav-link " href="billing/v/weight_reconciliation"><i class="mdi mdi-weight"></i> Weight Reconciliation</a>
                            </li>-->
                        </ul>
                        <div class="row">
                            <div class="col-sm-6 main_class">
                                <div class="card m-b-20 mail_cal_heading">
                                    <div class="card-header bg-dark text-white">
                                        <h5 class="m-b-0" style="text-align:center;">
                                        Shipping Rates Calculator
                                        </h5>
                                    </div>
                                </div>
                                <div class="tabbable boxed parentTabs">
                                    <div class="tab-content m-t-10">
                                        <ul class="nav nav-tabs tab-line ul-nav-tabs">
                                            <li class="nav-item">
                                                <a href="#b2c_cal" onclick="showdiv_cal('b2c')" class="nav-link active">B2C Calculator</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#b2b_cal" onclick="showdiv_cal('b2b')" class="nav-link">B2B Calculator</a>
                                            </li>
                                        </ul>
                                        <div class="tab-pane fade show active" id="b2c_cal" role="tabpanel" aria-labelledby="contact-tab">
                                            <div class="table-responsive m-t-10">
                                                <form id="pricing_calculator_form" method="get" action="/b2c_calculator">
                                                    @csrf
                                                    <div class="row m-t-20">
                                                        <div class="col-sm-4">
                                                            <div class="form-group mb-2">
                                                                <label>Pick-up Pincode</label>
                                                                <input type="text" name="origin" class="form-control" required="" placeholder="Pick-up Pincode" value="{{ $requestData ? $requestData['origin'] : old('origin') }}">
                                                                @error('origin')
                                                                    <span class="text-danger">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="form-group mb-2">
                                                                <label>Delivery Pincode</label>
                                                                <input type="text" name="destination" class="form-control" required="" placeholder="Delivery Pincode" value="{{ $requestData ? $requestData['destination'] : old('destination') }}">
                                                                @error('destination')
                                                                    <span class="text-danger">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="form-group mb-2">
                                                                <label>Weight (Kg)</label>
                                                                <input type="text" name="weight" id="weight" class="form-control" required="" value="{{ $requestData ? $requestData['weight'] : old('weight') }}" placeholder="Weight">
                                                                @error('weight')
                                                                    <span class="text-danger">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-2">
                                                            <div class="form-group">
                                                                <label>L(cm)</label>
                                                                <input type="text" name="length" id="length" class="form-control" required="" value="{{ $requestData ? $requestData['length'] : old('length') }}" placeholder="cm">
                                                                @error('length')
                                                                    <span class="text-danger">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <div class="form-group">
                                                                <label>H(cm)</label>
                                                                <input type="text" name="height" id="height" class="form-control" required="" value="{{ $requestData ? $requestData['height'] : old('height') }}" placeholder="cm">
                                                                @error('height')
                                                                    <span class="text-danger">{{ $message }}</span>
                                                                @enderror
                                                                
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <div class="form-group">
                                                                <label>B(cm)</label>
                                                                <input type="text" name="breadth" id="breadth" class="form-control" required="" value="{{ $requestData ? $requestData['breadth'] : old('breadth') }}" placeholder="cm">
                                                                @error('breadth')
                                                                    <span class="text-danger">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <!--<div class="col-sm-3">-->
                                                        <!--    <div class="form-group mb-2">-->
                                                        <!--        <label>Value in INR </label>-->
                                                        <!--        <input type="text" name="cod_amount" id="cod_amount" class="form-control" placeholder="e.g 1000">-->
                                                        <!--    </div>-->
                                                        <!--</div>-->
                                                        <div class="col-sm-2">
                                                            <div class="form-group">
                                                                <label>COD</label>
                                                                <select required="" name="cod" class="form-control" id="cod">
                                                                    <option value="no" {{ $requestData ? ($requestData['cod'] == "no" ? 'selected' :'') : ''}}>No</option>
                                                                    <option value="yes" {{ $requestData ? ($requestData['cod'] == "yes" ? 'selected' :'') : ''}}>Yes</option>
                                                                </select>
                                                                @error('cod')
                                                                    <span class="text-danger">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group" style="margin-top: 14px;">
                                                                <button type="submit" style="margin-top: 10px;" class="btn btn-primary"><i class="mdi mdi-calculator"></i> Calculate</button>
                                                                <input type="reset" style="margin-top: 10px;margin-left: 12px;position: absolute;" class="btn btn-danger" value="Clear">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="table-responsive m-t-30" style="margin-top: 75px;">
                                                        <table class="table table1" >
                                                                <thead>
                                                                    <tr>
                                                                        <th >S.No</th>
                                                                        <th >Provider</th>
                                                                        <th >Courier Charges</th>
                                                                        <th >COD Charges</th>
                                                                        <th >Total Charges</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                     @if(!empty($courier_data))
                                                                        @foreach($courier_data as $key=>$cData)
                                                                        <tr>
                                                                            <td>{{$key+1}}</td>
                                                                            <td>{{ $cData['courier_name'].' '.$cData['shipment_mode'].' '.$cData['weight'] }}</td>
                                                                            <td>{{ $cData['courier_rate'] }}</td>
                                                                            <td>{{ $cData['cod'] }}</td>
                                                                            <td>{{ $cData['courier_rate']+ $cData['cod'] }}</td>
                                                                        </tr>
                                                                        @endforeach
                                                                    @else
                                                                        <tr><td colspan="5" class="text-center">no data found</td></tr>
                                                                    @endif
                                                                </tbody>
                                                                <!--<tfoot>-->
                                                                <!--    <tr>-->
                                                                <!--        <td colspan="5" class="text-right">*GST Additional</td>-->
                                                                <!--    </tr>-->
                                                                <!--</tfoot>-->
                                                            </table>
                                                    </div>
                                                </form>
                                                <div class="col-sm-12 p-all-15 border-top m-t-15">
                                                        <p><b>Terms &amp; Conditions:</b></p>
                                                        <ul>
                                                            <li>Above Shared Commercials are Exclusive GST.</li>
                                                            <li>Above pricing subject to change based on courier company updation or change in any commercials.</li>
                                                            <li>Freight Weight is Picked - Volumetric or Dead weight whichever is higher will be charged.</li>
                                                            <li>Return charges as same as Forward for currier's where special RTO pricing is not shared.</li>
                                                            <li>Fixed COD charge or COD % of the order value whichever is higher.</li>
                                                            <li>Other charges like address correction charges if applicable shall be charged extra.</li>
                                                            <li>Prohibited item not to be ship, if any penalty will charge to seller.</li>
                                                            <li>No Claim would be entertained for Glassware, Fragile products,</li>
                                                            <li>Concealed damages and improper packaging.</li>
                                                            <li>Any weight dispute due to incorrect weight declaration cannot be claimed.</li>
                                                            <li>Chargeable weight would be volumetric or actual weight, whichever is higher (LxBxH/5000).
                                                            </li>
                                                            <li>Delhivery 2 KG, 5 KG &amp; 10 KG accounts have 4000 volumetric divisor.
                                                            </li>
                                                            <li>Liability of Reverse QC check - maximum limit INR 2000 or product value whichever is lower.</li>
                                                        </ul>
                                                    </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="b2b_cal" role="tabpanel" aria-labelledby="contact-tab">
                                            <div class="table-responsive m-t-10">
                                                <div class="row">
                                                    <div class="col-sm-6 ">
                                                        <form id="b2b_pricing_calculator_form" method="post" action="#">
                                                            <div class="row m-t-20">
                                                                <div class="col-sm-3">
                                                                    <div class="form-group">
                                                                        <label>Pick-up Pincode</label>
                                                                        <div class="input-group mb-2">
                                                                            <div class="input-group-prepend">
                                                                                <div class="input-group-text"><i class="mdi mdi-map-marker"></i></div>
                                                                            </div>
                                                                            <input type="text" name="origin" class="form-control" required="" placeholder="">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-3">
                                                                    <div class="form-group">
                                                                        <label>Delivery Pincode</label>
                                                                        <div class="input-group mb-2">
                                                                            <div class="input-group-prepend">
                                                                                <div class="input-group-text"><i class="mdi mdi-map-marker"></i></div>
                                                                            </div>
                                                                            <input type="text" name="destination" class="form-control" required="" placeholder="">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-3">
                                                                    <div class="form-group">
                                                                        <label>Value In INR </label>
                                                                        <div class="input-group mb-2">
                                                                            <div class="input-group-prepend">
                                                                            </div>
                                                                            <input type="text" name="cod_amount" class="form-control" style="margin-left: -1; margin-left: 2px;" placeholder="e.g 1000">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-3">
                                                                    <div class="form-group">
                                                                        <label>COD</label>
                                                                        <select required="" name="cod" class="form-control">
                                                                            <option value="prepaid">No</option>
                                                                            <option value="cod">Yes</option>
                                                                        </select>
                                                                    </div>
                                                                </div>                
                                                            </div>
                                                            <div class="row">
                                                                <section class="after-add-more" style="display:flex;">
                                                                    <div class="col-sm-2">
                                                                        <div class="form-group">
                                                                            <label>Qty</label>
                                                                            <input type="text" name="product_b2b_qty[]" class="form-control" required="1" value="1" placeholder="1">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-3">
                                                                        <div class="form-group">
                                                                            <label>Weight (Kg)</label>
                                                                            <div class="input-group mb-2">
                                                                                <div class="input-group-prepend">
                                                                                <div class="input-group-text">Kg</div>
                                                                                </div>
                                                                                <input type="text" name="weight[]" class="form-control" required="" value="5" placeholder="">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-2">
                                                                        <div class="form-group">
                                                                            <label>L (cm)</label>
                                                                            <input type="text" name="length[]" class="form-control" required="" value="10" placeholder="cm">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-2">
                                                                        <div class="form-group">
                                                                            <label>H (cm)</label>
                                                                            <input type="text" name="height[]" class="form-control" required="" value="10" placeholder="cm">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-2">
                                                                        <div class="form-group">
                                                                            <label>B (cm)</label>
                                                                            <input type="text" name="breadth[]" class="form-control" required="" value="10" placeholder="cm">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <div class="form-group change">
                                                                            <label for="">&nbsp;</label><br> 
                                                                            <button type="button" class="btn btn-danger btn-sm remove" disabled=""><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16"><path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"></path></svg></button>
                                                                        </div>
                                                                    </div>
                                                                </section>            
                                                                 end 
                                                                <div class="col-md-3">
                                                                    <div class="form-group change">
                                                                        <label for="">&nbsp;</label><br>
                                                                        <button type="button" class="btn btn-success add-more"> Add More</button>
                                                                    </div>
                                                                </div>   
                                                                <div class="row m-t-20">
                                                                    <div class="col-sm-12 text-center">
                                                                        <button type="submit" style="margin-top: 8px;margin-left: -29px;" name="submit" class="btn btn-primary"><i class="mdi mdi-calculator"></i> Calculate</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="table-responsive m-t-30">
                                                            <table class="table table-bordered table-sm text-left table-hover" id="b2b_calculated_price" style="display:none;">
                                                                <thead>
                                                                    <tr>
                                                                        <th>S.No</th>
                                                                        <th>Carrier</th>
                                                                        <th>Courier Charges</th>
                                                                        <th>Courier Charges (Bifurcation)</th>
                                                                        <th>Transportation Id</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                </tbody>
                                                                <tfoot>
                                                                    <tr>
                                                                        <td colspan="5" class="text-right">*GST Additional</td>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 p-all-15 border-top m-t-15">
                                                    <p><b>Terms &amp; Conditions:</b></p>
                                                    <ul>
                                                        <li>Above Shared Commercials are Exclusive GST.</li>
                                                        <li>Above pricing subject to change based on courier company updation or change in any commercials.</li>
                                                        <li>Freight Weight is Picked - Volumetric or Dead weight whichever is higher will be charged.</li>
                                                        <li>Other charges like address correction charges if applicable shall be charged extra.</li>
                                                        <li>Prohibited item not to be ship, if any penalty will charge to seller.</li>
                                                        <li>No Claim would be entertained for Glassware, Fragile products,</li>
                                                        <li>Concealed damages and improper packaging.</li>
                                                        <li>Any weight dispute due to incorrect weight declaration cannot be claimed.</li>
                                                        <li>Chargeable weight would be volumetric or actual weight, whichever is higher
                                                            <br>
                                                            <strong>Xpressbees:</strong> (LxBxH/27000)*CFT
                                                            <br>
                                                            <strong>Bluedart:</strong> (LxBxH/27000)*CFT
                                                            <br>
                                                            <strong>Delhivery:</strong> (LxBxH/27000)*CFT
                                                            <br>
                                                            <strong>Oxyzen:</strong> (LxBxH/27000)*CFT
                                                            <br>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="international_cal" role="tabpanel" aria-labelledby="contact-tab">
                                                <div class="table-responsive m-t-10">
                                                    <div class="row">
                                                        <div class="col-sm-6 ">
                                                            <form id="int_pricing_calculator_form" method="post" action="javascript:;">
                                                                <div class="row m-t-20">
                                                                    <div class="col-sm-4">
                                                                        <div class="form-group">
                                                                            <label>Pick-up Country</label>
                                                                            <div class="input-group mb-2">
                                                                                <div class="input-group-prepend">
                                                                                    <div class="input-group-text"><i class="mdi mdi-map-marker"></i></div>
                                                                                </div>
                                                                                <select required="" name="origin" class="form-control">
                                                                                    <option value="">Select Origin</option>
                                                                                    <option value="India">India (IN)</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-4">
                                                                        <div class="form-group">
                                                                            <label>Delivery Country</label>
                                                                            <div class="input-group mb-2">
                                                                                <div class="input-group-prepend">
                                                                                    <div class="input-group-text"><i class="mdi mdi-map-marker"></i></div>
                                                                                </div>
                                                                                <select required="" name="destination" class="form-control">
                                                                                    <option value="">Select Destination</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-4">
                                                                        <div class="form-group">
                                                                            <label>Weight</label>
                                                                            <div class="input-group mb-2">
                                                                                <div class="input-group-prepend">
                                                                                    <div class="input-group-text">Kg</div>
                                                                                </div>
                                                                                <input type="text" name="weight" class="form-control" required="" value="0.5" placeholder="">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-sm-3">
                                                                        <div class="form-group">
                                                                            <label>L(cm)</label>
                                                                            <input type="text" name="length" class="form-control" required="" value="10" placeholder="cm">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-3">
                                                                        <div class="form-group">
                                                                            <label>H(cm)</label>
                                                                            <input type="text" name="height" class="form-control" required="" value="10" placeholder="cm">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-3">
                                                                        <div class="form-group">
                                                                            <label>B(cm)</label>
                                                                            <input type="text" name="breadth" class="form-control" required="" value="10" placeholder="cm">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-3">
                                                                        <div class="form-group">
                                                                            <label>Value in INR</label>
                                                                            <div class="input-group mb-2">
                                                                                <div class="input-group-prepend">
                                                                                    <div class="input-group-text">₹</div>
                                                                                </div>
                                                                                <input type="text" name="cod_amount" class="form-control" placeholder="e.g 1000">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row m-t-20">
                                                                        <div class="col-sm-12 text-center">
                                                                            <button type="submit" style="margin-top: 8px;margin-left: 15px;" name="submit" class="btn btn-primary"><i class="mdi mdi-calculator"></i> Calculate</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="table-responsive m-t-30">
                                                                    <table class="table table-bordered table-sm text-left table-hover" id="int_calculated_price" style="display:none;">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>S.No</th>
                                                                                <th>Carrier</th>
                                                                                <th>Courier Charges</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        </tbody>
                                                                        <tfoot>
                                                                            <tr>
                                                                                <td colspan="5" class="text-right">*GST Additional</td>
                                                                            </tr>
                                                                        </tfoot>
                                                                    </table>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 p-all-15 border-top m-t-15">
                                                    <p><b>Terms &amp; Conditions:</b></p>
                                                    <ul>
                                                        <li>Above Shared Commercials are Exclusive GST.</li>
                                                        <li>Carriage of shipment is subject to destination country restrictions.</li>
                                                        <li>Custom duty shall be additional as per destination country and it needs to be payable immediately.</li>
                                                        <li>NimbusPost reserves the right to inspect shipment prior to carriage.</li>
                                                        <li>Applicable charges will be based on the volumetric or actual weight, whichever is higher.</li>
                                                        <li>Volumetric weight calculation (CMS): (L * B * H) / 5000</li>
                                                        <li>For Fedex commercial shipment INR 950 + GST will be extra</li>
                                                        <li>Address correction charges for Fedex 1550 + GST or 10 Rs per kg, whichever is higher</li>
                                                        <li>For TNT commercial shipment INR 1200 + GST will be extra</li>
                                                        <li>Address correction charges for TNT 1550 + GST or 10rs per kg, whichever is higher</li>
                                                        <li>For DHL commercial shipment INR 2350 + GST will be extra</li>
                                                        <li>Address correction charges for DHL 950 + GST.</li>
                                                        <li>For Aramex commercial shipment INR 2500 + GST will be extra</li>
                                                        <li>Address correction charges for Aramex 50 + GST.</li>
                                                        <li>Maximum liability for lost/shortage is USD 100 only or Invoice value, whichever is lower.</li>
                                                        <li>Any commercial shipment connect our Dedicated Sales Team.</li>
                                                        <li>Due to Covid-19 outbreak Global countries are affected, please expect delay in all inbound and outbound shipments. All pickups, clearance and Deliveries are affected.</li>
                                                        <li>Nimbus Post will make every reasonable effort to deliver the shipment but Nimbus Post is not liable for any damages or loss caused by delay.</li>
                                                    </ul>
                                                </div>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 table_highlight">
                                <div class="card m-b-20">
                                    <div class="card-header bg-dark text-white">
                                        <h5 class="m-b-0" style="text-align:center;">
                                            Pricing Plans
                                        </h5>
                                    </div>
                                </div>
                                <div class="tabbable boxed parentTabs">
                                    <div class="tab-content m-t-10">
                                        <ul class="nav nav-tabs tab-line ul-nav-tabs">
                                            <li class="nav-item">
                                                <a href="#custom" class="nav-link active">Forward</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#custom_rto" class="nav-link">RTO</a>
                                            </li>
                                        </ul>
                                        <div class="tab-pane fade show active" id="custom" role="tabpanel" aria-labelledby="contact-tab">
                                            <div class="table-responsive m-t-10">
                                                <table class="table table1" >
                                                    <tr class="bg-light" >
                                                        <!--<th rowspan="2" class="pt-15">#</th>-->
                                                        <th rowspan="1" style="width: 20%;" class="pt-15">Category</th>
                                                     
                                                        @foreach($zones as $zone)
                                    				        <th class="p-s">
                        										{{ $zone->zone_code }} ( ₹ )	
                        									</th>
                        								@endforeach
                                                        <th colspan="2">Whichever is Higher</th>   
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        @foreach($zones as $zone)
                                                        <td class="p-s">{{ $zone->description }}</td>
                                                        @endforeach
                                                        <td class="p-s">COD ( ₹ )</td>
                                                        <td class="p-s">COD (%)</td>
                                                    </tr>
                                                    @foreach($rates as $key => $rate)
                        								@php  
                        									$jsonData = $rate->forward;
                        									$dataArray = json_decode($jsonData, true);
                        									$forward_additionalData = $rate->forward_additional;
                        									$forward_additionalArray = json_decode($forward_additionalData, true);
                        									$reverseData = $rate->reverse;
                        									$reverseArray = json_decode($reverseData, true);
                                                            $dtoData = $rate->dto;
                        									$dtoArray = json_decode($dtoData, true);
                        								@endphp
                                                        
                                                    <tr>
                                                        <!--<td rowspan="4" class="pt-35">-->
                                                        <!--    <div class="d-flex align-items-center">-->
                                                        <!--        <a title='View Partners' href="{{ route('edit-card', ['id' => Crypt::encrypt($rate->id)]) }}">-->
                                                        <!--            <svg class="svg-icon svg-icon-sm svg-icon-heavy me-2"><use xlink:href="#survey-1"></use></svg>-->
                                                        <!--        </a>-->
                                                        <!--    </div>  -->
                                                        <!--</td>-->
                                                        <td rowspan="1" class="pt-35">
                                                            Forward
                                                            @if($rate->aggregator) 
                                                                {{ $rate->courier. ' - '. $rate->shipment_mode.' - '.$rate->min_weight.' Kgs'}} 
                                                            @else 
                                                                {{ $rate->courier. ' - '. $rate->shipment_mode.' - '.$rate->min_weight.' Kgs' }} 
                                                            @endif
                                                        </td>
                                                        
                        									@foreach($dataArray['forward']  as $key => $value)																
                        										<td >{{$value ?? '0.00'}}</td>									
                        									@endforeach                                         
                        									
                                                        <td rowspan="1" class="pt-35">{{ $rate->cod}}</td>
                                                        <td rowspan="1" class="pt-35">{{ $rate->cod_percent }}</td>
                                                    </tr>
                                                    <tr>
                                                        <!-- <td>@if($rate->aggregator) 
                                                                {{ $rate->aggregator.' - '.$rate->courier. ' - '. $rate->shipment_mode.' - '.$rate->min_weight.' Kgs'}} 
                                                            @else 
                                                                {{ $rate->courier. ' - '. $rate->shipment_mode.' - '.$rate->min_weight.' Kgs' }} 
                                                            @endif
                                                        </td> -->
                                                          <td>Additional {{ $rate->additional_weight.' Kg'}}</td>
                        									@foreach($forward_additionalArray['forward_additional']  as $key => $value)																
                        										<td >{{ $value ?? '0.00'}}</td>									
                        									@endforeach                                         
                        									<td rowspan="1" class="pt-35">{{ $rate->cod}}</td>
                                                        <td rowspan="1" class="pt-35">{{ $rate->cod_percent }}</td>
                                                        <!-- <td>{{ $rate->cod}}</td>
                                                        <td>{{ $rate->cod_percent }}</td> -->
                                                    </tr>
                                                    
                                                    
                                                    @endforeach 
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="custom_rto" role="tabpanel" aria-labelledby="contact-tab">
                                            <div class="table-responsive m-t-10">
                                                <table class="table table1" >
                                                    <tr class="bg-light" >
                                                        <!--<th rowspan="2" class="pt-15">#</th>-->
                                                        <th rowspan="1" style="width: 20%;" class="pt-15">Category</th>
                                                     
                                                        @foreach($zones as $zone)
                                    				        <th class="p-s">
                        										{{ $zone->zone_code }} ( ₹ )	
                        									</th>
                        								@endforeach
                                                         
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        @foreach($zones as $zone)
                                                        <td class="p-s">{{ $zone->description }}</td>
                                                        @endforeach
                                                        
                                                    </tr>
                                                    @foreach($rates as $key => $rate)
                        								@php  
                        									$jsonData = $rate->forward;
                        									$dataArray = json_decode($jsonData, true);
                        									
                        									$reverseData = $rate->reverse;
                        									$reverseArray = json_decode($reverseData, true);
                                                            
                        								@endphp
                                                        
                                                    <tr>
                                                        <!--<td rowspan="4" class="pt-35">-->
                                                        <!--    <div class="d-flex align-items-center">-->
                                                        <!--        <a title='View Partners' href="{{ route('edit-card', ['id' => Crypt::encrypt($rate->id)]) }}">-->
                                                        <!--            <svg class="svg-icon svg-icon-sm svg-icon-heavy me-2"><use xlink:href="#survey-1"></use></svg>-->
                                                        <!--        </a>-->
                                                        <!--    </div>  -->
                                                        <!--</td>-->
                                                        <td rowspan="1" class="pt-35">
                                                            Forward
                                                            @if($rate->aggregator) 
                                                                {{ $rate->courier. ' - '. $rate->shipment_mode.' - '.$rate->min_weight.' Kgs'}} 
                                                            @else 
                                                                {{ $rate->courier. ' - '. $rate->shipment_mode.' - '.$rate->min_weight.' Kgs' }} 
                                                            @endif
                                                        </td>
                                                        
                        									@foreach($reverseArray['reverse']  as $key => $value)																
                        										<td >{{$value ?? '0.00'}}</td>									
                        									@endforeach                                         
                        									
                                                        
                                                    </tr>
                                                    
                                                    
                                                    
                                                    @endforeach 
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">*GST Additional</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>  
    </div>
</section>
<script>
    $(document).ready(function() {
        $("body").on("click",".add-more",function(){ 
            var html = $(".after-add-more").first().clone();   
            if($('.after-add-more .change').length>0)
            $('.btn-danger').removeAttr('disabled');
              $(html).find(".change").html("<label for=''>&nbsp;</label><br/> <button type='button' class='btn btn-danger btn-sm remove'><svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-x' viewBox='0 0 16 16'><path d='M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z'/></svg></button>");
          
          
            $(".after-add-more").last().after(html);
            $(html).find('input:text').val(''); 
           
        });
    
        $("body").on("click",".remove",function(){ 
            $('.btn-danger').removeAttr('disabled');
            $(this).parents(".after-add-more").remove();
    
            if($('.after-add-more .change').length==1){
            $('.btn-danger').attr('disabled','disabled');
            }
           
            return false;
    
        });
    });
        
    $("ul.ul-nav-tabs a").click(function(e) {
        e.preventDefault();
        $(this).tab('show');
    });

    function showdiv_cal(div_name)
    {
      if(div_name=='b2c')
      {
        $(".mail_cal_heading").removeClass("col-sm-6");
        $(".main_class").removeClass("col-sm-12");
        $('.table_highlight').show();
      }
       else if(div_name=='b2b')
      {
        $(".mail_cal_heading").addClass("col-sm-6");
        $(".main_class").addClass("col-sm-12");
        $('.table_highlight').hide();
      } 
      else  if(div_name=='int')
      {
        $(".mail_cal_heading").addClass("col-sm-6");
        $(".main_class").addClass("col-sm-12");
        $('.table_highlight').hide();
      }
    }
    function enabledDisabledFields(val)
    {
        if(val=='yes'){
            $('#weight').prop("disabled", true);
            $('#length').prop("disabled", true);
            $('#height').prop("disabled", true);
            $('#breadth').prop("disabled", true);
            $('#cod_amount').prop("disabled", true);
            $('#cod').prop("disabled", true);
        }
        else{
            $('#weight').prop("disabled", false);
            $('#length').prop("disabled", false);
            $('#height').prop("disabled", false);
            $('#breadth').prop("disabled", false);
            $('#cod_amount').prop("disabled", false);
            $('#cod').prop("disabled", false);
        }
    }
</script>             
@endsection