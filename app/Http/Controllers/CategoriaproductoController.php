<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\categoriaproducto;

class CategoriaproductoController extends Controller
{
    public function index(){         
    	$categoriaproductos = Categoriaproducto::all();
	   	return $categoriaproductos;
	}
	public function show(Request $request, $id)
    {              
		/*if ($request->ajax()) {
            $producto    = DB::table('productos')                                        
                    ->where('productos.id',$id)
                    ->first();
            return json_encode($producto);
        }*/

    }
}
