<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained();
            $table->enum('company_name_toggle',['1','0'])->default(0);  
            $table->string('invoice_prefix')->nullable();   
            $table->longtext('logo')->nullable();    
            $table->enum('logo_toggle',['1','0'])->default(0);
            $table->longtext('signature')->nullable(); 
            $table->enum('signature_toggle',['1','0'])->default(0);
            $table->longtext('customize_field')->nullable();          
            $table->string('page_size')->nullable();             
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
        Schema::dropIfExists('invoice_settings');
    }
}
