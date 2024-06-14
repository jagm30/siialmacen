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
        border-color: black;
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
        height:   28cm;

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
<table style="margin-top: -2.5cm; margin-left: 3.5cm; width: 82% !important; border: solid gray;">
    <tr style="background-color: #E6F9FF; color:black;" >
        <th style="background-color: white; border:solid gray; font-size: 10pt; color: gray; text-align: left;">NOTA DE VENTA</th>        
        <th style="background-color: white; border:solid gray; font-size: 10pt; color: gray; text-align: center;"> {{ $salida->fecha }}</th>   
        <th style="background-color: white; border:solid gray; font-size: 10pt; color: red; text-align: right;">Folio: <b>{{ $salida->id }}</b> </th>     
    </tr>
    <tr style="background-color: #E6F9FF; color:black; ">
        <th colspan="3"  style="background-color: gray; border: 1px solid gray; font-size: 10pt; color: white;  text-align: left;">CLIENTE: {{$salida->solicitante}}</th>
    </tr>    
</table>
<br><br>
    <div id="contenido" class="contenido">
            <table width="100%" style="width:100%" style="font-size: 10pt;   font-family: Arial, Helvetica, sans-serif;">             
                <tr style="background-color: #E6F9FF; color:black;" >                   
                    <th>Descripción</th>
                    <th>Cantidad</th>                    
                    <th>Precio U.</th>
                    <th>Subtotal</th>                    
                </tr>                                            
                @foreach($salidadetalle as $detalle)
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
                        <th style="text-align: right;">Cantidad de articulos:</th>
                        <th><b>{{ $totalarticulos[0]->totalarticulos }} </b></th>
                        <th>Total: </th>
                        <th style="text-align: right;"><b>$ {{ $totalpagar[0]->totalpagar }}</b></th>
                    </tr>
                </tfoot>
            </table>       
            <br>
            <span>Forma de pago:
                @if($salida->formapago==1) Efectivo
                @elseif($salida->formapago==2) Tarjeta de Debito 
                @elseif($salida->formapago==3) Tarjeta de Credito @endif</span> 
            @if($salida->formapago==3)
                Total + comisión 0.8% = <b>$ {{ number_format($totalpagar[0]->totalpagar *1.08,2) }} </b>
            @endif
        </div>
</main>
<!--
<div id="firmas" style="display: flex;
  align-items: flex-end;">
    <div style="padding-left: 10px;
    padding-top: 45px;
    margin-left: 0px;
    float: left;
    position: relative;
    width: 50%;
    /*border: steelblue solid 1px;*/
    height: auto;"><p style="text-align: center; margin-top:-50px;">Total de articulos: </div>
    <div style="padding-top: 10px;
    padding-left: 0px;
    margin-left: 0px;
    position: relative;
    float: left;
    width: 50%;
    /*border: steelblue solid 1px;*/
    height: auto; text-align: center;"><p style="text-align: center; margin-top:-16px;">Total a pagar: </p> </div>
</div>
-->
</body>
</html>