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
        margin-bottom: 3cm;
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

    /* ── Sección de encabezado del documento ── */
    .doc-header {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 12px;
    }
    .doc-header td {
        padding: 0;
        border: none;
    }
    .doc-title {
        font-size: 13pt;
        font-weight: bold;
        color: #1a5276;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }
    .doc-folio {
        font-size: 11pt;
        color: #555;
        text-align: right;
    }

    /* ── Caja de datos generales ── */
    .info-box {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 14px;
        border: 1px solid #b3cde3;
        border-radius: 4px;
    }
    .info-box td {
        padding: 5px 8px;
        font-size: 9.5pt;
        border: 1px solid #d6e8f5;
    }
    .info-label {
        font-weight: bold;
        color: #1a5276;
        background-color: #eaf4fb;
        width: 110px;
    }
    .info-value {
        color: #222;
        background-color: #ffffff;
    }
    .obs-row td {
        background-color: #fdfefe;
        font-style: italic;
        color: #444;
    }

    /* ── Tabla de detalle ── */
    .detalle {
        width: 100%;
        border-collapse: collapse;
        font-size: 9.5pt;
    }
    .detalle thead tr {
        background-color: #1a5276;
        color: white;
    }
    .detalle thead th {
        padding: 6px 8px;
        text-align: left;
        font-weight: bold;
        letter-spacing: 0.3px;
        border: 1px solid #154360;
    }
    .detalle thead th.num {
        text-align: right;
    }
    .detalle tbody tr:nth-child(even) {
        background-color: #eaf4fb;
    }
    .detalle tbody tr:nth-child(odd) {
        background-color: #ffffff;
    }
    .detalle tbody td {
        padding: 5px 8px;
        border: 1px solid #d0e4f0;
        vertical-align: middle;
    }
    .detalle tbody td.num {
        text-align: right;
    }

    /* ── Fila de totales ── */
    .detalle tfoot tr.fila-articulos td {
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
        font-size: 10.5pt;
    }
    .detalle tfoot td.num {
        text-align: right;
    }
</style>
</head>
<body>

<div id="watermark">
    <img src="{{ public_path().'/images/formato.jpg' }}" width="100%" height="100%">
</div>

<main>

    {{-- Título y folio --}}
    <table class="doc-header">
        <tr>
            <td><span class="doc-title">Entrada de mercancía</span></td>
            <td class="doc-folio">Fecha: {{ $entrada->fecha }}</td>
        </tr>
    </table>

    {{-- Datos generales --}}
    <table class="info-box">
        <tr>
            <td class="info-label">No. Factura</td>
            <td class="info-value">{{ $entrada->nfactura }}</td>
            <td class="info-label">Proveedor</td>
            <td class="info-value">{{ $entrada->nombreproveedor }}</td>
        </tr>
        <tr>
            <td class="info-label">Referencia</td>
            <td class="info-value" colspan="3">{{ $entrada->referencia }}</td>
        </tr>
        @if($entrada->observaciones)
        <tr class="obs-row">
            <td class="info-label">Observaciones</td>
            <td class="info-value" colspan="3">{{ $entrada->observaciones }}</td>
        </tr>
        @endif
    </table>

    {{-- Tabla de detalle --}}
    <table class="detalle">
        <thead>
            <tr>
                <th style="width:45%;">Descripción</th>
                <th class="num" style="width:15%;">Cantidad</th>
                <th class="num" style="width:20%;">Precio unit.</th>
                <th class="num" style="width:20%;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($entradadetalle as $detalle)
            <tr>
                <td>{{ $detalle->descripcion }}</td>
                <td class="num">{{ number_format($detalle->cantidad, 0) }}</td>
                <td class="num">$ {{ number_format($detalle->precio, 2) }}</td>
                <td class="num">$ {{ number_format($detalle->precio * $detalle->cantidad, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="fila-articulos">
                <td>Total de artículos</td>
                <td class="num">{{ number_format($totalarticulos[0]->totalarticulos, 0) }}</td>
                <td colspan="2"></td>
            </tr>
            <tr class="fila-total">
                <td colspan="3" class="num">TOTAL</td>
                <td class="num">$ {{ number_format($totalimporte[0]->totalimporte, 2) }}</td>
            </tr>
        </tfoot>
    </table>

</main>
</body>
</html>
