@extends('layouts.app')
@section('contenidoprincipal')

<div class="row">
  <div class="col-xs-12">
    <div class="box box-primary">

      <div class="box-header with-border">
        <h3 class="box-title">
          <i class="fa fa-tags"></i>&nbsp; Catálogo de artículos
        </h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-success btn-sm"
                  data-toggle="modal" data-target="#modal-agregar">
            <i class="fa fa-plus"></i> Agregar artículo
          </button>
        </div>
      </div>

      <div class="box-body">

        {{-- Filtro por almacén --}}
        <div class="row" style="margin-bottom:14px;">
          <div class="col-md-4 col-sm-6">
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-filter"></i></span>
              <select id="filtroAlmacen" class="form-control">
                <option value="">Todos los almacenes</option>
                @foreach($categoriaproductos as $cat)
                  <option value="{{ $cat->nombre }}">{{ $cat->nombre }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>

        <table id="example1" class="table table-bordered table-striped table-hover">
          <thead>
            <tr>
              <th style="width:40px;">#</th>
              <th>Descripción</th>
              <th style="width:70px;">Talla</th>
              <th>Almacén</th>
              <th style="width:100px;">Precio venta</th>
              <th style="width:110px;">Precio mayoreo</th>
              <th style="width:80px; text-align:center;">Status</th>
              <th style="width:80px; text-align:center;">Acción</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($productos as $producto)
            <tr>
              <td>{{ $producto->id }}</td>
              <td>{{ $producto->nombre }}</td>
              <td>{{ $producto->talla }}</td>
              <td>{{ $producto->categoriaproducto }}</td>
              <td>$ {{ number_format($producto->precio, 2) }}</td>
              <td>$ {{ number_format($producto->precioPromocion, 2) }}</td>
              <td style="text-align:center;">
                @if($producto->status == 'activo')
                  <span class="label label-success">Activo</span>
                @else
                  <span class="label label-danger">Inactivo</span>
                @endif
              </td>
              <td style="text-align:center;">
                <button type="button" class="btn btn-primary btn-xs"
                        id="btneditar" data-id="{{ $producto->id }}"
                        data-toggle="modal" data-target="#modal-default"
                        title="Editar">
                  <i class="fa fa-pencil"></i>
                </button>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>

      </div>{{-- /.box-body --}}
    </div>{{-- /.box --}}
  </div>
</div>


{{-- ===================== MODAL: AGREGAR ===================== --}}
<div class="modal fade" id="modal-agregar">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header" style="background-color:#00a65a; color:#fff;">
        <button type="button" class="close" data-dismiss="modal" style="color:#fff; opacity:1;">
          <span>&times;</span>
        </button>
        <h4 class="modal-title"><i class="fa fa-plus-circle"></i>&nbsp; Nuevo artículo</h4>
      </div>

      <div class="modal-body">
        <form id="formmodal">
          <input type="hidden" name="_token" id="csrf" value="{{ Session::token() }}">
          <input type="hidden" name="status" id="status" value="activo">

          <div class="form-group col-md-12">
            <label>Descripción</label>
            <input id="nombre" type="text" class="form-control" name="nombre" required autofocus
                   placeholder="Nombre del artículo">
          </div>
          <div class="form-group col-md-6">
            <label>Talla</label>
            <input type="text" name="talla" id="talla" class="form-control" value="0" placeholder="Ej: M, XL, 30">
          </div>
          <div class="form-group col-md-6">
            <label>Clave</label>
            <input type="text" name="claveproducto" id="claveproducto" class="form-control" value="NA">
          </div>
          <div class="form-group col-md-12">
            <label>Almacén</label>
            <select id="categoria" name="categoria" class="form-control">
              @foreach($categoriaproductos as $categoriaproducto)
                <option value="{{ $categoriaproducto->id }}">{{ $categoriaproducto->nombre }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group col-md-6">
            <label>Precio venta</label>
            <div class="input-group">
              <span class="input-group-addon">$</span>
              <input id="precio" type="text" class="form-control" name="precio" value="0">
            </div>
          </div>
          <div class="form-group col-md-6">
            <label>Precio mayoreo</label>
            <div class="input-group">
              <span class="input-group-addon">$</span>
              <input id="precioPromocion" type="text" class="form-control" name="precioPromocion" value="0">
            </div>
          </div>
          <div class="col-md-12" id="cajaerror"></div>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
          <i class="fa fa-times"></i> Cancelar
        </button>
        <button id="btn_guardaregistro" type="button" class="btn btn-success">
          <i class="fa fa-save"></i> Guardar
        </button>
      </div>

    </div>
  </div>
</div>


{{-- ===================== MODAL: EDITAR ===================== --}}
<div class="modal fade" id="modal-default">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header" style="background-color:#3c8dbc; color:#fff;">
        <button type="button" class="close" data-dismiss="modal" style="color:#fff; opacity:1;">
          <span>&times;</span>
        </button>
        <h4 class="modal-title"><i class="fa fa-pencil-square-o"></i>&nbsp; Editar artículo</h4>
      </div>

      <div class="modal-body">
        <form id="formmodal-e">
          <input id="id_producto" type="hidden" name="id_producto">

          <div class="form-group col-md-12">
            <label>Descripción</label>
            <input id="nombre-e" type="text" class="form-control" name="nombre-e" required autofocus>
          </div>
          <div class="form-group col-md-6">
            <label>Talla</label>
            <input id="talla-e" name="talla-e" type="text" class="form-control" placeholder="Ej: M, XL, 30">
          </div>
          <div class="form-group col-md-6">
            <label>Clave de producto</label>
            <input id="claveproducto-e" name="claveproducto-e" type="text" class="form-control">
          </div>
          <div class="form-group col-md-12">
            <label>Almacén</label>
            <select id="categoria-e" name="categoria-e" class="form-control">
              @foreach($categoriaproductos as $categoriaproducto)
                <option value="{{ $categoriaproducto->id }}">{{ $categoriaproducto->nombre }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group col-md-6">
            <label>Precio venta</label>
            <div class="input-group">
              <span class="input-group-addon">$</span>
              <input id="precio-e" type="text" class="form-control" name="precio-e">
            </div>
          </div>
          <div class="form-group col-md-6">
            <label>Precio mayoreo</label>
            <div class="input-group">
              <span class="input-group-addon">$</span>
              <input id="precioPromocion-e" type="text" class="form-control" name="precioPromocion-e">
            </div>
          </div>
          <div class="form-group col-md-6">
            <label>Status</label>
            <select id="status-e" name="status-e" class="form-control">
              <option value="activo">Activo</option>
              <option value="inactivo">Inactivo</option>
            </select>
          </div>
          <div class="col-md-12" id="cajaerror-e"></div>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
          <i class="fa fa-times"></i> Cancelar
        </button>
        <button id="btn_guardarcambio" type="button" class="btn btn-primary">
          <i class="fa fa-save"></i> Guardar cambios
        </button>
      </div>

    </div>
  </div>
</div>

@endsection
@section("scriptpie")
<script>
  // ── Ordenamiento personalizado para tallas ──
  var tallasRopa = ['XS','S','M','L','XL','XXL','XXXL','XXXXL'];

  function tallaANumero(talla) {
    if (!talla || talla === '0' || talla.trim() === '' ||
        talla.trim().toUpperCase() === 'NA' || talla.trim().toUpperCase() === 'N/A') {
      return -1;
    }
    var t = talla.trim().toUpperCase();
    var idx = tallasRopa.indexOf(t);
    if (idx !== -1) return 1000 + idx;
    var n = parseFloat(t);
    if (!isNaN(n)) return n;
    return 999;
  }

  $.fn.dataTable.ext.type.order['talla-pre'] = function(d) {
    return tallaANumero(d);
  };

  $(function () {
    var table = $('#example1').DataTable({
      language: {
        "emptyTable":    "No hay información",
        "info":          "Mostrando _START_ a _END_ de _TOTAL_ registros",
        "infoEmpty":     "Mostrando 0 registros",
        "infoFiltered":  "(filtrado de _MAX_ total)",
        "lengthMenu":    "Mostrar _MENU_ registros",
        "loadingRecords":"Cargando...",
        "processing":    "Procesando...",
        "search":        "Buscar:",
        "zeroRecords":   "Sin resultados encontrados",
        "thousands":     ","
      },
      "search": { "addClass": 'form-control' },
      "fnDrawCallback": function(){
        $("input[type='search']").attr("id", "searchBox");
        $('#searchBox').css("width", "300px").focus();
      },
      dom: 'Bfrtip',
      buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
      order: [[1, 'asc'], [2, 'asc']],
      columnDefs: [
        { type: 'talla', targets: 2 },
        { orderable: false, targets: 7 }
      ]
    });

    $("#menuproductos").addClass("important active");

    // Filtro por almacén (columna 3)
    $('#filtroAlmacen').on('change', function () {
      var val = $.fn.dataTable.util.escapeRegex($(this).val());
      table.column(3).search(val ? '^' + val + '$' : '', true, false).draw();
    });
  });

  // ── Cargar datos al abrir modal editar ──
  $(document).on("click", "#btneditar", function () {
    var id_producto = $(this).attr('data-id');
    $.ajax({
      url: "/productos/" + id_producto,
      async: false,
      dataType: "json",
      success: function(html){
        $("#id_producto").val(html.id);
        $("#nombre-e").val(html.nombre);
        $("#talla-e").val(html.talla);
        $("#claveproducto-e").val(html.claveproducto);
        $("#categoria-e").val(html.categoria);
        $("#precio-e").val(html.precio);
        $("#precioPromocion-e").val(html.precioPromocion);
        $("#status-e").val(html.status);
      }
    });
  });

  // ── Guardar cambios ──
  $('#btn_guardarcambio').click(function() {
    var id_producto     = $("#id_producto").val();
    var nombre          = $("#nombre-e").val();
    var talla           = $("#talla-e").val().trim() || 'null';
    var claveproducto   = $("#claveproducto-e").val();
    var descripcion     = nombre;
    var categoria       = $("#categoria-e").val();
    var precio          = $("#precio-e").val();
    var precioPromocion = $("#precioPromocion-e").val();
    var status          = $("#status-e").val();

    if (!nombre) {
      $("#cajaerror-e").html('<div class="alert alert-warning"><i class="fa fa-warning"></i> Ingrese el nombre.</div>');
      return false;
    }
    if (!precio) {
      $("#cajaerror-e").html('<div class="alert alert-warning"><i class="fa fa-warning"></i> Si no maneja precio, ingrese 0.</div>');
      return false;
    }
    if (!precioPromocion) {
      $("#cajaerror-e").html('<div class="alert alert-warning"><i class="fa fa-warning"></i> Si no maneja precio mayoreo, ingrese 0.</div>');
      return false;
    }
    if (!claveproducto) {
      $("#cajaerror-e").html('<div class="alert alert-warning"><i class="fa fa-warning"></i> Coloque "N/A" si no maneja clave.</div>');
      return false;
    }

    $.ajax({
      url: "/productos/edicion/" + id_producto + "/" + nombre + "/" + talla + "/" + claveproducto + "/" + descripcion + "/" + categoria + "/" + precio + "/" + precioPromocion + "/" + status,
      dataType: "json",
      success: function(){
        $('#modal-default').modal('hide');
        location.reload();
      }
    });
  });

  // ── Agregar producto ──
  $('#btn_guardaregistro').click(function() {
    var nombre          = $('#nombre').val();
    var talla           = $('#talla').val();
    var categoria       = $('#categoria').val();
    var claveproducto   = $('#claveproducto').val();
    var precio          = $('#precio').val();
    var precioPromocion = $('#precioPromocion').val();

    if (!nombre) {
      $("#cajaerror").html('<div class="alert alert-warning"><i class="fa fa-warning"></i> Ingrese el nombre.</div>');
      return false;
    }
    if (!precio) {
      $("#cajaerror").html('<div class="alert alert-warning"><i class="fa fa-warning"></i> Si no maneja precio, ingrese 0.</div>');
      return false;
    }
    if (!precioPromocion) {
      $("#cajaerror").html('<div class="alert alert-warning"><i class="fa fa-warning"></i> Si no maneja precio mayoreo, ingrese 0.</div>');
      return false;
    }

    $.ajax({
      url: "/productos",
      type: "POST",
      data: {
        _token:          $("#csrf").val(),
        nombre:          nombre,
        talla:           talla,
        descripcion:     nombre,
        categoria:       categoria,
        claveproducto:   claveproducto,
        precio:          precio,
        precioPromocion: precioPromocion,
        status:          'activo',
        stock:           0,
        id_usuario:      1
      },
      success: function(result){
        alert(result.data);
        $('#modal-agregar').modal('hide');
        location.reload();
      }
    });
  });
</script>
@endsection
