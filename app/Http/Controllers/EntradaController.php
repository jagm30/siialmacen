<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\DB;
use App\Models\Entrada;
use Illuminate\Http\Request;

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
        $entradas = Entrada::all();
        return view('entradas.index',compact('entradas'));

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
    public function show($id)
    {
        //
        $entrada  =Entrada::findOrFail($id);
        //return $entrada;
        $entradas = Entrada::all();
        return view('entradas.show',compact('entradas','entrada'));
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
