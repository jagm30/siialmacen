  @extends('layouts.app') 
@section('contenidoprincipal') 

   <div class="row">
    <div class="col-xs-12">
      <div class="box">
          <div class="box-header">
            <h3 class="box-title"><button type="button" class="btn btn-warning"> Catalogo de articulos</button> </h3> 
            <button type="button" class="btn btn-success" id="btneditar"   data-toggle="modal" data-target="#modal-agregar"> Agregar articulo</button>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
             <table id="example1" class="table table-bordered table-striped">           
            <thead>                  
              <tr>                    
                <th scope="col">Id</th>                    
                <th scope="col">Nombre</th>                    
                <th scope="col">Descripción</th>   
                <th scope="col">Almacen</th>                    
                <th scope="col">Precio Venta</th>                    
                <th scope="col">Precio con descuento</th>                    
                <th scope="col">Acción</th>                    
                <th scope="col"></th>                  
                </tr>                
            </thead>                
            <tbody>                    
              @foreach ($productos as $producto)                        
                <tr>                            
                  <td>{{ $producto->id }}</td>                            
                  <td>{{ $producto->nombre }}</td>                            
                  <td>{{ $producto->descripcion }}</td>
                  <td>{{ $producto->nomalmacen}}</td>                            
                  <td>$ {{ $producto->precio }}</td>                            
                  <td>$ {{ $producto->precioPromocion }}</td>                            
                  <td>                                
                    <button type="button" class="btn btn-success" id="btneditar"  data-id="{{$producto->id}}" data-toggle="modal" data-target="#modal-default">
                Editar
              </button>
                  </td>                            
                  <td>                                
                  <!--  <button type="button" id="btn-eliminar" name="btn-eliminar" data-id="{{$producto->id}}" class="btn btn-danger">Borrar</button>-->
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
        <h4 class="modal-title">Registro de productos</h4>
      </div>
      <div class="modal-body">
         <div class="row">
           <form id="formmodal">
              <input type="hidden" name="_token" id="csrf" value="{{Session::token()}}">
                <input id="id_producto" type="hidden" class="form-control" name="id_producto">
                <div class="form-group has-success col-md-12">
                    <label class="control-label" for="inputSuccess1">Descripción</label>                     
                    <input id="nombre" type="text" class="form-control" name="nombre"  required  autofocus oninput="actualizarValor()">
                </div>
                <div class="form-group has-warning col-md-6">
                    <label class="control-label" for="inputWarning1">Clave</label>
                    <input type="text" name="claveproducto" id="claveproducto" class="form-control" value="N/A">
                </div>
                <div class="form-group has-error col-md-6">
                    <label class="control-label" for="inputError1">Almacén</label>
                    <select id="categoria" name="categoria" class="form-control">
                        @foreach($almacenes as $almacene)
                            <option value="{{$almacene->id}}">{{$almacene->nombre}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group has-success col-md-6">
                    <label class="control-label" for="inputSuccess1">Precio Venta</label>
                    <input id="precio" type="text" class="form-control" name="precio"  required  autofocus value="0">
                </div>
                <div class="form-group has-error col-md-6">
                    <label class="control-label" for="inputError1">Precio Mayoreo</label>
                    <input id="precioPromocion" type="text" class="form-control" name="precioPromocion"  required  autofocus value="0">
                </div>
                <div class="form-group has-error col-md-6" id="cajaerror">                    
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
           <form id="formmodal" method="POST" action="/clientes">
                <input id="id_producto" type="hidden" class="form-control" name="id_producto">
                <div class="form-group has-success col-md-12">
                    <label class="control-label" for="inputSuccess1">Nombre</label>                     
                    <input id="nombre-e" type="text" class="form-control" name="nombre-e"  required  autofocus>
                </div>
                <div class="form-group has-warning col-md-6">
                    <label class="control-label" for="inputWarning1">Clave de producto</label>
                    <input id="claveproducto-e" name="claveproducto-e" type="text" class="form-control">
                </div>
                <div class="form-group has-error col-md-6">
                    <label class="control-label" for="inputError1">Categoria</label>
                    <select id="categoria-e" name="categoria-e" class="form-control">
                        @foreach($almacenes as $almacene)
                            <option value="{{$almacene->id}}">{{$almacene->nombre}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group has-success col-md-6">
                    <label class="control-label" for="inputSuccess1">Precio Venta</label>
                    <input id="precio-e" type="text" class="form-control" name="precio-e"  required  autofocus>
                </div>
                <div class="form-group has-error col-md-6">
                    <label class="control-label" for="inputError1">Precio Mayoreo</label>
                    <input id="precioPromocion-e" type="text" class="form-control" name="precioPromocion-e"  required  autofocus>
                </div>
                <div class="form-group has-error col-md-6" id="cajaerror-e">                    
                </div>
            </form>
         </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cancelar</button>
        <button id="btn_guardarcambio" name="btn_guardarcambio" type="button" class="btn btn-primary">Guardar cambio</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
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
      },
      dom: 'Bfrtip',
      buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    })
    $("#menuproductos").addClass("important active");
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
              $("#claveproducto-e").val(html.claveproducto);
              $("#descripcion-e").val(html.descripcion);
              $("#categoria-e option[value='"+ html.categoria +"']").attr("selected",true);              
              $("#precio-e").val(html.precio);
              $("#precioPromocion-e").val(html.precioPromocion);            
           }
        })
  });

  $('#btn_guardarcambio').click(function() {    
    var id_producto   = $("#id_producto").val();
    var nombre        = $("#nombre-e").val();
    var claveproducto = $("#claveproducto-e").val();
    var descripcion   = $("#nombre-e").val();
    var categoria     = $("#categoria-e").val();
    var precio        = $("#precio-e").val();
    var precioPromocion = $("#precioPromocion-e").val(); 
    
    if (nombre == '' || nombre.length == 0 ) {
      document.getElementById("nombre-e").focus();
      document.getElementById("cajaerror-e").innerHTML = '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-warning"></i> Alerta!</h4>Ingrese el nombre.</div>';
      return false;
    } 
    if (descripcion == '' || descripcion.length == 0 ) {
      document.getElementById("descripcion-e").focus();
      document.getElementById("cajaerror-e").innerHTML = '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-warning"></i> Alerta!</h4>Ingrese la descripcion.</div>';
      return false;
    }
    if (precio == '' || precio.length == 0 ) {
      document.getElementById("precio-e").focus();
      document.getElementById("cajaerror-e").innerHTML = '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-warning"></i> Alerta!</h4>Si no maneja precio, deje el campo con el valor 0</div>';
      return false;
    } 
    if (precioPromocion == '' || precioPromocion.length == 0 ) {
      document.getElementById("precioPromocion-e").focus();
      document.getElementById("cajaerror-e").innerHTML = '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-warning"></i> Alerta!</h4>Si no maneja precio, deje el campo con el valor 0</div>';
      return false;
    } 
    if (claveproducto == '' || claveproducto.length == 0 ) {
      document.getElementById("claveproducto-e").focus();
      document.getElementById("cajaerror-e").innerHTML = '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-warning"></i> Alerta!</h4>Coloque "n/a" si no maneja clave de producto.</div>';
      return false;
    } 
      $.ajax({
         url:"/productos/edicion/"+id_producto+"/"+nombre+"/"+claveproducto+"/"+descripcion+"/"+categoria+"/"+precio+"/"+precioPromocion,
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
    
    var nombre          = $('#nombre').val();
    var descripcion     = $('#nombre').val();    
    var categoria       = $('#categoria').val();
    var claveproducto   = $('#claveproducto').val();
    var precio          = $('#precio').val();
    var precioPromocion = $('#precioPromocion').val();
    var stock           = 0;
    var id_usuario      = 1;
    
    if (nombre == '' || nombre.length == 0 ) {
      document.getElementById("nombre").focus();
      document.getElementById("cajaerror").innerHTML = '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-warning"></i> Alerta!</h4>Ingrese el nombre.</div>';
      return false;
    } 
    if (descripcion == '' || descripcion.length == 0 ) {
      document.getElementById("descripcion").focus();
      document.getElementById("cajaerror").innerHTML = '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-warning"></i> Alerta!</h4>Ingrese la descripcion.</div>';
      return false;
    }
    if (precio == '' || precio.length == 0 ) {
      document.getElementById("precio").focus();
      document.getElementById("cajaerror").innerHTML = '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-warning"></i> Alerta!</h4>Si no maneja precio, deje el campo con el valor 0</div>';
      return false;
    } 
    if (precioPromocion == '' || precioPromocion.length == 0 ) {
      document.getElementById("precioPromocion").focus();
      document.getElementById("cajaerror").innerHTML = '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-warning"></i> Alerta!</h4>Si no maneja precio, deje el campo con el valor 0</div>';
      return false;
    } 
    

      $.ajax({
          url: "/productos",
          type: "POST",
          data: {
              _token: $("#csrf").val(),
              type: 1,
              nombre:         nombre,
              descripcion:    descripcion,
              categoria:      categoria,
              claveproducto:  claveproducto,
              precio:         precio,
              precioPromocion:precioPromocion,
              stock:          stock,
              id_usuario:     id_usuario
          },
          cache: false,
          success: function(dataResult){
            alert(dataResult.data);     
            $("#formmodal")[0].reset();
            $('#modal-agregar').modal('toggle');
            location.reload();             
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

  function actualizarValor(){
    let nombre = document.getElementById("nombre").value;
    document.getElementById("descripcion").value = nombre;
  }
</script>
@endsection