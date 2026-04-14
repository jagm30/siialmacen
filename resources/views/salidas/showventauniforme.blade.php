@extends('layouts.app')
@section('contenidoprincipal')

{{-- ══════════════════════════════════════════
     PANEL SUPERIOR — datos de la venta
══════════════════════════════════════════ --}}
<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title">
      <i class="fa fa-shopping-cart"></i>&nbsp; Venta de uniformes
      &nbsp;<span class="label label-default">{{ $salida->nomalmacen }}</span>
      &nbsp;
      @if($salida->status == 'captura')
        <span class="label label-warning"><i class="fa fa-pencil"></i> En captura</span>
      @else
        <span class="label label-success"><i class="fa fa-check"></i> Finalizado</span>
      @endif
    </h3>
    <div class="box-tools pull-right">
      @if($salida->status == 'finalizado')
        <a href="/salidas/ventapdf/{{ $salida->id }}" target="_blank"
           class="btn btn-danger btn-sm">
          <i class="fa fa-file-pdf-o"></i> Ver PDF
        </a>
      @endif
      @if($salida->status == 'captura')
        <button type="button" class="btn btn-success btn-sm"
                data-toggle="modal" data-target="#modal-agregar">
          <i class="fa fa-plus"></i> Agregar artículo
        </button>
        <button type="button" class="btn btn-warning btn-sm" id="btnfinalizar">
          <i class="fa fa-flag-checkered"></i> Finalizar venta
        </button>
      @endif
    </div>
  </div>

  <div class="box-body">
    <div class="row">
      <div class="col-md-4">
        <div class="form-group">
          <label><i class="fa fa-user"></i> Cliente</label>
          <input id="solicitante" type="text" class="form-control"
                 name="solicitante" readonly value="{{ $salida->solicitante }}">
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <label><i class="fa fa-calendar"></i> Fecha</label>
          <input id="fecha" type="date" class="form-control"
                 name="fecha" readonly value="{{ $salida->fecha }}">
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <label><i class="fa fa-comment"></i> Observaciones</label>
          <input id="referencia" type="text" class="form-control"
                 name="referencia" readonly value="{{ $salida->observaciones }}">
        </div>
      </div>
    </div>
  </div>
</div>

{{-- ══════════════════════════════════════════
     PANEL INFERIOR — detalle de artículos
══════════════════════════════════════════ --}}
<div class="row">
  <div class="col-xs-12">
    <div class="box box-default">

      {{-- Barra de pago --}}
      <div class="box-header with-border" style="background-color:#f9f9f9;">
        <div class="row" style="margin:0;">

          <div class="col-md-2 col-sm-4">
            <div class="form-group" style="margin-bottom:0;">
              <label style="font-size:11px; color:#777;">Comisión T.Crédito</label>
              <input type="text" name="comisiontcredito" id="comisiontcredito"
                     class="form-control input-sm" value="1.0187">
            </div>
          </div>

          <div class="col-md-2 col-sm-4">
            <div class="form-group" style="margin-bottom:0;">
              <label style="font-size:11px; color:#777;">Forma de pago</label>
              <select class="form-control input-sm" id="formapago" name="formapago">
                <option value="1" @if($salida->formapago=='1') selected @endif>Efectivo</option>
                <option value="2" @if($salida->formapago=='2') selected @endif>T. Débito</option>
                <option value="3" @if($salida->formapago=='3') selected @endif>T. Crédito</option>
              </select>
            </div>
          </div>

          <div class="col-md-2 col-sm-4">
            <div class="form-group" style="margin-bottom:0;">
              <label style="font-size:11px; color:#777;">Total a pagar</label>
              <div class="input-group input-group-sm">
                <span class="input-group-addon">$</span>
                <span class="form-control text-center"
                      style="background:#fff; font-weight:bold; color:#c0392b; font-size:14px;">
                  <span id="totalventaspan" name="totalventaspan">
                    @if($salida->status != 'captura'){{ number_format($salida->total,2) }}@endif
                  </span>
                </span>
              </div>
              <input type="hidden" name="totalventaorigen" id="totalventaorigen">
              <input type="hidden" name="totalventacalc"   id="totalventacalc">
            </div>
          </div>

          <div class="col-md-2 col-sm-4">
            <div class="form-group" style="margin-bottom:0;">
              <label style="font-size:11px; color:#777;">Paga con</label>
              <div class="input-group input-group-sm">
                <span class="input-group-addon">$</span>
                <input type="text" name="pagacon" id="pagacon" class="form-control">
              </div>
            </div>
          </div>

          <div class="col-md-2 col-sm-4">
            <div class="form-group" style="margin-bottom:0;">
              <label style="font-size:11px; color:#777;">Cambio</label>
              <div class="input-group input-group-sm">
                <span class="input-group-addon">$</span>
                <input type="text" name="cambio" id="cambio"
                       class="form-control" readonly>
              </div>
            </div>
          </div>

        </div>
      </div>

      {{-- Tabla de artículos --}}
      <div class="box-body">
        <table id="productos_table" class="table table-bordered table-striped table-hover">
          <thead>
            <tr>
              <th>Descripción</th>
              <th style="width:80px;">Talla</th>
              <th style="width:80px; text-align:center;">Cantidad</th>
              <th style="width:110px; text-align:right;">Precio unit.</th>
              <th style="width:110px; text-align:right;">Subtotal</th>
              <th style="width:130px; text-align:center;">Acción</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th colspan="3"></th>
              <th style="text-align:right;">Total:</th>
              <th></th>
              <th></th>
            </tr>
          </tfoot>
        </table>
      </div>

    </div>
  </div>
</div>


