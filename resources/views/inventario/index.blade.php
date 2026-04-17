@extends('layouts.app')
@section('contenidoprincipal')

<div class="row">
  <div class="col-xs-12">
    <div class="box box-primary">

      <div class="box-header with-border">
        <h3 class="box-title">
          <i class="fa fa-cubes"></i>&nbsp; Inventario de artículos
        </h3>
        <div class="box-tools pull-right">
          <a id="btnExportarPDF" href="/inventario/inventariopdf?categoria=todos"
             target="_blank" class="btn btn-danger btn-sm">
            <i class="fa fa-file-pdf-o"></i> Exportar PDF
          </a>
        </div>
      </div>

      <div class="box-body">

        {{-- Filtros --}}
        <div class="row" style="margin-bottom:14px;">
          <div class="col-md-4 col-sm-6" style="margin-bottom:6px;">
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-filter"></i></span>
              <select id="categoria" name="categoria" class="form-control">
                <option value="todos">Todos los almacenes</option>
                @foreach($categoriaproductos as $categoriaproducto)
                  <option value="{{ $categoriaproducto->id }}">{{ $categoriaproducto->nombre }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-4 col-sm-6" style="margin-bottom:6px;">
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-search"></i></span>
              <input type="text" id="buscar" class="form-control"
                     placeholder="Buscar descripción o talla...">
              <span class="input-group-btn">
                <button id="btnLimpiarBuscar" class="btn btn-default" type="button"
                        style="display:none;" title="Limpiar búsqueda">
                  <i class="fa fa-times"></i>
                </button>
              </span>
            </div>
          </div>
        </div>

        <table id="example2" class="table table-bordered table-striped table-hover">
          <thead>
            <tr>
              <th>Descripción</th>
              <th style="width:80px;">Talla</th>
              <th style="width:110px;">Precio</th>
              <th style="width:80px; text-align:center;">Stock</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>

      </div>{{-- /.box-body --}}
    </div>{{-- /.box --}}
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

  var dt;
  var categoriaActual = 'todos';
  var buscarActual    = '';
  var buscarTimer;

  function getAjaxUrl() {
    var url = '/inventario/' + categoriaActual;
    if (buscarActual) url += '?buscar=' + encodeURIComponent(buscarActual);
    return url;
  }

  function actualizarPDFBtn() {
    var url = '/inventario/inventariopdf?categoria=' + categoriaActual;
    if (buscarActual) url += '&buscar=' + encodeURIComponent(buscarActual);
    $('#btnExportarPDF').attr('href', url);
  }

  $(function () {
    dt = $('#example2').DataTable({
      processing: true,
      serverSide: true,
      ajax: getAjaxUrl(),
      columns: [
        { data: 'descripcion', name: 'descripcion' },
        { data: 'talla', name: 'talla',
          render: function(data) {
            return '<span style="display:block;text-align:center;">' + (data || '—') + '</span>';
          }
        },
        { data: 'precio', name: 'precio',
          render: function(data) {
            return '$ ' + parseFloat(data).toFixed(2);
          }
        },
        { data: 'stock', name: 'stock',
          render: function(data) {
            var n = parseInt(data);
            var cls = n <= 0 ? 'danger' : (n <= 5 ? 'warning' : 'success');
            return '<span class="label label-' + cls + '" style="display:block;text-align:center;">' + n + '</span>';
          },
          orderable: false
        }
      ],
      language: {
        "emptyTable":    "No hay información",
        "info":          "Mostrando _START_ a _END_ de _TOTAL_ registros",
        "infoEmpty":     "Mostrando 0 registros",
        "infoFiltered":  "(filtrado de _MAX_ total)",
        "lengthMenu":    "Mostrar _MENU_ registros",
        "loadingRecords":"Cargando...",
        "processing":    "Procesando...",
        "zeroRecords":   "Sin resultados encontrados",
        "thousands":     ","
      },
      dom: 'Brtip',
      buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
      order: [[0, 'asc']],
      columnDefs: [
        { type: 'talla', targets: 1 },
        { orderable: false, targets: 3 }
      ]
    });

    $("#menuinventario").addClass("important active");

    // ── Filtro por almacén ──
    $('#categoria').change(function() {
      categoriaActual = $(this).val();
      dt.ajax.url(getAjaxUrl()).load();
      actualizarPDFBtn();
    });

    // ── Búsqueda por texto (debounce 400ms) ──
    $('#buscar').on('input', function() {
      var val = $(this).val().trim();
      $('#btnLimpiarBuscar').toggle(val.length > 0);
      clearTimeout(buscarTimer);
      buscarTimer = setTimeout(function() {
        buscarActual = val;
        dt.ajax.url(getAjaxUrl()).load();
        actualizarPDFBtn();
      }, 400);
    });

    // ── Botón limpiar búsqueda ──
    $('#btnLimpiarBuscar').click(function() {
      $('#buscar').val('').trigger('input');
    });
  });
</script>
@endsection
