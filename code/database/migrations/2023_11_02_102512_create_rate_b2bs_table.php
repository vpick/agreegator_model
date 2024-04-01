<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRateB2bsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rate_b2bs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('user_type');
            $table->string('origin');
            $table->text('destinations');
            $table->string('region');
            $table->string('courier');
            $table->string('courier_charge',10,2)->default(0.00);  
            $table->decimal('docket_charge',10,2)->default(0.00);   
            $table->decimal('fuel_surcharge',10,2)->default(0.00);               
            $table->longText('fov_owner_risk');
            $table->decimal('min_chargable_weight',10,2)->default(0.00);
            $table->decimal('min_chargable_amount',10,2)->default(0.00);
            $table->decimal('volumetric_weight',10,2)->default(0.00);
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
        Schema::dropIfExists('rate_b2bs');
    }
}
