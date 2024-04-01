<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('company_code')->unique();
            $table->string('url')->nullable();
            $table->longText('logo')->nullable();
            $table->string('email');
            $table->string('phone');
            $table->string('pan_no');
            $table->string('pan_name');
            $table->string('pan_photo');
            $table->string('gst_no')->nullable();
            $table->longText('gst_photo')->nullable();
            $table->string('address');
            $table->foreignId('state_id')->constrained();
            $table->string('city');
            $table->string('district');
            $table->string('pincode');
            $table->string('tan_no')->nullable();
            $table->string('account_name');
            $table->string('account_no');
            $table->longText('cancelled_cheque');
            $table->string('bank_name');
            $table->string('bank_branch');
            $table->string('account_type');
            $table->string('ifsc_code');
            $table->enum('approval',['1','0'])->nullable();
            $table->string('firm_type')->nullable();
            $table->string('document_id')->nullable();;
            $table->string('document_type')->nullable();
            $table->string('document_name')->nullable();
            $table->longText('document_photo')->nullable();
            $table->string('section_name')->nullable();
            $table->string('version')->nullable();
            $table->longText('change_description')->nullable();
            $table->longText('doc_link')->nullable();
            $table->string('user_name')->nullable();
            $table->string('acceptance_date')->nullable();
            $table->string('published_on')->nullable();
            $table->string('ip_address')->nullable();
            $table->enum('acceptance',['1','0'])->nullable();
            $table->enum('status',['1','0'])->default(1);
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
        Schema::dropIfExists('companies');
    }
}
