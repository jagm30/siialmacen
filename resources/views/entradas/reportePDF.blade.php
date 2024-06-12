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
        background-color: #051F62;
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
        width:    21cm;
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
                width:    21cm;
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
        <th style="background-color: white; border: 1px solid gray; font-size: 10pt; color: #051F62; text-align: left;" >Entrada de mercancia</th>       
        <th style="background-color: white; border: 1px solid gray; font-size: 10pt; color: #051F62; text-align: right;" colspan="2">Fecha de registro: {{ $entrada->fecha }} </th> 
    </tr>
    <tr style="background-color: #E6F9FF; color:black; ">
        <th style="background-color: #051F62; border: 1px solid gray; font-size: 10pt; color: white;">{{$entrada->nomalmacen}} - {{$entrada->nfactura}} </th>
        <th style="background-color: #051F62; border: 1px solid gray; font-size: 10pt; color: white;">Proveedor: {{$entrada->nombreproveedor}}</th>
        <th style="background-color: #051F62; border: 1px solid gray; font-size: 10pt; color: white;">Referencia: {{$entrada->referencia}}</th>
    </tr>
    <tr>
        <th style="background-color: white; border: 1px solid gray;color: #051F62;font-size: 10pt;" colspan="3">Observaciones: {{$entrada->observaciones}}</th>
        
    </tr>
</table>
<br><br>
    <div id="contenido" class="contenido">
            <table width="100%" style="width:100% !important" style="font-size: 10pt;   font-family: Arial, Helvetica, sans-serif;">             
                <tr>                    
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
            </table>        
        </div>
</main>

<div id="firmas" style="display: flex;
  align-items: flex-end;">
    <div style="padding-left: 10px;
    padding-top: 45px;
    margin-left: 0px;
    float: left;
    position: relative;
    width: 50%;
    /*border: steelblue solid 1px;*/
    height: auto;"><p style="text-align: center; margin-top:-50px;">Recibió <br>____________________________<br></p> </div>
    <div style="padding-top: 10px;
    padding-left: 0px;
    margin-left: 0px;
    position: relative;
    float: left;
    width: 50%;
    /*border: steelblue solid 1px;*/
    height: auto; text-align: center;"><p style="text-align: center; margin-top:-16px;">Vo. Bo. <br>____________________________<br></p></div>
</div>
<footer>
SII ALMACEN - SISTEMA DE GESTIÓN DE ALMACÉN © <?php echo date("Y");?>
</footer>
</body>
</html>