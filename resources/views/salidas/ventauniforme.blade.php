@extends('layouts.app') 
@section('contenidoprincipal') 
   
   <div class="row">
    <div class="col-xs-12">
      <div class="box">
          <div class="box-header">  
              <div class="col-sm-2">
                <button type="button" class="btn btn-success"  data-toggle="modal" data-target="#modal-agregar"> Nueva venta</button>
              </div>            
              <label for="inputEmail3" class="col-sm-1 control-label">Filtrar por fecha</label>
              <div class="col-sm-2">
                <input type="date" class="form-control" name="fecha1" id="fecha1" value="{{$date}}">
              </div>
              <div class="col-sm-2">
                <input type="date" class="form-control" name="fecha2" id="fecha2" value="{{$date}}">
              </div> 
              <div class="col-sm-2">
                <input type="button" class="form-control btn-primary" name="btnfiltrofecha" id="btnfiltrofecha" value="Filtrar">
              </div> 
              <div class="col-sm-2">
                <input type="button" class="form-control btn-primary" name="btnfiltrofechaPDF" id="btnfiltrofechaPDF" value="Exportar PDF">
              </div> 
          </div>
          <!-- /.box-header -->
          <div class="box-body">
             <table id="example1" class="table table-bordered table-striped">           
            <thead>                  
              <tr>                              
                <th scope="col">#</th>                    
                <th scope="col">Fecha</th>                    
                <th scope="col">Cliente</th>
                <th scope="col">Forma de pago</th>
                <th scope="col">Total</th>                   
                <th scope="col" style="width: 100px;">Status</th>                   
                <th scope="col" style="width: 350px;">Acción</th>                    
                </tr>                
            </thead>                     
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
        <h4 class="modal-title">Venta de uniformes</h4>
      </div>
      <div class="modal-body">
         <div class="row">
           <form id="formmodal">
              <input type="hidden" name="_token" id="csrf" value="{{Session::token()}}">
              <input id="folioreq" type="hidden" class="form-control" name="folioreq"  required  value="NA">
                <div class="form-group has-success col-md-12">
                    <label class="control-label" for="inputSuccess1">Cliente</label>                     
                    <input id="solicitante" type="text" class="form-control" name="solicitante"  required  autofocus value="PUBLICO GENERAL">
                </div>
                <div class="form-group has-success col-md-6">
                    <label class="control-label" for="inputSuccess1">Fecha</label>                     
                    <input id="fecha" type="date" class="form-control" name="fecha"  required  value="{{$date}}">
                </div>                
                <!--<div class="form-group has-warning col-md-4">
                    <label class="control-label" for="inputWarning1">¿Donde pago?</label>
                    <select id="cajapago" name="cajapago" class="form-control">
                      <option value="almacen">Almacen</option>
                      <option value="cajagral">Caja General</option>
                    </select>
                </div>
                <div id="contnnotaventa" class="form-group has-error col-md-4">
                    <label class="control-label" for="inputError1">Nota de venta</label>
                    <input id="nnotaventa" type="text" class="form-control" name="nnotaventa" value="N/A">
                </div>
                <div id="contfventa" class="form-group has-success col-md-4" style="display: none !important;">
                    <label class="control-label" for="inputSuccess1">Folio de pago de cajaa</label>
                    <input id="fventa" type="text" class="form-control" name="fventa"  value="N/A" required  autofocus>
                </div>-->
                <div class="form-group has-warning col-md-6">
                    <label class="control-label" for="inputWarning1">Almacen</label>
                    <select id="almacen" name="almacen" class="form-control">
                        @foreach($almacenes as $almacen)
                          <option value="{{$almacen->id}}">{{$almacen->nombre}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group has-warning col-md-12">
                    <label class="control-label" for="inputWarning1">Observaciones</label>
                    <input id="observaciones" type="text" class="form-control" name="observaciones"  required  autofocus value="Ninguno">
                </div>
            </form>
         </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cancelar</button>
        <button id="btn_guardaregistro" name="btn_guardaregistro" type="button" class="btn btn-primary">Registrar salida</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="modal-agregaralmacen">
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
              <input type="hidden" name="_token" id="csrfalm" value="{{Session::token()}}">
                
                <div class="form-group has-success col-md-4">
                    <label class="control-label" for="inputSuccess1">Folio requerimiento</label>                     
                    <input id="folioreqalm" type="text" class="form-control" name="folioreqalm"  required  autofocus>
                </div>
                <div class="form-group has-success col-md-8">
                    <label class="control-label" for="inputSuccess1">Nombre del solicitante / Departamento</label>                     
                    <input id="solicitantealm" type="text" class="form-control" name="solicitantealm"  required  autofocus>
                </div>
                <div class="form-group has-success col-md-6">
                    <label class="control-label" for="inputSuccess1">Fecha</label>                     
                    <input id="fechaalm" type="date" class="form-control" name="fechaalm"  required  value="{{ $date }}">
                </div>                
                <div class="form-group has-warning col-md-4" style="display: none !important;">
                    <label class="control-label" for="inputWarning1">¿Donde pago?</label>
                    <input type="text" id="cajapagoalm" name="cajapagoalm" value="n/a">
                </div>
                <div id="contnnotaventa" class="form-group has-error col-md-4" style="display: none !important;">
                    <label class="control-label" for="inputError1">Nota de venta</label>
                    <input type="text" id="nnotaventaalm" name="nnotaventaalm" value="n/a">
                </div>
                <div id="contfventa" class="form-group has-success col-md-4" style="display: none !important;">
                    <label class="control-label" for="inputSuccess1">Folio de pago de cajaa</label>
                    <input type="text" id="fventaalm" name="fventaalm" value="n/a">
                </div>
                <div class="form-group has-warning col-md-6">
                    <label class="control-label" for="inputWarning1">Almacen</label>
                    <select id="almacenalm" name="almacenalm" class="form-control">
                        <option value="">Seleccione un almacen</option>
                        @foreach($almacenes as $almacen)
                          <option value="{{$almacen->id}}">{{$almacen->nombre}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group has-warning col-md-12">
                    <label class="control-label" for="inputWarning1">Observaciones</label>
                    <input id="observacionesalm" type="text" class="form-control" name="observacionesalm"  required  autofocus value="ninguno">
                </div>
                <div class="form-group has-error col-md-12" id="cajaerror">
                    
                </div>
            </form>
         </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cancelar</button>
        <button id="btn_guardaregistroalm" name="btn_guardaregistroalm" type="button" class="btn btn-primary">Registrar salida</button>
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
                <input id="id_salida_e" type="hidden" class="form-control" name="id_salida_e">
                <!--<div class="form-group has-success col-md-4">
                    <label class="control-label" for="inputSuccess1">Folio requerimiento</label>                     
                    <input id="folioreq-e" type="text" class="form-control" name="folioreq-e"  required  autofocus>
                </div>-->
                <div class="form-group has-success col-md-12">
                    <label class="control-label" for="inputSuccess1">Cliente</label>                     
                    <input id="solicitante-e" type="text" class="form-control" name="solicitante-e"  required  autofocus>
                </div>
                <div class="form-group has-success col-md-6">
                    <label class="control-label" for="inputSuccess1">Fecha</label>                     
                    <input id="fecha-e" type="date" class="form-control" name="fecha-e"  required  value="{{$date}}">
                </div>                

                <input id="cajapago-e" type="hidden" class="form-control" name="cajapago-e" value="NA" >
                <input id="nnotaventa" type="hidden" class="form-control" name="nnotaventa" value="NA" >
                <input id="fventa" type="hidden" class="form-control" name="fventa"  value="NA" required >
                <input id="status-e" type="hidden" class="form-control" name="status-e"  value="" required >

                <div class="form-group has-warning col-md-6">
                    <label class="control-label" for="inputWarning1">Almacen</label>
                    <select id="almacen-e" name="almacen-e" class="form-control">
                        <option>Seleccione un almacen</option>
                        @foreach($almacenes as $almacen)
                          <option value="{{$almacen->id}}">{{$almacen->nombre}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group has-warning col-md-12">
                    <label class="control-label" for="inputWarning1">Observaciones</label>
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

<!-- /.modal cancelar-->
<div class="modal fade" id="modal-cancelar">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Cancelar</h4>
      </div>
      <div class="modal-body">
         <div class="row">
           <form id="formmodal">
                <input id="id_salida_c" type="hidden" class="form-control" name="id_salida_c">
                <!--<div class="form-group has-success col-md-4">
                    <label class="control-label" for="inputSuccess1">Folio requerimiento</label>                     
                    <input id="folioreq-e" type="text" class="form-control" name="folioreq-e"  required  autofocus>
                </div>-->               
                <div class="form-group has-warning col-md-12">
                    <label class="control-label" for="inputSuccess1">Motivo:</label>
                    <input id="motivo-c" type="text" class="form-control" name="motivo-c"  required  autofocus>
                </div>
                <div class="form-group has-error col-md-12" id="cajaerror-c">
                    
                </div>
            </form>
         </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cancelar</button>
        <button id="btn_cancelarventa" name="btn_cancelarventa" type="button" class="btn btn-primary">Guardar Cambios</button>
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
      processing: true,
      serverSide: true,
      ajax: "/salidas/ventaxalmacen/1",
        columns:[
          {
            data: 'id',
            name: 'id'
          },
          {
            data: 'fecha',
            name: 'fecha'
          },          
          {
            data: 'solicitante',
            name: 'solicitante'
          },
          {
            "data": null,
            "bSortable": false,
            "mRender": function(data, type, value) {
                if(value["formapago"]=='1'){
                  return 'Efectivo';
                }
                else if(value["formapago"]=='2'){
                  return 'T. Debito';
                }
                else{
                  return 'T. de Credito';
                }                
            }
          }, 
          {
            data: 'totalpago',
            name: 'totalpago'
          },        
          {
            data: 'status',
            name: 'status'
          },          
          {
            "data": null,
            "bSortable": false,
            "mRender": function(data, type, value) {
                if(value["status"]=='finalizado'){
                  return '<a href="/salidas/showventauniforme/'+value["id"]+'"><button type="button" id="btn-agregar" name="btn-agregar" data-id="'+value["id"]+'" class="btn btn-info">Ver</button></a> <a href="/salidas/ventapdf/'+value["id"]+'" target="_blank"><img src="/images/pdf.png" width="36" height="36"></a> <a href="#"><button type="button" id="btn-cancelarventa" name="btn-cancelarventa" data-id="'+value["id"]+'" class="btn btn-warning" data-toggle="modal" data-target="#modal-cancelar">Cancelar</button></a>   ';
                }
                else if(value["status"]=='cancelado'){
                  return '<a href="/salidas/showventauniforme/'+value["id"]+'"><button type="button" id="btn-agregar" name="btn-agregar" data-id="'+value["id"]+'" class="btn btn-info">Ver</button></a> <a href="/salidas/ventapdf/'+value["id"]+'" target="_blank"><img src="/images/pdf.png" width="36" height="36"></a>';
                }
                else{
                  return '<a href="/salidas/showventauniforme/'+value["id"]+'"><button type="button" id="btn-agregar" name="btn-agregar" data-id="'+value["id"]+'" class="btn btn-info">Ver</button></a>  <button type="button" class="btn btn-success" id="btneditar"  data-id="'+value["id"]+'" data-toggle="modal" data-target="#modal-default">Editar</button> <button type="button" id="btn-eliminar" name="btn-eliminar" data-id="'+value["id"]+'" class="btn btn-danger">Borrar</button>';
                }                
            }
          }      
        ],
        columnDefs: [{targets: 5,
                    render: function ( data, type, row ) {
                      var color = 'green';
                      if (data == 'captura') {
                        color = 'blue';
                      } 
                      if (data == 'cancelado') {
                        color = 'red';
                      } 
                      return '<span style="color:' + color + '"><b>' + data + '</b></span>';
                    }
               }],
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
      order: [[0, 'desc']]
    })
     $("#menuventauniforme").addClass("important active");
  })

 $(document).on("click", "#btneditar", function () {
    //alert("accediendo a la edicion..."+$(this).attr('data-id'));
    var id_salida = $(this).attr('data-id');    
    $.ajax({
           url:"/salidas/"+id_salida,
           async: false,
           dataType:"json",
           success:function(html){   
              $('#id_salida_e').val(id_salida)
              $("#folioreq-e").val(html.folioreq);
              $("#status-e").val(html.status);              
              $("#solicitante-e").val(html.solicitante);
              $("#fecha-e").val(html.fecha);
              $("#almacen-e option[value='"+ html.almacen +"']").attr("selected",true);
              $("#observaciones-e").val(html.observaciones);             
           }
        })
  });

  $('#btn_guardarcambio').click(function() {   
    var id_salida      = $('#id_salida_e').val();
    var folioreq      = $('#folioreq-e').val();
    var solicitante   = $('#solicitante-e').val();    
    var fecha         = $('#fecha-e').val();
    var almacen       = $('#almacen-e').val();
    var cajapago      = 'na';
    var nnotaventa    = 'na';
    var fventa        = 'na';
    var status        = $('#status-e').val();
    var observaciones = $('#observaciones-e').val();
    var id_usuario    = {{ Auth::user()->id }};

      $.ajax({
         url:"/salidas/edicion/"+id_salida+"/"+folioreq+"/"+solicitante+"/"+fecha+"/"+almacen+"/"+cajapago+"/"+nnotaventa+"/"+fventa+"/"+status+"/"+observaciones+"/"+id_usuario,
         dataType:"json",
         success:function(html){
          location.reload();
         }
      })
    }); 

