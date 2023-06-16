@extends('layouts.app') 
@section('contenidoprincipal') 
<div class="container">     
   <div class="row">
    <div class="col-xs-12">
      <div class="box">
          <div class="box-header">
            <h3 class="box-title"><button type="button" class="btn btn-warning"> Registro de salidas</button> </h3> 
            <button type="button" class="btn btn-success"  data-toggle="modal" data-target="#modal-agregar"> Agregar salida</button>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
             <table id="example1" class="table table-bordered table-striped">           
            <thead>                  
              <tr>                    
                <th scope="col">Id</th>                    
                <th scope="col">No. de Factura</th>                    
                <th scope="col">Proveedor</th>   
                <th scope="col">Fecha</th>                    
                <th scope="col">Almacen</th>                    
                <th scope="col">Orden / Referencia</th>                    
                <th scope="col">Acción</th>                    
                <th scope="col"></th>                  
                </tr>                
            </thead>                
            <tbody>                    
              @foreach ($salidas as $salida)                        
                <tr>                            
                  <td>{{ $salida->folioreq }}</td>                            
                  <td>{{ $salida->fecha }}</td>                            
                  <td>{{ $salida->solicitante }}</td>
                  <td>{{ $salida->cajapago}}</td>                            
                  <td>{{ $salida->observaciones }}</td>                            
                  <td>${{ $salida->status }} MXN</td>                                              
                  <td><a href="/entradas/{{$salida->id}}"><button type="button" id="btn-agregar" name="btn-agregar" data-id="{{$salida->id}}" class="btn btn-info">Agregar / Ver</button></a></td>
                  <td>                                
                    <button type="button" class="btn btn-success" id="btneditar"  data-id="{{$salida->id}}" data-toggle="modal" data-target="#modal-default">Editar</button></td>                            
                  <td><button type="button" id="btn-eliminar" name="btn-eliminar" data-id="{{$salida->id}}" class="btn btn-danger">Borrar</button></td>
                </tr>                    
              @endforeach                
            </tbody>            
           </table>                
          <!-- /.box-body -->
        </div>
    </div>
  </div>
</div> 


<div class="modal fade" id="modal-agregar">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Registro de salidas</h4>
      </div>
      <div class="modal-body">
         <div class="row">
           <form id="formmodal">
              <input type="hidden" name="_token" id="csrf" value="{{Session::token()}}">
                <input id="id_producto" type="hidden" class="form-control" name="id_producto">
                <div class="form-group has-success col-md-4">
                    <label class="control-label" for="inputSuccess1">Folio requerimiento</label>                     
                    <input id="folioreq" type="text" class="form-control" name="folioreq"  required  autofocus>
                </div>
                <div class="form-group has-success col-md-8">
                    <label class="control-label" for="inputSuccess1">Nombre del solicitante / Departamento</label>                     
                    <input id="solicitante" type="text" class="form-control" name="solicitante"  required  autofocus>
                </div>
                <div class="form-group has-success col-md-4">
                    <label class="control-label" for="inputSuccess1">Fecha</label>                     
                    <input id="fecha" type="date" class="form-control" name="fecha"  required  value="{{ $date }}">
                </div>                
                <div class="form-group has-warning col-md-4">
                    <label class="control-label" for="inputWarning1">Recepcion de pago</label>
                    <select id="cajapago" name="cajapago" class="form-control">
                      <option value="almacen">Almacen</option>
                      <option value="cajagral">Caja General</option>
                    </select>
                </div>
                <div id="contnnotaventa" class="form-group has-error col-md-4">
                    <label class="control-label" for="inputError1">Nota de venta</label>
                    <input id="nnotaventa" type="text" class="form-control" name="nnotaventa">
                </div>
                <div id="contnfoliopago" class="form-group has-success col-md-4">
                    <label class="control-label" for="inputSuccess1">Folio de pago de caja</label>
                    <input id="nfoliopago" type="text" class="form-control" name="nfoliopago"  required  autofocus>
                </div>
                <div class="form-group has-warning col-md-12">
                    <label class="control-label" for="inputWarning1">Observaciones</label>
                    <input id="observaciones" type="text" class="form-control" name="observaciones"  required  autofocus>
                </div>
            </form>
         </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cancelar</button>
        <button id="btn_guardaregistro" name="btn_guardaregistro" type="button" class="btn btn-primary">Registrar entrada</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="modal-default">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Edición</h4>
      </div>
      <div class="modal-body">
         <div class="row">
           <form id="formmodal">
                <input id="id_entrada_e" type="hidden" class="form-control" name="id_entrada_e">
                <div class="form-group has-success col-md-6">
                    <label class="control-label" for="inputSuccess1">No. de factura</label>                     
                    <input id="nfactura-e" type="text" class="form-control" name="nfactura-e"  required  autofocus>
                </div>
                <div class="form-group has-warning col-md-6">
                    <label class="control-label" for="inputWarning1">Proveedor</label>
                    <select id="proveedor-e" name="proveedor-e" class="form-control">
                        <option>Seleccione un proveedor</option>
                      
                    </select>
                </div>
                <div class="form-group has-error col-md-6">
                    <label class="control-label" for="inputError1">Fecha de recepción</label>
                    <input id="fecha-e" type="date" class="form-control" name="fecha-e"  required  autofocus>
                </div>
                <div class="form-group has-success col-md-6">
                    <label class="control-label" for="inputSuccess1">Referencia / Orden de compra</label>
                    <input id="referencia-e" type="text" class="form-control" name="referencia-e"  required  autofocus>
                </div>
                <div class="form-group has-warning col-md-6">
                    <label class="control-label" for="inputWarning1">Categoria</label>
                    <select id="categoria-e" name="categoria-e" class="form-control">
                        <option>Seleccione un almacen</option>
                        
                    </select>
                </div>
                <div class="form-group has-error col-md-6">
                    <label class="control-label" for="inputError1">Observaciones</label>
                    <input id="observaciones-e" type="text" class="form-control" name="observaciones-e"  required  autofocus>
                </div>
            </form>
         </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cancelar</button>
        <button id="btn_guardarcambio" name="btn_guardarcambio" type="button" class="btn btn-primary">Guardar Cambios</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
