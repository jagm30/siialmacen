<html>
<head>
<style>       
    @page {
        margin: 0cm 0cm;
        font-family: Arial, Helvetica, sans-serif;
    }
    /** Defina ahora los márgenes reales de cada página en el PDF **/
    body {
        margin-top: 4cm;
        margin-left: 1cm;
        margin-right: 1cm;
        margin-bottom: 2cm;
        font-family: Arial, Helvetica, sans-serif;
    }
    /** Definir las reglas del encabezado **/
    header {
        position: fixed;
        top: 0cm;
        left: 0cm;
        right: 0cm;
        height: 2cm;

        /** Estilos extra personales **/
        background-color: #03a9f4;
        color: white;
        text-align: center;
        line-height: 1.5cm;
    }
    /** Definir las reglas del pie de página **/
    footer {
        position: fixed;
        bottom: 0cm;
        left: 0cm;
        right: 0cm;
        height: 1cm;
        /** Estilos extra personales **/
        background-color: #03a9f4;
        color: white;
        text-align: center;
        line-height: 1.5cm;
    }
    /** Definir las reglas del pie de página **/    
    table {
        border-collapse: collapse;
        border:1px solid black;
        width: 100%;
        border-color: white;
    }
    th, td {
        text-align: left;
        padding: 5px;
        border:1px solid gray;
    }
    /*tr:nth-child(even){background-color: #f2f2f2}*/
    th {
        background-color: gray;
        color: white;
    }
    /** 
    * Define the width, height, margins and position of the watermark.
    **/
    #watermark {
        position: fixed;
        bottom:   0px;
        left:     0px;
        top:     0px;
        /** The width and height may change 
            according to the dimensions of your letterhead
        **/
        width:    21.5cm;
        height:   27.9cm;

        /** Your watermark should be behind every content**/
        z-index:  -1000;
    }
    #firmas {
        position: fixed;
        bottom: 2cm;
        left: 0cm;
        right: 0cm;
        height: 2cm;
        /** Estilos extra personales **/
        background-color: white;
        color: black;        
        line-height: .5cm;
        width: 100%
    }
    /** 
            * Define the width, height, margins and position of the watermark.
            **/
            #watermark {
                position: fixed;
                bottom:   0px;
                left:     0px;
                top:     0px;
                /** The width and height may change 
                    according to the dimensions of your letterhead
                **/
                width:    21.5cm;
                height:   28cm;

                /** Your watermark should be behind every content**/
                z-index:  -1000;
            }
}
</style>
</head>
<body>
<div id="watermark">
 <img src="{{ public_path().'/images/formato.jpg' }}" width="100%" height="100%">
</div>

<!-- Defina bloques de encabezado y pie de página antes de su contenido -->

<!-- Envuelva el contenido de su PDF dentro de una etiqueta principal -->
<main>

<table style="margin-top: -2.5cm; margin-left: 3.5cm; width: 82% !important; border-radius: 10px 10px;">
    <tr style="background-color: #E6F9FF; color:black;" >
        <th style="background-color: white; border: 1px solid gray; font-size: 10pt; color: black; text-align: left;" >Entrada de mercancia</th>       
        <th style="background-color: white; border: 1px solid gray; font-size: 10pt; color: black; text-align: right;" colspan="2">Fecha de registro: {{ $entrada->fecha }} </th> 
    </tr>
    <tr style="background-color: #E6F9FF; color:black; ">
        <th style="background-color: #E6F9FF; border: 1px solid gray; font-size: 10pt; color: black;">No. de factura: {{$entrada->nfactura}} </th>
        <th style="background-color: #E6F9FF; border: 1px solid gray; font-size: 10pt; color: black;">Proveedor: {{$entrada->nombreproveedor}}</th>
        <th style="background-color: #E6F9FF; border: 1px solid gray; font-size: 10pt; color: black;">Referencia: {{$entrada->referencia}}</th>
    </tr>
    <tr>
        <th style="background-color: white; border: 1px solid gray;color: black;font-size: 10pt;" colspan="3">Observaciones: {{$entrada->observaciones}}</th>
        
    </tr>
</table>
<br><br>
    <div id="contenido" class="contenido">
            <table width="100%" style="width:100% !important" style="font-size: 10pt;   font-family: Arial, Helvetica, sans-serif;">             
                <tr style="background-color: #E6F9FF !important; color:black; ">                    
                    <th>Descripcion</th>
                    <th>Cantidad</th>                    
                    <th>Precio</th>
                    <th>Subtotal</th>                    
                </tr>                                            
                @foreach($entradadetalle as $detalle)
                <?php 
                    //$originalDate = $alumn->periodo_vencimiento;
                    //$newDate = date("d-m-Y", strtotime($originalDate));
                ?>
                <tr style="font-size:10pt;">
                    <td>{{$detalle->descripcion}} </td>
                    <td>{{ $detalle->cantidad }}</td>                    
                    <td>$ {{$detalle->precio}} </td>
                    <td style="text-align: right;">$ {{$detalle->precio*$detalle->cantidad}} </td>
                </tr>
                @endforeach    
                <tfoot>
                    <tr>                        
                        <th>Total de articulos</th>
                        <th>{{ number_format($totalarticulos[0]->totalarticulos,0) }}</th>
                        <th></th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>        
        </div>
</main>



</body>
</html>