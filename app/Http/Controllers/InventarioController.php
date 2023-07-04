<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Producto;
use App\Models\CatAlmacen;
use App\Models\categoriaproducto;


class InventarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request){
        if ($request->ajax()) {
        // $data = Entradaproducto::all();
            return datatables()->of(DB::table('productos')
            ->select('productos.id','productos.nombre','productos.descripcion','productos.categoria','productos.claveproducto','productos.precio','productos.precioPromocion','productos.stock','cat_almacens.nombre as nomalmacen')
            ->leftJoin('cat_almacens', 'cat_almacens.id', '=', 'productos.categoria')
            ->get())
            ->make(true);
        }
        $almacenes      = CatAlmacen::all();         
        $categoriaproductos = Categoriaproducto::all();       
        $productos = DB::table('productos')
            ->select('productos.id','productos.nombre','productos.descripcion','productos.categoria','productos.claveproducto','productos.precio','productos.precioPromocion','productos.stock','cat_almacens.nombre as nomalmacen')
            ->leftJoin('cat_almacens', 'cat_almacens.id', '=', 'productos.categoria')
            ->get();  
        return view('inventario.index', compact('productos','categoriaproductos','almacenes'));     
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
     * @param  \App\Models\Inventario  $inventario
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        //
        if ($request->ajax()) {
        // $data = Entradaproducto::all();
            return datatables()->of(DB::table('productos')
            ->select('productos.id','productos.nombre','productos.descripcion','productos.categoria','productos.claveproducto','productos.precio','productos.precioPromocion','productos.stock','cat_almacens.nombre as nomalmacen')
            ->leftJoin('cat_almacens', 'cat_almacens.id', '=', 'productos.categoria')
            ->where('productos.categoria', '=', $id)
            ->get())
            ->make(true);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Inventario  $inventario
     * @return \Illuminate\Http\Response
     */
    public function edit(Inventario $inventario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Inventario  $inventario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Inventario $inventario)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Inventario  $inventario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Inventario $inventario)
    {
        //
    }
}
