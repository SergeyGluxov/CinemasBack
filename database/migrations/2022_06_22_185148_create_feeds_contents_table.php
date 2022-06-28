<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedsContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feeds_contents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('feed_id')->unsigned();
            $table->foreign('feed_id')->references('id')->on('feeds')->onDelete('cascade');
            $table->integer('content_id')->unsigned();
            $table->foreign('content_id')->references('id')->on('contents')->onDelete('cascade');
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
        Schema::dropIfExists('feeds_contents');
    }
}
