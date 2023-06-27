<html>
<head>
<style>       
    @page {
        margin: 0cm 0cm;
        font-family: Arial, Helvetica, sans-serif;
    }
    /** Defina ahora los márgenes reales de cada página en el PDF **/
    body {
        margin-top: 1.5cm;
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
        height: 2cm;
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
        border:1px solid black;
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
        background-color: #041130;
        color: white;        
        line-height: .5cm;
        width: 100%
    }
}
</style>
</head>
<body>
<!-- Defina bloques de encabezado y pie de página antes de su contenido -->
<header>
    <h2> Reporte de entradas</h2>
</header>
<!-- Envuelva el contenido de su PDF dentro de una etiqueta principal -->
<main>
<h2><p>ALMACEN: <B>{{ $entrada->nomalmacen }},  {{ $entrada->fecha}}  </B></p></h2>
    <div id="contenido" class="contenido">
            <table width="100%" style="width:100%" style="font-size: 10pt;   font-family: Arial, Helvetica, sans-serif;">             
                <tr>                    
                    <th>Cantidad</th>
                    <th>Nombre</th>
                    <th>Descripcion</th>
                    <th>Precio</th>
                    <th>Subtotal</th>                    
                </tr>                                            
                @foreach($entradadetalle as $detalle)
                <?php 
                    //$originalDate = $alumn->periodo_vencimiento;
                    //$newDate = date("d-m-Y", strtotime($originalDate));
                ?>
                <tr style="font-size:10pt;">
                    <td>{{ $detalle->cantidad }}</td>
                    <td>{{$detalle->nombre}} </td>
                    <td>{{$detalle->descripcion}} </td>
                    <td>$ {{$detalle->precio}} </td>
                    <td>$ {{$detalle->precio*$detalle->cantidad}} </td>
                </tr>
                @endforeach    
            </table>        
        </div>
</main>

<div id="firmas" style="display: flex;
  align-items: flex-end;">
    <div style="padding-left: 10px;
    padding-top: 50px;
    margin-left: 0px;
    float: left;
    position: relative;
    width: 50%;
    border: steelblue solid 1px;
    height: auto;"><p style="text-align: center; margin-top:-50px;">Recibió <br>____________________________<br>José Gijon<br>Encargado de almacén</p> </div>
    <div style="padding-top: 15px;
    padding-left: 0px;
    margin-left: 0px;
    position: relative;
    float: left;
    width: 50%;
    border: steelblue solid 1px;
    height: auto; text-align: center;"><p style="text-align: center; margin-top:-16px;">Vo. Bo. <br>____________________________<br>José Gijon<br>Recursos materiales</p> </div>
</div>
<footer>
SII ALMACEN - SISTEMA DE GESTIÓN DE ALMACÉN © <?php echo date("Y");?>
</footer>
</body>
</html>