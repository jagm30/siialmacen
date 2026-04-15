@extends('layouts.app')

@section('contenidoprincipal')

{{-- Encabezado --}}
<div class="content-header" style="padding: 10px 15px 0;">
  <h1 style="font-size:22px; color:#333;">
    <i class="fa fa-dashboard"></i> Dashboard
    <small>{{ \Carbon\Carbon::now()->locale('es')->isoFormat('dddd, D [de] MMMM YYYY') }}</small>
  </h1>
</div>

{{-- ═══════════════════════════════════════════════════════ --}}
{{-- FILA 1 — KPIs                                          --}}
{{-- ═══════════════════════════════════════════════════════ --}}
<div class="row" style="margin-top:15px;">

  {{-- Productos activos --}}
  <div class="col-lg-3 col-sm-6">
    <div class="small-box bg-blue">
      <div class="inner">
        <h3>{{ $totalProductos }}</h3>
        <p>Productos activos</p>
      </div>
      <div class="icon"><i class="fa fa-cube"></i></div>
      <a href="/productos" class="small-box-footer">Ver catálogo &nbsp;<i class="fa fa-arrow-circle-right"></i></a>
    </div>
  </div>

  {{-- Entradas del mes --}}
  <div class="col-lg-3 col-sm-6">
    <div class="small-box bg-green">
      <div class="inner">
        <h3>{{ $entradasMes }}</h3>
        <p>Entradas este mes</p>
      </div>
      <div class="icon"><i class="fa fa-download"></i></div>
      <a href="/entradas" class="small-box-footer">Ver entradas &nbsp;<i class="fa fa-arrow-circle-right"></i></a>
    </div>
  </div>

  {{-- Ventas del mes (cantidad) --}}
  <div class="col-lg-3 col-sm-6">
    <div class="small-box bg-yellow">
      <div class="inner">
        <h3>{{ $ventasMes }}</h3>
        <p>Ventas este mes</p>
      </div>
      <div class="icon"><i class="fa fa-shopping-cart"></i></div>
      <a href="/salidas/ventauniforme" class="small-box-footer">Ver ventas &nbsp;<i class="fa fa-arrow-circle-right"></i></a>
    </div>
  </div>

  {{-- Total $ ventas del mes --}}
  <div class="col-lg-3 col-sm-6">
    <div class="small-box bg-aqua">
      <div class="inner">
        <h3>$ {{ number_format($totalVentasMes, 2) }}</h3>
        <p>Monto vendido este mes</p>
      </div>
      <div class="icon"><i class="fa fa-money"></i></div>
      <a href="/salidas/ventauniforme" class="small-box-footer">Ver detalle &nbsp;<i class="fa fa-arrow-circle-right"></i></a>
    </div>
  </div>

</div>

{{-- ═══════════════════════════════════════════════════════ --}}
{{-- FILA 2 — Gráficas                                      --}}
{{-- ═══════════════════════════════════════════════════════ --}}
<div class="row">

  {{-- Gráfica: Ventas últimos 7 días --}}
  <div class="col-md-8">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-bar-chart"></i> Ventas — últimos 7 días</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
      </div>
      <div class="box-body">
        <canvas id="chartVentas" height="110"></canvas>
      </div>
    </div>
  </div>

  {{-- Gráfica: Top 5 productos del mes --}}
  <div class="col-md-4">
    <div class="box box-success">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-trophy"></i> Top 5 productos — este mes</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
      </div>
      <div class="box-body">
        @if($topProductos->isEmpty())
          <p class="text-muted text-center" style="padding:20px 0;">Sin ventas registradas este mes.</p>
        @else
          <canvas id="chartTop" height="200"></canvas>
        @endif
      </div>
    </div>
  </div>

</div>

