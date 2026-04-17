<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;
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
            ->select('productos.id','productos.nombre','productos.descripcion','productos.talla','productos.categoria','productos.claveproducto','productos.precio','productos.precioPromocion','productos.stock','cat_almacens.nombre as nomalmacen')
            ->leftJoin('cat_almacens', 'cat_almacens.id', '=', 'productos.categoria')
            ->where('status','=', 'activo')
            ->get())
            ->make(true);
        }
        $almacenes      = CatAlmacen::all();         
        $categoriaproductos = Categoriaproducto::all();       
        $productos = DB::table('productos')
            ->select('productos.id','productos.nombre','productos.descripcion','productos.talla','productos.categoria','productos.claveproducto','productos.precio','productos.precioPromocion','productos.stock','cat_almacens.nombre as nomalmacen')
            ->leftJoin('cat_almacens', 'cat_almacens.id', '=', 'productos.categoria')
            ->where('status','=', 'activo')
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
        //Filtro de inventarios segun el almacen seleccionado
        if ($request->ajax()) {
            if($id=='todos'){
                return datatables()->of(DB::table('productos')
                    ->select('productos.id','productos.nombre','productos.descripcion','productos.talla','productos.categoria','productos.claveproducto','productos.precio','productos.precioPromocion','productos.stock')
                    ->where('status','=', 'activo')
                    ->get())
                    ->make(true);
            }else{
                return datatables()->of(DB::table('productos')
                    ->select('productos.id','productos.nombre','productos.descripcion','productos.talla','productos.categoria','productos.claveproducto','productos.precio','productos.precioPromocion','productos.stock','categoriaproductos.nombre as categoriaproducto')
                    ->leftJoin('categoriaproductos', 'categoriaproductos.id', '=', 'productos.categoria')
                    ->where('productos.status','=', 'activo')
                    ->where('productos.categoria', '=', $id)
                    ->get())
                    ->make(true);
            }           
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

    public function inventariopdf()
    {
        $productos  = DB::table('productos')
            ->select('productos.id','productos.nombre','productos.descripcion','productos.talla','productos.categoria','productos.claveproducto','productos.precio','productos.precioPromocion','productos.stock','cat_almacens.nombre as nomalmacen')
            ->leftJoin('cat_almacens', 'cat_almacens.id', '=', 'productos.categoria')
            ->where('status','=', 'activo')
            ->orderBy('cat_almacens.nombre', 'asc')
            ->orderBy('productos.descripcion', 'asc')
            ->orderByRaw("
                CASE
                    WHEN productos.talla IS NULL OR productos.talla IN ('0','','NA','N/A') THEN -1
                    WHEN UPPER(productos.talla) = 'XS'    THEN 1000
                    WHEN UPPER(productos.talla) = 'S'     THEN 1001
                    WHEN UPPER(productos.talla) = 'M'     THEN 1002
                    WHEN UPPER(productos.talla) = 'L'     THEN 1003
                    WHEN UPPER(productos.talla) = 'XL'    THEN 1004
                    WHEN UPPER(productos.talla) = 'XXL'   THEN 1005
                    WHEN UPPER(productos.talla) = 'XXXL'  THEN 1006
                    WHEN UPPER(productos.talla) = 'XXXXL' THEN 1007
                    WHEN productos.talla REGEXP '^[0-9]+(\\.[0-9]+)?$' THEN CAST(productos.talla AS DECIMAL(10,2))
                    ELSE 999
                END ASC
            ")
            ->get();
        $today = Carbon::now()->format('d/m/Y');
        $pdf = \PDF::loadView('inventario.inventarioPDF', compact('today','productos'))->setPaper(array(0,0,612.00,792.00));
        return $pdf->stream();

    }
}
