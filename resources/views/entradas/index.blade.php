@extends('layouts.app')
@section('contenidoprincipal')

<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header">
        <div class="col-sm-2">
          <a href="/entradas/nueva" class="btn btn-success">
            <i class="fa fa-plus"></i> Registrar entrada
          </a>
        </div>
        <label class="col-sm-1 control-label">Filtrar por fecha</label>
        <div class="col-sm-2">
          <input type="date" class="form-control" id="fecha1" value="{{ $date }}">
        </div>
        <div class="col-sm-2">
          <input type="date" class="form-control" id="fecha2" value="{{ $date }}">
        </div>
        <div class="col-sm-2">
          <input type="button" class="form-control btn-primary" id="btnfiltrofecha" value="Filtrar">
        </div>
      </div>
      <div class="box-body">
        <table id="example1" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>Id</th>
              <th>No. de Factura</th>
              <th>Proveedor</th>
              <th>Fecha</th>
              <th>Almacén</th>
              <th>Status</th>
              <th style="width:220px;">Acción</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>

{{-- Modal: Editar entrada --}}
<div class="modal fade" id="modal-default">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        <h4 class="modal-title">Editar entrada</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <input id="id_entrada_e" type="hidden">
          <div class="form-group col-md-6">
            <label>No. de factura</label>
            <input id="nfactura-e" type="text" class="form-control">
          </div>
          <div class="form-group col-md-6">
            <label>Proveedor</label>
            <select id="proveedor-e" class="form-control">
              <option value="">Seleccione un proveedor</option>
              @foreach($proveedores as $prov)
                <option value="{{ $prov->id }}">{{ $prov->nombre }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group col-md-6">
            <label>Fecha de recepción</label>
            <input id="fecha-e" type="date" class="form-control">
          </div>
          <div class="form-group col-md-6">
            <label>Referencia / Orden de compra</label>
            <input id="referencia-e" type="text" class="form-control">
          </div>
          <div class="form-group col-md-6">
            <label>Almacén</label>
            <select id="categoria-e" class="form-control">
              <option value="">Seleccione un almacén</option>
              @foreach($almacenes as $almacen)
                <option value="{{ $almacen->id }}">{{ $almacen->nombre }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group col-md-6">
            <label>Observaciones</label>
            <input id="observaciones-e" type="text" class="form-control">
          </div>
          <div class="col-md-12" id="cajaerror-e"></div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cancelar</button>
        <button id="btn_guardarcambio" type="button" class="btn btn-primary">Guardar cambios</button>
      </div>
    </div>
  </div>
</div>

@endsection
@section("scriptpie")
<script>

  function columnasEntrada() {
    return [
      { data: 'id',              name: 'id' },
      { data: 'nfactura',        name: 'nfactura' },
      { data: 'nombreproveedor', name: 'nombreproveedor' },
      { data: 'fecha',           name: 'fecha' },
      { data: 'nomalmacen',      name: 'nomalmacen' },
      { data: 'status',          name: 'status',
        render: function(d) {
          if (d === 'finalizado') return '<span class="label label-success">Finalizado</span>';
          if (d === 'captura')    return '<span class="label label-warning">En captura</span>';
          if (d === 'cancelado')  return '<span class="label label-danger">Cancelado</span>';
          return d;
        }
      },
      { data: 'id', bSortable: false,
        mRender: function(data, type, row) {
          var btns = '<a href="/entradas/' + row['id'] + '" class="btn btn-info btn-xs"><i class="fa fa-eye"></i> Ver</a> ';
          if (row['status'] === 'finalizado') {
            btns += '<a href="/entradas/reportepdf/' + row['id'] + '" target="_blank">' +
                    '<img src="/images/pdf.png" width="28" height="28" style="vertical-align:middle;"></a>';
          } else if (row['status'] === 'captura') {
            btns += '<button class="btn btn-success btn-xs btneditar" data-id="' + row['id'] + '" ' +
                    'data-toggle="modal" data-target="#modal-default">Editar</button> ' +
                    '<button class="btn btn-danger btn-xs btn-elim-entrada" data-id="' + row['id'] + '">Borrar</button>';
          }
          return btns;
        }
      }
    ];
  }

  var idioma = {
    emptyTable:     "No hay información",
    info:           "Mostrando _START_ a _END_ de _TOTAL_ entradas",
    infoEmpty:      "Mostrando 0 entradas",
    infoFiltered:   "(filtrado de _MAX_ total)",
    thousands:      ",",
    lengthMenu:     "Mostrar _MENU_ entradas",
    loadingRecords: "Cargando...",
    processing:     "Procesando...",
    search:         "Buscar:",
    zeroRecords:    "Sin resultados encontrados"
  };

  $(function() {

    $('#example1').DataTable({
      processing: true, serverSide: true,
      ajax: "/entradas/",
      columns: columnasEntrada(),
      language: idioma,
      fnDrawCallback: function() {
        $("input[type='search']").attr("id", "searchBox");
        $('#searchBox').css("width", "400px").focus();
      },
      order: [[0, 'desc']]
    });

    $("#menuentradas").addClass("important active");

    // ── Filtrar por fecha ──────────────────────────────────────────────
    $('#btnfiltrofecha').click(function() {
      var fecha1 = $('#fecha1').val();
      var fecha2 = $('#fecha2').val();
      $('#example1').DataTable().clear().destroy();
      $('#example1').DataTable({
        processing: true, serverSide: true,
        ajax: "/entradas/filtrofecha/" + fecha1 + '/' + fecha2,
        columns: columnasEntrada(),
        language: idioma,
        fnDrawCallback: function() {
          $("input[type='search']").attr("id", "searchBox");
          $('#searchBox').css("width", "400px").focus();
        }
      });
    });

    // ── Cargar datos al editar ─────────────────────────────────────────
    $(document).on('click', '.btneditar', function() {
      var id = $(this).data('id');
      $('#cajaerror-e').html('');
      $.ajax({
        url: "/entradas/" + id, dataType: "json",
        success: function(e) {
          $('#id_entrada_e').val(id);
          $('#nfactura-e').val(e.nfactura);
          $('#fecha-e').val(e.fecha);
          $('#referencia-e').val(e.referencia);
          $('#observaciones-e').val(e.observaciones);
          $('#proveedor-e').val(e.proveedor);
          $('#categoria-e').val(e.categoria);
        }
      });
    });

    // ── Guardar edición ────────────────────────────────────────────────
    $('#btn_guardarcambio').click(function() {
      var id            = $('#id_entrada_e').val();
      var nfactura      = $('#nfactura-e').val().trim();
      var proveedor     = $('#proveedor-e').val();
      var fecha         = $('#fecha-e').val();
      var referencia    = $('#referencia-e').val();
      var categoria     = $('#categoria-e').val();
      var observaciones = $('#observaciones-e').val();

      if (!nfactura)  { $('#cajaerror-e').html('<div class="alert alert-warning">Ingrese el No. de factura.</div>'); return; }
      if (!proveedor) { $('#cajaerror-e').html('<div class="alert alert-warning">Seleccione un proveedor.</div>'); return; }
      if (!categoria) { $('#cajaerror-e').html('<div class="alert alert-warning">Seleccione un almacén.</div>'); return; }

      $.ajax({
        url: "/entradas/edicion/" + id + "/" + proveedor + "/" + fecha + "/" + nfactura + "/" + referencia + "/" + categoria + "/" + observaciones,
        dataType: "json",
        success: function(res) {
          alert(res.data);
          $('#modal-default').modal('hide');
          $('#example1').DataTable().ajax.reload(null, false);
        }
      });
    });

    // ── Eliminar entrada ───────────────────────────────────────────────
    $(document).on('click', '.btn-elim-entrada', function() {
      var id = $(this).data('id');
      if (!confirm("¿Desea eliminar el registro #" + id + "?")) return;
      $.ajax({
        url: "{{ url('entradas/delete') }}/" + id,
        success: function(data) {
          alert(data.data);
          $('#example1').DataTable().ajax.reload(null, false);
        }
      });
    });

  });
</script>
@endsection
