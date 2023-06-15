<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntradaProducto extends Model
{
    use HasFactory;
    protected $fillable = ['id_entrada','id_producto','cantidad','precio','categoria','status','id_usuario'];
}








