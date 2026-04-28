<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\DB;
use App\Models\Entrada;
use App\Models\EntradaProducto;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\CatAlmacen;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class EntradaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        if ($request->ajax()) {
                return datatables()->of(DB::table('entradas')
            ->select('entradas.id','entradas.proveedor','entradas.fecha','entradas.nfactura','entradas.referencia','entradas.categoria','entradas.observaciones','entradas.status','entradas.id_usuario',DB::raw('COALESCE(proveedors.nombre, entradas.proveedor) as nombreproveedor'),'cat_almacens.nombre as nomalmacen')
            ->leftJoin('proveedors', 'entradas.proveedor', '=', 'proveedors.id')
            ->leftJoin('cat_almacens', 'entradas.categoria', '=', 'cat_almacens.id')
            ->orderBy('id', 'DESC')
            ->take(10)
            ->get())
                    ->make(true);
        }
        $date = Carbon::now();
        $date = $date->format('Y-m-d');
        $almacenes      = CatAlmacen::all();
        $proveedores    = Proveedor::all();
        $productos      = Producto::all();
        $entradas       = DB::table('entradas')
            ->select('entradas.id','entradas.proveedor','entradas.fecha','entradas.nfactura','entradas.referencia','entradas.categoria','entradas.observaciones','entradas.status','entradas.id_usuario',DB::raw('COALESCE(proveedors.nombre, entradas.proveedor) as nombreproveedor'),'cat_almacens.nombre as nomalmacen')
            ->leftJoin('proveedors', 'entradas.proveedor', '=', 'proveedors.id')
            ->leftJoin('cat_almacens', 'entradas.categoria', '=', 'cat_almacens.id')
            ->get();
        return view('entradas.index',compact('entradas','proveedores','almacenes','date','productos'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function nueva()
    {
        $date        = Carbon::now()->format('Y-m-d');
        $almacenes   = CatAlmacen::all();
        $proveedores = Proveedor::all();
        $productos   = Producto::all();
        return view('entradas.nueva', compact('almacenes', 'proveedores', 'productos', 'date'));
    }

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
        $entrada = Entrada::create($request->all());
        return response()->json(['data' => $entrada->id]);  
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Entrada  $entrada
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $entrada = DB::table('entradas')
            ->select('entradas.id','entradas.proveedor','entradas.fecha','entradas.nfactura','entradas.referencia','entradas.categoria','entradas.observaciones','entradas.status','entradas.id_usuario',DB::raw('COALESCE(proveedors.nombre, entradas.proveedor) as nombreproveedor'),'cat_almacens.nombre as nomalmacen')
            ->leftJoin('proveedors', 'entradas.proveedor', '=', 'proveedors.id')
            ->leftJoin('cat_almacens', 'entradas.categoria', '=', 'cat_almacens.id')
            ->where('entradas.id', '=', $id)
            ->first();

        if ($request->ajax()) {
            return response()->json($entrada);
        }

        if (!$entrada) {
            return redirect('/entradas')->with('error', 'Entrada no encontrada.');
        }

        $id_entrada  = $id;
        $almacenes   = CatAlmacen::all();
        $proveedores = Proveedor::all();
        $productos   = Producto::all();
        return view('entradas.show', compact('entrada','productos','id_entrada','almacenes','proveedores'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Entrada  $entrada
     * @return \Illuminate\Http\Response
     */
    public function edit(Entrada $entrada)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Entrada  $entrada
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Entrada $entrada)
    {
        $entrada->proveedor     = $request->proveedor;
        $entrada->nfactura      = $request->nfactura;
        $entrada->observaciones = $request->observaciones;
        $entrada->save();
        return response()->json(['data' => 'Datos actualizados correctamente.']);
    }

    public function edicion(Request $request, $id_entrada ,$proveedor ,$fecha ,$nfactura ,$referencia ,$categoria, $observaciones)
    {       
        try {              
            $entrada = Entrada::find($id_entrada);
            $entrada->proveedor         = $proveedor;
            $entrada->fecha             = $fecha;
            $entrada->nfactura          = $nfactura;
            $entrada->referencia        = $referencia;
            $entrada->categoria         = $categoria;
            $entrada->observaciones     = $observaciones;
            $entrada->id_usuario        = 1;
            $entrada->save();
            return response()->json(['data' => "Cambios guardados correctamente..."]);

        } catch (\Exception $e) {
            
            return response()->json(['data' => $e->getMessage()]);  
        }              
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Entrada  $entrada
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $entrada   = Entrada::findOrFail($id);
        if($entrada->status =='captura'){
            $entrada->delete();
            EntradaProducto::where('id_entrada', $id)->delete();
            return response()->json(['data' => "Eliminado correctamente..."]);
        }else{
            return response()->json(['data' => "El registro no se puede borrar, estatus: finalizado"]);
        }                
    }
    public function finalizarentrada(Request $request, $id)
    {
        try {
            $entrada = Entrada::find($id);
            $entrada->status            = 'finalizado';
            $entrada->id_usuario        = 100;
            $entrada->save();

            return response()->json(['data' => "Enbtrada guardada correctamente..."]);         
        } catch (Exception $e) {
            return response()->json(['data' => "Error:"]);                     
        }
    }
    public function reportepdf($id)
    {
        $entrada       = DB::table('entradas')
            ->select('entradas.id','entradas.proveedor','entradas.fecha','entradas.nfactura','entradas.referencia','entradas.categoria','entradas.observaciones','entradas.status','entradas.id_usuario',DB::raw('COALESCE(proveedors.nombre, entradas.proveedor) as nombreproveedor'),'cat_almacens.nombre as nomalmacen')
            ->leftJoin('proveedors', 'entradas.proveedor', '=', 'proveedors.id')
            ->leftJoin('cat_almacens', 'entradas.id_almacen', '=', 'cat_almacens.id')
            ->where('entradas.id', '=', $id)
            ->first();
        $entradadetalle       = DB::table('entrada_productos')
            ->select('entrada_productos.id','entrada_productos.id_entrada','entrada_productos.id_producto','entrada_productos.cantidad','entrada_productos.precio','entrada_productos.categoria','productos.nombre','productos.descripcion','productos.talla')
            ->leftJoin('productos', 'entrada_productos.id_producto', '=', 'productos.id')            
            ->where('entrada_productos.id_entrada', '=', $id)
            ->get();
        $totalarticulos       = DB::table('entrada_productos')
            ->select(DB::raw('SUM(entrada_productos.cantidad) as totalarticulos'))
            ->where('entrada_productos.id_entrada', '=', $id)
            ->get();
        $totalimporte         = DB::table('entrada_productos')
            ->select(DB::raw('SUM(entrada_productos.precio * entrada_productos.cantidad) as totalimporte'))
            ->where('entrada_productos.id_entrada', '=', $id)
            ->get();
        $today = Carbon::now()->format('d/m/Y');
        $pdf = \PDF::loadView('entradas.reportePDF', compact('today','entrada','entradadetalle','totalarticulos','totalimporte'))->setPaper(array(0,0,612.00,792.00));
        return $pdf->stream();

    }

    
    public function cancelarentrada(Request $request, $id)
    {
        $motivo  = $request->input('motivo', 'sin motivo');
        $entrada = Entrada::findOrFail($id);

        if ($entrada->status !== 'finalizado') {
            return response()->json(['data' => 'Solo se pueden cancelar entradas finalizadas.'], 422);
        }

        $items = EntradaProducto::where('id_entrada', $id)
                                ->where('status', 'finalizado')
                                ->get();

        foreach ($items as $item) {
            $producto = Producto::find($item->id_producto);
            if ($producto) {
                $producto->stock -= $item->cantidad;
                if ($producto->stock < 0) {
                    $producto->stock = 0;
                }
                $producto->save();
            }

            DB::table('kardexes')->insert([
                'tipomovimiento' => 'cancelacion',
                'id_movimiento'  => $item->id_entrada,
                'id_producto'    => $item->id_producto,
                'cantidad'       => $item->cantidad,
                'motivo'         => 'cancelacion de entrada: ' . $motivo,
                'id_usuario'     => Auth::id(),
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            $item->status = 'cancelado';
            $item->save();
        }

        $entrada->status = 'cancelado';
        $entrada->save();

        return response()->json(['data' => 'Entrada cancelada correctamente.']);
    }

    public function filtrofecha(Request $request, $fecha1, $fecha2)
    {
        if ($request->ajax()) {
            return datatables()->of(DB::table('entradas')
            ->select('entradas.id','entradas.proveedor','entradas.fecha','entradas.nfactura','entradas.referencia','entradas.categoria','entradas.observaciones','entradas.status','entradas.id_usuario',DB::raw('COALESCE(proveedors.nombre, entradas.proveedor) as nombreproveedor'),'cat_almacens.nombre as nomalmacen')
            ->leftJoin('proveedors', 'entradas.proveedor', '=', 'proveedors.id')
            ->leftJoin('cat_almacens', 'entradas.categoria', '=', 'cat_almacens.id')
            ->where('entradas.fecha','>=',$fecha1)
            ->where('entradas.fecha','<=',$fecha2)
            ->get())->make(true);
        }
    }
}