<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Producto;
use App\Models\categoriaproducto;
class ProductoController extends Controller
{
    //    
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(){         
    	$productos = Producto::all();    	
		$categoriaproductos = Categoriaproducto::all();
	   	return view('producto.index', compact('productos','categoriaproductos'));     
	}
	public function store(Request $request)
    {              
        Producto::create($request->all());
        return json_encode(array(
            "Estado"=>"Agregado correctamente"
        ));
    }
    public function show(Request $request, $id)
    {              
		if ($request->ajax()) {
            $producto    = DB::table('productos')                                        
                    ->where('productos.id',$id)
                    ->first();
            return json_encode($producto);
        }

    }
    public function edicion(Request $request ,$id_producto ,$nombre ,$descripcion ,$categoria ,$precio ,$precioPromocion)
    {              
        $producto = Producto::find($id_producto);
        $producto->nombre           = $nombre;
        $producto->descripcion      = $descripcion;
        $producto->categoria        = $categoria;
        $producto->precio           = $precio;
        $producto->precioPromocion  = $precioPromocion;
        $producto->save();
        return response()->json(['data' => "Cambios guardados correctamente..."]);

    }
    public function destroy($id){
        $producto   = Producto::findOrFail($id);
        $producto->delete();
        return response()->json(['data' => "Eliminado correctamente..."]);
    }
}
