<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;
    protected $fillable = ['nombre','descripcion','talla','precio','precioPromocion','categoria','claveproducto','stock','status','id_usuario'];
}
