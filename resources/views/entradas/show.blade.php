@extends('layouts.app')
@section('contenidoprincipal')

{{-- ══════════════════════════════════════════
     ENCABEZADO DE LA ENTRADA
══════════════════════════════════════════ --}}
<div class="box box-primary" style="margin-bottom:10px;">
  <div class="box-header with-border" style="padding:8px 15px;">
    <div style="display:flex; align-items:center; flex-wrap:wrap; gap:4px 20px;">

      <div style="font-size:14px; font-weight:bold; white-space:nowrap;">
        <i class="fa fa-truck"></i>&nbsp; Entrada de artículos
      </div>

      <div style="font-size:13px; color:#555; white-space:nowrap;">
        <i class="fa fa-building-o" style="color:#aaa;"></i>&nbsp;
        <strong>{{ $entrada->nomalmacen }}</strong>
      </div>

      <div style="font-size:13px; color:#555; white-space:nowrap;">
        <i class="fa fa-file-text-o" style="color:#aaa;"></i>&nbsp;
        Factura: <strong>{{ $entrada->nfactura ?: '—' }}</strong>
      </div>

      <div style="font-size:13px; color:#555; white-space:nowrap;">
        <i class="fa fa-user" style="color:#aaa;"></i>&nbsp;
        <strong>{{ $entrada->nombreproveedor ?: '—' }}</strong>
      </div>

      <div style="font-size:13px; color:#555; white-space:nowrap;">
        <i class="fa fa-calendar" style="color:#aaa;"></i>&nbsp;
        {{ \Carbon\Carbon::parse($entrada->fecha)->format('d/m/Y') }}
      </div>

      @if($entrada->referencia)
      <div style="font-size:13px; color:#555; white-space:nowrap;">
        <i class="fa fa-tag" style="color:#aaa;"></i>&nbsp;
        OC: {{ $entrada->referencia }}
      </div>
      @endif

      @if($entrada->observaciones)
      <div style="font-size:13px; color:#555; white-space:nowrap; max-width:220px; overflow:hidden; text-overflow:ellipsis;">
        <i class="fa fa-comment" style="color:#aaa;"></i>&nbsp;
        {{ $entrada->observaciones }}
      </div>
      @endif

      <div style="white-space:nowrap;">
        @if($entrada->status == 'captura')
          <span class="label label-warning"><i class="fa fa-pencil"></i> En captura</span>
        @else
          <span class="label label-success"><i class="fa fa-check"></i> Finalizado</span>
        @endif
      </div>

      <div style="margin-left:auto; white-space:nowrap; display:flex; gap:6px; align-items:center;">
        @if($entrada->status !== 'cancelado')
          <button type="button" class="btn btn-sm btn-info" id="btnEditarEncabezado"
                  title="Editar proveedor, factura y observaciones">
            <i class="fa fa-pencil"></i> Editar
          </button>
        @endif
        @if($entrada->status == 'finalizado')
          <a href="/entradas/reportepdf/{{ $entrada->id }}" target="_blank"
             class="btn btn-danger btn-sm">
            <i class="fa fa-file-pdf-o"></i> Ver PDF
          </a>
          <button type="button" class="btn btn-sm btn-default" id="btnCancelarEntrada"
                  style="border-color:#d9534f; color:#d9534f;">
            <i class="fa fa-ban"></i> Cancelar entrada
          </button>
        @endif
        @if($entrada->status == 'captura')
          <button type="button" class="btn btn-lg btn-warning" id="btnfinalizar"
                  style="font-weight:bold; letter-spacing:.5px; box-shadow:0 2px 6px rgba(0,0,0,.25);">
            <i class="fa fa-flag-checkered"></i> Finalizar captura
          </button>
        @endif
        @if($entrada->status == 'cancelado')
          <span class="label label-danger" style="font-size:13px; padding:5px 10px;">
            <i class="fa fa-ban"></i> Cancelada
          </span>
        @endif
      </div>

    </div>
  </div>
</div>

