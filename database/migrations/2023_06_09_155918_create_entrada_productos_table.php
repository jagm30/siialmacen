<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntradaProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entrada_productos', function (Blueprint $table) {
            $table->id();
            $table->integer('id_entrada');
            $table->integer('id_producto');
            $table->integer('cantidad');
            $table->float('precio', 8,2);            
            $table->string('categoria',30);
            $table->string('status',30);
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
        Schema::dropIfExists('entrada_productos');
    }
}
