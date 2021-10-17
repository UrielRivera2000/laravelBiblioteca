<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAutoresLibrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('autores_libros', function (Blueprint $table) {
            
            $table->unsignedBigInteger('autores_id');
            $table->unsignedBigInteger('libros_id');
        });


        Schema::table('autores_libros', function (Blueprint $table){
            $table->foreign('autores_id')->references('id')->on('autores');
        });

        Schema::table('autores_libros', function (Blueprint $table){
            $table->foreign('libros_id')->references('id')->on('libros');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('autores_libros');
    }
}