{{-- ══════════════════════════════════════════
     MODAL — Agregar artículo
══════════════════════════════════════════ --}}
<div class="modal fade" id="modal-agregar">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header" style="background-color:#00a65a; color:#fff;">
        <button type="button" class="close" data-dismiss="modal"
                style="color:#fff; opacity:1;"><span>&times;</span></button>
        <h4 class="modal-title">
          <i class="fa fa-plus-circle"></i>&nbsp; Agregar artículo a la venta
        </h4>
      </div>

      <div class="modal-body">
        <form id="formmodal" name="formmodal">
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
            <div class="col-md-4">
              <div class="form-group">
                <label>Stock disponible</label>
                <input id="stock" type="text" class="form-control"
                       name="stock" readonly>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Cantidad</label>
                <input id="cantidad" type="number" class="form-control"
                       name="cantidad" min="1" required>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Precio</label>
                <div class="input-group">
                  <span class="input-group-addon">$</span>
                  <input id="precio" type="text" class="form-control"
                         name="precio" required>
                </div>
              </div>
            </div>
          </div>

        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left"
                data-dismiss="modal">
          <i class="fa fa-times"></i> Cerrar
        </button>
        <button id="btn_guardaregistro" type="button" class="btn btn-success">
          <i class="fa fa-plus"></i> Agregar
        </button>
      </div>

    </div>
  </div>
</div>


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
        { data: 'precio',      name: 'precio',
          render: function(data) {
            return '<span style="display:block; text-align:right;">$' + number_format(data, 2, '.', ',') + '</span>';
          }
        },
        { data: 'subtotal',    name: 'subtotal',
          render: function(data) {
            return '<span style="display:block; text-align:right;">$' + number_format(data, 2, '.', ',') + '</span>';
          }
        },
        { data: 'action', name: 'action', orderable: false }
      ],
      searching: true,
      paging: false,
      autoWidth: false,
      "footerCallback": function(row, data, start, end, display) {
        total = this.api()
          .column(4)
          .data()
          .reduce(function(a, b) { return parseInt(a) + parseInt(b); }, 0);

        var formapago        = $('#formapago').val();
        var comisiontcredito = parseFloat($('#comisiontcredito').val());

        if (formapago == '3') {
          $('#totalventacalc').val(total * comisiontcredito);
          $('#totalventaspan').text(number_format(total * comisiontcredito, 2, '.', ','));
        } else {
          $('#totalventacalc').val(total);
          $('#totalventaspan').text(number_format(total, 2, '.', ','));
        }
        $('#totalventaorigen').val(total);
        $(this.api().column(4).footer()).html(
          '<span style="display:block; text-align:right; color:#c0392b; font-size:14pt; font-weight:bold;">$' +
          number_format(total, 2, '.', ',') + '</span>'
        );
      }
    });

    $("#menuventauniforme").addClass("important active");
  });

  function number_format(number, decimals, dec_point, thousands_sep) {
    number = number.toFixed(decimals);
    var nstr = number.toString(), x = nstr.split('.');
    var x1 = x[0], x2 = x.length > 1 ? dec_point + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) x1 = x1.replace(rgx, '$1' + thousands_sep + '$2');
    return x1 + x2;
  }

  // ── Agregar artículo ──
  $('#btn_guardaregistro').click(function() {
    var id_salida   = $('#id_salida').val();
    var id_producto = $('#id_producto').val();
    var cantidad    = parseInt($('#cantidad').val());
    var stock       = parseInt($('#stock').val());
    var precio      = $('#precio').val();

    if (!id_producto) { alert("Seleccione un artículo."); return false; }
    if (!cantidad || cantidad == 0) { alert("Ingrese una cantidad."); return false; }
    if (cantidad > stock) { alert("No hay suficiente stock disponible."); return false; }

    $.ajax({
      url: "/salidaproductos",
      type: "POST",
      data: {
        _token:     $("#csrf").val(),
        id_salida:  id_salida,
        id_producto:id_producto,
        cantidad:   cantidad,
        stock:      stock,
        precio:     precio,
        status:     'captura',
        id_usuario: {{ Auth::user()->id }}
      },
      success: function() {
        $('#productos_table').DataTable().ajax.reload();
        $('#formmodal').trigger("reset");
        $('.select2').val('').trigger('change');
        $('#stock').val('');
      }
    });
  });

  // ── Cargar stock y precio al seleccionar artículo ──
  $("#id_producto").change(function() {
    var id_producto = $(this).val();
    if (!id_producto) { $('#stock').val(''); $('#precio').val(''); return; }
    $.ajax({
      url: "/productos/" + id_producto,
      async: false,
      dataType: "json",
      success: function(data) {
        $("#stock").val(data.stock);
        $("#precio").val(data.precio);
        $("#cantidad").val(1);
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
  $(document).on("click", "#btnfinalizar", function() {
    var id_salida      = $('#id_salida').val();
    var formapago      = $('#formapago').val();
    var totalventacalc = $('#totalventacalc').val();
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

  // ── Recalcular total al cambiar forma de pago ──
  $("#formapago, #comisiontcredito").change(function() {
    var formapago        = $('#formapago').val();
    var total            = parseFloat($('#totalventaorigen').val()) || 0;
    var comisiontcredito = parseFloat($('#comisiontcredito').val());
    var resultado = (formapago == '3') ? total * comisiontcredito : total;
    $('#totalventacalc').val(resultado);
    $('#totalventaspan').text(number_format(resultado, 2, '.', ','));
  });

  // ── Calcular cambio ──
  $("#pagacon").on('input', function() {
    var total  = parseFloat($('#totalventacalc').val()) || 0;
    var pagacon = parseFloat($(this).val()) || 0;
    $('#cambio').val(number_format(pagacon - total, 2, '.', ','));
  });
</script>
@endsection
