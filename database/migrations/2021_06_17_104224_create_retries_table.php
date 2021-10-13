<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRetriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('retries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('processed')->default(false);
            $table->integer('succeeded')->default(false);
            $table->integer('failed')->default(false);
            $table->integer('retry')->default(false);
            $table->integer('total')->default(false);
            $table->string('status');
            $table->bigInteger('batch_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('service_id')->unsigned();
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
        Schema::dropIfExists('retries');
    }
}