<div class="row">

  {{-- ══════════════════════════════════════════
       COLUMNA IZQUIERDA — Agregar artículo
  ══════════════════════════════════════════ --}}
  <div class="col-md-4">

    @if($entrada->status == 'captura')
    <div class="box box-success">
      <div class="box-header with-border"
           style="background-color:#00a65a; color:#fff; padding:10px 15px;">
        <h3 class="box-title" style="color:#fff;">
          <i class="fa fa-plus-circle"></i>&nbsp; Agregar artículo
        </h3>
      </div>
      <div class="box-body">
        <input type="hidden" name="_token" id="csrf" value="{{ Session::token() }}">
        <input id="id_entrada" type="hidden" value="{{ $id_entrada }}" name="id_entrada">
        <input type="hidden" id="categoria" name="categoria" value="{{ $entrada->categoria }}">

        <div class="form-group">
          <label><i class="fa fa-search"></i> Artículo</label>
          <select id="id_producto" name="id_producto"
                  class="form-control select2" style="width:100%;">
            <option value="">Seleccione un artículo...</option>
            @foreach($productos as $producto)
              <option value="{{ $producto->id }}">{{ $producto->descripcion }}{{ $producto->talla ? ' - ' . $producto->talla : '' }}</option>
            @endforeach
          </select>
        </div>

        <div class="row">
          <div class="col-xs-6">
            <div class="form-group">
              <label>Cantidad</label>
              <input id="cantidad" type="number" class="form-control input-sm"
                     name="cantidad" min="1" placeholder="1">
            </div>
          </div>
          <div class="col-xs-6">
            <div class="form-group">
              <label>Precio factura</label>
              <div class="input-group input-group-sm">
                <span class="input-group-addon">$</span>
                <input id="precio" type="text" class="form-control"
                       name="precio" placeholder="0.00" value="0">
              </div>
            </div>
          </div>
        </div>

        <button id="btn_guardaregistro" type="button"
                class="btn btn-success btn-block">
          <i class="fa fa-plus"></i>&nbsp; Agregar artículo
        </button>
      </div>
    </div>

    {{-- Botón finalizar al pie del panel izquierdo --}}
    <button type="button" class="btn btn-warning btn-block" id="btnfinalizar2"
            style="font-weight:bold; font-size:15px; letter-spacing:.5px;
                   box-shadow:0 2px 6px rgba(0,0,0,.25); margin-bottom:15px;">
      <i class="fa fa-flag-checkered"></i>&nbsp; Finalizar captura
    </button>
    @endif

  </div>{{-- /col-md-4 --}}

  {{-- ══════════════════════════════════════════
       COLUMNA DERECHA — Detalle de artículos
  ══════════════════════════════════════════ --}}
  <div class="col-md-8">
    <div class="box box-default">
      <div class="box-header with-border" style="padding:10px 15px;">
        <h3 class="box-title">
          <i class="fa fa-list"></i>&nbsp; Artículos de la entrada
        </h3>
      </div>
      <div class="box-body" style="padding:10px;">
        <table id="alumnos_table"
               class="table table-bordered table-striped table-hover"
               style="font-size:13px;">
          <thead>
            <tr>
              <th>Descripción</th>
              <th style="width:80px; text-align:center;">Talla</th>
              <th style="width:80px; text-align:center;">Cantidad</th>
              <th style="width:110px; text-align:right;">Precio</th>
              <th style="width:110px; text-align:right;">Subtotal</th>
              <th style="width:60px; text-align:center;">Acción</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th colspan="3"></th>
              <th style="text-align:right; font-size:12px;">Total:</th>
              <th id="footer-total"></th>
              <th></th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>{{-- /col-md-8 --}}

</div>{{-- /row --}}

{{-- ── Modal: Editar encabezado de entrada ── --}}
<div class="modal fade" id="modalEditarEncabezado" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background:#00c0ef; padding:10px 15px;">
        <h4 class="modal-title" style="color:#fff;">
          <i class="fa fa-pencil"></i> Editar datos de la entrada
        </h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label><i class="fa fa-user"></i> Proveedor</label>
          <input id="edit_proveedor" type="text" class="form-control"
                 value="{{ $entrada->nombreproveedor }}" placeholder="Nombre del proveedor">
        </div>
        <div class="form-group">
          <label><i class="fa fa-file-text-o"></i> Número de factura</label>
          <input id="edit_nfactura" type="text" class="form-control"
                 value="{{ $entrada->nfactura }}" placeholder="Núm. de factura">
        </div>
        <div class="form-group">
          <label><i class="fa fa-comment"></i> Observaciones</label>
          <textarea id="edit_observaciones" class="form-control" rows="3"
                    placeholder="Observaciones...">{{ $entrada->observaciones }}</textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button id="btn-guardar-encabezado" type="button" class="btn btn-info">
          <i class="fa fa-save"></i> Guardar cambios
        </button>
      </div>
    </div>
  </div>
</div>

