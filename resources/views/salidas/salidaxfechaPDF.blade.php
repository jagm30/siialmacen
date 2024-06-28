<html>
<head>
<style>       
    @page {
        margin: 0cm 0cm;
        font-family: Arial, Helvetica, sans-serif;
    }
    /** Defina ahora los márgenes reales de cada página en el PDF **/
    body {
        margin-top: 5cm;
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
        border:0.3px solid gray;
        width: 100%;
    }
    th {
        border:0.3px solid gray;
        text-align: left;
     }
    td{
        border:0.3px solid gray;
        text-align: left;
         height: 25px;
         padding-left: 3px;
     
    }
    span{
        font-size: 10pt;
    }
    /*tr:nth-child(even){background-color: #f2f2f2}*/
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
        bottom: 4.5cm;
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

    <table style="margin-top: -3.8cm; margin-left: 3.5cm; width: 82% !important; border: none;">
        <tr style="background-color: #F0F8FA; color:black;" >
            <th style="background-color: white; border:none; font-size: 9pt; color: black; text-align: left;">Colegio La Salle de Tuxtla, A. C.<br>
                       Bolvd. La Salle No. 504. Col el Retiro C.P. 29040<br>                        
                    Tuxtla Gutiérrez, Chiapas    <br>
                    RFC: CST7001145E9<br>         
                    Tel. 961 6191943, 961 6141953<br>
                    www.lasalletuxtla.edu.mx</th>        
            <th colspan="2" style="background-color: white; border:none gray; font-size: 11pt; color: black; text-align: center;">Reporte de ventas <br> Del: {{ $fecha1 }} al: {{ $fecha2 }}</
        </tr>  
    </table>
<br><br>
    <div id="contenido" class="contenido">
            <table width="100%" style="width:100%" style="font-size: 10pt;   font-family: Arial, Helvetica, sans-serif;">             
                <tr style="background-color: #E6F9FF; color:black;" >                   
                    <th>Folio</th>
                    <th>Fecha</th>                    
                    <th>Cliente</th>
                    <th>Forma de pago</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>                                            
                @foreach($salidas as $salida)
                <tr style="font-size:10pt;">
                    <td style="text-align: center;">{{$salida->id}} </td>
                    <td>{{$salida->fecha }}</td>                    
                    <td>{{$salida->solicitante}} </td>
                    <td>@if($salida->formapago==1) Efectivo @endif
                        @if($salida->formapago==2) T. Debito @endif
                        @if($salida->formapago==3) T. Credito @endif
                    </td>
                    <td> $ {{number_format($salida->total,2)}} </td>
                    <td @if($salida->status=='cancelado')style="color:red" @endif @if($salida->status=='captura')style="color:blue" @endif> {{$salida->status}} </td>
                </tr>
                @endforeach 
        </div>        
        <br>
            <span>Total Efectivo: $ {{ number_format($totalefectivo[0]->totalefectivo) }}</span>
        <br>
            <span>Total T. Debito: $ {{ number_format($totaldebito[0]->totaldebito) }}</span>
        <br>
            <span>Total T. de Credito: $ {{ number_format($totalcredito[0]->totalcredito) }}</span>
        <br>
            <span>Total  Ventas: $ <b>{{ number_format($totalregistro[0]->totalregistro) }}</b></span>
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