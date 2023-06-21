@extends('layouts.app') 
@section('contenidoprincipal') 
    
   <div class="row">
    <div class="col-xs-12">
      <div class="box">
          <div class="box-header">
            <h3 class="box-title"><button type="button" class="btn btn-warning">Usuarios</button> </h3> 
            <button type="button" class="btn btn-success" id="btneditar"   data-toggle="modal" data-target="#modal-agregar">Agregar usuarios</button>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
             <table id="example1" class="table table-bordered table-striped">           
            <thead>                  
              <tr>                    
                <th scope="col">Id</th>                    
                <th scope="col">Nombre</th>                    
                <th scope="col">Email</th>                    
                <th scope="col">Tipo de usuario</th>                                    
                <th scope="col">Acción</th>                    
                <th scope="col"></th>                  
                </tr>                
            </thead>                
            <tbody>                    
              @foreach ($usuarios as $usuario)                        
                <tr>                            
                  <td>{{ $usuario->id }}</td>                            
                  <td>{{ $usuario->name }}</td>                            
                  <td>{{ $usuario->email}}</td>
                  <td>{{ $usuario->tipo_usuario}}</td>                                     
                  <td>                                
                    <button type="button" class="btn btn-success" id="btneditar"  data-id="{{$usuario->id}}" data-toggle="modal" data-target="#modal-default">
                Editar
              </button>
                  </td>                            
                  <td>                                
                    <button type="button" id="btn-eliminar" name="btn-eliminar" data-id="{{$usuario->id}}" class="btn btn-danger">Borrar</button>                            
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
        <h4 class="modal-title">Registro de usuario</h4>
      </div>
      <div class="modal-body">
         <div class="row">
           <form id="formmodal">
              <input type="hidden" name="_token" id="csrf" value="{{Session::token()}}">
                <input id="id_usuario" type="hidden" class="form-control" name="id_usuario">
                <div class="form-group has-success col-md-12">
                    <label class="control-label" for="inputSuccess1">Nombre</label>                     
                    <input id="nombre" type="text" class="form-control" name="nombre"  required  autofocus>
                </div>
                <div class="form-group has-warning col-md-6">
                    <label class="control-label" for="inputWarning1">Email</label>
                    <input id="email" name="email" type="email" class="form-control">
                </div>
                <div class="form-group has-error col-md-6">
                    <label class="control-label" for="inputError1">Tipo de usuario</label>
                    <select id="tipo_usuario" name="tipo_usuario" class="form-control">
                        <option value="admin">Administrador</option>
                        <option value="capturista">Capturista</option>
                    </select>
                </div>
                <div class="form-group has-success col-md-6">
                    <label class="control-label" for="inputSuccess1">Contraseña</label>
                    <input id="password" type="password" class="form-control" name="password"  required  autofocus>
                </div>
                <div class="form-group has-error col-md-6">
                    <label class="control-label" for="inputError1">Confirma tu contraseña</label>
                    <input id="rpassword" type="password" class="form-control" name="rpassword"  required  autofocus>
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
                <input id="id_usuario" type="hidden" class="form-control" name="id_usuario">
                <div class="form-group has-success col-md-12">
                    <label class="control-label" for="inputSuccess1">Nombre</label>                     
                    <input id="nombre-e" type="text" class="form-control" name="nombre-e"  required  autofocus>
                </div>
                <div class="form-group has-warning col-md-6">
                    <label class="control-label" for="inputWarning1">E-mail</label>
                    <input id="email-e" name="email-e" type="text" class="form-control">
                </div>
                <div class="form-group has-error col-md-6">
                    <label class="control-label" for="inputError1">Tipo de usuario</label>
                    <select id="tipo_usuario-e" name="tipo_usuario-e" class="form-control">
                        <option value="admin">Administrador</option>
                        <option value="capturista">Capturista</option>
                    </select>
                </div>
                <div class="form-group has-success col-md-6">
                    <label class="control-label" for="inputSuccess1">Contraseña</label>
                    <input id="password-e" type="password" class="form-control" name="password-e" autocomplete="off"  required  autofocus>
                </div>
                <div class="form-group has-error col-md-6">
                    <label class="control-label" for="inputError1">Confirma tu contraseña</label>
                    <input id="rpassword-e" autocomplete="off" type="password" class="form-control" name="rpassword-e"  required  autofocus>
                </div>               
            </form>
         </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Close</button>
        <button id="btn_guardarcambio" name="btn_guardarcambio" type="button" class="btn btn-primary">Save changes</button>
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
    $("#menuusuario").addClass("important active");
  })

 $(document).on("click", "#btneditar", function () {
    //alert("accediendo a la edicion..."+$(this).attr('data-id'));
    var id_usuario = $(this).attr('data-id');
     // alert(id_usuario);
    $.ajax({
           url:"/usuarios/"+id_usuario,
           async: false,
           dataType:"json",
           success:function(html){                
              $("#id_usuario").val(html.id);
              $("#nombre-e").val(html.name);
              $("#email-e").val(html.email);
              $("#tipo_usuario-e option[value='"+ html.tipo_usuario +"']").attr("selected",true);              
           }
        })
  });

  $('#btn_guardarcambio').click(function() {    
    var id_usuario      = $("#id_usuario").val();
    var nombre          = $('#nombre-e').val();
    var email           = $('#email-e').val();    
    var password        = String($('#password-e').val());
    var tipo_usuario    = $('#tipo_usuario-e').val();
    var rpassword       = String($('#rpassword-e').val());
    
    if (nombre.length == 0 || email.length == 0 ) {
      alert("No pueden haber campos vacios.");
      return false;
    }    
    //alert(password);
    if(password !== rpassword){
      alert("la contraseña no coincide");
      return false;
    }else{
      alert("son iguales");
    }

    if(password=='' && rpassword == ''){
      password = 'ninguno';
    }
      $.ajax({
         url:"/usuarios/edicion/"+id_usuario+"/"+nombre+"/"+email+"/"+password+"/"+tipo_usuario,
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
    var email           = $('#email').val();    
    var password        = $('#password').val();
    var tipo_usuario    = $('#tipo_usuario').val();

      $.ajax({
          url: "/usuarios",
          type: "POST",
          data: {
              _token: $("#csrf").val(),
              type: 1,
              nombre:         nombre,
              email:          email,
              password:       password,
              tipo_usuario:   tipo_usuario
          },
          cache: false,
          success: function(dataResult){
            alert(dataResult);     
            $("#formmodal")[0].reset();
            $('#modal-agregar').modal('toggle');
            location.reload();          
          }
      });    
  });

  $(document).on("click", "#btn-eliminar", function () {
    var id_usuario = $(this).attr('data-id');
    if (confirm("Desea eliminar el registro!"+id_usuario) == true) {
      $.ajax({
            type: "get",
            url: "{{ url('usuarios/delete') }}"+'/'+ id_usuario,
            success: function (data) {
              alert(data.data);
              location.reload();
            }
        });
    }
  });

</script>
@endsection