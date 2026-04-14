<html>
<head>
<style>
    @page {
        margin: 0cm 0cm;
        font-family: Arial, Helvetica, sans-serif;
    }
    body {
        margin-top: 4.2cm;
        margin-left: 1.5cm;
        margin-right: 1.5cm;
        margin-bottom: 1.5cm;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 10pt;
        color: #222;
    }
    #watermark {
        position: fixed;
        bottom: 0px;
        left: 0px;
        top: 0px;
        width: 21.5cm;
        height: 28cm;
        z-index: -1000;
    }
    .page-break {
        page-break-after: always;
    }

    /* ── Pie de página con número de página ── */
    footer {
        position: fixed;
        bottom: 0cm;
        left: 0cm;
        right: 0cm;
        height: 0.8cm;
        background-color: #1a5276;
        color: white;
        font-size: 8pt;
        text-align: center;
        line-height: 0.8cm;
    }
    .pagenum:before   { content: counter(page); }
    .pagecount:before { content: counter(pages); }

    /* ── Encabezado del documento ── */
    .doc-header {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 12px;
    }
    .doc-header td {
        padding: 2px 0;
        border: none;
        vertical-align: top;
    }
    .emisor {
        font-size: 8.5pt;
        color: #333;
        line-height: 1.5;
    }
    .emisor strong {
        font-size: 9.5pt;
        color: #1a5276;
    }
    .titulo-box {
        text-align: right;
    }
    .titulo-box .titulo-doc {
        font-size: 13pt;
        font-weight: bold;
        color: #1a5276;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .titulo-box .titulo-fecha {
        font-size: 8.5pt;
        color: #555;
        margin-top: 4px;
    }

    /* ── Tabla de detalle ── */
    .detalle {
        width: 100%;
        border-collapse: collapse;
        font-size: 9pt;
    }
    .detalle thead tr {
        background-color: #1a5276;
        color: white;
    }
    .detalle thead th {
        padding: 6px 8px;
        text-align: left;
        font-weight: bold;
        border: 1px solid #154360;
        letter-spacing: 0.3px;
    }
    .detalle thead th.num {
        text-align: right;
    }
    .detalle thead th.ctr {
        text-align: center;
    }
    .detalle tbody tr:nth-child(even) {
        background-color: #eaf4fb;
    }
    .detalle tbody tr:nth-child(odd) {
        background-color: #ffffff;
    }
    .detalle tbody td {
        padding: 4px 8px;
        border: 1px solid #d0e4f0;
        vertical-align: middle;
    }
    .detalle tbody td.num {
        text-align: right;
    }
    .detalle tbody td.ctr {
        text-align: center;
    }

    /* Stock badges */
    .badge-ok      { background-color: #27ae60; color: white; padding: 2px 6px; border-radius: 3px; font-weight: bold; }
    .badge-low     { background-color: #e67e22; color: white; padding: 2px 6px; border-radius: 3px; font-weight: bold; }
    .badge-empty   { background-color: #c0392b; color: white; padding: 2px 6px; border-radius: 3px; font-weight: bold; }

    /* ── Totales ── */
    .detalle tfoot tr.fila-subtotales td {
        background-color: #d6eaf8;
        border-top: 2px solid #1a5276;
        padding: 5px 8px;
        font-weight: bold;
        color: #1a5276;
        border-bottom: 1px solid #b3cde3;
    }
    .detalle tfoot tr.fila-total td {
        background-color: #1a5276;
        color: white;
        padding: 6px 8px;
        font-weight: bold;
        font-size: 10pt;
    }
    .detalle tfoot td.num { text-align: right; }
    .detalle tfoot td.ctr { text-align: center; }
</style>
</head>
<body>

<div id="watermark">
    <img src="{{ public_path().'/images/formato.jpg' }}" width="100%" height="100%">
</div>

<footer>
    Página <span class="pagenum"></span> de <span class="pagecount"></span>
    &nbsp;&nbsp;|&nbsp;&nbsp; Generado el {{ $today }}
</footer>

<?php
    $totalUnidades  = $productos->sum('stock');
    $totalValor     = $productos->sum(function($p){ return $p->stock * $p->precio; });
?>

<main>

    {{-- Encabezado --}}
    <table class="doc-header">
        <tr>
            <td>
                <div class="emisor">
                    <strong>Colegio La Salle de Tuxtla, A. C.</strong><br>
                    Blvd. La Salle No. 504. Col. El Retiro C.P. 29040<br>
                    Tuxtla Gutiérrez, Chiapas<br>
                    RFC: CST7001145E9 &nbsp;|&nbsp; Tel. 961 619-1943
                </div>
            </td>
            <td style="width:200px;">
                <div class="titulo-box">
                    <div class="titulo-doc">Inventario de almacén</div>
                    <div class="titulo-fecha">Generado el: {{ $today }}</div>
                </div>
            </td>
        </tr>
    </table>

    {{-- Tabla --}}
    <table class="detalle">
        <thead>
            <tr>
                <th style="width:42%;">Descripción</th>
                <th style="width:9%;">Talla</th>
                <th style="width:20%;">Almacén</th>
                <th class="num" style="width:14%;">Precio unit.</th>
                <th class="ctr" style="width:7%;">Stock</th>
                <th class="num" style="width:8%;">Valor</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productos as $producto)
            <tr>
                <td>{{ $producto->descripcion }}</td>
                <td>{{ $producto->talla }}</td>
                <td>{{ $producto->nomalmacen }}</td>
                <td class="num">$ {{ number_format($producto->precio, 2) }}</td>
                <td class="ctr">
                    @if($producto->stock <= 0)
                        <span class="badge-empty">{{ $producto->stock }}</span>
                    @elseif($producto->stock <= 5)
                        <span class="badge-low">{{ $producto->stock }}</span>
                    @else
                        <span class="badge-ok">{{ $producto->stock }}</span>
                    @endif
                </td>
                <td class="num">$ {{ number_format($producto->stock * $producto->precio, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="fila-subtotales">
                <td colspan="4">Total de artículos en catálogo</td>
                <td class="ctr">{{ number_format($totalUnidades, 0) }}</td>
                <td class="num">$ {{ number_format($totalValor, 2) }}</td>
            </tr>
            <tr class="fila-total">
                <td colspan="4">VALOR TOTAL DEL INVENTARIO</td>
                <td colspan="2" class="num">$ {{ number_format($totalValor, 2) }}</td>
            </tr>
        </tfoot>
    </table>

</main>

</body>
</html>
