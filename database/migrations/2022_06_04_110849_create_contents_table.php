<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('rating')->nullable();
            $table->string('restrict')->nullable();
            $table->integer('year')->nullable();
            $table->string('country')->nullable();
            $table->integer('duration');
            $table->integer('type_content_id')->unsigned()->index();
            $table->foreign('type_content_id')->references('id')->on('type_contents')->onDelete('cascade');
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
        Schema::dropIfExists('content_infos');
    }
}
