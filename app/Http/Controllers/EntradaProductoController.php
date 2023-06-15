<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Entrada;
use App\Models\Producto;
use App\Models\EntradaProducto;
use Illuminate\Http\Request;

class EntradaProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $entrada = EntradaProducto::create($request->all());
        return response()->json(['data' => $entrada->id]);   
    }

    public function listarxentrada(Request $request, $id)
    {

        if ($request->ajax()) {
        // $data = Entradaproducto::all();
            return datatables()->of(DB::table('entrada_productos')
            ->select('entrada_productos.id','entrada_productos.id_entrada','entrada_productos.id_producto','entrada_productos.precio','entrada_productos.cantidad','entrada_productos.id_usuario','productos.descripcion')
            ->leftJoin('productos', 'entrada_productos.id_producto', '=', 'productos.id')
            ->where('entrada_productos.id_entrada',$id)
            ->get()
        )->addColumn('action', function($data){
            //$btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-original-title="Edit" class="edit btn btn-primary btn-sm editItem">Edit</a>'; {{route('productos.borrar',$producto->id)}},,href="#" data-id="{{ $entradaproductos->id }}

            $btn = ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$data->id.'" id="btn-eliminar" name="btn-eliminar" data-original-title="Delete" class="btn btn-danger btn-sm deleteItem">Eliminar</a>';
            return $btn;

        })
        ->rawColumns(['action'])
        ->make(true);
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EntradaProducto  $entradaProducto
     * @return \Illuminate\Http\Response
     */
    public function show(EntradaProducto $entradaProducto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EntradaProducto  $entradaProducto
     * @return \Illuminate\Http\Response
     */
    public function edit(EntradaProducto $entradaProducto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EntradaProducto  $entradaProducto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EntradaProducto $entradaProducto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EntradaProducto  $entradaProducto
     * @return \Illuminate\Http\Response
     */
    public function destroy ($id)
    {
        $entradaproducto   = EntradaProducto::findOrFail($id);
        if($entradaproducto->status=='captura'){
            $entradaproducto->delete();
            return response()->json(['data' => "Eliminado correctamente..."]);
        }else{
            return response()->json(['data' => "No se puede eliminar..."]);
        }
        
    }
}
