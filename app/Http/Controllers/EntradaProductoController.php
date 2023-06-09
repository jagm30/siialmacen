<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
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
    public function destroy(EntradaProducto $entradaProducto)
    {
        //
    }
}
