<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatrimoniosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patrimonios', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('fundo_id')->unsigned();
            $table->date('date');
            $table->string('value');             
            $table->timestamps();

        });

        Schema::table('patrimonios', function (Blueprint $table) {
            $table->foreign('fundo_id')->references('id')->on('fundos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patrimonios');
    }
}
