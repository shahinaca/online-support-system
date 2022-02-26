<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnquiriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enquiries', function (Blueprint $table) {
            $table->id();
            $table->string('enquiry_code',15);
            $table->longText('question');
            $table->tinyInteger('is_read')->default(0);
            $table->enum('status', ['Not Answered', 'In Progress', 'Answered', 'SPAM'])->default("Not Answered");
            $table->bigInteger('closed_by')->unsigned()->index()->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->bigInteger('created_by')->unsigned()->index();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('created_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('closed_by')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enquiries');
    }
    
}
