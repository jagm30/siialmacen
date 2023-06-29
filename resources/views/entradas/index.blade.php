@extends('layouts.app') 
@section('contenidoprincipal') 
   
   <div class="row">
    <div class="col-xs-12">
      <div class="box">
          <div class="box-header">
            <h3 class="box-title"><button type="button" class="btn btn-warning"> Registro de entradas</button> </h3> 
            <button type="button" class="btn btn-success"  data-toggle="modal" data-target="#modal-agregar"> Agregar entrada</button>
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
                <th scope="col" style="width: 100px;">Status</th>                    
                <th scope="col" style="width: 300px">Acción</th>                      
                </tr>                
            </thead>                
            <tbody>                    
              @foreach ($entradas as $entrada)                        
                <tr>                            
                  <td>{{ $entrada->id }}</td>                            
                  <td>{{ $entrada->nfactura }}</td>                            
                  <td>{{ $entrada->nombreproveedor }}</td>
                  <td>{{ $entrada->fecha}}</td>                            
                  <td>{{ $entrada->nomalmacen }}</td>                            
                  <td>@if($entrada->status=='finalizado')<button type="button" class="btn btn-block btn-success">{{ $entrada->status }}</button>@else <button type="button" class="btn btn-block btn-primary">{{ $entrada->status }}</button> @endif </td>                                              
                  <td><a href="/entradas/{{$entrada->id}}"><button type="button" id="btn-agregar" name="btn-agregar" data-id="{{$entrada->id}}" class="btn btn-info">Ver</button></a> | <button type="button" class="btn btn-success" id="btneditar"  data-id="{{$entrada->id}}" data-toggle="modal" data-target="#modal-default">Editar</button> | <button type="button" id="btn-eliminar" name="btn-eliminar" data-id="{{$entrada->id}}" class="btn btn-danger">Borrar</button>
                <a href="/entradas/reportepdf/{{ $entrada->id }}" target="_blank"><img src="/images/pdf.png" width="36" height="36"></a>                
            </div></td>
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
        <h4 class="modal-title">Registro de entradas</h4>
      </div>
      <div class="modal-body">
         <div class="row">
           <form id="formmodal">
              <input type="hidden" name="_token" id="csrf" value="{{Session::token()}}">
                <input id="id_producto" type="hidden" class="form-control" name="id_producto">
                <div class="form-group has-success col-md-6">
                    <label class="control-label" for="inputSuccess1">No. de factura</label>                     
                    <input id="nfactura" type="text" class="form-control" name="nfactura"  required  autofocus>
                </div>
                <div class="form-group has-warning col-md-6">
                    <label class="control-label" for="inputWarning1">Proveedor</label>
                    <select id="proveedor" name="proveedor" class="form-control">
                      <option value="">Seleccione un proveedor</option>
                        @foreach($proveedores as $proveedor)
                          <option value="{{$proveedor->id}}">{{$proveedor->nombre}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group has-error col-md-6">
                    <label class="control-label" for="inputError1">Fecha de recepción</label>
                    <input id="fecha" type="date" class="form-control" name="fecha"  required  autofocus value="{{$date}}">
                </div>
                <div class="form-group has-success col-md-6">
                    <label class="control-label" for="inputSuccess1">Referencia / Orden de compra</label>
                    <input id="referencia" type="text" class="form-control" name="referencia"  required  autofocus value="NA">
                </div>
                <div class="form-group has-warning col-md-6">
                    <label class="control-label" for="inputWarning1">Almacen</label>
                    <select id="categoria" name="categoria" class="form-control">
                        <option value="">Seleccione un almacen</option>
                        @foreach($almacenes as $almacen)
                          <option value="{{$almacen->id}}">{{$almacen->nombre}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group has-error col-md-6">
                    <label class="control-label" for="inputError1">Observaciones</label>
                    <input id="observaciones" type="text" class="form-control" name="observaciones"  required  autofocus value="NINGUNO">
                </div>
                <div class="form-group has-error col-md-12" id="cajaerror">
                    
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
                        <option value="">Seleccione un proveedor</option>
                        @foreach($proveedores as $proveedor)
                          <option value="{{$proveedor->id}}">{{$proveedor->nombre}}</option>
                        @endforeach
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
                        <option value="">Seleccione un almacen</option>
                        @foreach($almacenes as $almacen)
                          <option value="{{$almacen->id}}">{{$almacen->nombre}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group has-error col-md-6">
                    <label class="control-label" for="inputError1">Observaciones</label>
                    <input id="observaciones-e" type="text" class="form-control" name="observaciones-e"  required  autofocus>
                </div>
                <div class="form-group has-error col-md-12" id="cajaerror-e">
                    
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
    $("#menuentradas").addClass("important active");
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
    var referencia    = String($("#referencia-e").val());
    var categoria     = $("#categoria-e").val();
    var observaciones = $("#observaciones-e").val(); 
    //alert(referencia);
    if (nfactura == '' || nfactura.length == 0 ) {
      document.getElementById("nfactura-e").focus();
      document.getElementById("cajaerror-e").innerHTML = '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-warning"></i> Alerta!</h4>Ingrese el No. de factura.</div>';
      return false;
    } 
    if (proveedor == '' || proveedor.length == 0 ) {
      document.getElementById("proveedor-e").focus();
      document.getElementById("cajaerror-e").innerHTML = '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-warning"></i> Alerta!</h4>Seleccione un proveedor.</div>';
      return false;
    }
    if (categoria == '' || categoria.length == 0 ) {
      document.getElementById("categoria-e").focus();
      document.getElementById("cajaerror-e").innerHTML = '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-warning"></i> Alerta!</h4>Seleccione un almacén.</div>';
      return false;
    } 

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

    if (nfactura == '' || nfactura.length == 0 ) {
      document.getElementById("nfactura").focus();
      document.getElementById("cajaerror").innerHTML = '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-warning"></i> Alerta!</h4>Ingrese el No. de factura.</div>';
      return false;
    } 
    if (proveedor == '' || proveedor.length == 0 ) {
      document.getElementById("proveedor").focus();
      document.getElementById("cajaerror").innerHTML = '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-warning"></i> Alerta!</h4>Seleccione un proveedor.</div>';
      return false;
    }
    if (categoria == '' || categoria.length == 0 ) {
      document.getElementById("categoria").focus();
      document.getElementById("cajaerror").innerHTML = '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-warning"></i> Alerta!</h4>Seleccione un almacén.</div>';
      return false;
    } 
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

</script>
@endsection