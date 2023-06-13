<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\DB;
use App\Models\Entrada;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\CatAlmacen;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EntradaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $date = Carbon::now();
        $date = $date->format('Y-m-d');
        $almacenes      = CatAlmacen::all();
        $proveedores    = Proveedor::all();
        $entradas       = Entrada::all();
        return view('entradas.index',compact('entradas','proveedores','almacenes','date'));

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
        if ($request->ajax()) {
            $entrada    = DB::table('entradas')
                    ->where('entradas.id',$id)
                    ->first();
            return json_encode($entrada);
        }
        $id_entrada = $id;
        $entrada    =Entrada::findOrFail($id);
        $entradas   = Entrada::all();
        $productos  = Producto::all();
        return view('entradas.show',compact('entradas','entrada','productos','id_entrada'));
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
        //
    }

    public function edicion(Request $request, $id_entrada ,$proveedor ,$fecha ,$nfactura ,$referencia ,$categoria, $observaciones)
    { 
        
           /* $entrada = Entrada::find($id_entrada);
            $entrada->proveedor         = $proveedor;
            $entrada->fecha             = $fecha;
            $entrada->nfactura          = $nfactura;
            $entrada->referencia        = $referencia;
            $entrada->categoria         = $categoria;
            $entrada->observaciones     = $observaciones;
            $entrada->save();*/
            return response()->json(['data' => "Cambios guardados correctamente..."]);    
                 
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Entrada  $entrada
     * @return \Illuminate\Http\Response
     */
    public function destroy(Entrada $entrada)
    {
        //
    }
}
