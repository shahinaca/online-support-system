<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnquiryResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enquiry_responses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('created_by')->unsigned()->index(); 
            $table->bigInteger('enquiry_id')->unsigned()->index(); 
            $table->longText('reply');
            $table->enum('user_type', ['admin', 'customer']);
            $table->tinyInteger('notification_email')->default(0);
            $table->tinyInteger('is_read')->default(0);
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('enquiry_id')->references('id')->on('enquiries')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enquiry_responses');
    }
}