//Agregar producto
  $('#btn_guardaregistro').click(function() {    
    
    var folioreq      = $('#folioreq').val();
    var solicitante   = $('#solicitante').val();    
    var fecha         = $('#fecha').val();
    var almacen       = $('#almacen').val();
    var cajapago      = $('#cajapago').val();
    var nnotaventa    = $('#nnotaventa').val();
    var fventa        = $('#fventa').val();
    var observaciones = $('#observaciones').val();
    var status        = 'captura';
    var id_usuario    = {{ Auth::user()->id }};
    //alert(fecha);
      $.ajax({
          url: "/salidas",
          type: "POST",
          data: {
              _token: $("#csrf").val(),
              type: 1,
              folioreq:     folioreq,
              solicitante:  solicitante,
              fecha:        fecha,            
              almacen:      almacen,
              cajapago:     cajapago,
              nnotaventa:   nnotaventa,
              fventa:       fventa,
              observaciones:observaciones,
              status:       status,
              id_usuario:   id_usuario
          },
          cache: false,
          success: function(dataResult){
            window.location.href = '/salidas/showventauniforme/'+dataResult.data;             
          }
      });   
  });

  $('#btn_guardaregistroalm').click(function() {    
    
    var folioreq      = $('#folioreqalm').val();
    var solicitante   = $('#solicitantealm').val();    
    var fecha         = $('#fechaalm').val();
    var almacen       = $('#almacenalm').val();
    var cajapago      = $('#cajapagoalm').val();
    var nnotaventa    = $('#nnotaventaalm').val();
    var fventa        = $('#fventaalm').val();
    var observaciones = $('#observacionesalm').val();
    var status        = 'captura';
    var id_usuario    = {{ Auth::user()->id }};

    if (folioreq == '' || folioreq.length == 0 ) {
      document.getElementById("folioreqalm").focus();
      document.getElementById("cajaerror").innerHTML = '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-warning"></i> Alerta!</h4>Ingrese el No. de requisicion.</div>';
      return false;
    } 
    if (solicitante == '' || solicitante.length == 0 ) {
      document.getElementById("solicitantealm").focus();
      document.getElementById("cajaerror").innerHTML = '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-warning"></i> Alerta!</h4>Ingrese el nombre del solicitantre</div>';
      return false;
    }
    if (almacen == '' || almacen.length == 0 ) {
      document.getElementById("almacenalm").focus();
      document.getElementById("cajaerror").innerHTML = '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-warning"></i> Alerta!</h4>Seleccione un almacén.</div>';
      return false;
    } 

//alert("ok");
      $.ajax({
          url: "/salidas",
          type: "POST",
          data: {
              _token: $("#csrfalm").val(),
              type: 1,
              folioreq:     folioreq,
              solicitante:  solicitante,
              fecha:        fecha,            
              almacen:      almacen,
              cajapago:     cajapago,
              nnotaventa:   nnotaventa,
              fventa:       fventa,
              observaciones:observaciones,
              status:       status,
              id_usuario:   id_usuario
          },
          cache: false,
          success: function(dataResult){
            //alert(data.data);
            window.location.href = '/salidas/'+dataResult.data;             
          }
      });   
  });



  $(document).on("click", "#btn-eliminar", function () {
    var id_entrada = $(this).attr('data-id');
    if (confirm("Desea eliminar el registro? "+id_entrada) == true) {
      $.ajax({
            type: "get",
            url: "{{ url('salidas/delete') }}"+'/'+ id_entrada,
            success: function (data) {
              //alert(data.data);
              location.reload();
            }
        });
    }else{
      alert("cancelado");  
    }
  });
  $(document).on("click", "#btn-cancelarventa", function () {
    //alert("accediendo a la edicion..."+$(this).attr('data-id'));
    var id_salida = $(this).attr('data-id');    
    $('#id_salida_c').val(id_salida);
            
  });
  $(document).on("click", "#btn_cancelarventa", function () {
    var id_salida = $('#id_salida_c').val();
    var motivo    = $('#motivo-c').val();
    if (motivo == '' || motivo.length == 0 ) {
      document.getElementById("motivo-c").focus();
      document.getElementById("cajaerror-c").innerHTML = '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-warning"></i> Alerta!</h4>Ingrese el motivo de cancelacion.</div>';
      return false;
    }
    //alert(id_salida);
    if (confirm("Desea cancelar la venta ? Folio: "+id_salida) == true) {
      
      $.ajax({
            type: "get",
            url: "{{ url('salidas/cancelar') }}"+'/'+id_salida+'/'+motivo,
            success: function (data) {
              alert(data.data);
              window.location.href = '/salidas/ventauniforme/';             
            }
        });
    }
  });

  $("#cajapago" ).change(function() {  
      var cajapago       = $('#cajapago').val();
      if(cajapago=='cajagral'){
        $('#contfventa').show();
        $('#contnnotaventa').hide();
      }else{
        $('#contfventa').hide();
        $('#contnnotaventa').show();
      }
      
      //almacen
    });

    $(document).on("click", "#btnfiltrofechaPDF", function () {
      var fecha1       = $('#fecha1').val();
      var fecha2       = $('#fecha2').val();      
      window.open(
                  '/salidas/salidaxfechaPDF/'+fecha1+'/'+fecha2,
                  '_blank' 
                );

    });
  $(document).on("click", "#btnfiltrofecha", function () {
    var fecha1       = $('#fecha1').val();
    var fecha2       = $('#fecha2').val();
  //  alert(fecha1);
    //alert(fecha2);
    $('#example1').DataTable().clear().destroy();
    $('#example1').DataTable({
      processing: true,
      serverSide: true,
      ajax: "/salidas/filtroalmacenfecha/1/"+fecha1+'/'+fecha2,
        columns:[
          {
            data: 'id',
            name: 'id'
          },
          {
            data: 'fecha',
            name: 'fecha'
          },          
          {
            data: 'solicitante',
            name: 'solicitante'
          },
          {
            "data": null,
            "bSortable": false,
            "mRender": function(data, type, value) {
                if(value["formapago"]=='1'){
                  return 'Efectivo';
                }
                else if(value["formapago"]=='2'){
                  return 'T. Debito';
                }
                else{
                  return 'T. de Credito';
                }                
            }
          }, 
          {
            data: 'totalpago',
            name: 'totalpago'
          },        
          {
            data: 'status',
            name: 'status'
          },          
          {
            "data": null,
            "bSortable": false,
            "mRender": function(data, type, value) {
                if(value["status"]=='finalizado'){
                  return '<a href="/salidas/showventauniforme/'+value["id"]+'"><button type="button" id="btn-agregar" name="btn-agregar" data-id="'+value["id"]+'" class="btn btn-info">Ver</button></a> <a href="/salidas/ventapdf/'+value["id"]+'" target="_blank"><img src="/images/pdf.png" width="36" height="36"></a> <a href="#"><button type="button" id="btn-cancelarventa" name="btn-cancelarventa" data-id="'+value["id"]+'" class="btn btn-warning" data-toggle="modal" data-target="#modal-cancelar">Cancelar</button></a>   ';
                }
                else if(value["status"]=='cancelado'){
                  return '<a href="/salidas/showventauniforme/'+value["id"]+'"><button type="button" id="btn-agregar" name="btn-agregar" data-id="'+value["id"]+'" class="btn btn-info">Ver</button></a> <a href="/salidas/ventapdf/'+value["id"]+'" target="_blank"><img src="/images/pdf.png" width="36" height="36"></a>';
                }
                else{
                  return '<a href="/salidas/showventauniforme/'+value["id"]+'"><button type="button" id="btn-agregar" name="btn-agregar" data-id="'+value["id"]+'" class="btn btn-info">Ver</button></a>  <button type="button" class="btn btn-success" id="btneditar"  data-id="'+value["id"]+'" data-toggle="modal" data-target="#modal-default">Editar</button> <button type="button" id="btn-eliminar" name="btn-eliminar" data-id="'+value["id"]+'" class="btn btn-danger">Borrar</button>';
                }                
            }
          }      
        ],
        columnDefs: [{targets: 5,
                    render: function ( data, type, row ) {
                      var color = 'green';
                      if (data == 'captura') {
                        color = 'blue';
                      } 
                      if (data == 'cancelado') {
                        color = 'red';
                      } 
                      return '<span style="color:' + color + '"><b>' + data + '</b></span>';
                    }
               }],
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
      order: [[0, 'desc']]
    })
  });
</script>
@endsection