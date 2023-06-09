@extends('layouts.app') 
@section('contenidoprincipal') 

<div class="container">   
<div class="row">
        <div class="col-lg-2 col-xs-4" style="padding-right:2px; padding-left: 2px;">
          <!-- small box -->
          <div class="small-box bg-default">
            <div class="inner">
              <h3>{{$entrada->nfactura}}</h3>

              <p>No. de Factura</p>
            </div>

           
          </div>
        </div>

        <div class="col-md-6 col-sm-12 col-xs-12" style="padding-right:2px; padding-left: 2px;">
          <div class="info-box bg-default">
            

            <div class="info-box-content">
              <span class="info-box-text">Proveedor: {{$entrada->proveedor}}   <br> Fecha: {{$entrada->fecha}}   </span>

              <div class="progress">
                <div class="progress-bar" style="width: 100%"></div>
              </div>
                  <span class="progress-description">
                    Requisicion: {{$entrada->referencia}} <br> Observaciones: {{$entrada->categoria}}
                  </span>

            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- ./col -->
        <div class="col-lg-2 col-xs-4" style="padding-right:2px; padding-left: 2px;">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3>{{$entrada->categoria}}</h3>

              <p>Categoria</p>
            </div>
           
          </div>
        </div>

        <div class="col-lg-2 col-xs-4" style="padding-right:2px; padding-left: 2px;">
          <!-- small box -->
          <div class="small-box ">
            <div class="inner">
             <button class="form-control btn-default">Editar</button><br>
             <button class="form-control btn-default">Finalizar</button>
            </div>
           
          </div>
        </div>
       
     
        <!-- ./col -->
      </div>
      <!-- /.row -->  
   <div class="row">
    <div class="col-xs-12">
      <div class="box">
          <div class="box-header">
            <button type="button" class="btn btn-success" id="btneditar"   data-toggle="modal" data-target="#modal-agregar"> Agregar Articulo</button>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
             <table id="example1" class="table table-bordered table-striped">           
            <thead>                  
              <tr>                    
                <th scope="col">Id</th>                    
                <th scope="col">Nombre</th>                    
                <th scope="col">Descripción</th>   
                <th scope="col">Categoria</th>                    
                <th scope="col">Precio</th>                    
                <th scope="col">Precio con descuento</th>                    
                <th scope="col">Acción</th>                    
                <th scope="col"></th>                  
                </tr>                
            </thead>                
            <tbody>                    
              @foreach ($entradas as $entrada)                        
                <tr>                            
                  <td>{{ $entrada->id }}</td>                            
                  <td>{{ $entrada->nfactura }}</td>                            
                  <td>{{ $entrada->proveedor }}</td>
                  <td>{{ $entrada->fecha}}</td>                            
                  <td>{{ $entrada->categoria }}</td>                            
                  <td>${{ $entrada->referencia }} MXN</td>                            
                  <td>                                
                    <button type="button" class="btn btn-success" id="btneditar"  data-id="{{$entrada->id}}" data-toggle="modal" data-target="#modal-default">
                Editar
              </button>
                  </td>                            
                  <td>                                
                    <button type="button" id="btn-eliminar" name="btn-eliminar" data-id="{{$entrada->id}}" class="btn btn-danger">Borrar</button>                            
                  </td>                        
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
                        <option value="1">SIGMA</option>
                        <option value="2">MCA COMPUTO</option>
                        <option value="3">COCA COLA</option>
                    </select>
                </div>
                <div class="form-group has-error col-md-6">
                    <label class="control-label" for="inputError1">Fecha de recepción</label>
                    <input id="fecha" type="date" class="form-control" name="fecha"  required  autofocus>
                </div>
                <div class="form-group has-success col-md-6">
                    <label class="control-label" for="inputSuccess1">Referencia / Orden de compra</label>
                    <input id="referencia" type="text" class="form-control" name="referencia"  required  autofocus>
                </div>
                <div class="form-group has-warning col-md-6">
                    <label class="control-label" for="inputWarning1">Categoria</label>
                    <select id="categoria" name="categoria" class="form-control">
                        <option value="1">ALMACEN GRAL</option>
                        <option value="2">ALMACEN 2</option>
                        <option value="3">ALMACEN 3</option>
                    </select>
                </div>
                <div class="form-group has-error col-md-6">
                    <label class="control-label" for="inputError1">Observaciones</label>
                    <input id="observaciones" type="text" class="form-control" name="observaciones"  required  autofocus>
                </div>
            </form>
         </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cancelar</button>
        <button id="btn_guardaregistro" name="btn_guardaregistro" type="button" class="btn btn-primary">Guardar cambios</button>
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
                <input id="id_producto" type="hidden" class="form-control" name="id_producto">
                <div class="form-group has-success col-md-6">
                    <label class="control-label" for="inputSuccess1">No. de factura</label>                     
                    <input id="nfactura-e" type="text" class="form-control" name="nfactura-e"  required  autofocus>
                </div>
                <div class="form-group has-warning col-md-6">
                    <label class="control-label" for="inputWarning1">Proveedor</label>
                    <select id="proveedor-e" name="proveedor-e" class="form-control">
                        <option value="1">SIGMA</option>
                        <option value="2">MCA COMPUTO</option>
                        <option value="3">COCA COLA</option>
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
                        <option value="1">ALMACEN GRAL</option>
                        <option value="2">ALMACEN 2</option>
                        <option value="3">ALMACEN 3</option>
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
    var id_producto = $(this).attr('data-id');
     // alert(id_producto);
    $.ajax({
           url:"/productos/"+id_producto,
           async: false,
           dataType:"json",
           success:function(html){                
              $("#id_producto").val(html.id);
              $("#nombre-e").val(html.nombre);
              $("#descripcion-e").val(html.descripcion);
              $("#categoria-e option[value='"+ html.categoria +"']").attr("selected",true);              
              $("#precio-e").val(html.precio);
              $("#precioPromocion-e").val(html.precioPromocion);            
           }
        })
  });

  $('#btn_guardarcambio').click(function() {    
    var id_producto = $("#id_producto").val();
    var nombre = $("#nombre-e").val();
    var descripcion = $("#descripcion-e").val();
    var categoria = $("#categoria-e").val();
    var precio = $("#precio-e").val();
    var precioPromocion = $("#precioPromocion-e").val(); 
      $.ajax({
         url:"/productos/edicion/"+id_producto+"/"+nombre+"/"+descripcion+"/"+categoria+"/"+precio+"/"+precioPromocion,
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
              id_usuario:     id_usuario
          },
          cache: false,
          success: function(dataResult){
            alert(dataResult.data);    
            window.location.href = '/entradas/'+dataResult.data; 
            /*$("#formmodal")[0].reset();
            $('#modal-agregar').modal('toggle');
            location.reload();             */
          }
      });    
  });



  $(document).on("click", "#btn-eliminar", function () {
    var id_producto = $(this).attr('data-id');
    if (confirm("Desea eliminar el registro!"+id_producto) == true) {
      $.ajax({
            type: "get",
            url: "{{ url('productos/delete') }}"+'/'+ id_producto,
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