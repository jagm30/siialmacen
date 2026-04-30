<?php

namespace App\Http\Controllers;

use App\Models\Salida;
use App\Models\Salidaproducto;
use App\Models\Producto;
use App\Models\CatAlmacen;
use App\Models\Cancelacion;
use Illuminate\Support\Facades\Auth;
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
    public function index(Request $request)
    {
        $date      = Carbon::now()->format('Y-m-d');
        $almacenes = CatAlmacen::all();
        $productos = Producto::where('stock', '>', '0')->get();
        return view('salidas.index', compact('date', 'almacenes', 'productos'));
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
    public function nuevaventa(Request $request)
    {
        $date      = Carbon::now()->format('Y-m-d');
        $almacenes = CatAlmacen::all();
        return view('salidas.ventauniforme', compact('date', 'almacenes'));
    }

    public function showventauniforme(Request $request, $id)
    {
        $salida = DB::table('salidas')
            ->select('salidas.id','salidas.folioreq','salidas.solicitante','salidas.fecha','salidas.almacen','salidas.cajapago','salidas.nnotaventa','salidas.fventa','salidas.observaciones','salidas.status','salidas.id_usuario','salidas.formapago','cat_almacens.nombre as nomalmacen','salidas.total')
            ->leftJoin('cat_almacens', 'salidas.almacen', '=', 'cat_almacens.id')
            ->where('salidas.id', '=', $id)
            ->first();

        if ($request->ajax()) {
            return response()->json($salida);
        }

        if (!$salida) {
            return redirect('/salidas/ventauniforme')->with('error', 'Venta no encontrada.');
        }

        $id_salida = $id;
        $productos = Producto::where('stock', '>', '0')->get();
        return view('salidas.showventauniforme', compact('salida', 'productos', 'id_salida'));
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
        $salida = Salida::findOrFail($id);

        if ($salida->status !== 'captura') {
            return response()->json(['data' => 'El registro no se puede cancelar, estatus: ' . $salida->status]);
        }

        $motivo = 'venta no concretada';

        $items = Salidaproducto::where('id_salida', $id)
                    ->whereIn('status', ['captura', 'finalizado'])
                    ->get();

        foreach ($items as $item) {
            if ($item->cantidad > 0) {
                DB::table('productos')
                    ->where('id', $item->id_producto)
                    ->increment('stock', $item->cantidad);

                DB::table('kardexes')->insert([
                    'tipomovimiento' => 'cancelacion',
                    'id_movimiento'  => $id,
                    'id_producto'    => $item->id_producto,
                    'cantidad'       => $item->cantidad,
                    'motivo'         => 'cancelacion venta: ' . $motivo,
                    'id_usuario'     => Auth::id(),
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);
            }

            $item->status = 'cancelado';
            $item->save();
        }

        $salida->status = 'cancelado';
        $salida->save();

        $cancelacion = new Cancelacion;
        $cancelacion->id_salida  = $id;
        $cancelacion->motivo     = $motivo;
        $cancelacion->fecha      = Carbon::now()->format('Y-m-d');
        $cancelacion->id_usuario = Auth::id();
        $cancelacion->save();

        return response()->json(['data' => 'Venta cancelada. El folio #' . $id . ' queda registrado.']);
    }
    public function finalizarsalida(Request $request, $id, $formapago, $totalventa)
    {
        
        $salida = Salida::find($id);
        $salida->status            = 'finalizado';
        $salida->formapago         = $formapago;        
        $totalventa = str_replace(",", "", $totalventa);
        $salida->total             = $totalventa;
        $salida->id_usuario        = 1;
        $salida->save();
        return response()->json(['data' => $totalventa]);      
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
        $pdf = \PDF::loadView('salidas.reportePDF', compact('today','salida','salidadetalle'))->setPaper(array(0,0,612.00,792.00));
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
            ->select('salidaproductos.id','salidaproductos.id_salida','salidaproductos.id_producto','salidaproductos.cantidad','salidaproductos.precio','salidaproductos.status','productos.nombre','productos.descripcion','productos.talla')
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
        $pdf = \PDF::loadView('salidas.ventaPDF', compact('today','salida','salidadetalle','totalpagar','totalarticulos'))->setPaper(array(0,0,612.00,792.00));
        return $pdf->stream();

    }
    public function ventauniforme(Request $request){
        $date      = Carbon::now()->format('Y-m-d');
        $almacenes = CatAlmacen::all();
        $productos = Producto::where('stock', '>', '0')->get();
        return view('salidas.ventauniforme', compact('date', 'almacenes'));
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
        if ($request->ajax()) {
            $query = DB::table('salidas')
                ->select('salidas.id', DB::raw('SUM(salidaproductos.cantidad*salidaproductos.precio) as totalpago'), 'salidas.fecha', 'salidas.status', 'salidas.formapago', 'salidas.solicitante')
                ->leftJoin('cat_almacens', 'salidas.almacen', '=', 'cat_almacens.id')
                ->leftJoin('salidaproductos', 'salidaproductos.id_salida', '=', 'salidas.id')
                ->where('salidas.fecha', '>=', $fecha1)
                ->where('salidas.fecha', '<=', $fecha2)
                ->groupBy('salidas.id', 'salidas.fecha', 'salidas.status', 'salidas.formapago', 'salidas.solicitante');

            if ($almacen != 'todos') {
                $query->where('salidas.almacen', '=', $almacen);
            }

            return datatables()->of($query->get())->make(true);
        }
    }
    public function cancelarsalida(Request $request, $id)
    {
        $motivo = $request->input('motivo', 'sin motivo');
        $salida = Salida::find($id);

        if (!$salida || !in_array($salida->status, ['captura', 'finalizado'])) {
            return response()->json(['data' => 'La venta no puede cancelarse.'], 422);
        }

        // Restaurar stock directamente en PHP para cada artículo
        $items = Salidaproducto::where('id_salida', $id)
                    ->whereIn('status', ['captura', 'finalizado'])
                    ->get();

        foreach ($items as $item) {
            if ($item->cantidad > 0) {
                DB::table('productos')
                    ->where('id', $item->id_producto)
                    ->increment('stock', $item->cantidad);

                DB::table('kardexes')->insert([
                    'tipomovimiento' => 'cancelacion',
                    'id_movimiento'  => $id,
                    'id_producto'    => $item->id_producto,
                    'cantidad'       => $item->cantidad,
                    'motivo'         => 'cancelacion venta: ' . $motivo,
                    'id_usuario'     => Auth::id(),
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);
            }

            $item->status = 'cancelado';
            $item->save();
        }

        $salida->status = 'cancelado';
        $salida->save();

        $cancelacion = new Cancelacion;
        $cancelacion->id_salida  = $id;
        $cancelacion->motivo     = $motivo;
        $cancelacion->fecha      = Carbon::now()->format('Y-m-d');
        $cancelacion->id_usuario = Auth::id();
        $cancelacion->save();

        return response()->json(['data' => 'Venta cancelada correctamente.']);
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
        $pdf = \PDF::loadView('salidas.salidaxfechaPDF', compact('today','salidas','fecha1','fecha2', 'totalregistro','totalefectivo','totaldebito','totalcredito'))->setPaper(array(0,0,612.00,792.00));
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
