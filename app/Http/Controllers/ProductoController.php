<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Producto;
use App\Models\categoriaproducto;
use App\Models\CatAlmacen;
class ProductoController extends Controller
{
    //    
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(){         
    	$productos = DB::table('productos')
            ->select('productos.id','productos.nombre','productos.descripcion','productos.categoria','productos.claveproducto','productos.precio','productos.precioPromocion','cat_almacens.nombre as nomalmacen')
            ->leftJoin('cat_almacens', 'productos.categoria', '=', 'cat_almacens.id')
            ->get();    

        $almacenes          = CatAlmacen::all();
		$categoriaproductos = Categoriaproducto::all();
	   	return view('producto.index', compact('productos','categoriaproductos','almacenes'));     
	}
	public function store(Request $request)
    { 
        try {              
            Producto::create($request->all());
            return response()->json(['data' => "Registrado correctamente."]);  

        } catch (\Exception $e) {
            
            return response()->json(['data' => $e->getMessage()]);  
        }                        
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
