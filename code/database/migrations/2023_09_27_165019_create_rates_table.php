<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained();
            $table->string('contract_type');
            $table->string('logistics_type');
            $table->string('aggregator')->nullable();
            $table->string('courier');
            $table->string('shipment_mode');
            $table->decimal('min_weight',10,2)->default(0.00);
            $table->longText('forward');
            $table->longText('forward_additional');
            $table->longText('reverse');
            $table->longText('dto');
            $table->decimal('cod',10,2)->default(0.00);
            $table->decimal('cod_percent',10,2)->default(0.00);
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rates');
    }
}
