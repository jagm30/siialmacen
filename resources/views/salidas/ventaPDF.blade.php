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

    /* ── Encabezado: datos del emisor y folio ── */
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
    .folio-box {
        text-align: center;
        border: 2px solid #1a5276;
        border-radius: 4px;
        padding: 6px 10px;
        background-color: #eaf4fb;
        width: 120px;
        float: right;
    }
    .folio-box .folio-label {
        font-size: 8pt;
        color: #555;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .folio-box .folio-num {
        font-size: 14pt;
        font-weight: bold;
        color: #1a5276;
    }
    .folio-box .folio-fecha {
        font-size: 8.5pt;
        color: #333;
        margin-top: 3px;
    }

    /* ── Caja de cliente ── */
    .cliente-box {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 12px;
        border: 1px solid #b3cde3;
    }
    .cliente-box td {
        padding: 5px 8px;
        border: 1px solid #d6e8f5;
        font-size: 9.5pt;
    }
    .cliente-label {
        font-weight: bold;
        color: #1a5276;
        background-color: #eaf4fb;
        width: 90px;
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

    /* ── Pie fijo: totales + leyendas ── */
    #pie {
        position: fixed;
        bottom: 0.8cm;
        left: 1.5cm;
        right: 1.5cm;
    }
    .totales-box {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 6px;
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
        width: 140px;
    }
    .totales-box .val {
        background-color: #fff;
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
    .letras {
        font-size: 8.5pt;
        color: #333;
        font-style: italic;
        margin-bottom: 5px;
        padding: 3px 0;
        border-top: 1px solid #d0e4f0;
        border-bottom: 1px solid #d0e4f0;
    }
    .politicas {
        font-size: 7.5pt;
        color: #555;
        text-align: justify;
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

    {{-- Encabezado: emisor + folio --}}
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
            <td style="width:140px; text-align:right; vertical-align:top;">
                <div class="folio-box">
                    <div class="folio-label">Nota de venta</div>
                    <div class="folio-num"># {{ $salida->id }}</div>
                    <div class="folio-fecha">{{ $salida->fecha }}</div>
                </div>
            </td>
        </tr>
    </table>

    {{-- Cliente --}}
    <table class="cliente-box">
        <tr>
            <td class="cliente-label">Cliente</td>
            <td>{{ $salida->solicitante }}</td>
            <td class="cliente-label" style="width:110px;">Forma de pago</td>
            <td>
                @if($salida->formapago==1) <strong>Efectivo</strong>
                @elseif($salida->formapago==2) <strong>Tarjeta de débito</strong>
                @elseif($salida->formapago==3) <strong>Tarjeta de crédito</strong>
                @else {{ $salida->formapago }}
                @endif
            </td>
        </tr>
    </table>

    {{-- Detalle --}}
    <table class="detalle">
        <thead>
            <tr>
                <th style="width:50%;">Descripción</th>
                <th class="num" style="width:12%;">Cantidad</th>
                <th class="num" style="width:19%;">Precio unit.</th>
                <th class="num" style="width:19%;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($salidadetalle as $detalle)
            <tr>
                <td>{{ $detalle->descripcion }}{{ $detalle->talla ? ' - Talla: ' . $detalle->talla : '' }}</td>
                <td class="num">{{ number_format($detalle->cantidad, 0) }}</td>
                <td class="num">$ {{ number_format($detalle->precio, 2) }}</td>
                <td class="num">$ {{ number_format($detalle->precio * $detalle->cantidad, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</main>

{{-- Pie fijo con totales y leyendas --}}
<div id="pie">

    <div class="letras">
        Cantidad con letra: <em>{{ Convertidor::numtoletras($salida->total) }}</em>
    </div>

    <table class="totales-box">
        <tr>
            <td class="lbl">Artículos</td>
            <td class="val">{{ $totalarticulos[0]->totalarticulos }}</td>
            <td class="lbl">Subtotal</td>
            <td class="val" style="text-align:right;">$ {{ number_format($totalpagar[0]->totalpagar, 2) }}</td>
        </tr>
        <tr>
            @if($salida->observaciones)
            <td class="lbl">Observaciones</td>
            <td class="val" colspan="1" style="font-style:italic;">{{ $salida->observaciones }}</td>
            @else
            <td class="lbl">Observaciones</td><td class="val"></td>
            @endif
            <td class="lbl">Comisiones</td>
            <td class="val" style="text-align:right;">$ {{ number_format($salida->total - $totalpagar[0]->totalpagar, 2) }}</td>
        </tr>
        <tr class="fila-total-final">
            <td colspan="3">TOTAL A PAGAR</td>
            <td class="num">$ {{ number_format($salida->total, 2) }}</td>
        </tr>
    </table>

    <div class="politicas">
        * Únicamente tiene dos días hábiles para cambios de cualquier prenda.<br>
        * No se hacen devoluciones en efectivo; únicamente se realizan cambios de prendas por talla o por otro artículo de igual o mayor precio.<br>
        * En caso de requerir factura, podrá solicitarla solo el día posterior a su expedición.
    </div>

</div>

</body>
</html>
