@extends('layouts.app')
@section('contenidoprincipal')

{{-- ══════════════════════════════════════════
     ENCABEZADO DE LA VENTA
══════════════════════════════════════════ --}}
<div class="box box-primary" style="margin-bottom:10px;">
  <div class="box-header with-border" style="padding:8px 15px;">
    <div class="row" style="margin:0; display:flex; align-items:center; flex-wrap:wrap; gap:4px 20px;">

      <div style="font-size:14px; font-weight:bold; white-space:nowrap;">
        <i class="fa fa-shopping-cart"></i>&nbsp; Venta de uniformes
      </div>

      <div style="font-size:13px; color:#555; white-space:nowrap;">
        <i class="fa fa-building-o" style="color:#aaa;"></i>&nbsp;
        <strong>{{ $salida->nomalmacen }}</strong>
      </div>

      <div style="font-size:13px; color:#555; white-space:nowrap;">
        <i class="fa fa-user" style="color:#aaa;"></i>&nbsp;
        <strong>{{ $salida->solicitante }}</strong>
      </div>

      <div style="font-size:13px; color:#555; white-space:nowrap;">
        <i class="fa fa-calendar" style="color:#aaa;"></i>&nbsp;
        {{ \Carbon\Carbon::parse($salida->fecha)->format('d/m/Y') }}
      </div>

      @if($salida->observaciones)
      <div style="font-size:13px; color:#555; white-space:nowrap; max-width:260px; overflow:hidden; text-overflow:ellipsis;">
        <i class="fa fa-comment" style="color:#aaa;"></i>&nbsp;
        {{ $salida->observaciones }}
      </div>
      @endif

      <div style="white-space:nowrap;">
        @if($salida->status == 'captura')
          <span class="label label-warning"><i class="fa fa-pencil"></i> En captura</span>
        @elseif($salida->status == 'cancelado')
          <span class="label label-danger"><i class="fa fa-ban"></i> Cancelada</span>
        @else
          <span class="label label-success"><i class="fa fa-check"></i> Finalizado</span>
        @endif
      </div>

      <div class="pull-right" style="margin-left:auto; display:flex; gap:6px; align-items:center;">
        @if($salida->status == 'finalizado')
          <a href="/salidas/ventapdf/{{ $salida->id }}" target="_blank"
             class="btn btn-danger btn-sm">
            <i class="fa fa-file-pdf-o"></i> Ver PDF
          </a>
          <button type="button" id="btnCancelarVenta"
                  class="btn btn-sm btn-default"
                  style="border-color:#d9534f; color:#d9534f;">
            <i class="fa fa-ban"></i> Cancelar venta
          </button>
        @endif
        @if($salida->status == 'captura')
          <button type="button" class="btn btn-lg btn-warning" id="btnfinalizar"
                  style="font-weight:bold; letter-spacing:.5px; box-shadow:0 2px 6px rgba(0,0,0,.25);">
            <i class="fa fa-flag-checkered"></i> Finalizar venta
          </button>
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

    @if($salida->status == 'captura')
    <div class="box box-success">
      <div class="box-header with-border" style="background-color:#00a65a; color:#fff; padding:10px 15px;">
        <h3 class="box-title" style="color:#fff;">
          <i class="fa fa-plus-circle"></i>&nbsp; Agregar artículo
        </h3>
      </div>
      <div class="box-body">
        <input type="hidden" name="_token" id="csrf" value="{{ Session::token() }}">
        <input id="id_salida" type="hidden" value="{{ $id_salida }}" name="id_salida">

        <div class="form-group">
          <label><i class="fa fa-search"></i> Artículo</label>
          <select id="id_producto" name="id_producto"
                  class="form-control select2" style="width:100%;">
            <option value="">Seleccione un artículo...</option>
            @foreach($productos as $producto)
              <option value="{{ $producto->id }}">
                {{ $producto->descripcion }}
                @if(!empty($producto->talla) && $producto->talla != '0' && strtoupper($producto->talla) != 'NA' && strtoupper($producto->talla) != 'N/A')
                  — Talla: {{ $producto->talla }}
                @endif
              </option>
            @endforeach
          </select>
        </div>

        <div class="row">
          <div class="col-xs-6">
            <div class="form-group">
              <label>Stock disponible</label>
              <input id="stock" type="text" class="form-control input-sm"
                     name="stock" readonly placeholder="—">
            </div>
          </div>
          <div class="col-xs-6">
            <div class="form-group">
              <label>Cantidad</label>
              <input id="cantidad" type="number" class="form-control input-sm"
                     name="cantidad" min="1" placeholder="1">
            </div>
          </div>
        </div>

        <div class="form-group">
          <label>Precio unitario</label>
          <div class="input-group input-group-sm">
            <span class="input-group-addon">$</span>
            <input id="precio" type="text" class="form-control"
                   name="precio" placeholder="0.00">
          </div>
        </div>

        <button id="btn_guardaregistro" type="button"
                class="btn btn-success btn-block">
          <i class="fa fa-plus"></i>&nbsp; Agregar artículo
        </button>
      </div>
    </div>
    @endif

    {{-- Panel de pago --}}
    <div class="box box-default" style="margin-bottom:0;">
      <div class="box-header with-border" style="padding:8px 15px;">
        <h3 class="box-title"><i class="fa fa-money"></i>&nbsp; Pago</h3>
      </div>
      <div class="box-body" style="padding:10px 15px;">

        {{-- Fila 1: Forma de pago + Comisión --}}
        <div class="row" style="margin-bottom:6px;">
          <div class="col-xs-7">
            <label style="font-size:11px; margin-bottom:2px;">Forma de pago</label>
            <select class="form-control input-sm" id="formapago" name="formapago"
                    @if($salida->status != 'captura') disabled @endif>
              <option value="1" @if($salida->formapago=='1') selected @endif>Efectivo</option>
              <option value="2" @if($salida->formapago=='2') selected @endif>T. Débito</option>
              <option value="3" @if($salida->formapago=='3') selected @endif>T. Crédito</option>
            </select>
          </div>
          @if($salida->status == 'captura')
          <div class="col-xs-5">
            <label style="font-size:11px; margin-bottom:2px;">Comisión T.C.</label>
            <input type="text" name="comisiontcredito" id="comisiontcredito"
                   class="form-control input-sm" value="1.0187">
          </div>
          @endif
        </div>

        {{-- Total a pagar --}}
        <div style="background:#fdf2f2; border:1px solid #e8b4b4; border-radius:4px;
                    padding:8px 12px; margin-bottom:6px; text-align:center;">
          <div style="font-size:11px; color:#999; margin-bottom:2px;">TOTAL A PAGAR</div>
          <div style="font-size:24px; font-weight:bold; color:#c0392b; line-height:1.1;">
            $<span id="totalventaspan">@if($salida->status != 'captura'){{ number_format($salida->total,2) }}@endif</span>
          </div>
        </div>
        <input type="hidden" name="totalventaorigen" id="totalventaorigen">
        <input type="hidden" name="totalventacalc"   id="totalventacalc">

        @if($salida->status == 'captura')
        {{-- Fila 2: Paga con + Cambio --}}
        <div class="row" style="margin-bottom:0;">
          <div class="col-xs-6">
            <label style="font-size:11px; margin-bottom:2px;">Paga con</label>
            <div class="input-group input-group-sm">
              <span class="input-group-addon">$</span>
              <input type="text" name="pagacon" id="pagacon" class="form-control"
                     placeholder="0.00">
            </div>
          </div>
          <div class="col-xs-6">
            <label style="font-size:11px; margin-bottom:2px;">Cambio</label>
            <div class="input-group input-group-sm">
              <span class="input-group-addon">$</span>
              <input type="text" name="cambio" id="cambio"
                     class="form-control" readonly placeholder="0.00"
                     style="font-weight:bold; color:#27ae60;">
            </div>
          </div>
        </div>
        @endif

        @if($salida->status == 'captura')
        {{-- Botón finalizar duplicado --}}
        <button type="button" class="btn btn-warning btn-block"
                id="btnfinalizar2"
                style="margin-top:10px; font-weight:bold; font-size:15px;
                       letter-spacing:.5px; box-shadow:0 2px 6px rgba(0,0,0,.25);">
          <i class="fa fa-flag-checkered"></i>&nbsp; Finalizar venta
        </button>
        @endif

      </div>
    </div>

  </div>{{-- /col-md-4 --}}

  {{-- ══════════════════════════════════════════
       COLUMNA DERECHA — Detalle de artículos
  ══════════════════════════════════════════ --}}
  <div class="col-md-8">
    <div class="box box-default">
      <div class="box-header with-border" style="padding:10px 15px;">
        <h3 class="box-title"><i class="fa fa-list"></i>&nbsp; Artículos de la venta</h3>
      </div>
      <div class="box-body" style="padding:10px;">
        <table id="productos_table"
               class="table table-bordered table-striped table-hover"
               style="font-size:13px;">
          <thead>
            <tr>
              <th>Descripción</th>
              <th style="width:70px;">Talla</th>
              <th style="width:70px; text-align:center;">Cant.</th>
              <th style="width:100px; text-align:right;">Precio</th>
              <th style="width:100px; text-align:right;">Subtotal</th>
              <th style="width:110px; text-align:center;">Acción</th>
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

