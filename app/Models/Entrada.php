<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entrada extends Model
{
    use HasFactory;
    protected $fillable = ['nfactura','proveedor','fecha','id_almacen','referencia','categoria','observaciones','status','id_usuario'];
}
