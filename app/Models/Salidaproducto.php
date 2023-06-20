<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salidaproducto extends Model
{
    use HasFactory;
    protected $fillable = ['id_salida','id_producto','cantidad','precio','status','id_usuario'];
}
