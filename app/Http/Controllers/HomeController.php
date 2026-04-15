<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Salida;
use App\Models\Entrada;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $mesActual  = Carbon::now()->month;
        $anioActual = Carbon::now()->year;

        // ── KPIs ──────────────────────────────────────────────────────────────
        $totalProductos = Producto::where('status', 'activo')->count();

        $entradasMes = Entrada::whereMonth('fecha', $mesActual)
                              ->whereYear('fecha', $anioActual)
                              ->count();

        $ventasMes = Salida::where('status', 'finalizado')
                           ->whereMonth('fecha', $mesActual)
                           ->whereYear('fecha', $anioActual)
                           ->count();

        $totalVentasMes = Salida::where('status', 'finalizado')
                                ->whereMonth('fecha', $mesActual)
                                ->whereYear('fecha', $anioActual)
                                ->sum('total') ?? 0;

        // ── Stock ─────────────────────────────────────────────────────────────
        $sinStock  = Producto::where('stock', '<=', 0)->where('status', 'activo')->count();
        $stockBajo = Producto::where('stock', '>', 0)->where('stock', '<=', 5)
                             ->where('status', 'activo')
                             ->orderBy('stock')
                             ->get();

        // ── Ventas últimos 7 días ─────────────────────────────────────────────
        $ventasRaw = DB::table('salidas')
            ->selectRaw('DATE(fecha) as dia, SUM(total) as monto')
            ->where('status', 'finalizado')
            ->where('fecha', '>=', Carbon::now()->subDays(6)->toDateString())
            ->groupBy('dia')
            ->orderBy('dia')
            ->pluck('monto', 'dia');

        $labelsVentas = [];
        $dataVentas   = [];
        for ($i = 6; $i >= 0; $i--) {
            $dia            = Carbon::now()->subDays($i)->toDateString();
            $labelsVentas[] = Carbon::now()->subDays($i)->locale('es')->isoFormat('ddd D');
            $dataVentas[]   = round($ventasRaw[$dia] ?? 0, 2);
        }

        // ── Top 5 productos más vendidos del mes ─────────────────────────────
        $topProductos = DB::table('salidaproductos')
            ->join('productos', 'salidaproductos.id_producto', '=', 'productos.id')
            ->join('salidas', 'salidaproductos.id_salida', '=', 'salidas.id')
            ->selectRaw('productos.nombre, SUM(salidaproductos.cantidad) as total_vendido')
            ->where('salidas.status', 'finalizado')
            ->whereMonth('salidas.fecha', $mesActual)
            ->whereYear('salidas.fecha', $anioActual)
            ->groupBy('productos.id', 'productos.nombre')
            ->orderByDesc('total_vendido')
            ->limit(5)
            ->get();

        // ── Últimas 5 ventas finalizadas ──────────────────────────────────────
        $ultimasVentas = Salida::where('status', 'finalizado')
                               ->orderByDesc('fecha')
                               ->orderByDesc('id')
                               ->limit(5)
                               ->get();

        // ── Últimas 5 entradas ────────────────────────────────────────────────
        $ultimasEntradas = Entrada::orderByDesc('fecha')
                                  ->orderByDesc('id')
                                  ->limit(5)
                                  ->get();

        return view('home', compact(
            'totalProductos', 'entradasMes', 'ventasMes', 'totalVentasMes',
            'sinStock', 'stockBajo',
            'labelsVentas', 'dataVentas',
            'topProductos',
            'ultimasVentas', 'ultimasEntradas'
        ));
    }
}
