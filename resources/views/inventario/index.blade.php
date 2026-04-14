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
          <a href="/inventario/inventariopdf" target="_blank" class="btn btn-danger btn-sm">
            <i class="fa fa-file-pdf-o"></i> Exportar PDF
          </a>
        </div>
      </div>

      <div class="box-body">

        {{-- Filtro por almacén --}}
        <div class="row" style="margin-bottom:14px;">
          <div class="col-md-4 col-sm-6">
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
          <tbody>
            @foreach ($productos as $producto)
            <tr>
              <td>{{ $producto->descripcion }}</td>
              <td>{{ $producto->talla }}</td>
              <td>$ {{ number_format($producto->precio, 2) }}</td>
              <td style="text-align:center;">
                @if($producto->stock <= 0)
                  <span class="label label-danger">{{ $producto->stock }}</span>
                @elseif($producto->stock <= 5)
                  <span class="label label-warning">{{ $producto->stock }}</span>
                @else
                  <span class="label label-success">{{ $producto->stock }}</span>
                @endif
              </td>
            </tr>
            @endforeach
          </tbody>
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

  var dtConfig = {
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
    "search": { "addClass": "form-control" },
    "fnDrawCallback": function() {
      $("input[type='search']").attr("id", "searchBox");
      $('#searchBox').css("width", "300px").focus();
    },
    dom: 'Bfrtip',
    buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
    order: [[0, 'asc'], [1, 'asc']],
    columnDefs: [
      { type: 'talla', targets: 1 },
      { orderable: false, targets: 3 }
    ]
  };

  $(function () {
    $('#example2').DataTable(dtConfig);
    $("#menuinventario").addClass("important active");
  });

  // ── Filtro server-side por almacén ──
  $("#categoria").change(function() {
    var filtro = $(this).val();
    $('#example2').DataTable().clear().destroy();
    $('#example2').DataTable($.extend(true, {}, dtConfig, {
      processing: true,
      serverSide: true,
      ajax: "/inventario/" + filtro,
      columns: [
        { data: 'descripcion', name: 'descripcion' },
        { data: 'talla',       name: 'talla'       },
        { data: 'precio',      name: 'precio',
          render: function(data) { return '$ ' + parseFloat(data).toFixed(2); }
        },
        { data: 'stock',       name: 'stock',
          render: function(data) {
            var n = parseInt(data);
            var cls = n <= 0 ? 'danger' : (n <= 5 ? 'warning' : 'success');
            return '<span class="label label-' + cls + '">' + n + '</span>';
          }
        }
      ]
    }));
  });
</script>
@endsection
