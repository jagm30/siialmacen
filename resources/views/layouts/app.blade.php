<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>SISTEMA DE ALMACÉN</title>
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="{{ asset("bower_components/bootstrap/dist/css/bootstrap.min.css") }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset("bower_components/font-awesome/css/font-awesome.min.css") }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{ asset("bower_components/Ionicons/css/ionicons.min.css") }}">
  <!-- jvectormap -->
  <link rel="stylesheet" href="{{ asset("bower_components/jvectormap/jquery-jvectormap.css") }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset("dist/css/AdminLTE.min.css") }}">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="{{ asset("dist/css/skins/_all-skins.min.css") }}">
 <!-- DataTables -->
 <link rel="stylesheet" href="{{ asset("bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css") }}">

 <link rel="stylesheet" href="{{ asset("bower_components/select2/dist/css/select2.min.css") }}"> 
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
<style type="text/css">
  table.dataTable thead{
    background-color: rgb(17,17,111);
    color: azure;
  }
  .page-item.active .page.link{
    background-color: rgb(17,17,111) !important;
    color: azure !important;
  }
  .page-link{
    color: black !important
  }
  .btn-flotante {
  font-size: 16px; /* Cambiar el tamaño de la tipografia */
  text-transform: uppercase; /* Texto en mayusculas */
  font-weight: bold; /* Fuente en negrita o bold */
  color: #ffffff; /* Color del texto */
  border-radius: 5px; /* Borde del boton */
  letter-spacing: 2px; /* Espacio entre letras */
  background-color: #E91E63; /* Color de fondo */
  padding: 18px 30px; /* Relleno del boton */
  position: fixed;
  bottom: 40px;
  right: 40px;
  transition: all 300ms ease 0ms;
  box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.1);
  z-index: 99;
}
.btn-flotante:hover {
  background-color: #2c2fa5; /* Color de fondo al pasar el cursor */
  box-shadow: 0px 15px 20px rgba(0, 0, 0, 0.3);
  transform: translateY(-7px);
}
@media only screen and (max-width: 600px) {
  .btn-flotante {
    font-size: 14px;
    padding: 12px 20px;
    bottom: 20px;
    right: 20px;
  }
} 
</style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="{{ url('/') }}" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>SII</b>A</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>ALMACEN</b>SII</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="../../dist/img/user2-160x160.png" class="user-image" alt="User Image">
              <span class="hidden-xs">@guest
                        @if (Route::has('login'))                           
                          <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        @endif
                    @else
                        {{ Auth::user()->name }}                        
                    @endguest  </span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="../../dist/img/user2-160x160.png" class="img-circle" alt="User Image">

                <p>
                  @guest
                        @if (Route::has('login'))                           
                          <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        @endif
                    @else
                        {{ Auth::user()->name }}                        
                    @endguest  
                </p>
              </li>
              <!-- Menu Body -->

              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-right">
                  <a href="{{ route('logout') }}" class="btn btn-warning btn-flat" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Cerrar sesión</a>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
                </div>
              </li>
            </ul>
          </li>

        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="../../dist/img/user2-160x160.png" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          @guest
                @if (Route::has('login'))                           
                  <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                @endif
            @else
              <p>
                {{ Auth::user()->name }}
                <br>
                
              </p>
            @endguest  
        </div>
      </div>

      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MENU DE NAVEGACION</li>
        @guest

        @else
        
        <li id="menuinicio">
          <a href="/home">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          </a>
        </li>
        <li id="menuproductos">
          <a href="/productos/">
            <i class="fa fa-th"></i> <span>Catálogo de Productos</span>
            <span class="pull-right-container">              
            </span>
          </a>
        </li>
        <li id="menuentradas">
          <a href="/entradas/">
            <i class="fa fa-share"></i> <span>Entrada de mercancia</span>
            <span class="pull-right-container">              
            </span>
          </a>
        </li>
        <!-- <li id="menusalida">
          <a href="/salidas/">
            <i class="fa fa-reply"></i> <span>Ventas</span>
            <span class="pull-right-container">              
            </span>
          </a>
        </li> -->
        <li id="menuventauniforme">
          <a href="/salidas/ventauniforme/">
            <i class="fa fa-reply"></i> <span>Venta de uniformes</span>
            <span class="pull-right-container">              
            </span>
          </a>
        </li>
        <li id="menuinventario">
          <a href="/inventario/">
            <i class="fa fa-pie-chart"></i> <span>Inventario</span>
            <span class="pull-right-container">              
            </span>
          </a>
        </li>
        <li id="menuusuario">
          <a href="#">
            <i class="fa fa-user"></i> <span>Usuarios</span>
            <span class="pull-right-container">              
            </span>
          </a>
        </li>
       
        @endguest
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->


    <!-- Main content -->
    <section class="content">
      @yield("contenidoprincipal")    
        
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 2.4.13
    </div>
    <strong>Copyright &copy; 2023 <a href="https://adminlte.io">AdminLTE</a>.</strong> All rights
    reserved.
  </footer>


  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="{{ asset("bower_components/jquery/dist/jquery.min.js") }}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset("bower_components/bootstrap/dist/js/bootstrap.min.js") }}"></script>
<!-- FastClick -->
<script src="{{ asset("bower_components/fastclick/lib/fastclick.js") }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset("bower_components/select2/dist/js/select2.full.min.js") }}"></script>
<script src="{{ asset("dist/js/adminlte.min.js") }}"></script>
<!-- Sparkline -->
<script src="{{ asset("bower_components/jquery-sparkline/dist/jquery.sparkline.min.js") }}"></script>
<!-- jvectormap  -->
<script src="{{ asset("plugins/jvectormap/jquery-jvectormap-1.2.2.min.js") }}"></script>
<script src="{{ asset("plugins/jvectormap/jquery-jvectormap-world-mill-en.js") }}"></script>
<!-- SlimScroll -->
<script src="{{ asset("bower_components/jquery-slimscroll/jquery.slimscroll.min.js") }}"></script>
<!-- ChartJS -->
<script src="{{ asset("bower_components/chart.js/Chart.js") }}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{ asset("dist/js/pages/dashboard2.js") }}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset("dist/js/demo.js") }}"></script>
<!-- DataTables -->
<!-- plugins para descargar los aricvos Excel, PDF y mas -->
<script src="{{ asset("bower_components/datatables.net/js/jquery.dataTables.min.js") }}"></script>
<script src="{{ asset("bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js") }}"></script>>
<script src="{{ asset("https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js") }}"></script>>
<script src="{{ asset("https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js") }}"></script>>
<script src="{{ asset("https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js") }}"></script>>
<script src="{{ asset("https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js") }}"></script>>
<script src="{{ asset("https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js") }}"></script>>
<script src="{{ asset("https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js") }}"></script>>

@yield("scriptpie")
<style>
.container2:hover {
  background-color: gray;
}
</style>

</body>
</html>
