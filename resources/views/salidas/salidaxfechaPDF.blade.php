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
        margin-bottom: 6cm;
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

    /* ── Encabezado ── */
    .doc-header {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 10px;
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
    .reporte-box {
        text-align: center;
        border: 2px solid #1a5276;
        border-radius: 4px;
        padding: 6px 10px;
        background-color: #eaf4fb;
        width: 150px;
        float: right;
    }
    .reporte-box .rep-label {
        font-size: 8pt;
        color: #555;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .reporte-box .rep-titulo {
        font-size: 11pt;
        font-weight: bold;
        color: #1a5276;
    }
    .reporte-box .rep-periodo {
        font-size: 7.5pt;
        color: #333;
        margin-top: 3px;
    }

    /* ── Tabla de ventas ── */
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
    }
    .detalle tbody td.num {
        text-align: right;
    }
    .status-finalizado { color: #1a7a1a; font-weight: bold; }
    .status-cancelado  { color: #c0392b; font-weight: bold; }
    .status-captura    { color: #2471a3; font-weight: bold; }

    /* ── Pie fijo: totales ── */
    #pie {
        position: fixed;
        bottom: 0.8cm;
        left: 1.5cm;
        right: 1.5cm;
    }
    .totales-box {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 4px;
    }
    .totales-box td {
        padding: 4px 8px;
        border: 1px solid #d0e4f0;
        font-size: 9pt;
    }
    .totales-box .lbl {
        background-color: #eaf4fb;
        font-weight: bold;
        color: #1a5276;
        width: 160px;
    }
    .totales-box .val {
        background-color: #fff;
        text-align: right;
    }
    .fila-total-final td {
        background-color: #1a5276;
        color: white;
        font-weight: bold;
        font-size: 10pt;
        padding: 5px 8px;
        border: 1px solid #154360;
    }
    .fila-total-final td.num {
        text-align: right;
    }
    .pie-leyenda {
        font-size: 7.5pt;
        color: #555;
        text-align: center;
        margin-top: 4px;
        border-top: 1px dashed #aaa;
        padding-top: 4px;
    }
</style>
</head>
<body>

<div id="watermark">
    <img src="{{ public_path().'/images/formato.jpg' }}" width="100%" height="100%">
</div>

<main>

    {{-- Encabezado: emisor + periodo --}}
    <table class="doc-header">
        <tr>
            <td>
                <div class="emisor">
                    <strong>Colegio La Salle de Tuxtla, A. C.</strong><br>
                    Blvd. La Salle No. 504. Col. El Retiro C.P. 29040<br>
                    Tuxtla Gutiérrez, Chiapas<br>
                    RFC: CST7001145E9<br>
                    Tel. 961 619-1943 &nbsp;|&nbsp; 961 614-1953<br>
                    www.lasalletuxtla.edu.mx
                </div>
            </td>
            <td style="width:160px; text-align:right; vertical-align:top;">
                <div class="reporte-box">
                    <div class="rep-label">Reporte de ventas</div>
                    <div class="rep-titulo">{{ $today }}</div>
                    <div class="rep-periodo">Del: {{ $fecha1 }}<br>Al: &nbsp; {{ $fecha2 }}</div>
                </div>
            </td>
        </tr>
    </table>

    {{-- Tabla de ventas --}}
    <table class="detalle">
        <thead>
            <tr>
                <th style="width:8%;">Folio</th>
                <th style="width:13%;">Fecha</th>
                <th style="width:35%;">Cliente</th>
                <th style="width:16%;">Forma de pago</th>
                <th class="num" style="width:16%;">Total</th>
                <th style="width:12%;">Estatus</th>
            </tr>
        </thead>
        <tbody>
            @foreach($salidas as $salida)
            <tr>
                <td>{{ $salida->id }}</td>
                <td>{{ $salida->fecha }}</td>
                <td>{{ $salida->solicitante }}</td>
                <td>
                    @if($salida->formapago == 1) Efectivo
                    @elseif($salida->formapago == 2) T. Débito
                    @elseif($salida->formapago == 3) T. Crédito
                    @else — @endif
                </td>
                <td class="num">$ {{ number_format($salida->total, 2) }}</td>
                <td>
                    @if($salida->status == 'finalizado')
                        <span class="status-finalizado">Finalizado</span>
                    @elseif($salida->status == 'cancelado')
                        <span class="status-cancelado">Cancelado</span>
                    @elseif($salida->status == 'captura')
                        <span class="status-captura">Captura</span>
                    @else
                        {{ $salida->status }}
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</main>

{{-- Pie fijo con totales por forma de pago --}}
<div id="pie">

    <table class="totales-box">
        <tr>
            <td class="lbl">Efectivo</td>
            <td class="val">$ {{ number_format($totalefectivo[0]->totalefectivo ?? 0, 2) }}</td>
            <td class="lbl">Tarjeta de débito</td>
            <td class="val">$ {{ number_format($totaldebito[0]->totaldebito ?? 0, 2) }}</td>
        </tr>
        <tr>
            <td class="lbl">Tarjeta de crédito</td>
            <td class="val">$ {{ number_format($totalcredito[0]->totalcredito ?? 0, 2) }}</td>
            <td class="lbl">Registros en el periodo</td>
            <td class="val">{{ $salidas->count() }}</td>
        </tr>
        <tr class="fila-total-final">
            <td colspan="3">TOTAL VENTAS (finalizadas)</td>
            <td class="num">$ {{ number_format($totalregistro[0]->totalregistro ?? 0, 2) }}</td>
        </tr>
    </table>

    <div class="pie-leyenda">
        SII ALMACEN — Sistema de Gestión de Almacén &copy; {{ date('Y') }} &nbsp;|&nbsp; Generado el {{ $today }}
    </div>

</div>

</body>
</html>