</div>
@endsection
@section("scriptpie")
<script>
  $(function () {
    $('#example1').DataTable({
      language: {
        "decimal": "",
        "emptyTable": "No hay información",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
        "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
        "infoFiltered": "(Filtrado de _MAX_ total entradas)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Mostrar _MENU_ Entradas",
        "loadingRecords": "Cargando...",
        "processing": "Procesando...",
        "search": "Buscar:",
        "zeroRecords": "Sin resultados encontrados"        
      },
      "search": {
            "addClass": 'form-control input-lg col-xs-12'
      },
      "fnDrawCallback":function(){
        $("input[type='search']").attr("id", "searchBox");            
        $('#searchBox').css("width", "400px").focus();
      }
    })
  })

 $(document).on("click", "#btneditar", function () {
    //alert("accediendo a la edicion..."+$(this).attr('data-id'));
    var id_entrada = $(this).attr('data-id');    
    $.ajax({
           url:"/entradas/"+id_entrada,
           async: false,
           dataType:"json",
           success:function(html){   
              $("#id_entrada_e").val(id_entrada);
              $("#proveedor-e option[value='"+ html.proveedor +"']").attr("selected",true);
              $("#fecha-e").val(html.fecha);
              $("#nfactura-e").val(html.nfactura);
              $("#referencia-e").val(html.referencia);
              $("#categoria-e option[value='"+ html.categoria +"']").attr("selected",true);
              $("#observaciones-e").val(html.observaciones);            
           }
        })
  });

  $('#btn_guardarcambio').click(function() {   

    var id_entrada    = $("#id_entrada_e").val();
    var proveedor     = $("#proveedor-e").val();
    var fecha         = $("#fecha-e").val();
    var nfactura      = $("#nfactura-e").val();
    var referencia    = $("#referencia-e").val();
    var categoria     = $("#categoria-e").val();
    var observaciones = $("#observaciones-e").val(); 

      $.ajax({
         url:"/entradas/edicion/"+id_entrada+"/"+proveedor+"/"+fecha+"/"+nfactura+"/"+referencia+"/"+categoria+"/"+observaciones,
         dataType:"json",
         success:function(html){
          alert(html.data);
          $("#formmodal")[0].reset();
          $('#modal-default').modal('toggle');
          location.reload();
         }
      })
    }); 

//Agregar producto
  $('#btn_guardaregistro').click(function() {    
    
    var proveedor       = $('#proveedor').val();
    var fecha           = $('#fecha').val();    
    var nfactura        = $('#nfactura').val();
    var referencia      = $('#referencia').val();
    var categoria       = $('#categoria').val();
    var observaciones   = $('#observaciones').val();
    var status          = 'captura';
    var id_usuario      = 1;

      $.ajax({
          url: "/entradas",
          type: "POST",
          data: {
              _token: $("#csrf").val(),
              type: 1,
              proveedor:      proveedor,
              fecha:          fecha,
              nfactura:       nfactura,            
              referencia:     referencia,
              categoria:      categoria,
              observaciones:  observaciones,
              status:         status,
              id_usuario:     id_usuario
          },
          cache: false,
          success: function(dataResult){
          //  alert(dataResult.data);    
            window.location.href = '/entradas/'+dataResult.data; 
            /*$("#formmodal")[0].reset();
            $('#modal-agregar').modal('toggle');
            location.reload();             */
          }
      });    
  });



  $(document).on("click", "#btn-eliminar", function () {
    var id_entrada = $(this).attr('data-id');
    if (confirm("Desea eliminar el registro!"+id_entrada) == true) {
      $.ajax({
            type: "get",
            url: "{{ url('entradas/delete') }}"+'/'+ id_entrada,
            success: function (data) {
              alert(data.data);
              location.reload();
            }
        });
    }else{
      alert("cancelado");  
    }
  });

  $("#cajapago" ).change(function() {  
      var cajapago       = $('#cajapago').val();
      if(cajapago=='cajagral'){
        $('#contnfoliopago').show();
        $('#contnnotaventa').hide();
      }else{
        $('#contnfoliopago').hide();
        $('#contnnotaventa').show();
      }
      
      //almacen
    });


</script>
@endsection