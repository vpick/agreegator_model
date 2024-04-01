<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
			$table->string('omnee_order')->unique();
			$table->string('order_no')->default('');
			$table->string('order_type')->default('');
			$table->string('business_account')->default('');
			$table->string('client_code')->default('');
			$table->string('gst_no')->default('');
			$table->string('currency_code')->default('');
			$table->string('consignment_type')->default('');
			
			$table->string('shipping_label')->default('');
			$table->string('manifest_url')->default('');
			$table->string('invoice_url')->default('');
			
			$table->string('payment_mode')->default('');
			$table->integer('total_quantity')->default(0);
			$table->decimal('shipping_charges',10,2)->default(0.00);
			$table->decimal('cod_amount',10,2)->default(0.00);
			$table->decimal('discount_amount',10,2)->default(0.00);
			$table->decimal('tax_amount',10,2)->default(0.00);
			$table->decimal('total_amount',10,2)->default(0.00);
			
			$table->string('invoice_no')->default('');
			$table->decimal('invoice_amount',10,2)->default(0.00);
			$table->integer('no_of_invoice')->default(0);
			$table->string('invoice_date')->default('');
			
			$table->integer('no_of_box')->default(0);
			$table->string('awb_no')->default('');
			
			$table->string('dimension_unit')->default('');
			$table->string('weight_unit')->default('');
			$table->decimal('total_weight',10,2)->default(0.00);
			$table->decimal('volumetric_weight',10,2)->default(0.00);
			$table->decimal('vol_weight',10,2)->default(0.00);
			
			$table->integer('length')->default(0);
			$table->integer('breadth')->default(0);
			$table->integer('height')->default(0);
			
			$table->string('courier_name')->default('');
			$table->integer('courrier_id')->default(0);
			
			$table->string('shipping_first_name')->default('');	
			$table->string('shipping_last_name')->default('');
			$table->string('shipping_company_name')->default('');
			$table->string('shipping_address_1')->default('');
			$table->string('shipping_address_2')->default('');
			$table->bigInteger('shipping_phone_number')->default(0);
			$table->bigInteger('shipping_alternate_phone')->default(0);
			$table->string('shipping_email')->default('');
			$table->string('shipping_city')->default('');
			$table->string('shipping_state')->default('');
			$table->integer('shipping_pincode')->default(0);

            $table->string('billing_first_name')->default('');	
			$table->string('billing_last_name')->default('');
			$table->string('billing_company_name')->default('');
			$table->string('billing_address_1')->default('');
			$table->string('billing_address_2')->default('');
			$table->bigInteger('billing_phone_number')->default(0);
			$table->bigInteger('billing_alternate_phone')->default(0);
			$table->string('billing_email')->default('');
			$table->string('billing_city')->default('');
			$table->string('billing_state')->default('');
			$table->integer('billing_pincode')->default(0);
			
			$table->string('latitude')->default('');
			$table->string('longitude')->default('');
			$table->string('hyperlocal_address')->default('');
            $table->integer('postal_code')->default(0);
			
			$table->string('warehouse_name')->default('');
            $table->string('warehouse_code')->default('');			
			$table->string('warehouse_address')->default('');
			$table->string('warehouse_address_2')->default('');
			$table->string('warehouse_state')->default('');
			$table->string('warehouse_city')->default('');
			$table->integer('warehouse_pincode')->default(0);
			$table->bigInteger('warehouse_phone_number')->default(0);
			$table->bigInteger('warehouse_alternate_phone')->default(0);
			
			$table->string('remarks')->default('');
			$table->longText('tracking_history')->nullable()->default(null);
			$table->string('order_status')->default('');
			$table->string('sending_status')->default('');
			$table->longText('order_request')->nullable()->default(null);
			$table->string('request_partner')->default('');
			$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
		// Drop the 'orders' table
		Schema::dropIfExists('orders');
		Schema::enableForeignKeyConstraints();
    }
}
