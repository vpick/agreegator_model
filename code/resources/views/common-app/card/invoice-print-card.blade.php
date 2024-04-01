@extends('common-app/master')
@section('title', 'Invoice Print')
@section('content')
<style>
@page {
            size: A4; /* You can use other sizes like 'letter', 'legal', or specific dimensions like '8.5in 11in' */
        }
</style>
<!-- Page Header-->
<!-- <header class="py-4">
    <div class="container-fluid py-2">
        <h1 class="h3 fw-normal mb-0">Invoice</h1>
    </div>
</header> -->
<section>
<div class="container-fluid">
    <div class="row py-2">
        <div class="col-xl-12">
            <div class="row align-items-center gy-3 text-center ">
                <div class="col-sm-6 text-sm-start">
                    <!--<h1 class="h4 mb-0"> Order</h1>-->
                </div>
            <div class="col-sm-6 text-sm-end">
            <button onclick="printSection()" class="btn btn-outline-dark btn-sm" > Print Invoice</button>   
            <button id="download-button"  class="btn btn-outline-dark btn-sm" >Download as PDF</button>
        </div>
    </div>
    <hr/>
</div>
 
<div class="container-fluid" id="sectionToPrint">
    <div class="row py-2" >  
        <div class="col-xl-2"></div>
        <div class="col-xl-8">
            <div class="card" id="invoice">
                <div class="card-body p-sm-3">
                    <div class="row  mb-2">
                        <div class="col text-center">
                            <!-- Logo-->
                            <!-- <img class="img-fluid mb-4" src="img/brand/brand-1.svg" alt="..." style="max-width: 6rem;"> -->
                            <!-- Heading-->
                            <h2 class="mb-2">Tax Invoice</h2>
                            @if($setting['company_name_hide_show']!='0')
                                <p><strong>{{ Auth::user()->company->name }}</strong></p>
                            @endif
                        </div>
                    </div>
                   
                    <div class="row">
                        <div class="col-6">
                            <!-- Badge-->
                            @if($setting['logo']!='' && $setting['logo_toggle']=='1')
                            <img class="img-fluid mb-4" src="{{ $setting['logo'] }}" alt="company logo" style="max-width: 6rem;">
                            @endif
                        </div>
                        <div class="col-6 text-end">
                            <!-- Badge-->
                            @php 
                            if($setting['invoice_prefix']!=''){
                                $pre = $setting['invoice_prefix'];   
                            }else{
                                $pre = '';                    
                            }
                            @endphp
                            <input type="hidden" id="invoice_no" value="{{ $pre.$data[0]->invoice_no }}">
                            <p class="text-muted mb-6">Invoice No:  {{ $pre.$data[0]->invoice_no }}</br>
                            Invoice Date:  {{ $data[0]->invoice_date }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <h6 class="text-uppercase text-muted">Bill & Ship To</h6>
                            <p class="text-muted mb-4 text-sm"><strong class="text-gray-900">{{ $data[0]->shipping_first_name.' '.$data[0]->shipping_last_name }}</strong><br>{{ $data[0]->business_account }}<br>{{ $data[0]->shipping_address_1 }}<br>{{ $data[0]->shipping_city }}<br>{{ $data[0]->shipping_state }}<br>{{ $data[0]->shipping_pincode }}</p>
                            <h6 class="text-uppercase text-muted">Payment Meyhod</h6>
                            <p class="mb-4 text-sm">{{ $data[0]->payment_mode }}</p>
                            
                            <h6 class="text-uppercase text-muted">Awb No</h6>
                            <p class="mb-4 text-sm">{{ $data[0]->awb_no }}</p>
                        </div>
                        <div class="col-12 col-md-6 text-md-end">
                            <h6 class="text-uppercase text-muted">Sold By</h6>
                            <p class="text-muted mb-4 text-sm"><strong class="text-gray-900">{{ $data[0]->name }}</strong><br>{{ $data[0]->warehouse_code }}<br>{{ $data[0]->address }}<br>Middle of Nowhere</p>
                            
                            
                            <h6 class="text-uppercase text-muted">Shipped By</h6>
                            <p class="mb-4 text-sm">{{ $data[0]->courier_name }}</p>
                            <div style="float:center">
                                <table class="table " style="border:0px solid white!important;">
                                @php  
                                    $jsonData = $setting->customize_field;
                                    $dataArray = json_decode($jsonData, true);
                                @endphp
                                @foreach($dataArray['customize_fields'] as $field)
                                    <tr>
                                        <th style="color:#6c757d !important;">{{ $field['column_name'] }}</th>
                                        <td>{{ $field['column_value'] }}</td>
                                    </tr>
                                @endforeach
                                </table>
                            </div>
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <!-- Table-->
                            <div class="table-responsive">
                                <table class="table mt-4 mb-0">
                                    <thead>
                                        <tr>
                                            <th class="px-0 bg-transparent border-top-0"><span class="h6">#</span></th>
                                            <th class="px-0 bg-transparent border-top-0"><span class="h6">Product</span></th>
                                            <th class="px-0 bg-transparent border-top-0"><span class="h6">HSN Code</span></th>
                                            <th class="px-0 bg-transparent border-top-0"><span class="h6">SKU</span></th>
                                            <th class="px-0 bg-transparent border-top-0 "><span class="h6">Quantity</span></th>
                                            <th class="px-0 bg-transparent border-top-0 text-end"><span class="h6">Size</span></th>
                                            <th class="px-0 bg-transparent border-top-0 text-end"><span class="h6">Weight</span></th>
                                            <th class="px-0 bg-transparent border-top-0 text-end"><span class="h6">Price</span></th>
                                            <th class="px-0 bg-transparent border-top-0 text-end"><span class="h6">Total</span></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $sub_total = 0;
                                        @endphp
                                        @foreach($products as $key => $product)
                                        <tr>
                                            <td class="px-0">{{ $key+1 }}{{ '.  ' }}</td>
                                            <td class="px-0">{{ $product->product_description }}</td>
                                            <td class="px-0">{{ $product->product_hsn_code }}</td>
                                            <td class="px-0">{{ $product->product_code }}</td>
                                            <td class="px-0 ">{{ $product->product_quantity }}</td>
                                            <td class="px-0 text-end">{{ $product->product_length.'X'.$product->product_breadth.'X'.$product->product_height.' '. $product->product_lbh_unit}}</td>
                                            <td class="px-0 text-end">{{ (int)$product->product_weight.' '.$product->product_weight_unit }}</td>
                                            
                                            <td class="px-0 text-end">Rs. {{ $product->product_price }}</td>
                                                <td class="px-0 text-end">Rs. {{ $product->product_price*$product->product_quantity }}</td>
                                                @php
                                                $sub_total+= $product->product_price*$product->product_quantity;
                                            @endphp
                                        </tr>
                                        @endforeach
                                        
                                        
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td class="px-0 border-bottom-0" colspan="5"><span class="fw-5"> Tax Amount</span></td>
                                            <td class="px-0 text-end border-bottom-0" colspan="5"><span class="h5 mb-0"> Rs. {{ $data[0]->tax_amount }}</span></td>
                                        </tr>
                                        <tr>
                                            <td class="px-0 border-bottom-0" colspan="5"><span class="fw-5"> Shipping Charges</span></td>
                                            <td class="px-0 text-end border-bottom-0" colspan="5"><span class="h5 mb-0"> Rs. {{ $data[0]->shipping_charges }}</span></td>
                                        </tr>
                                        <tr>
                                            <td class="px-0 border-bottom-0" colspan="5"><span class="fw-5"> COD Charges</span></td>
                                            <td class="px-0 text-end border-bottom-0" colspan="5"><span class="h5 mb-0"> Rs. {{ $data[0]->cod_amount }}</span></td>
                                        </tr>
                                        <tr>
                                            <td class="px-0 border-bottom-0" colspan="5"><span class="fw-5"> Discount</span></td>
                                            <td class="px-0 text-end border-bottom-0" colspan="5"><span class="h5 mb-0"> Rs.{{ $data[0]->discount_amount }}</span></td>
                                        </tr>
                                        <tr>
                                            <td class="px-0 border-bottom-0" colspan="5"><strong>Grand Total</strong></td>
                                            @php
                                                $grand_total = $sub_total + $data[0]->tax_amount + $data[0]->shipping_charges + $data[0]->cod_amount - $data[0]->discount_amount;
                                            @endphp
                                            <td class="px-0 text-end border-bottom-0" colspan="5"><span class="h3 mb-0"> Rs. {{ $grand_total }}</span></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>  
                            @if($setting['signature']!='' && $setting['signature_toggle']=='1')
                                <p style="margin-top:40px">Authorised Signature</p>
                                <img class="img-fluid" src="{{ $setting['signature'] }}" alt="Authorised Signature" style="max-width: 6rem;">
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2"></div>
    </div>
</div>
</section>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
        const button = document.getElementById('download-button');

        function generatePDF() {
            // Choose the element that your content will be rendered to.
            const element = document.getElementById('invoice');
            // Choose the element and save the PDF for your user.
            const invoice_no = document.getElementById('invoice_no').value;
            const opt = {
              filename: 'invoice-'+invoice_no+'.pdf',
              margin: 2,
              image: {type: 'jpeg', quality: 0.9},
              jsPDF: {format: 'A4', orientation: 'portrait'}
            };
            // New Promise-based usage:
            html2pdf().set(opt).from(element).save();
            // Old monolithic-style usage:
            html2pdf(element, opt);
        }

        button.addEventListener('click', generatePDF);

        
    </script>
    <script>
     function printSection() {
            // Get the content of the section you want to print
            var sectionToPrint = document.getElementById('sectionToPrint');

            if (!sectionToPrint) {
                console.error("Section not found");
                return;
            }

            // Create a new window
            var printWindow = window.open('', '_blank', 'width=600,height=600');

            // Write the content to the new window
            printWindow.document.write('<html><head><title>Print Section</title></head><body>');
            printWindow.document.write(sectionToPrint.innerHTML);
            printWindow.document.write('</body></html>');
            printWindow.document.close();

            // Wait for the content to be fully loaded before triggering the print dialog
            printWindow.onload = function () {
                // Trigger the print dialog for the new window
                printWindow.print();

                // Close the new window after printing (optional)
                printWindow.close();
            };
        }
</script>

@endsection