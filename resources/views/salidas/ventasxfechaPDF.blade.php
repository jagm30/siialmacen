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
        border:none solid black;
        width: 100%;
        border-color: black;
    }
    th {
        text-align: left;
     }
    td{
        text-align: left;
         height: 25px;
     
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
<table style="margin-top: -3cm; margin-left: 3.5cm; width: 82% !important; border: none;">
    <tr style="background-color: #E6F9FF; color:black;" >
        <th style="background-color: white; border:none; font-size: 9pt; color: black; text-align: left;">Colegio La Salle de Tuxtla, A. C.<br>
                   Bolvd. La Salle No. 504. Col el Retiro C.P. 29040<br>                        
                Tuxtla Gutiérrez, Chiapas    <br>
                RFC: CST7001145E9<br>         
                Tel. 961 6191943, 961 6141953<br>
                www.lasalletuxtla.edu.mx</th>        
        <th style="background-color: white; border:none; font-size: 9pt; color: black; text-align: center;"> </th>   
        <th style="background-color: white; border:none; font-size: 9pt; color: black; text-align: center;">Folio:<br> <b>{{ $salida->id }}</b> <br>
                    <br>Fecha:  <br>
                    {{ $salida->fecha }} </th>     
    </tr>  
</table>
<!-- Envuelva el contenido de su PDF dentro de una etiqueta principal -->
<main>

<br><br>
    <div id="contenido" class="contenido">
            <table width="100%" style="width:100%" style="font-size: 10pt;   font-family: Arial, Helvetica, sans-serif;">             
                <tr style="background-color: #E6F9FF; color:black;" >                   
                    <th>Descripción</th>
                    <th>Cantidad</th>                    
                    <th>Precio U.</th>
                    <th>Subtotal</th>                    
                </tr>        
                @foreach($salidas as $salida)
                <tr style="font-size:10pt;">
                    <td>{{$salida->id}} </td>
                    <td>{{$salida->fecha }}</td>                    
                    <td>{{$salida->cliente}} </td>
                    <td>{{$salida->formapago}} </td>
                </tr>
                @endforeach                                       
                
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