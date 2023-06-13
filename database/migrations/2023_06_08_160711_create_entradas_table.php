<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntradasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entradas', function (Blueprint $table) {
            $table->id();
            $table->integer('proveedor');
            $table->date('fecha');
            $table->string('nfactura',20);
            $table->string('referencia',100);
            $table->string('categoria',20);
            $table->string('observaciones',200);
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
        Schema::dropIfExists('entradas');
    }
}









