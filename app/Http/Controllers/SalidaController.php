<?php

namespace App\Http\Controllers;

use App\Models\Salida;
use App\Models\Producto;
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
        $salidas       = Salida::all();
        return view('salidas.index',compact('salidas','date'));
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
        $salida     = Salida::findOrFail($id);
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
    public function destroy(Salida $salida)
    {
        //
    }
    public function finalizarsalida($id)
    {
        $salida = Salida::find($id);
        $salida->status            = 'finalizado';
        $salida->id_usuario        = 1;
        $salida->save();
        return response()->json(['data' => "Cambios guardados correctamente..."]);      
    }
}
