@extends('layouts.app')
@section('contenidoprincipal')

<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header">
        <div class="col-sm-2">
          <a href="/salidas" class="btn btn-success">
            <i class="fa fa-plus"></i> Registrar venta
          </a>
        </div>
        <label class="col-sm-1 control-label">Buscar por fecha</label>
        <div class="col-sm-2">
          <input type="date" class="form-control" id="fecha1" value="{{ $date }}">
        </div>
        <div class="col-sm-2">
          <input type="date" class="form-control" id="fecha2" value="{{ $date }}">
        </div>
        <div class="col-sm-2">
          <input type="button" class="form-control btn-primary" id="btnfiltrofecha" value="Buscar">
        </div>
        <div class="col-sm-2">
          <input type="button" class="form-control btn-primary" id="btnfiltrofechaPDF" value="Exportar a PDF">
        </div>
      </div>
      <div class="box-body">
        <table id="example1" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Fecha</th>
              <th>Cliente</th>
              <th>Forma de pago</th>
              <th>Total</th>
              <th>Status</th>
              <th style="width:320px;">Acción</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>

{{-- Modal: Editar venta --}}
<div class="modal fade" id="modal-default">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        <h4 class="modal-title">Editar venta</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <input id="id_salida_e" type="hidden">
          <input id="status-e"    type="hidden">
          <div class="form-group col-md-12">
            <label>Cliente</label>
            <input id="solicitante-e" type="text" class="form-control">
          </div>
          <div class="form-group col-md-6">
            <label>Fecha</label>
            <input id="fecha-e" type="date" class="form-control">
          </div>
          <div class="form-group col-md-6">
            <label>Almacén</label>
            <select id="almacen-e" class="form-control">
              <option value="">Seleccione un almacén</option>
              @foreach($almacenes as $almacen)
                <option value="{{ $almacen->id }}">{{ $almacen->nombre }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group col-md-12">
            <label>Observaciones</label>
            <input id="observaciones-e" type="text" class="form-control">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cancelar</button>
        <button id="btn_guardarcambio" type="button" class="btn btn-primary">Guardar cambios</button>
      </div>
    </div>
  </div>
</div>

{{-- Modal: Cancelar venta --}}
<div class="modal fade" id="modal-cancelar">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        <h4 class="modal-title">Cancelar venta</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <input id="id_salida_c" type="hidden">
          <div class="form-group col-md-12">
            <label>Motivo de cancelación</label>
            <input id="motivo-c" type="text" class="form-control" autofocus>
          </div>
          <div class="col-md-12" id="cajaerror-c"></div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
        <button id="btn_cancelarventa" type="button" class="btn btn-danger">Confirmar cancelación</button>
      </div>
    </div>
  </div>
</div>

@endsection
@section("scriptpie")
<script>

  function colsVenta() {
    return [
      { data: 'id',          name: 'id' },
      { data: 'fecha',       name: 'fecha' },
      { data: 'solicitante', name: 'solicitante' },
      { data: null, bSortable: false,
        mRender: function(data, type, row) {
          if (row['formapago'] == '1') return 'Efectivo';
          if (row['formapago'] == '2') return 'T. Débito';
          return 'T. Crédito';
        }
      },
      { data: 'totalpago', name: 'totalpago' },
      { data: 'status', name: 'status',
        render: function(d) {
          if (d === 'finalizado') return '<span style="color:green;font-weight:bold;">Finalizado</span>';
          if (d === 'cancelado')  return '<span style="color:red;font-weight:bold;">Cancelado</span>';
          return '<span style="color:blue;font-weight:bold;">' + d + '</span>';
        }
      },
      { data: null, bSortable: false,
        mRender: function(data, type, row) {
          var id = row['id'];
          var ver = '<a href="/salidas/showventauniforme/' + id + '" class="btn btn-info btn-xs"><i class="fa fa-eye"></i> Ver</a> ';
          var pdf = '<a href="/salidas/ventapdf/' + id + '" target="_blank"><img src="/images/pdf.png" width="28" height="28" style="vertical-align:middle;"></a> ';
          if (row['status'] === 'finalizado') {
            return ver + pdf +
              '<button class="btn btn-warning btn-xs btn-cancelar-venta" data-id="' + id + '" ' +
              'data-toggle="modal" data-target="#modal-cancelar">Cancelar</button>';
          }
          if (row['status'] === 'cancelado') {
            return ver + pdf;
          }
          // captura
          return ver +
            '<button class="btn btn-success btn-xs btneditar" data-id="' + id + '" ' +
            'data-toggle="modal" data-target="#modal-default">Editar</button> ' +
            '<button class="btn btn-danger btn-xs btn-elim-venta" data-id="' + id + '">Borrar</button>';
        }
      }
    ];
  }

  var idioma = {
    emptyTable: "No hay información", info: "Mostrando _START_ a _END_ de _TOTAL_ ventas",
    infoEmpty: "Mostrando 0 ventas", infoFiltered: "(filtrado de _MAX_ total)",
    thousands: ",", lengthMenu: "Mostrar _MENU_ ventas",
    loadingRecords: "Cargando...", processing: "Procesando...",
    search: "Buscar:", zeroRecords: "Sin resultados encontrados"
  };

  $(function() {

    $('#example1').DataTable({
      processing: true, serverSide: true,
      ajax: "/salidas/ventaxalmacen/1",
      columns: colsVenta(),
      language: idioma,
      fnDrawCallback: function() {
        $("input[type='search']").attr("id", "searchBox");
        $('#searchBox').css("width", "400px").focus();
      },
      order: [[0, 'desc']]
    });

    $("#menuventauniforme").addClass("important active");

    // ── Filtrar por fecha ──────────────────────────────────────────────
    $('#btnfiltrofecha').click(function() {
      var f1 = $('#fecha1').val(), f2 = $('#fecha2').val();
      $('#example1').DataTable().clear().destroy();
      $('#example1').DataTable({
        processing: true, serverSide: true,
        ajax: "/salidas/filtroalmacenfecha/1/" + f1 + '/' + f2,
        columns: colsVenta(), language: idioma,
        fnDrawCallback: function() {
          $("input[type='search']").attr("id", "searchBox");
          $('#searchBox').css("width", "400px").focus();
        },
        order: [[0, 'desc']]
      });
    });

    // ── Exportar PDF ───────────────────────────────────────────────────
    $('#btnfiltrofechaPDF').click(function() {
      var f1 = $('#fecha1').val(), f2 = $('#fecha2').val();
      window.open('/salidas/salidaxfechaPDF/' + f1 + '/' + f2, '_blank');
    });

    // ── Cargar datos edición ───────────────────────────────────────────
    $(document).on('click', '.btneditar', function() {
      var id = $(this).data('id');
      $.ajax({
        url: "/salidas/" + id, dataType: "json",
        success: function(s) {
          $('#id_salida_e').val(id);
          $('#status-e').val(s.status);
          $('#solicitante-e').val(s.solicitante);
          $('#fecha-e').val(s.fecha);
          $('#almacen-e').val(s.almacen);
          $('#observaciones-e').val(s.observaciones);
        }
      });
    });

    // ── Guardar edición ────────────────────────────────────────────────
    $('#btn_guardarcambio').click(function() {
      var id            = $('#id_salida_e').val();
      var solicitante   = $('#solicitante-e').val().trim();
      var fecha         = $('#fecha-e').val();
      var almacen       = $('#almacen-e').val();
      var status        = $('#status-e').val();
      var observaciones = $('#observaciones-e').val();

      if (!solicitante) { alert("Ingrese el nombre del cliente."); return; }

      $.ajax({
        url: "/salidas/edicion/" + id + "/NA/" + solicitante + "/" + fecha + "/" + almacen + "/na/na/na/" + status + "/" + observaciones + "/{{ Auth::user()->id }}",
        dataType: "json",
        success: function() {
          $('#modal-default').modal('hide');
          $('#example1').DataTable().ajax.reload(null, false);
        }
      });
    });

    // ── Cargar id al abrir cancelación ────────────────────────────────
    $(document).on('click', '.btn-cancelar-venta', function() {
      $('#id_salida_c').val($(this).data('id'));
      $('#motivo-c').val('');
      $('#cajaerror-c').html('');
    });

    // ── Confirmar cancelación ──────────────────────────────────────────
    $('#btn_cancelarventa').click(function() {
      var id     = $('#id_salida_c').val();
      var motivo = $('#motivo-c').val().trim();
      if (!motivo) {
        $('#cajaerror-c').html('<div class="alert alert-warning">Ingrese el motivo de cancelación.</div>');
        return;
      }
      if (!confirm("¿Desea cancelar la venta #" + id + "?")) return;
      $.ajax({
        url: "{{ url('salidas/cancelar') }}/" + id + "/" + motivo,
        success: function(data) {
          alert(data.data);
          $('#modal-cancelar').modal('hide');
          $('#example1').DataTable().ajax.reload(null, false);

        }
      });
    });

    // ── Eliminar venta ─────────────────────────────────────────────────
    $(document).on('click', '.btn-elim-venta', function() {
      var id = $(this).data('id');
      if (!confirm("¿Desea eliminar la venta #" + id + "?")) return;
      $.ajax({
        url: "{{ url('salidas/delete') }}/" + id,
        success: function(data) {
          alert(data.data);
          $('#example1').DataTable().ajax.reload(null, false);
        }
      });
    });

  });
</script>
@endsection
