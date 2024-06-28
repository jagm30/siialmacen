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
    .page-break {
        page-break-after: always;
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
        <th colspan="2" style="background-color: white; border:none; font-size: 11pt; color: black; text-align:right;"> Inventario de almacén</th>      
    </tr>  
</table>
<br><br>
    <div id="contenido" class="contenido">
        <style type="text/css">
            tbody tr:nth-child(even) { background-color: white; } tbody tr:nth-child(odd) { background-color:#FAFAFA; }
        </style>
        <table width="100%" style="width:100%" style="font-size: 9pt;   font-family: Arial, Helvetica, sans-serif;">             
            <tr style="background-color: #E6F9FF; color:black; ">
                <th colspan="3"  style="background-color: white; border: none ; font-size: 9pt; color: black;  text-align: left;"></th>
            </tr>
            <tr>
                <th></th>
            </tr>    
            <tr style="background-color: #F1F0F0; color:black;" >                   
                <th>DESCRIPCIÓN</th>
                <th>CANTIDAD</th>                    
                <th>PRECIO U.</th>
                <th>Stock</th>                    
            </tr>
            <tbody>
                @foreach($productos as $producto)
                    <tr >
                        <td>{{ $producto->id}}</td>
                        <td>{{ $producto->descripcion }}</td>
                        <td>$ {{ number_format($producto->precio,2) }}</td>
                        <td style="text-align: center;"><b>{{ $producto->stock }}</b></td>
                    </tr>
                @endforeach
            </tbody>                                            
           
            
        </table>       
    </div>
</main>

</body>
</html>