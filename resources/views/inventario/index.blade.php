@extends('layouts.app') 
@section('contenidoprincipal') 

   <div class="row">
    <div class="col-xs-12">
      <!-- /.box-header -->
      <div class="box">
          <div class="box-header" style="background-color: lightsteelblue !important;">            
            <div class="form-group">
              <label for="inputEmail3" class="col-sm-2 control-label">Filtrar por almacén</label>
              <div class="col-sm-4">
                <select id="categoria" name="categoria" class="form-control">
                  <option value="todos">Todos</option>
                    @foreach($categoriaproductos as $categoriaproducto)
                      <option value="{{$categoriaproducto->id}}">{{$categoriaproducto->nombre}}</option>
                    @endforeach
                </select>
              </div>              
            </div>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
             <table id="example2" class="table table-bordered table-striped">           
            <thead>                  
              <tr>                                                                     
                <th scope="col" style="width: 30%;">Descripción</th>   
                <th scope="col" style="width: 20%;">Precio</th>                                     
                <th scope="col" style="width: 10%;">Stock</th>                             
                </tr>                
            </thead>       
            <tbody>
              @foreach ($productos as $producto)                        
                <tr>                                                                        
                  <td>{{ $producto->descripcion }}</td>                         
                  <td>$ {{ $producto->precio }}</td>                            
                  <td>{{ $producto->stock }}</td>                       
                </tr>                    
              @endforeach  
            </tbody>         
                       
           </table>                
          <!-- /.box-body -->
        </div>
    </div>
  </div>
</div> 


<!-- /.modal -->

@endsection
@section("scriptpie")
<script>
  $(function () {

    $('#example2').DataTable({
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
    $("#menuinventario").addClass("important active");
  })

 $("#categoria" ).change(function() {  
    var filtro = $(this).val();
    $('#example2').DataTable().clear().destroy();
    $('#example2').DataTable({
      processing: true,
      serverSide: true,
      ajax: "/inventario/"+filtro,
        columns:[
          {
            data: 'descripcion',
            name: 'descripcion'
          },
          {
            data: 'precio',
            name: 'precio'
          },
          {
            data: 'stock',
            name: 'stock'
          }   
        ],
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
    
  });



</script>
@endsection