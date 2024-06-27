<?php

namespace App\Http\Controllers;

use App\Models\Salida;
use App\Models\Salidaproducto;
use App\Models\Producto;
use App\Models\CatAlmacen;
use App\Models\Cancelacion;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use NumeroALetras\NumeroALetras;

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
        //$salidas       = Entrada::all();
        $salidas       = DB::table('salidas')
            ->select('salidas.id','salidas.folioreq','salidas.solicitante','salidas.fecha','salidas.almacen','salidas.cajapago','salidas.nnotaventa','salidas.fventa','salidas.observaciones','salidas.status','salidas.id_usuario','salidas.formapago','cat_almacens.nombre as nomalmacen')
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
    public function showventauniforme(Request $request, $id)
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
            ->select('salidas.id','salidas.folioreq','salidas.solicitante','salidas.fecha','salidas.almacen','salidas.cajapago','salidas.nnotaventa','salidas.fventa','salidas.observaciones','salidas.status','salidas.id_usuario','salidas.formapago','cat_almacens.nombre as nomalmacen','salidas.total')
            ->leftJoin('cat_almacens', 'salidas.almacen', '=', 'cat_almacens.id')
            ->where('salidas.id','=',$id)
            ->first();
        $salidas    = Salida::all();
        $productos  = Producto::where('stock','>','0')->get();
        return view('salidas.showventauniforme',compact('salidas','salida','productos','id_salida'));
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
    public function finalizarsalida(Request $request, $id, $formapago, $totalventa)
    {
        $salida = Salida::find($id);
        $salida->status            = 'finalizado';
        $salida->formapago         = $formapago;
        $salida->total             = $totalventa;
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
    public function ventapdf($id)
    {
        $salida       = DB::table('salidas')
            ->select('salidas.id','salidas.folioreq','salidas.solicitante','salidas.fecha','salidas.almacen','salidas.cajapago','salidas.nnotaventa','salidas.fventa','salidas.observaciones','salidas.status','salidas.id_usuario','cat_almacens.nombre as nomalmacen','salidas.formapago','salidas.total')
            ->leftJoin('cat_almacens', 'salidas.almacen', '=', 'cat_almacens.id')
            ->where('salidas.id', '=', $id)
            ->first();
        $salidadetalle       = DB::table('salidaproductos')
            ->select('salidaproductos.id','salidaproductos.id_salida','salidaproductos.id_producto','salidaproductos.cantidad','salidaproductos.precio','salidaproductos.status','productos.nombre','productos.descripcion')
            ->leftJoin('productos', 'salidaproductos.id_producto', '=', 'productos.id')            
            ->where('salidaproductos.id_salida', '=', $id)
            ->get();
        $totalpagar       = DB::table('salidaproductos')
            ->select(DB::raw('SUM(salidaproductos.cantidad*salidaproductos.precio) as totalpagar'))
            ->where('salidaproductos.id_salida', '=', $id)
            ->get();
        $totalarticulos       = DB::table('salidaproductos')
            ->select(DB::raw('SUM(salidaproductos.cantidad) as totalarticulos'))
            ->where('salidaproductos.id_salida', '=', $id)
            ->get();

        $today = Carbon::now()->format('d/m/Y');        
        $pdf = \PDF::loadView('salidas/ventaPDF', compact('today','salida','salidadetalle','totalpagar','totalarticulos'))->setPaper(array(0,0,612.00,792.00));
        return $pdf->stream();

    }
    public function ventauniforme(Request $request){
        $date = Carbon::now();
        $date = $date->format('Y-m-d');                
        //$salidas       = Entrada::all();
        $salidas       = DB::table('salidas')
            ->select('salidas.id','salidas.folioreq','salidas.solicitante','salidas.fecha','salidas.almacen','salidas.cajapago','salidas.nnotaventa','salidas.fventa','salidas.observaciones','salidas.status','salidas.id_usuario','cat_almacens.nombre as nomalmacen')
            ->leftJoin('cat_almacens', 'salidas.almacen', '=', 'cat_almacens.id')
            ->get();
        $almacenes      = CatAlmacen::all();
        return view('salidas.ventauniforme',compact('salidas','date','almacenes'));
    }
    public function ventaxalmacen(Request $request, $almacen){
        $date = Carbon::now();
        $date = $date->format('Y-m-d'); 
        if ($request->ajax()) {
            return datatables()->of(DB::table('salidas')
            ->select('salidas.id',DB::raw('SUM(salidaproductos.cantidad*salidaproductos.precio) as totalpago'),'salidas.fecha','salidas.status','salidas.formapago','salidas.solicitante')
            ->leftJoin('cat_almacens', 'salidas.almacen', '=', 'cat_almacens.id')
            ->leftJoin('salidaproductos', 'salidaproductos.id_salida', '=', 'salidas.id')
            ->where('salidas.almacen','=',$almacen)
            ->where('salidas.fecha','=',$date)
            ->groupBy('salidas.id','salidas.fecha','salidas.status','salidas.formapago','salidas.solicitante')
            ->get())->make(true);                
        } 

        return DB::table('salidas')
            ->select('salidas.id',DB::raw('SUM(salidaproductos.cantidad*salidaproductos.precio) as totalpago'),'salidas.fecha','salidas.status','salidas.formapago','salidas.solicitante')
            ->leftJoin('cat_almacens', 'salidas.almacen', '=', 'cat_almacens.id')
            ->leftJoin('salidaproductos', 'salidaproductos.id_salida', '=', 'salidas.id')
            ->where('salidas.almacen','=',$almacen)
            ->where('salidas.fecha','=',$date)
            ->groupBy('salidas.id','salidas.fecha','salidas.status','salidas.formapago','salidas.solicitante')
            ->get();

            //

    }

    public function edicionsalida(Request $request, $id_salida ,$folioreq ,$solicitante ,$fecha ,$almacen ,$cajapago, $nnotaventa,$fventa ,$status ,$observaciones, $id_usuario)
    {       
        try {              
            $salida = Salida::find($id_salida);
            $salida->folioreq       = $folioreq;
            $salida->solicitante    = $solicitante;
            $salida->fecha          = $fecha;
            $salida->almacen        = $almacen;
            $salida->cajapago       = $cajapago;
            $salida->nnotaventa     = $nnotaventa;
            $salida->fventa         = $fventa;
            $salida->status         = $status;
            $salida->observaciones  = $observaciones;
            $salida->id_usuario     = $id_usuario;
            $salida->save();
            return response()->json(['data' => "Cambios guardados correctamente..."]);

        } catch (\Exception $e) {
            
            return response()->json(['data' => $e->getMessage()]);  
        }              
    }

    public function filtrofecha(Request $request, $fecha1, $fecha2)
    {
        if ($request->ajax()) {
            return datatables()->of(DB::table('salidas')
            ->select('salidas.id','salidas.folioreq','salidas.solicitante','salidas.fecha','salidas.almacen','salidas.cajapago','salidas.nnotaventa','salidas.fventa','salidas.observaciones','salidas.status','salidas.id_usuario','cat_almacens.nombre as nomalmacen')
            ->leftJoin('cat_almacens', 'salidas.almacen', '=', 'cat_almacens.id')                        
            ->where('salidas.fecha','>=',$fecha1)
            ->where('salidas.fecha','<=',$fecha2)
            ->get())->make(true); 
        }

    }
    public function filtroalmacenfecha(Request $request,$almacen,$fecha1, $fecha2)
    {
        if($almacen=='todos'){
            if ($request->ajax()) {
                return datatables()->of(DB::table('salidas')
                ->select('salidas.id',DB::raw('SUM(salidaproductos.cantidad*salidaproductos.precio) as totalpago'),'salidas.fecha','salidas.status','salidas.formapago','salidas.solicitante')
                ->leftJoin('cat_almacens', 'salidas.almacen', '=', 'cat_almacens.id')
                ->leftJoin('salidaproductos', 'salidaproductos.id_salida', '=', 'salidas.id')
                ->where('salidas.almacen','=','1')
                ->where('salidas.fecha','>=',$fecha1)
                ->where('salidas.fecha','<=',$fecha2)
                ->groupBy('salidas.id','salidas.fecha','salidas.status','salidas.formapago','salidas.solicitante')
                ->get())->make(true);        
                
            }
        }else{
            if ($request->ajax()) {
                return datatables()->of(DB::table('salidas')
                ->select('salidas.id',DB::raw('SUM(salidaproductos.cantidad*salidaproductos.precio) as totalpago'),'salidas.fecha','salidas.status','salidas.formapago','salidas.solicitante')
                ->leftJoin('cat_almacens', 'salidas.almacen', '=', 'cat_almacens.id')
                ->leftJoin('salidaproductos', 'salidaproductos.id_salida', '=', 'salidas.id')
                ->where('salidas.almacen','=','1')
                ->where('salidas.fecha','>=',$fecha1)
                ->where('salidas.fecha','<=',$fecha2)
                ->groupBy('salidas.id','salidas.fecha','salidas.status','salidas.formapago','salidas.solicitante')
                ->get())->make(true); 
            }
        }

    }
    public function cancelarsalida(Request $request,$id, $motivo)
    {
        $date = Carbon::now();
        $date = $date->format('Y-m-d');
        $salida = Salida::find($id);
        $salida->status            = 'cancelado';
        //$salida->id_usuario        = 1;
        $salida->save();

        $cancelacion = new Cancelacion;
        $cancelacion->id_salida     = $id;
        $cancelacion->motivo        = $motivo;
        $cancelacion->fecha         = $date;
        $cancelacion->id_usuario    = 1;
        $cancelacion->save();
        return response()->json(['data' => "Cancelado correctamente..."]);      
    }
    public function salidaxfechaPDF(Request $request, $fecha1, $fecha2){
        $salidas  = DB::table('salidas')
                ->select('salidas.id',DB::raw('SUM(salidaproductos.cantidad*salidaproductos.precio) as totalpago'),'salidas.fecha','salidas.status','salidas.formapago','salidas.solicitante','salidas.total')
                ->leftJoin('cat_almacens', 'salidas.almacen', '=', 'cat_almacens.id')
                ->leftJoin('salidaproductos', 'salidaproductos.id_salida', '=', 'salidas.id')
                ->where('salidas.almacen','=','1')
                //->where('salidas.status','!=','cancelado')
                ->where('salidas.fecha','>=',$fecha1)
                ->where('salidas.fecha','<=',$fecha2)
                ->groupBy('salidas.id','salidas.fecha','salidas.status','salidas.formapago','salidas.solicitante','salidas.total')
                ->get();
        $totalregistro  = DB::table('salidas')
                ->select(DB::raw('SUM(total) as totalregistro'))                
                ->where('salidas.status','=','finalizado')
                ->where('salidas.fecha','>=',$fecha1)
                ->where('salidas.fecha','<=',$fecha2)
                ->get();

        $totalefectivo  = DB::table('salidas')
                ->select(DB::raw('SUM(total) as totalefectivo'))                
                ->where('salidas.formapago','=',1)
                ->where('salidas.status','=','finalizado')
                ->where('salidas.fecha','>=',$fecha1)
                ->where('salidas.fecha','<=',$fecha2)
                ->get();

        $totaldebito  = DB::table('salidas')
                ->select(DB::raw('SUM(total) as totaldebito'))                
                ->where('salidas.formapago','=',2)
                ->where('salidas.status','=','finalizado')
                ->where('salidas.fecha','>=',$fecha1)
                ->where('salidas.fecha','<=',$fecha2)
                ->get();

        $totalcredito  = DB::table('salidas')
                ->select(DB::raw('SUM(total) as totalcredito'))                
                ->where('salidas.formapago','=',3)
                ->where('salidas.status','=','finalizado')
                ->where('salidas.fecha','>=',$fecha1)
                ->where('salidas.fecha','<=',$fecha2)
                ->get();
        
        $today = Carbon::now()->format('d/m/Y');        
        $pdf = \PDF::loadView('salidas/salidaxfechaPDF', compact('today','salidas','fecha1','fecha2', 'totalregistro','totalefectivo','totaldebito','totalcredito'))->setPaper(array(0,0,612.00,792.00));
        return $pdf->stream();
    }
    // Venta version 2
    public function ventauniformev2(Request $request)
    {
        $id = 1;
        if ($request->ajax()) {
            $salida = DB::table('salidas')
                    ->where('salidas.id',$id)
                    ->first();
            return json_encode($salida);
        }
        $id_salida  = $id;
        //$salida     = Salida::findOrFail($id);
        $salida     = DB::table('salidas')
            ->select('salidas.id','salidas.folioreq','salidas.solicitante','salidas.fecha','salidas.almacen','salidas.cajapago','salidas.nnotaventa','salidas.fventa','salidas.observaciones','salidas.status','salidas.id_usuario','salidas.formapago','cat_almacens.nombre as nomalmacen','salidas.total')
            ->leftJoin('cat_almacens', 'salidas.almacen', '=', 'cat_almacens.id')
            ->where('salidas.id','=',$id)
            ->first();
        $salidas    = Salida::all();
        $productos  = Producto::where('stock','>','0')->get();
        return view('salidas.ventauniformev2',compact('salidas','salida','productos','id_salida'));
    }
}
