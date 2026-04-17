@extends('layouts.app')
@section('contenidoprincipal')

<input type="hidden" id="csrf" value="{{ Session::token() }}">

{{-- ═══════════════════════════════════════════
     PASO 1 — Datos generales de la entrada
═══════════════════════════════════════════ --}}
<div id="panel-cabecera">
  <div class="box box-success">
    <div class="box-header with-border" style="background:#00a65a; color:#fff; padding:10px 15px;">
      <h3 class="box-title" style="color:#fff;">
        <i class="fa fa-truck"></i>&nbsp; Nueva entrada — Datos generales
      </h3>
      <div class="box-tools pull-right">
        <a href="/entradas" class="btn btn-sm"
           style="color:#fff; background:transparent; border:1px solid rgba(255,255,255,.5);">
          <i class="fa fa-arrow-left"></i> Cancelar
        </a>
      </div>
    </div>
    <div class="box-body">
      <div class="row">
        <div class="form-group col-md-4">
          <label>No. de factura</label>
          <input id="nfactura" type="text" class="form-control" placeholder="Número de factura" autofocus>
        </div>
        <div class="form-group col-md-4">
          <label>Proveedor</label>
          <select id="proveedor" class="form-control">
            @foreach($proveedores as $prov)
              <option value="{{ $prov->id }}">{{ $prov->nombre }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group col-md-4">
          <label>Fecha de recepción</label>
          <input id="fecha-entrada" type="date" class="form-control" value="{{ $date }}">
        </div>
        <div class="form-group col-md-4">
          <label>Referencia / Orden de compra</label>
          <input id="referencia" type="text" class="form-control" value="NA">
        </div>
        <div class="form-group col-md-4">
          <label>Almacén</label>
          <select id="categoria" class="form-control">
            @foreach($almacenes as $almacen)
              <option value="{{ $almacen->id }}">{{ $almacen->nombre }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group col-md-4">
          <label>Observaciones</label>
          <input id="observaciones" type="text" class="form-control" value="NINGUNO">
        </div>
      </div>
      <div id="cajaerror" class="col-md-12"></div>
    </div>
    <div class="box-footer">
      <button id="btn_guardaregistro" type="button" class="btn btn-success btn-lg">
        <i class="fa fa-arrow-right"></i>&nbsp; Continuar y agregar artículos
      </button>
      <a href="/entradas" class="btn btn-default btn-lg" style="margin-left:8px;">Cancelar</a>
    </div>
  </div>
</div>

{{-- ═══════════════════════════════════════════
     PASO 2 — Agregar productos (oculto hasta crear la entrada)
═══════════════════════════════════════════ --}}
<div id="panel-productos" style="display:none;">

  {{-- Barra de info de la entrada activa --}}
  <div class="box box-primary" style="margin-bottom:10px;">
    <div class="box-header with-border" style="padding:8px 15px;">
      <div style="display:flex; align-items:center; flex-wrap:wrap; gap:4px 20px;">
        <div style="font-size:14px; font-weight:bold; white-space:nowrap;">
          <i class="fa fa-truck"></i>&nbsp; Entrada de artículos
        </div>
        <div id="info-almacen"   style="font-size:13px; color:#555;"></div>
        <div id="info-factura"   style="font-size:13px; color:#555;"></div>
        <div id="info-proveedor" style="font-size:13px; color:#555;"></div>
        <div id="info-fecha"     style="font-size:13px; color:#555;"></div>
        <span class="label label-warning" style="font-size:12px; padding:4px 8px;">
          <i class="fa fa-pencil"></i> En captura
        </span>
        <div style="margin-left:auto; display:flex; gap:8px; align-items:center;">
          <a href="/entradas" class="btn btn-sm btn-default">
            <i class="fa fa-list"></i> Ver lista
          </a>
          <button type="button" class="btn btn-lg btn-warning" id="btnfinalizar"
                  style="font-weight:bold; letter-spacing:.5px;">
            <i class="fa fa-flag-checkered"></i>&nbsp; Finalizar captura
          </button>
        </div>
      </div>
    </div>
  </div>

  <div class="row">

    {{-- Columna izquierda: formulario agregar artículo --}}
    <div class="col-md-4">
      <div class="box box-success">
        <div class="box-header with-border"
             style="background:#00a65a; color:#fff; padding:10px 15px;">
          <h3 class="box-title" style="color:#fff;">
            <i class="fa fa-plus-circle"></i>&nbsp; Agregar artículo
          </h3>
        </div>
        <div class="box-body">
          <input id="id_entrada"  type="hidden" value="">
          <input id="cat_entrada" type="hidden" value="">

          <div class="form-group">
            <label><i class="fa fa-search"></i> Artículo</label>
            <select id="id_producto" class="form-control select2" style="width:100%;">
              <option value="">Seleccione un artículo...</option>
              @foreach($productos as $producto)
                <option value="{{ $producto->id }}">{{ $producto->descripcion }}</option>
              @endforeach
            </select>
          </div>

          <div class="row">
            <div class="col-xs-6">
              <div class="form-group">
                <label>Cantidad</label>
                <input id="cantidad" type="number" class="form-control input-sm" min="1" placeholder="1">
              </div>
            </div>
            <div class="col-xs-6">
              <div class="form-group">
                <label>Precio factura</label>
                <div class="input-group input-group-sm">
                  <span class="input-group-addon">$</span>
                  <input id="precio" type="text" class="form-control" placeholder="0.00" value="0">
                </div>
              </div>
            </div>
          </div>

          <button id="btn_agregar_producto" type="button" class="btn btn-success btn-block">
            <i class="fa fa-plus"></i>&nbsp; Agregar artículo
          </button>
        </div>
      </div>

      <button type="button" class="btn btn-warning btn-block" id="btnfinalizar2"
              style="font-weight:bold; font-size:15px; margin-bottom:15px;">
        <i class="fa fa-flag-checkered"></i>&nbsp; Finalizar captura
      </button>
    </div>

    {{-- Columna derecha: tabla de artículos --}}
    <div class="col-md-8">
      <div class="box box-default">
        <div class="box-header with-border" style="padding:10px 15px;">
          <h3 class="box-title">
            <i class="fa fa-list"></i>&nbsp; Artículos de la entrada
          </h3>
        </div>
        <div class="box-body" style="padding:10px;">
          <table id="productos_table"
                 class="table table-bordered table-striped table-hover"
                 style="font-size:13px;">
            <thead>
              <tr>
                <th>Descripción</th>
                <th style="width:80px; text-align:center;">Cantidad</th>
                <th style="width:110px; text-align:right;">Precio</th>
                <th style="width:110px; text-align:right;">Subtotal</th>
                <th style="width:60px; text-align:center;">Acción</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th colspan="2"></th>
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

  var productosTable = null;

  function initProductosTable(id_entrada) {
    if (productosTable) { productosTable.destroy(); productosTable = null; }
    productosTable = $('#productos_table').DataTable({
      processing: true, serverSide: true,
      ajax: "/entradaproductos/listarxentrada/" + id_entrada,
      columns: [
        { data: 'descripcion', name: 'descripcion' },
        { data: 'cantidad', name: 'cantidad',
          render: function(d) { return '<span style="display:block;text-align:center;">' + d + '</span>'; }
        },
        { data: 'precio', name: 'precio',
          render: function(d) { return '<span style="display:block;text-align:right;">$' + numFmt(d) + '</span>'; }
        },
        { data: 'subtotal', name: 'subtotal',
          render: function(d) { return '<span style="display:block;text-align:right;">$' + numFmt(d) + '</span>'; }
        },
        { data: 'action', name: 'action', orderable: false }
      ],
      searching: false, paging: false, autoWidth: false,
      language: { processing: 'Cargando...', emptyTable: 'Sin artículos agregados.' },
      footerCallback: function() {
        var total = this.api().column(3).data()
          .reduce(function(a, b) { return parseFloat(a) + parseFloat(b); }, 0);
        $('#footer-total').html(
          '<span style="display:block;text-align:right;color:#00a65a;font-size:14pt;font-weight:bold;">$' + numFmt(total) + '</span>'
        );
      }
    });
  }

  $(function() {

    $("#menuentradas").addClass("important active");
    $('.select2').select2();

    // ── Paso 1: guardar cabecera y mostrar paso 2 ──────────────────────
    $('#btn_guardaregistro').click(function() {
      var nfactura      = $('#nfactura').val().trim();
      var proveedor     = $('#proveedor').val();
      var fecha         = $('#fecha-entrada').val();
      var referencia    = $('#referencia').val();
      var categoria     = $('#categoria').val();
      var observaciones = $('#observaciones').val();

      if (!nfactura) {
        $('#cajaerror').html('<div class="alert alert-warning">Ingrese el No. de factura.</div>');
        $('#nfactura').focus(); return;
      }
      if (!proveedor) {
        $('#cajaerror').html('<div class="alert alert-warning">Seleccione un proveedor.</div>'); return;
      }
      if (!categoria) {
        $('#cajaerror').html('<div class="alert alert-warning">Seleccione un almacén.</div>'); return;
      }

      var $btn = $(this).prop('disabled', true)
                        .html('<i class="fa fa-spinner fa-spin"></i> Guardando...');

      $.ajax({
        url: "/entradas", type: "POST",
        data: {
          _token: $('#csrf').val(), type: 1,
          proveedor: proveedor, fecha: fecha, nfactura: nfactura,
          referencia: referencia, categoria: categoria,
          observaciones: observaciones, status: 'captura',
          id_almacen: 1, id_usuario: {{ Auth::user()->id }}
        },
        success: function(result) {
          // Obtener datos completos con joins
          $.ajax({
            url: "/entradas/" + result.data, dataType: "json",
            success: function(entrada) {
              $('#id_entrada').val(entrada.id);
              $('#cat_entrada').val(entrada.categoria);
              $('#info-almacen').html('<i class="fa fa-building-o" style="color:#aaa;"></i>&nbsp;<strong>' + (entrada.nomalmacen || '') + '</strong>');
              $('#info-factura').html('<i class="fa fa-file-text-o" style="color:#aaa;"></i>&nbsp;Factura: <strong>' + (entrada.nfactura || '—') + '</strong>');
              $('#info-proveedor').html('<i class="fa fa-user" style="color:#aaa;"></i>&nbsp;<strong>' + (entrada.nombreproveedor || '—') + '</strong>');
              $('#info-fecha').html('<i class="fa fa-calendar" style="color:#aaa;"></i>&nbsp;' + (entrada.fecha || ''));
              // Transición: ocultar paso 1, mostrar paso 2
              $('#panel-cabecera').slideUp(300, function() {
                $('#panel-productos').slideDown(300);
                initProductosTable(entrada.id);
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

    // ── Cargar precio al seleccionar artículo ──────────────────────────
    $('#id_producto').change(function() {
      var id = $(this).val();
      if (!id) { $('#precio').val('0'); return; }
      $.ajax({
        url: "/productos/" + id, dataType: "json",
        success: function(data) {
          $('#precio').val(data.precio);
          $('#cantidad').val(1).focus();
        }
      });
    });

    // ── Agregar artículo ───────────────────────────────────────────────
    $('#btn_agregar_producto').click(function() {
      var id_entrada  = $('#id_entrada').val();
      var id_producto = $('#id_producto').val();
      var cantidad    = $('#cantidad').val();
      var precio      = $('#precio').val();
      var categoria   = $('#cat_entrada').val();

      if (!id_producto) { alert("Seleccione un artículo."); return; }
      if (!cantidad || parseInt(cantidad) <= 0) { alert("Ingrese una cantidad válida."); return; }

      var $btn = $(this).prop('disabled', true)
                        .html('<i class="fa fa-spinner fa-spin"></i> Agregando...');
      $.ajax({
        url: "/entradaproductos", type: "POST",
        data: {
          _token: $('#csrf').val(),
          id_entrada: id_entrada, id_producto: id_producto,
          cantidad: cantidad, precio: precio,
          categoria: categoria, status: 'captura',
          id_usuario: {{ Auth::user()->id }}
        },
        success: function() {
          productosTable.ajax.reload();
          $('#id_producto').val('').trigger('change');
          $('#cantidad').val('');
          $('#precio').val('0');
        },
        complete: function() {
          $btn.prop('disabled', false).html('<i class="fa fa-plus"></i>&nbsp; Agregar artículo');
        }
      });
    });

    // ── Eliminar artículo ──────────────────────────────────────────────
    $(document).on('click', '#btn-eliminar', function() {
      var id = $(this).data('id');
      if (confirm("¿Desea eliminar este artículo?")) {
        $.ajax({
          url: "{{ url('entradaproductos/delete') }}/" + id,
          success: function() { productosTable.ajax.reload(); }
        });
      }
    });

    // ── Finalizar captura ──────────────────────────────────────────────
    $(document).on('click', '#btnfinalizar, #btnfinalizar2', function() {
      var id_entrada = $('#id_entrada').val();
      if (!confirm("¿Desea finalizar la captura de esta entrada?")) return;

      var $btn = $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Finalizando...');
      $.ajax({
        url: "{{ url('entradas/finalizarentrada') }}/" + id_entrada,
        success: function() {
          window.open('/entradas/reportepdf/' + id_entrada, '_blank');
          window.location.href = '/entradas';
        },
        error: function() {
          $btn.prop('disabled', false).html('<i class="fa fa-flag-checkered"></i>&nbsp; Finalizar captura');
          alert("Error al finalizar la entrada.");
        }
      });
    });

  });
</script>
@endsection
