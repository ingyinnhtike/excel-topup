<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill_requests', function (Blueprint $table) {
            $table->id();
            $table->string('reference_id');
            $table->string('phone_number');
            $table->string('provider');
            $table->string('operator');
            $table->bigInteger('batch_id')->unsigned();
            $table->string('status');
            $table->bigInteger('user_id')->unsigned();
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
        Schema::dropIfExists('bill_requests');
    }
}
