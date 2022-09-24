<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPosterToEpisodes extends Migration
{

    public function up()
    {
        Schema::table('episodes', function (Blueprint $table) {
            $table->string('poster')->nullable();
        });
    }


    public function down()
    {
        Schema::table('episodes', function (Blueprint $table) {
            //
        });
    }
}
