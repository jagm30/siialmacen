<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salida extends Model
{
    use HasFactory;
    protected $fillable = ['folioreq','solicitante','fecha','almacen','cajapago','nnotaventa','fventa','observaciones','status','id_usuario'];
}
