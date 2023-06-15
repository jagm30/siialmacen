<!DOCTYPE html>
<html lang="ES">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>SII Almacen</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
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
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <style type="text/css" media="screen">
    .content-wrapper{background-image: url(/images/fondo.jpg);}  
    .flotante {
      display:scroll;
          position:fixed;
          bottom: 0px;
          right:0px;
  }
  </style>
  
</head>
<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">

  <header class="main-header">
    <nav class="navbar navbar-static-top" style="background-color:  lightslategray">
      <div class="container">
        <div class="navbar-header">
          <a href="{{ url('/') }}" class="navbar-brand"><b>SIIAlmacen</b></a>
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
            <i class="fa fa-bars"></i>
          </button>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
          @guest

          @else
          <ul class="nav navbar-nav">
            <li><a href="/productos/">Catalogo de articulos</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Entradas<span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="/entradas/">Registrar entrada</a></li>
                <li><a href="#">Reportes</a></li>            
              </ul>
            </li>            
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Salida<span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="/cobros/reporte/">Inventario</a></li>
                <li><a href="/cobros/deudores/">Deudores</a></li>
              </ul>
            </li>
            <li><a href="/inventario">Inventario</a></li>
            <li><a href="/usuarios/">Usuarios</a></li>
            <li><a href="{{ route('logout') }}" class="btn btn-primary" onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">Salir</a></li>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
           </ul>  
          @endguest
        </div>
        <!-- /.navbar-collapse -->
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
            <!-- Messages: style can be found in dropdown.less-->

            <!-- User Account Menu -->
            <li class="dropdown user user-menu">
              <!-- Menu Toggle Button -->
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <!-- The user image in the navbar-->
                @guest
                    <img src="{{ asset("dist/img/user2-160x160.jpg") }}" class="user-image" alt="User Image">
                  @else
                    <img src="{{ asset("dist/img/user2-160x160.jpg") }}" class="user-image" align="middle" alt="User Image">
                @endguest
                <!-- hidden-xs hides the username on small devices so only the image appears. -->
                <span class="hidden-xs">
                    @guest
                        @if (Route::has('login'))                           
                          <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        @endif
                    @else
                        {{ Auth::user()->name }}                        
                    @endguest                                            
                </span>
              </a>
              <ul class="dropdown-menu">
                <!-- The user image in the menu -->
                <li class="user-header">
                  @guest
                      <img src="{{ asset("dist/img/user2-160x160.jpg") }}" class="img-circle" alt="User Image">
                    @else
                      <img src="{{ asset("dist/img/user2-160x160.jpg") }}" class="img-circle" alt="User Image">
                  @endguest
                  

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
              </ul>
            </li>         
          </ul>
        </div>
        <!-- /.navbar-custom-menu -->
      </div>
      <!-- /.container-fluid -->
    </nav>
  </header>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container">
      <!-- Content Header (Page header) -->
      <!-- Main content -->
      <section class="content">
        @yield("contenidoprincipal")
      </section>
      <!-- /.content -->
    </div>
    <!-- /.container -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="container">
      <div class="pull-right hidden-xs">
        <b>Version</b> 1.0
      </div>
      <strong>Copyright &copy; 2019-2020 
    </div>
    <!-- /.container -->
  </footer>
</div>
<!-- ./wrapper -->
<!-- jQuery 3 -->
<!-- Select2 -->

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
<script src="{{ asset("bower_components/datatables.net/js/jquery.dataTables.min.js") }}"></script>
<script src="{{ asset("bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js") }}"></script>
<!-- Select2 
https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css
https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js
https://cdn.datatables.net/buttons/1.4.0/js/dataTables.buttons.min.js
https://cdn.datatables.net/buttons/1.4.0/js/buttons.flash.min.js
https://cdn.datatables.net/buttons/1.4.0/js/buttons.html5.min.js
https://cdn.datatables.net/buttons/1.4.0/js/buttons.print.min.js
https://cdn.datatables.net/buttons/1.4.0/css/buttons.dataTables.min.css
https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js
https://cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/pdfmake.min.js
https://cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/vfs_fonts.js
-->

@yield("scriptpie")
<style>
.container2:hover {
  background-color: gray;
}
</style>


</body>
</html>