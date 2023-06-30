<?php

namespace App\Http\Controllers;

use App\Models\Salida;
use App\Models\Salidaproducto;
use App\Models\Producto;
use App\Models\CatAlmacen;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SalidaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $date = Carbon::now();
        $date = $date->format('Y-m-d');                
        //$entradas       = Entrada::all();
        $salidas       = DB::table('salidas')
            ->select('salidas.id','salidas.folioreq','salidas.solicitante','salidas.fecha','salidas.almacen','salidas.cajapago','salidas.nnotaventa','salidas.fventa','salidas.observaciones','salidas.status','salidas.id_usuario','cat_almacens.nombre as nomalmacen')
            ->leftJoin('cat_almacens', 'salidas.almacen', '=', 'cat_almacens.id')
            ->get();
        $almacenes      = CatAlmacen::all();
        return view('salidas.index',compact('salidas','date','almacenes'));
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
            $salida = Salida::create($request->all());
            return response()->json(['data' => $salida->id]);  

        } catch (\Exception $e) {
            
            return response()->json(['data' => $e->getMessage()]);  
        }    
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Salida  $salida
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if ($request->ajax()) {
            $salida = DB::table('salidas')
                    ->where('salidas.id',$id)
                    ->first();
            return json_encode($salida);
        }
        $id_salida  = $id;
        //$salida     = Salida::findOrFail($id);
        $salida     = DB::table('salidas')
            ->select('salidas.id','salidas.folioreq','salidas.solicitante','salidas.fecha','salidas.almacen','salidas.cajapago','salidas.nnotaventa','salidas.fventa','salidas.observaciones','salidas.status','salidas.id_usuario','cat_almacens.nombre as nomalmacen')
            ->leftJoin('cat_almacens', 'salidas.almacen', '=', 'cat_almacens.id')
            ->where('salidas.id','=',$id)
            ->first();
        $salidas    = Salida::all();
        $productos  = Producto::where('stock','>','0')->get();
        return view('salidas.show',compact('salidas','salida','productos','id_salida'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Salida  $salida
     * @return \Illuminate\Http\Response
     */
    public function edit(Salida $salida)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Salida  $salida
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Salida $salida)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Salida  $salida
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $salida   = Salida::findOrFail($id);
        if($salida->status =='captura'){
            $salida->delete();
            Salidaproducto::where('id_salida', $id)->delete();
            return response()->json(['data' => "Eliminado correctamente..."]);
        }else{
            return response()->json(['data' => "El registro no se puede borrar, estatus: finalizado"]);
        }
        
    }
    public function finalizarsalida($id)
    {
        $salida = Salida::find($id);
        $salida->status            = 'finalizado';
        $salida->id_usuario        = 1;
        $salida->save();
        return response()->json(['data' => "Cambios guardados correctamente..."]);      
    }
    public function reportepdf($id)
    {
        $salida       = DB::table('salidas')
            ->select('salidas.id','salidas.folioreq','salidas.solicitante','salidas.fecha','salidas.almacen','salidas.cajapago','salidas.nnotaventa','salidas.fventa','salidas.observaciones','salidas.status','salidas.id_usuario','cat_almacens.nombre as nomalmacen')
            ->leftJoin('cat_almacens', 'salidas.almacen', '=', 'cat_almacens.id')
            ->where('salidas.id', '=', $id)
            ->first();
        $salidadetalle       = DB::table('salidaproductos')
            ->select('salidaproductos.id','salidaproductos.id_salida','salidaproductos.id_producto','salidaproductos.cantidad','salidaproductos.precio','salidaproductos.status','productos.nombre','productos.descripcion')
            ->leftJoin('productos', 'salidaproductos.id_producto', '=', 'productos.id')            
            ->where('salidaproductos.id_salida', '=', $id)
            ->get();
        $today = Carbon::now()->format('d/m/Y');        
        $pdf = \PDF::loadView('salidas/reportePDF', compact('today','salida','salidadetalle'))->setPaper(array(0,0,612.00,792.00));
        return $pdf->stream();

    }
}
