<?php

namespace App\Http\Controllers;

use App\Models\Salidaproducto;
use Illuminate\Support\Facades\DB;
use App\Models\Producto;
use Illuminate\Http\Request;

class SalidaproductoController extends Controller
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
        try {
            $cantidad = (int) $request->input('cantidad');
            $producto = Producto::findOrFail($request->input('id_producto'));

            if ($producto->stock < $cantidad) {
                return response()->json(['data' => 'Stock insuficiente.'], 422);
            }

            $salida = Salidaproducto::create($request->all());

            $producto->stock -= $cantidad;
            $producto->save();

            return response()->json(['data' => $salida->id]);

        } catch (\Exception $e) {
            return response()->json(['data' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Salidaproducto  $salidaproducto
     * @return \Illuminate\Http\Response
     */
    public function show(Salidaproducto $salidaproducto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Salidaproducto  $salidaproducto
     * @return \Illuminate\Http\Response
     */
    public function edit(Salidaproducto $salidaproducto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Salidaproducto  $salidaproducto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Salidaproducto $salidaproducto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Salidaproducto  $salidaproducto
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $salidaproducto = Salidaproducto::findOrFail($id);
        if ($salidaproducto->status == 'captura') {
            $producto = Producto::findOrFail($salidaproducto->id_producto);
            $producto->stock += $salidaproducto->cantidad;
            $producto->save();

            $salidaproducto->delete();
            return response()->json(['data' => "Eliminado correctamente..."]);
        } else {
            return response()->json(['data' => "No se puede eliminar..."]);
        }
    }

    public function listarxsalida(Request $request, $id)
    {        
        if ($request->ajax()) {
        // $data = Entradaproducto::all();
            return datatables()->of(DB::table('salidaproductos')
            ->select('salidaproductos.id','salidaproductos.id_salida','salidaproductos.id_producto','salidaproductos.precio','salidaproductos.cantidad','salidaproductos.id_usuario','productos.descripcion','productos.talla',DB::raw('salidaproductos.precio*salidaproductos.cantidad as subtotal'))
            ->leftJoin('productos', 'salidaproductos.id_producto', '=', 'productos.id')
            ->where('salidaproductos.id_salida',$id)
            ->get()
        )->addColumn('action', function($data){
            //$btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-original-title="Edit" class="edit btn btn-primary btn-sm editItem">Edit</a>'; {{route('productos.borrar',$producto->id)}},,href="#" data-id="{{ $entradaproductos->id }}

            $btn = '<a href="javascript:void(0)" data-toggle="tooltip" title="Eliminar artículo"
                        data-id="'.$data->id.'" id="btn-eliminar" name="btn-eliminar"
                        class="btn btn-danger btn-xs">
                        <i class="fa fa-trash"></i>
                    </a>
                    <a href="javascript:void(0)" data-toggle="tooltip" title="Devolver al inventario"
                        data-id="'.$data->id.'" id="btn-devolver" name="btn-devolver"
                        class="btn btn-info btn-xs">
                        <i class="fa fa-undo"></i>
                    </a>';
            return $btn;

        })
        ->rawColumns(['action'])
        ->make(true);
        }
    }

    public function devolver(Request $request, $id)
    {
        $salidaproducto = Salidaproducto::findOrFail($id);

        $producto = Producto::findOrFail($salidaproducto->id_producto);
        $producto->stock += $salidaproducto->cantidad;
        $producto->save();

        $salidaproducto->status   = 'devolucion';
        $salidaproducto->cantidad = 0;
        $salidaproducto->save();

        return response()->json(['data' => "Actualizado"]);
    }
}
