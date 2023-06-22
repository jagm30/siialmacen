<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalidasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salidas', function (Blueprint $table) {
            $table->id();
            $table->integer('folioreq');
            $table->string('solicitante',100);
            $table->date('fecha');
            $table->integer('almacen');
            $table->string('cajapago',30)->nullable();
            $table->string('nnotaventa',20)->nullable();
            $table->string('fventa',20)->nullable();
            $table->string('observaciones',150)->nullable();
            $table->string('status',15)->nullable();
            $table->integer('id_usuario');
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
        Schema::dropIfExists('salidas');
    }
}
