<?php

namespace App\Http\Controllers;

use App\Models\CatAlmacen;
use Illuminate\Http\Request;

class CatAlmacenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $almacenes = CatAlmacen::all();
        return $almacenes;
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
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CatAlmacen  $catAlmacen
     * @return \Illuminate\Http\Response
     */
    public function show(CatAlmacen $catAlmacen)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CatAlmacen  $catAlmacen
     * @return \Illuminate\Http\Response
     */
    public function edit(CatAlmacen $catAlmacen)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CatAlmacen  $catAlmacen
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CatAlmacen $catAlmacen)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CatAlmacen  $catAlmacen
     * @return \Illuminate\Http\Response
     */
    public function destroy(CatAlmacen $catAlmacen)
    {
        //
    }
}
