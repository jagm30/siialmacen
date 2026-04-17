@extends('layouts.app')
@section('contenidoprincipal')

<input type="hidden" id="csrf" value="{{ Session::token() }}">

{{-- ═══════════════════════════════════════════
     PASO 1 — Datos de la venta
═══════════════════════════════════════════ --}}
<div id="panel-cabecera">
  <div class="box box-success">
    <div class="box-header with-border" style="background:#00a65a; color:#fff; padding:10px 15px;">
      <h3 class="box-title" style="color:#fff;">
        <i class="fa fa-shopping-cart"></i>&nbsp; Nueva venta — Datos generales
      </h3>
      <div class="box-tools pull-right" style="display:flex; gap:6px; align-items:center;">
        <a href="/salidas/ventauniforme" class="btn btn-sm"
           style="color:#fff; background:transparent; border:1px solid rgba(255,255,255,.5);">
          <i class="fa fa-list"></i> Ver lista de ventas
        </a>
      </div>
    </div>
    <div class="box-body">
      <div class="row">
        <div class="form-group col-md-6">
          <label>Cliente</label>
          <input id="solicitante" type="text" class="form-control"
                 value="PUBLICO GENERAL" autofocus>
        </div>
        <div class="form-group col-md-3">
          <label>Fecha</label>
          <input id="fecha-venta" type="date" class="form-control" value="{{ $date }}">
        </div>
        <div class="form-group col-md-3">
          <label>Almacén</label>
          <select id="almacen" class="form-control">
            @foreach($almacenes as $alm)
              <option value="{{ $alm->id }}">{{ $alm->nombre }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group col-md-12">
          <label>Observaciones</label>
          <input id="observaciones" type="text" class="form-control" value="Ninguno">
        </div>
      </div>
      <div id="cajaerror" class="col-md-12"></div>
    </div>
    <div class="box-footer" style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:8px;">
      <div>
        <button id="btn_iniciarventa" type="button" class="btn btn-success btn-lg">
          <i class="fa fa-arrow-right"></i>&nbsp; Continuar y agregar artículos
        </button>
      </div>
      {{-- Exportar PDF de ventas por fecha --}}
      <div style="display:flex; align-items:center; gap:6px;">
        <label style="margin:0; font-weight:normal; color:#555;">Exportar ventas:</label>
        <input type="date" id="pdf-fecha1" class="form-control input-sm" value="{{ $date }}" style="width:130px;">
        <span style="color:#555;">al</span>
        <input type="date" id="pdf-fecha2" class="form-control input-sm" value="{{ $date }}" style="width:130px;">
        <button id="btn-exportar-pdf" type="button" class="btn btn-danger btn-sm">
          <i class="fa fa-file-pdf-o"></i> Exportar PDF
        </button>
      </div>
    </div>
  </div>
</div>

{{-- ═══════════════════════════════════════════
     PASO 2 — Agregar artículos y cobrar
═══════════════════════════════════════════ --}}
<div id="panel-productos" style="display:none;">

  {{-- Barra de info de la venta activa --}}
  <div class="box box-primary" style="margin-bottom:10px;">
    <div class="box-header with-border" style="padding:8px 15px;">
      <div style="display:flex; align-items:center; flex-wrap:wrap; gap:4px 20px;">
        <div style="font-size:14px; font-weight:bold; white-space:nowrap;">
          <i class="fa fa-shopping-cart"></i>&nbsp; Venta de uniformes
        </div>
        <div id="info-almacen"   style="font-size:13px; color:#555;"></div>
        <div id="info-cliente"   style="font-size:13px; color:#555;"></div>
        <div id="info-fecha"     style="font-size:13px; color:#555;"></div>
        <span class="label label-warning" style="font-size:12px; padding:4px 8px;">
          <i class="fa fa-pencil"></i> En captura
        </span>
        <div style="margin-left:auto; display:flex; gap:8px; align-items:center;">
          <a href="/salidas/ventauniforme" class="btn btn-sm btn-default">
            <i class="fa fa-list"></i> Ver lista
          </a>
          <button type="button" class="btn btn-lg btn-warning" id="btnfinalizar"
                  style="font-weight:bold; letter-spacing:.5px;">
            <i class="fa fa-flag-checkered"></i>&nbsp; Finalizar venta
          </button>
        </div>
      </div>
    </div>
  </div>

  <div class="row">

    {{-- Columna izquierda: agregar artículo + panel de pago --}}
    <div class="col-md-4">

      <div class="box box-success">
        <div class="box-header with-border"
             style="background:#00a65a; color:#fff; padding:10px 15px;">
          <h3 class="box-title" style="color:#fff;">
            <i class="fa fa-plus-circle"></i>&nbsp; Agregar artículo
          </h3>
        </div>
        <div class="box-body">
          <input id="id_salida" type="hidden" value="">

          <div class="form-group">
            <label><i class="fa fa-search"></i> Artículo</label>
            <select id="id_producto" class="form-control select2" style="width:100%;">
              <option value="">Seleccione un artículo...</option>
              @foreach($productos as $producto)
                <option value="{{ $producto->id }}">
                  {{ $producto->descripcion }}
                  @if(!empty($producto->talla) && $producto->talla != '0'
                      && strtoupper($producto->talla) != 'NA'
                      && strtoupper($producto->talla) != 'N/A')
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
                <input id="stock" type="text" class="form-control input-sm" readonly placeholder="—">
              </div>
            </div>
            <div class="col-xs-6">
              <div class="form-group">
                <label>Cantidad</label>
                <input id="cantidad" type="number" class="form-control input-sm" min="1" placeholder="1">
              </div>
            </div>
          </div>

          <div class="form-group">
            <label>Precio unitario</label>
            <div class="input-group input-group-sm">
              <span class="input-group-addon">$</span>
              <input id="precio" type="text" class="form-control" placeholder="0.00">
            </div>
          </div>

          <button id="btn_agregar_producto" type="button" class="btn btn-success btn-block">
            <i class="fa fa-plus"></i>&nbsp; Agregar artículo
          </button>
        </div>
      </div>

      {{-- Panel de pago --}}
      <div class="box box-default" style="margin-bottom:0;">
        <div class="box-header with-border" style="padding:8px 15px;">
          <h3 class="box-title"><i class="fa fa-money"></i>&nbsp; Pago</h3>
        </div>
        <div class="box-body" style="padding:10px 15px;">

          <div class="row" style="margin-bottom:6px;">
            <div class="col-xs-7">
              <label style="font-size:11px; margin-bottom:2px;">Forma de pago</label>
              <select class="form-control input-sm" id="formapago">
                <option value="1">Efectivo</option>
                <option value="2">T. Débito</option>
                <option value="3">T. Crédito</option>
              </select>
            </div>
            <div class="col-xs-5">
              <label style="font-size:11px; margin-bottom:2px;">Comisión T.C.</label>
              <input type="text" id="comisiontcredito" class="form-control input-sm" value="1.0187">
            </div>
          </div>

          <div style="background:#fdf2f2; border:1px solid #e8b4b4; border-radius:4px;
                      padding:8px 12px; margin-bottom:6px; text-align:center;">
            <div style="font-size:11px; color:#999; margin-bottom:2px;">TOTAL A PAGAR</div>
            <div style="font-size:24px; font-weight:bold; color:#c0392b; line-height:1.1;">
              $<span id="totalventaspan">0.00</span>
            </div>
          </div>
          <input type="hidden" id="totalventaorigen" value="0">
          <input type="hidden" id="totalventacalc"   value="0">

          <div class="row" style="margin-bottom:0;">
            <div class="col-xs-6">
              <label style="font-size:11px; margin-bottom:2px;">Paga con</label>
              <div class="input-group input-group-sm">
                <span class="input-group-addon">$</span>
                <input type="text" id="pagacon" class="form-control" placeholder="0.00">
              </div>
            </div>
            <div class="col-xs-6">
              <label style="font-size:11px; margin-bottom:2px;">Cambio</label>
              <div class="input-group input-group-sm">
                <span class="input-group-addon">$</span>
                <input type="text" id="cambio" class="form-control" readonly placeholder="0.00"
                       style="font-weight:bold; color:#27ae60;">
              </div>
            </div>
          </div>

          <button type="button" class="btn btn-warning btn-block" id="btnfinalizar2"
                  style="margin-top:10px; font-weight:bold; font-size:15px; letter-spacing:.5px;">
            <i class="fa fa-flag-checkered"></i>&nbsp; Finalizar venta
          </button>

        </div>
      </div>

    </div>{{-- /col-md-4 --}}

    {{-- Columna derecha: tabla de artículos --}}
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
    </div>

  </div>
</div>

@endsection
@section("scriptpie")
<script>

  function numFmt(n) {
    return parseFloat(n).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
  }

  function recalcularCambio() {
    var total  = parseFloat($('#totalventacalc').val()) || 0;
    var pagacon = parseFloat($('#pagacon').val().replace(/,/g,'')) || 0;
    if (pagacon > 0) $('#cambio').val(numFmt(pagacon - total));
  }

  var productosTable = null;

  function initProductosTable(id_salida) {
    if (productosTable) { productosTable.destroy(); productosTable = null; }
    productosTable = $('#productos_table').DataTable({
      processing: true, serverSide: true,
      ajax: "/salidaproductos/listarxsalida/" + id_salida,
      columns: [
        { data: 'descripcion', name: 'descripcion' },
        { data: 'talla',       name: 'talla' },
        { data: 'cantidad',    name: 'cantidad',
          render: function(d) { return '<span style="display:block;text-align:center;">' + d + '</span>'; }
        },
        { data: 'precio',    name: 'precio',
          render: function(d) { return '<span style="display:block;text-align:right;">$' + numFmt(d) + '</span>'; }
        },
        { data: 'subtotal',  name: 'subtotal',
          render: function(d) { return '<span style="display:block;text-align:right;">$' + numFmt(d) + '</span>'; }
        },
        { data: 'action', name: 'action', orderable: false }
      ],
      searching: false, paging: false, autoWidth: false,
      language: { processing: 'Cargando...', emptyTable: 'Sin artículos agregados.' },
      footerCallback: function() {
        var total = this.api().column(4).data()
          .reduce(function(a, b) { return parseFloat(a) + parseFloat(b); }, 0);
        var formapago        = $('#formapago').val();
        var comisiontcredito = parseFloat($('#comisiontcredito').val() || 1);
        var resultado = (formapago == '3') ? total * comisiontcredito : total;
        $('#totalventaorigen').val(total);
        $('#totalventacalc').val(resultado);
        $('#totalventaspan').text(numFmt(resultado));
        $('#footer-total').html(
          '<span style="display:block;text-align:right;color:#c0392b;font-size:14pt;font-weight:bold;">$' + numFmt(total) + '</span>'
        );
        recalcularCambio();
      }
    });
  }

  $(function() {

    $("#menuventauniforme").addClass("important active");
    $('.select2').select2();

    // ── Exportar PDF por rango de fecha ───────────────────────────────
    $('#btn-exportar-pdf').click(function() {
      var f1 = $('#pdf-fecha1').val();
      var f2 = $('#pdf-fecha2').val();
      if (!f1 || !f2) { alert("Seleccione el rango de fechas."); return; }
      window.open('/salidas/salidaxfechaPDF/' + f1 + '/' + f2, '_blank');
    });

    // ── Paso 1: iniciar venta ──────────────────────────────────────────
    $('#btn_iniciarventa').click(function() {
      var solicitante   = $('#solicitante').val().trim();
      var fecha         = $('#fecha-venta').val();
      var almacen       = $('#almacen').val();
      var observaciones = $('#observaciones').val();

      if (!solicitante) {
        $('#cajaerror').html('<div class="alert alert-warning">Ingrese el nombre del cliente.</div>');
        $('#solicitante').focus(); return;
      }

      var $btn = $(this).prop('disabled', true)
                        .html('<i class="fa fa-spinner fa-spin"></i> Guardando...');

      $.ajax({
        url: "/salidas", type: "POST",
        data: {
          _token: $('#csrf').val(), type: 1,
          folioreq: 'NA', solicitante: solicitante,
          fecha: fecha, almacen: almacen,
          cajapago: 'na', nnotaventa: 'na', fventa: 'na',
          observaciones: observaciones, status: 'captura',
          id_usuario: {{ Auth::user()->id }}
        },
        success: function(result) {
          $.ajax({
            url: "/salidas/showventauniforme/" + result.data,
            dataType: "json",
            success: function(salida) {
              $('#id_salida').val(salida.id);
              $('#info-almacen').html('<i class="fa fa-building-o" style="color:#aaa;"></i>&nbsp;<strong>' + (salida.nomalmacen || '') + '</strong>');
              $('#info-cliente').html('<i class="fa fa-user" style="color:#aaa;"></i>&nbsp;<strong>' + (salida.solicitante || '') + '</strong>');
              $('#info-fecha').html('<i class="fa fa-calendar" style="color:#aaa;"></i>&nbsp;' + (salida.fecha || ''));
              $('#panel-cabecera').slideUp(300, function() {
                $('#panel-productos').slideDown(300);
                initProductosTable(salida.id);
                $('html, body').animate({ scrollTop: 0 }, 200);
              });
            }
          });
        },
        complete: function() {
          $btn.prop('disabled', false)
              .html('<i class="fa fa-arrow-right"></i>&nbsp; Continuar y agregar artículos');
        }
      });
    });

    // ── Cargar stock y precio al seleccionar artículo ─────────────────
    $('#id_producto').change(function() {
      var id = $(this).val();
      if (!id) { $('#stock').val(''); $('#precio').val(''); $('#cantidad').val(''); return; }
      $.ajax({
        url: "/productos/" + id, dataType: "json",
        success: function(data) {
          $('#stock').val(data.stock);
          $('#precio').val(data.precio);
          $('#cantidad').val(1).focus();
        }
      });
    });

    // ── Agregar artículo ───────────────────────────────────────────────
    $('#btn_agregar_producto').click(function() {
      var id_salida   = $('#id_salida').val();
      var id_producto = $('#id_producto').val();
      var cantidad    = parseInt($('#cantidad').val());
      var stock       = parseInt($('#stock').val());
      var precio      = $('#precio').val();

      if (!id_producto) { alert("Seleccione un artículo."); return; }
      if (!cantidad || cantidad <= 0) { alert("Ingrese una cantidad válida."); return; }
      if (cantidad > stock) { alert("Stock insuficiente. Disponible: " + stock); return; }

      var $btn = $(this).prop('disabled', true)
                        .html('<i class="fa fa-spinner fa-spin"></i> Agregando...');
      $.ajax({
        url: "/salidaproductos", type: "POST",
        data: {
          _token: $('#csrf').val(),
          id_salida: id_salida, id_producto: id_producto,
          cantidad: cantidad, stock: stock, precio: precio,
          status: 'captura', id_usuario: {{ Auth::user()->id }}
        },
        success: function() {
          productosTable.ajax.reload();
          $('#id_producto').val('').trigger('change');
          $('#stock').val(''); $('#cantidad').val(''); $('#precio').val('');
        },
        complete: function() {
          $btn.prop('disabled', false).html('<i class="fa fa-plus"></i>&nbsp; Agregar artículo');
        }
      });
    });

    // ── Eliminar artículo ──────────────────────────────────────────────
    $(document).on('click', '#btn-eliminar', function() {
      if (confirm("¿Desea eliminar este artículo?")) {
        $.ajax({
          url: "{{ url('salidaproductos/delete') }}/" + $(this).data('id'),
          success: function() { productosTable.ajax.reload(); }
        });
      }
    });

    // ── Devolver artículo ──────────────────────────────────────────────
    $(document).on('click', '#btn-devolver', function() {
      if (confirm("¿Desea devolver este artículo al inventario?")) {
        $.ajax({
          url: "{{ url('salidaproductos/devolver') }}/" + $(this).data('id'),
          success: function() { productosTable.ajax.reload(); }
        });
      }
    });

    // ── Recalcular al cambiar forma de pago / comisión ────────────────
    $('#formapago, #comisiontcredito').change(function() {
      var total            = parseFloat($('#totalventaorigen').val()) || 0;
      var formapago        = $('#formapago').val();
      var comisiontcredito = parseFloat($('#comisiontcredito').val() || 1);
      var resultado = (formapago == '3') ? total * comisiontcredito : total;
      $('#totalventacalc').val(resultado);
      $('#totalventaspan').text(numFmt(resultado));
      recalcularCambio();
    });

    $('#pagacon').on('input', recalcularCambio);

    // ── Finalizar venta ────────────────────────────────────────────────
    $(document).on('click', '#btnfinalizar, #btnfinalizar2', function() {
      var id_salida      = $('#id_salida').val();
      var formapago      = $('#formapago').val();
      var totalventacalc = $('#totalventacalc').val();

      if (!totalventacalc || parseFloat(totalventacalc) == 0) {
        alert("Agregue al menos un artículo antes de finalizar."); return;
      }
      if (!confirm("¿Desea finalizar la captura de esta venta?")) return;

      var $btn = $(this).prop('disabled', true)
                        .html('<i class="fa fa-spinner fa-spin"></i> Finalizando...');
      $.ajax({
        url: "{{ url('salidas/finalizarsalida') }}/" + id_salida + '/' + formapago + '/' + totalventacalc,
        success: function() {
          window.open('/salidas/ventapdf/' + id_salida, '_blank');
          window.location.href = '/salidas';
        },
        error: function() {
          $btn.prop('disabled', false)
              .html('<i class="fa fa-flag-checkered"></i>&nbsp; Finalizar venta');
          alert("Error al finalizar la venta.");
        }
      });
    });

  });
</script>
@endsection
