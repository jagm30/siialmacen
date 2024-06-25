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
        bottom: 3.5cm;
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
<br><br>
    <div id="contenido" class="contenido">
            <table width="100%" style="width:100%" style="font-size: 9pt;   font-family: Arial, Helvetica, sans-serif;">             
                <tr style="background-color: #E6F9FF; color:black; ">
                    <th colspan="3"  style="background-color: white; border: none ; font-size: 9pt; color: black;  text-align: left;">CLIENTE: {{$salida->solicitante}}</th>
                </tr>
                <tr>
                    <th></th>
                </tr>    
                <tr style="background-color: #F1F0F0; color:black;" >                   
                    <th>DESCRIPCIÓN</th>
                    <th>CANTIDAD</th>                    
                    <th>PRECIO U.</th>
                    <th>SUBTOTAL</th>                    
                </tr>                                            
                @foreach($salidadetalle as $detalle)
                <?php 
                    //$originalDate = $alumn->periodo_vencimiento;
                    //$newDate = date("d-m-Y", strtotime($originalDate));
                ?>
                <tr >
                    <td>{{$detalle->descripcion}} </td>
                    <td>{{ $detalle->cantidad }}</td>                    
                    <td>$ {{number_format($detalle->precio,2)}} </td>
                    <td style="text-align: right;">$ {{number_format($detalle->precio*$detalle->cantidad,2)}} </td>
                </tr>
                @endforeach    
                
            </table>       
            <br>            
        </div>
</main>

<div id="firmas" style="display: flex;
  align-items: flex-end;">
    <div style="padding-left: 10px;
    padding-top: 45px;
    margin-left: 10px;
    float: center;
    position: relative;
    width: 95%;
    /*border: steelblue solid 1px;*/
    height: auto;">
    <hr>
    <table style="font-size: 9pt"><tr>
                        <th style="text-align: right;">CANT. DE ARTICULOS:</th>
                        <th><b>{{ $totalarticulos[0]->totalarticulos }} </b></th>
                        <th style="text-align: right;">TOTAL: </th>
                        <th style="text-align: center;"><b>$ {{ number_format($totalpagar[0]->totalpagar,2) }}</b></th>
                    </tr></table>
                    <br>
            <span style="font-size: 9pt;">FORMA DE PAGO:
                @if($salida->formapago==1)<b> EFECTIVO</b>
                @elseif($salida->formapago==2)<b>T. DE DEBITO</b> 
                @elseif($salida->formapago==3)<b>T. CREDITO</b> @endif</span> 
            @if($salida->formapago==3)
                Total + comisión= <b>$ {{ number_format($salida->total,2) }} </b>
            @endif
    <p style="text-align:justify; font-size: 9pt">* Tiene dos días hábiles para cambios de cualquier prenda. <br>* No se hacen devoluciones en efectivo, únicamente se realizán cambios de talla o por otro artículo de igual o de mayor precio.<br>
    * En caso de requerir factura podra solicitarla unicamente el día posterior a su expedición.</div>    
</div>

</body>
</html>