{{-- ── Modal: Editar precio ── --}}
<div class="modal fade" id="modalEditarPrecio" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background:#f39c12; padding:10px 15px;">
        <h4 class="modal-title" style="color:#fff;">
          <i class="fa fa-pencil"></i> Editar precio
        </h4>
      </div>
      <div class="modal-body">
        <input type="hidden" id="edit_id_entpro">
        <div class="form-group">
          <label>Nuevo precio de factura</label>
          <div class="input-group">
            <span class="input-group-addon">$</span>
            <input id="edit_precio" type="number" step="0.01" min="0"
                   class="form-control" placeholder="0.00">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button id="btn-guardar-precio" type="button" class="btn btn-warning">
          <i class="fa fa-save"></i> Guardar
        </button>
      </div>
    </div>
  </div>
</div>

@endsection
@section("scriptpie")
<script type="text/javascript">

  function number_format(number, decimals, dec_point, thousands_sep) {
    number = parseFloat(number).toFixed(decimals);
    var nstr = number.toString(), x = nstr.split('.');
    var x1 = x[0], x2 = x.length > 1 ? dec_point + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) x1 = x1.replace(rgx, '$1' + thousands_sep + '$2');
    return x1 + x2;
  }

  $(function() {
    $('.select2').select2();

    $('#alumnos_table').DataTable({
      processing: true,
      serverSide: true,
      ajax: "/entradaproductos/listarxentrada/" + {{ $entrada->id }},
      columns: [
        { data: 'descripcion', name: 'descripcion' },
        { data: 'talla',       name: 'talla',
          render: function(data) {
            return '<span style="display:block; text-align:center;">' + (data || '—') + '</span>';
          }
        },
        { data: 'cantidad',    name: 'cantidad',
          render: function(data) {
            return '<span style="display:block; text-align:center;">' + data + '</span>';
          }
        },
        { data: 'precio', name: 'precio',
          render: function(data) {
            return '<span style="display:block; text-align:right;">$' + number_format(parseFloat(data), 2, '.', ',') + '</span>';
          }
        },
        { data: 'subtotal', name: 'subtotal',
          render: function(data) {
            return '<span style="display:block; text-align:right;">$' + number_format(parseFloat(data), 2, '.', ',') + '</span>';
          }
        },
        { data: 'action', name: 'action', orderable: false }
      ],
      searching: false,
      paging: false,
      autoWidth: false,
      language: { processing: 'Cargando...', emptyTable: 'Sin artículos agregados.' },
      "footerCallback": function(row, data, start, end, display) {
        var total = this.api()
          .column(4)
          .data()
          .reduce(function(a, b) { return parseFloat(a) + parseFloat(b); }, 0);

        $('#footer-total').html(
          '<span style="display:block; text-align:right; color:#00a65a; font-size:14pt; font-weight:bold;">$' +
          number_format(total, 2, '.', ',') + '</span>'
        );
      }
    });

    $("#menuentradas").addClass("important active");
  });

  // ── Cargar precio al seleccionar artículo ──
  $("#id_producto").change(function() {
    var id_producto = $(this).val();
    if (!id_producto) { $('#precio').val('0'); return; }
    $.ajax({
      url: "/productos/" + id_producto,
      async: false,
      dataType: "json",
      success: function(data) {
        $("#precio").val(data.precio);
        $("#cantidad").val(1);
        $("#cantidad").focus();
      }
    });
  });

  // ── Agregar artículo ──
  $('#btn_guardaregistro').click(function() {
    var id_entrada  = $('#id_entrada').val();
    var id_producto = $('#id_producto').val();
    var cantidad    = $('#cantidad').val();
    var precio      = $('#precio').val();
    var categoria   = $('#categoria').val();

    if (!id_producto) { alert("Seleccione un artículo."); return false; }
    if (!cantidad || parseInt(cantidad) == 0) { alert("Ingrese una cantidad válida."); return false; }

    var $btn = $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Agregando...');

    $.ajax({
      url: "/entradaproductos",
      type: "POST",
      data: {
        _token:      $("#csrf").val(),
        id_entrada:  id_entrada,
        id_producto: id_producto,
        cantidad:    cantidad,
        precio:      precio,
        categoria:   categoria,
        status:      'captura',
        id_usuario:  {{ Auth::user()->id }}
      },
      success: function() {
        $('#alumnos_table').DataTable().ajax.reload();
        $('#id_producto').val('').trigger('change');
        $('#cantidad').val('');
        $('#precio').val('0');
      },
      complete: function() {
        $btn.prop('disabled', false).html('<i class="fa fa-plus"></i>&nbsp; Agregar artículo');
      }
    });
  });

  // ── Eliminar artículo ──
  $(document).on("click", "#btn-eliminar", function() {
    var id = $(this).attr('data-id');
    if (confirm("¿Desea eliminar este artículo?")) {
      $.ajax({
        url: "{{ url('entradaproductos/delete') }}/" + id,
        success: function() {
          $('#alumnos_table').DataTable().ajax.reload();
        }
      });
    }
  });

  // ── Finalizar captura ──
  $(document).on("click", "#btnfinalizar, #btnfinalizar2", function() {
    var id_entrada = $('#id_entrada').val();
    if (confirm("¿Desea finalizar la captura de esta entrada?")) {
      $.ajax({
        url: "{{ url('entradas/finalizarentrada') }}/" + id_entrada,
        success: function() {
          window.open('/entradas/reportepdf/{{ $entrada->id }}', '_blank');
          location.reload();
        }
      });
    }
  });

  // ── Editar encabezado (proveedor, factura, observaciones) ──
  $('#btnEditarEncabezado').click(function() {
    $('#modalEditarEncabezado').modal('show');
  });

  $('#btn-guardar-encabezado').click(function() {
    var $btn = $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Guardando...');
    $.ajax({
      url:  '/entradas/{{ $entrada->id }}',
      type: 'POST',
      data: {
        _token:        '{{ csrf_token() }}',
        _method:       'PUT',
        proveedor:     $('#edit_proveedor').val(),
        nfactura:      $('#edit_nfactura').val(),
        observaciones: $('#edit_observaciones').val()
      },
      success: function(res) {
        $('#modalEditarEncabezado').modal('hide');
        location.reload();
      },
      error: function() {
        alert('Error al guardar los cambios.');
      },
      complete: function() {
        $btn.prop('disabled', false).html('<i class="fa fa-save"></i> Guardar cambios');
      }
    });
  });

  // ── Editar precio de artículo ──
  $(document).on("click", ".btn-editar-precio", function() {
    $('#edit_id_entpro').val($(this).data('id'));
    $('#edit_precio').val($(this).data('precio'));
    $('#modalEditarPrecio').modal('show');
    setTimeout(function() { $('#edit_precio').select(); }, 500);
  });

  $('#btn-guardar-precio').click(function() {
    var id     = $('#edit_id_entpro').val();
    var precio = $('#edit_precio').val();
    if (precio === '' || isNaN(parseFloat(precio)) || parseFloat(precio) < 0) {
      alert('Ingrese un precio válido.');
      return;
    }
    var $btn = $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Guardando...');
    $.ajax({
      url:  '/entradaproductos/' + id,
      type: 'POST',
      data: {
        _token:  '{{ csrf_token() }}',
        _method: 'PUT',
        precio:  precio
      },
      success: function() {
        $('#modalEditarPrecio').modal('hide');
        $('#alumnos_table').DataTable().ajax.reload();
      },
      error: function() {
        alert('Error al actualizar el precio.');
      },
      complete: function() {
        $btn.prop('disabled', false).html('<i class="fa fa-save"></i> Guardar');
      }
    });
  });

  // ── Cancelar entrada finalizada ──
  $('#btnCancelarEntrada').click(function() {
    var motivo = prompt('Motivo de cancelación (requerido):');
    if (motivo === null) return;           // presionó Cancelar en el prompt
    motivo = motivo.trim();
    if (motivo === '') {
      alert('Debe ingresar un motivo para cancelar la entrada.');
      return;
    }

    if (!confirm('¿Confirma cancelar esta entrada?\nSe descontarán del stock todos los productos registrados.')) return;

    var $btn = $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Cancelando...');

    $.ajax({
      url: '{{ url("entradas/cancelar") }}/{{ $entrada->id }}',
      type: 'POST',
      data: {
        _token: '{{ csrf_token() }}',
        motivo: motivo
      },
      dataType: 'json',
      success: function(res) {
        alert(res.data);
        location.reload();
      },
      error: function(xhr) {
        var msg = (xhr.responseJSON && xhr.responseJSON.data) ? xhr.responseJSON.data : 'Error al cancelar la entrada.';
        console.error('Status:', xhr.status, '| Response:', xhr.responseText);
        alert('HTTP ' + xhr.status + ': ' + msg);
        $btn.prop('disabled', false).html('<i class="fa fa-ban"></i> Cancelar entrada');
      }
    });
  });

</script>
@endsection
