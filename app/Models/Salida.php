<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salida extends Model
{
    use HasFactory;
    protected $fillable = ['id','folioreq','solicitante','fecha','almacen','cajapago','nnotaventa','fventa','formapago','observaciones','total','status','id_usuario'];
}
