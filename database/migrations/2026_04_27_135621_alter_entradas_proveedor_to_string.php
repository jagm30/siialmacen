<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterEntradasProveedorToString extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE entradas MODIFY COLUMN proveedor VARCHAR(150) NOT NULL');
    }

    public function down()
    {
        DB::statement('ALTER TABLE entradas MODIFY COLUMN proveedor INT NOT NULL DEFAULT 0');
    }
}