{{-- ═══════════════════════════════════════════════════════ --}}
{{-- FILA 3 — Alertas de stock + últimas ventas             --}}
{{-- ═══════════════════════════════════════════════════════ --}}
<div class="row">

  {{-- Alertas de stock --}}
  <div class="col-md-4">
    <div class="box box-danger">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-warning"></i> Alertas de stock</h3>
      </div>
      <div class="box-body" style="padding:0;">

        @if($sinStock > 0)
        <div class="alert alert-danger" style="margin:10px; border-radius:4px;">
          <i class="fa fa-times-circle"></i>
          <strong>{{ $sinStock }}</strong> producto{{ $sinStock > 1 ? 's' : '' }} sin stock
        </div>
        @endif

        @if($stockBajo->isEmpty() && $sinStock === 0)
          <p class="text-muted text-center" style="padding:20px 0;"><i class="fa fa-check-circle text-green"></i> Stock en niveles normales</p>
        @else
          <ul class="list-group list-group-flush" style="margin-bottom:0;">
            @foreach($stockBajo as $p)
            <li class="list-group-item" style="padding:8px 15px; border-left: 4px solid #e74c3c;">
              <span class="badge" style="background:#e74c3c; float:right;">{{ $p->stock }} uds.</span>
              <small><strong>{{ Str::limit($p->nombre, 28) }}</strong></small><br>
              <small class="text-muted">{{ $p->categoria }}</small>
            </li>
            @endforeach
          </ul>
        @endif

      </div>
      <div class="box-footer text-center">
        <a href="/inventario" class="btn btn-sm btn-default"><i class="fa fa-pie-chart"></i> Ver inventario completo</a>
      </div>
    </div>
  </div>

  {{-- Últimas ventas --}}
  <div class="col-md-8">
    <div class="box box-warning">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-list"></i> Últimas ventas</h3>
      </div>
      <div class="box-body" style="padding:0;">
        <table class="table table-condensed table-hover" style="margin-bottom:0;">
          <thead style="background:#f39c12; color:#fff;">
            <tr>
              <th>#</th>
              <th>Folio</th>
              <th>Solicitante</th>
              <th>Fecha</th>
              <th>Total</th>
            </tr>
          </thead>
          <tbody>
            @forelse($ultimasVentas as $venta)
            <tr>
              <td>{{ $venta->id }}</td>
              <td><span class="label label-warning">{{ $venta->folioreq }}</span></td>
              <td>{{ Str::limit($venta->solicitante, 22) }}</td>
              <td>{{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') }}</td>
              <td><strong>$ {{ number_format($venta->total, 2) }}</strong></td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center text-muted">Sin ventas registradas</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="box-footer text-center">
        <a href="/salidas/ventauniforme" class="btn btn-sm btn-default"><i class="fa fa-shopping-cart"></i> Ver todas las ventas</a>
      </div>
    </div>
  </div>

</div>

{{-- ═══════════════════════════════════════════════════════ --}}
{{-- FILA 4 — Últimas entradas                              --}}
{{-- ═══════════════════════════════════════════════════════ --}}
<div class="row">
  <div class="col-md-12">
    <div class="box box-success">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-download"></i> Últimas entradas de mercancía</h3>
      </div>
      <div class="box-body" style="padding:0;">
        <table class="table table-condensed table-hover" style="margin-bottom:0;">
          <thead style="background:#00a65a; color:#fff;">
            <tr>
              <th>#</th>
              <th>Factura</th>
              <th>Referencia</th>
              <th>Categoría</th>
              <th>Fecha</th>
              <th>Estatus</th>
            </tr>
          </thead>
          <tbody>
            @forelse($ultimasEntradas as $entrada)
            <tr>
              <td>{{ $entrada->id }}</td>
              <td>{{ $entrada->nfactura }}</td>
              <td>{{ Str::limit($entrada->referencia, 30) }}</td>
              <td>{{ $entrada->categoria }}</td>
              <td>{{ \Carbon\Carbon::parse($entrada->fecha)->format('d/m/Y') }}</td>
              <td>
                @if($entrada->status === 'finalizado')
                  <span class="label label-success">Finalizado</span>
                @elseif($entrada->status === 'cancelado')
                  <span class="label label-danger">Cancelado</span>
                @else
                  <span class="label label-default">{{ $entrada->status ?? 'Pendiente' }}</span>
                @endif
              </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center text-muted">Sin entradas registradas</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="box-footer text-center">
        <a href="/entradas" class="btn btn-sm btn-default"><i class="fa fa-download"></i> Ver todas las entradas</a>
      </div>
    </div>
  </div>
</div>

@endsection

@section('scriptpie')
<script>
// ── Gráfica: Ventas últimos 7 días ────────────────────────────────────────────
(function () {
  var ctx = document.getElementById('chartVentas').getContext('2d');
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: {!! json_encode($labelsVentas) !!},
      datasets: [{
        label: 'Monto vendido ($)',
        data: {!! json_encode($dataVentas) !!},
        backgroundColor: 'rgba(60,141,188,0.75)',
        borderColor: 'rgba(60,141,188,1)',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      legend: { display: false },
      scales: {
        yAxes: [{
          ticks: {
            beginAtZero: true,
            callback: function(v) { return '$ ' + v.toLocaleString('es-MX'); }
          }
        }]
      },
      tooltips: {
        callbacks: {
          label: function(item) { return '$ ' + parseFloat(item.yLabel).toLocaleString('es-MX', {minimumFractionDigits:2}); }
        }
      }
    }
  });
})();

// ── Gráfica: Top 5 productos ──────────────────────────────────────────────────
@if(!$topProductos->isEmpty())
(function () {
  var ctx = document.getElementById('chartTop').getContext('2d');
  new Chart(ctx, {
    type: 'horizontalBar',
    data: {
      labels: {!! json_encode($topProductos->pluck('nombre')->map(fn($n) => \Str::limit($n, 20))->values()) !!},
      datasets: [{
        label: 'Unidades vendidas',
        data: {!! json_encode($topProductos->pluck('total_vendido')->values()) !!},
        backgroundColor: [
          'rgba(0,166,90,0.75)',
          'rgba(0,192,239,0.75)',
          'rgba(243,156,18,0.75)',
          'rgba(221,75,57,0.75)',
          'rgba(96,92,168,0.75)'
        ],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      legend: { display: false },
      scales: {
        xAxes: [{ ticks: { beginAtZero: true, precision: 0 } }]
      }
    }
  });
})();
@endif
</script>
@endsection
