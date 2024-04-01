<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_details', function (Blueprint $table) {
            $table->id();
			$table->string('product_code')->default('');
			$table->string('product_hsn_code')->default('');
			$table->string('product_description')->default('');
			$table->integer('product_quantity')->default(1);
			$table->decimal('product_price',10,2)->default(0.00);
			$table->integer('no_of_box')->default(1);
			$table->string('product_weight_unit')->default('grams');
			$table->decimal('product_weight',10,2)->default(0.00);
			$table->string('product_lbh_unit')->default('cm');
			$table->integer('product_breadth')->default(1);
			$table->integer('product_height')->default(1);
			$table->integer('product_length')->default(1);
			
			$table->unsignedBigInteger('order_id');
			$table->foreign('order_id') // Defining the foreign key constraint
                ->references('id')
                ->on('orders')
                ->onDelete('cascade');
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
        Schema::dropIfExists('product_details');
    }
}
