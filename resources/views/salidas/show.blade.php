@extends('layouts.app') 
@section('contenidoprincipal') 


<!-- SELECT2 EXAMPLE -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Datos Generales || Almacen: <b>{{$salida->nomalmacen}}</b></h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-2">
              <div class="form-group">
                <label class="control-label" for="inputSuccess1">Folio de Requerimiento</label>                     
                <input id="nfactura" type="text" class="form-control" name="nfactura"  readonly value="{{$salida->folioreq}}">                
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label class="control-label" for="inputSuccess1">Solicitante</label>                     
                <input id="solicitante" type="text" class="form-control" name="solicitante"  readonly value="{{$salida->solicitante}}">                
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label class="control-label" for="inputError1">Fecha de recepción</label>
                <input id="fecha" type="date" class="form-control" name="fecha"  readonly value="{{$salida->fecha}}">
              </div>             
            </div>
            <div class="col-md-2">              
              <div class="form-group">
                <label class="control-label" for="inputWarning1">Pago en:</label>
                <select id="proveedor" name="proveedor" class="form-control" value="{{$salida->almacen}}">
                    <option value="1">Almacen</option>
                    <option value="2">Caja Gral</option>
                </select>
              </div>
            </div>            
            <div class="col-md-4">
              <!-- /.form-group -->
              <div class="form-group">
                <label class="control-label" for="inputSuccess1">Observaciones</label>
                <input id="referencia" type="text" class="form-control" name="referencia" readonly value="{{$salida->observaciones}}">
              </div>
              <!-- /.form-group -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
      
      <!-- /.box --> 

   <div class="row">
    <div class="col-xs-12">
      <div class="box">
          <div class="box-header">
            @if($salida->status=='captura')<button type="button" class="btn btn-success" id="btneditar"   data-toggle="modal" data-target="#modal-agregar"> Agregar Articulo</button>
            <button type="button" class="btn btn-warning" style="float: right;" id="btnfinalizar"  > Finalizar captura</button>@endif
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <table id="alumnos_table" class="table table-bordered table-striped">           
            <thead>                  
              <tr>                    
                <th scope="col">Descripción</th>                    
                <th scope="col">Cantidad</th>                    
                <th scope="col">Precio</th>                 
                <th>Accion</th>
                </tr>                
            </thead>                
           
           </table>                
          <!-- /.box-body -->
        </div>
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
        <h4 class="modal-title">Registro de articulos</h4>
      </div>
      <div class="modal-body">
         <div class="row">
           <form id="formmodal" name="formmodal">
              <input type="hidden" name="_token" id="csrf" value="{{Session::token()}}">
                <input id="id_salida" type="hidden" value="{{ $id_salida }}" class="form-control" name="id_salida">
                <div class="form-group has-success col-md-12">
                    <label>Articulo</label>
                    <select id="id_producto" name="id_producto" class="form-control select2" style="width: 100%;" >
                      <option selected="selected">Seleccione un articulo</option>
                      @foreach($productos as $producto)
                        <option value="{{$producto->id}}">{{$producto->descripcion}}</option>        
                      @endforeach              
                    </select>
                </div>
                <div class="form-group has-error col-md-3">
                    <label class="control-label" for="inputError1">Stock</label>
                    <input id="stock" type="text" class="form-control" name="stock"  readonly>
                </div>                
                <div class="form-group has-error col-md-3">
                    <label class="control-label" for="inputError1">Cantidad</label>
                    <input id="cantidad" type="text" class="form-control" name="cantidad"  required  autofocus>
                </div>   
                <div class="form-group has-error col-md-3">
                    <label class="control-label" for="inputError1">Precio</label>
                    <input id="precio" type="text" class="form-control" name="precio"  required  autofocus readonly>
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
<!--

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
                <input id="id_producto-e" type="hidden" class="form-control" name="id_producto-e">
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
   
  </div>
 
</div>
.modal -->

@endsection('contenidoprincipal')
@section("scriptpie")
<script type="text/javascript">
  $(function() {
    $('.select2').select2();
     $('#alumnos_table').DataTable({
        processing: true,
        serverSide: true,

          ajax: "/salidaproductos/listarxsalida/"+{{$salida->id}},
          columns:[
        {
          data: 'descripcion',
          name: 'descripcion'
        },
        {
          data: 'cantidad',
          name: 'cantidad'
        },
        {
          data: 'precio',
          name: 'precio'
        },
        {
        data: 'action',
        name: 'action',
        orderable: false
        }
      ],
      searching: true,
      autoWidth: false
      });
     $("#menusalida").addClass("important active");
});

//Agregar producto
  $('#btn_guardaregistro').click(function() {    
    
    var id_salida     = $('#id_salida').val();
    var id_producto   = $('#id_producto').val();    
    var cantidad      = parseInt($('#cantidad').val());
    var stock         = parseInt($('#stock').val());
    var precio        = $('#precio').val();
    var status        = 'captura';
    var id_usuario    = 1;

   
    if (cantidad > stock) {
      alert("No hay suficiente stock...");
      return false;
    }  
    if (cantidad == 0 || cantidad.length == 0 ) {
      alert("Ingrese una cantidad");
      return false;
    }  
    $.ajax({
      url: "/salidaproductos",
      type: "POST",
      data: {
          _token: $("#csrf").val(),
          type: 1,
          id_salida:    id_salida,
          id_producto:  id_producto,
          cantidad:     cantidad,            
          stock:        stock,
          precio:       precio,
          status:       status,
          id_usuario:   id_usuario
      },
      cache: false,
      success: function(dataResult){
        alert("registrado correctamente...");     
            $('#alumnos_table').DataTable().ajax.reload();           
            $('#formmodal').trigger("reset");
      }
    });    
  });



  $(document).on("click", "#btn-eliminar", function () {
    var id_salidaproducto = $(this).attr('data-id');
    if (confirm("Desea eliminar el registro!"+id_salidaproducto) == true) {
      $.ajax({
            type: "get",
            url: "{{ url('salidaproductos/delete') }}"+'/'+ id_salidaproducto,
            success: function (data) {
              alert(data.data);
              $('#alumnos_table').DataTable().ajax.reload();
            }
        });
    }else{
      alert("cancelado");  
    }
  });

  $(document).on("click", "#btnfinalizar", function () {
    var id_salida    = $('#id_salida').val();
    
    if (confirm("Desea finalizar la captura") == true) {
      $.ajax({
            type: "get",
            url: "{{ url('salidas/finalizarsalida') }}"+'/'+ id_salida,
            success: function (data) {
              location.reload();
              //alert(data.data);
              //$('#alumnos_table').DataTable().ajax.reload();
            }
        });
    }
  });

  $("#id_producto" ).change(function() {  
      var id_producto       = $('#id_producto').val();
      $.ajax({
         url:"/productos/"+id_producto,
         async: false,
         dataType:"json",
         success:function(html){                
            $("#stock").val(html.stock);
            $("#precio").val(html.precio);
         }
      })      
    });

</script>
@endsection('scriptpie')