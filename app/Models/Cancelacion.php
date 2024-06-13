<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cancelacion extends Model
{
    use HasFactory;
    protected $fillable = ['id_salida','motivo','fecha','id_usuario'];
}