@endsection
@section("scriptpie")
<script type="text/javascript">
  $(function() {
    var total = 0;
    $('.select2').select2();

    $('#productos_table').DataTable({
      processing: true,
      serverSide: true,
      ajax: "/salidaproductos/listarxsalida/{{ $salida->id }}",
      columns: [
        { data: 'descripcion', name: 'descripcion' },
        { data: 'talla',       name: 'talla'       },
        { data: 'cantidad',    name: 'cantidad',
          render: function(data) {
            return '<span style="display:block; text-align:center;">' + data + '</span>';
          }
        },
        { data: 'precio',   name: 'precio',
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
        total = this.api()
          .column(4)
          .data()
          .reduce(function(a, b) { return parseFloat(a) + parseFloat(b); }, 0);

        var formapago        = $('#formapago').val();
        var comisiontcredito = parseFloat($('#comisiontcredito').val() || 1);

        var resultado = (formapago == '3') ? total * comisiontcredito : total;
        $('#totalventacalc').val(resultado);
        $('#totalventaorigen').val(total);
        $('#totalventaspan').text(number_format(resultado, 2, '.', ','));

        $('#footer-total').html(
          '<span style="display:block; text-align:right; color:#c0392b; font-size:14pt; font-weight:bold;">$' +
          number_format(total, 2, '.', ',') + '</span>'
        );
        recalcularCambio();
      }
    });

    $("#menuventauniforme").addClass("important active");
  });

  function number_format(number, decimals, dec_point, thousands_sep) {
    number = parseFloat(number).toFixed(decimals);
    var nstr = number.toString(), x = nstr.split('.');
    var x1 = x[0], x2 = x.length > 1 ? dec_point + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) x1 = x1.replace(rgx, '$1' + thousands_sep + '$2');
    return x1 + x2;
  }

  function recalcularCambio() {
    var total  = parseFloat($('#totalventacalc').val()) || 0;
    var pagacon = parseFloat($('#pagacon').val()) || 0;
    if (pagacon > 0) {
      $('#cambio').val(number_format(pagacon - total, 2, '.', ','));
    }
  }

  // ── Agregar artículo ──
  $('#btn_guardaregistro').click(function() {
    var id_salida   = $('#id_salida').val();
    var id_producto = $('#id_producto').val();
    var cantidad    = parseInt($('#cantidad').val());
    var stock       = parseInt($('#stock').val());
    var precio      = $('#precio').val();

    if (!id_producto) { alert("Seleccione un artículo."); return false; }
    if (!cantidad || cantidad == 0) { alert("Ingrese una cantidad válida."); return false; }
    if (cantidad > stock) { alert("No hay suficiente stock disponible. Stock: " + stock); return false; }

    var $btn = $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Agregando...');

    $.ajax({
      url: "/salidaproductos",
      type: "POST",
      data: {
        _token:      $("#csrf").val(),
        id_salida:   id_salida,
        id_producto: id_producto,
        cantidad:    cantidad,
        stock:       stock,
        precio:      precio,
        status:      'captura',
        id_usuario:  {{ Auth::user()->id }}
      },
      success: function() {
        $('#productos_table').DataTable().ajax.reload();
        $('#id_producto').val('').trigger('change');
        $('#stock').val('');
        $('#cantidad').val('');
        $('#precio').val('');
      },
      complete: function() {
        $btn.prop('disabled', false).html('<i class="fa fa-plus"></i>&nbsp; Agregar artículo');
      }
    });
  });

  // ── Cargar stock y precio al seleccionar artículo ──
  $("#id_producto").change(function() {
    var id_producto = $(this).val();
    if (!id_producto) { $('#stock').val(''); $('#precio').val(''); $('#cantidad').val(''); return; }
    $.ajax({
      url: "/productos/" + id_producto,
      async: false,
      dataType: "json",
      success: function(data) {
        $("#stock").val(data.stock);
        $("#precio").val(data.precio);
        $("#cantidad").val(1);
        $("#cantidad").focus();
      }
    });
  });

  // ── Eliminar artículo ──
  $(document).on("click", "#btn-eliminar", function() {
    var id = $(this).attr('data-id');
    if (confirm("¿Desea eliminar este artículo?")) {
      $.ajax({
        url: "{{ url('salidaproductos/delete') }}/" + id,
        success: function() { $('#productos_table').DataTable().ajax.reload(); }
      });
    }
  });

  // ── Devolver artículo ──
  $(document).on("click", "#btn-devolver", function() {
    var id = $(this).attr('data-id');
    if (confirm("¿Desea devolver este artículo al inventario?")) {
      $.ajax({
        url: "{{ url('salidaproductos/devolver') }}/" + id,
        success: function() { $('#productos_table').DataTable().ajax.reload(); }
      });
    }
  });

  // ── Finalizar venta ──
  $(document).on("click", "#btnfinalizar, #btnfinalizar2", function() {
    var id_salida      = $('#id_salida').val();
    var formapago      = $('#formapago').val();
    var totalventacalc = $('#totalventacalc').val();
    if (!totalventacalc || parseFloat(totalventacalc) == 0) {
      alert("Agregue al menos un artículo antes de finalizar.");
      return false;
    }
    if (confirm("¿Desea finalizar la captura de esta venta?")) {
      $.ajax({
        url: "{{ url('salidas/finalizarsalida') }}/" + id_salida + '/' + formapago + '/' + totalventacalc,
        success: function() {
          window.open('/salidas/ventapdf/{{ $salida->id }}', '_blank');
          location.reload();
        }
      });
    }
  });

  // ── Recalcular total al cambiar forma de pago / comisión ──
  $("#formapago, #comisiontcredito").change(function() {
    var formapago        = $('#formapago').val();
    var total            = parseFloat($('#totalventaorigen').val()) || 0;
    var comisiontcredito = parseFloat($('#comisiontcredito').val() || 1);
    var resultado = (formapago == '3') ? total * comisiontcredito : total;
    $('#totalventacalc').val(resultado);
    $('#totalventaspan').text(number_format(resultado, 2, '.', ','));
    recalcularCambio();
  });

  // ── Calcular cambio ──
  $("#pagacon").on('input', recalcularCambio);

  // ── Cancelar venta finalizada ──
  $('#btnCancelarVenta').click(function() {
    var motivo = prompt('Motivo de cancelación (requerido):');
    if (motivo === null) return;
    motivo = motivo.trim();
    if (motivo === '') {
      alert('Debe ingresar un motivo para cancelar la venta.');
      return;
    }
    if (!confirm('¿Confirma cancelar esta venta?\nSe devolverán al stock todos los artículos.')) return;

    var $btn = $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Cancelando...');

    $.ajax({
      url: '{{ url("salidas/cancelar") }}/{{ $salida->id }}',
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
        var msg = (xhr.responseJSON && xhr.responseJSON.data) ? xhr.responseJSON.data : 'Error al cancelar la venta.';
        alert(msg);
        $btn.prop('disabled', false).html('<i class="fa fa-ban"></i> Cancelar venta');
      }
    });
  });
</script>
@endsection
