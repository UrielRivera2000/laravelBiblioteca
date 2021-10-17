<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLibrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('libros', function (Blueprint $table) {
            $table->id();
            $table->string('isbn',15);
            $table->string('titulo', 255);
            $table->text('descripcion')->nullable();
            $table->date('fecha_publicacion')->nullable();
            $table->unsignedBigInteger('editorial_id');
            $table->unsignedBigInteger('categoria_id');
        });

        Schema::table('libros', function (Blueprint $table){
            $table->foreign('editorial_id')->references('id')->on('editoriales');
        });

          Schema::table('libros', function (Blueprint $table){
            $table->foreign('categoria_id')->references('id')->on('categorias');
        });



    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('libros');
    }
}
