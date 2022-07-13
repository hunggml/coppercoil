<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>STI-MES</title>
  <!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <link rel="icon" type="image/png" sizes="180x180" href="{{asset('dist/img/sti.png')}}">
  <!-- Sweet Alert 2 -->
  <link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
  <!-- Bootstrap-toggle -->
  <link rel="stylesheet" href="{{ asset('dist/bootstrap-toggle/bootstrap-toggle.min.css') }}">
  <!-- I-check -->
  <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}" media="all">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Color -->
  <link rel="stylesheet" href="{{ asset('plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}">
  <!-- Select2 -->
  <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
  
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{ asset('plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css') }}">
  
  <link rel="stylesheet" href="{{ asset('css/multi-select.css') }}">
  <!-- <link rel="stylesheet" href=""> -->
  <!-- dataTable https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css-->
  <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <!-- font awesome  -->
  <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">

  <link rel="stylesheet" href="{{ asset('css/ionicons.min.css') }}">

  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
  
  <!-- <link rel="stylesheet" href="https://printjs-4de6.kxcdn.com/print.min.css"> -->
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <style>
    table > tbody > tr > th,td {
      text-align: center;
      vertical-align: middle;
    }
    table > thead > tr > th {
      text-align: center;
      vertical-align: middle;
    }
    table.dataTable tbody td {
            vertical-align: middle;
            text-align: center;
        }

    table.dataTable thead th {
            vertical-align: middle;
            text-align: center;
    }
    .hide {
      display: none;
    }
    .btn {
      color: black;
      border: 2px solid #6c757d;
    }
  </style>
  @stack('myCss')

</head>
<body class="hold-transition sidebar-mini sidebar-collapse">
  <!-- Site wrapper -->
  <div class="wrapper">
    <!-- Navbar -->
    @include('layouts.nav-top')
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    @include('layouts.nav-left')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
        </div>
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <!-- Default box -->
              @yield('content')
              <!-- /.card -->
            </div>
          </div>
        </div>
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer">
      <div class="float-right d-none d-sm-block">
        <!-- <b>Black Clover</b> -->
      </div>
      <strong>{{__('Design By')}} <a href="#">STI</a>.</strong>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->
  <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
  <!-- Bootstrap 4 -->
  <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
  <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
  <!-- Select2 -->
  <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
  <script src="{{ asset('plugins/select2/js/i18n') }}/{{config('app.locale')}}.js"></script>
  <script src="{{ asset('plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.js') }}"></script>
  <!-- DataTables -->
  <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
  <!-- daterangepicker -->
  <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
  <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
  <!-- bootstrap color picker -->
  <script src="{{ asset('plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
  <!-- Bootstrap Switch -->
  <script src="{{ asset('plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
  <!-- ChartJS -->
  <script src="{{ asset('plugins/chart/Chart.min.js') }}"></script>
  
  <!-- Barcode -->
  <script src="{{ asset('dist/js/JsBarcode.all.min.js') }}"></script>
  <script src="{{ asset('plugins/qrcode/qrcode.min.js') }}"></script>
  <!-- <script src="https://printjs-4de6.kxcdn.com/print.min.js"></script> -->
  <!-- AdminLTE App -->
  <script src="{{  asset('dist/js/adminlte.js') }}"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="{{  asset('dist/js/demo.js') }}"></script>
  <script>let lang = $('html').attr('lang');</script>
  <!-- AngularJS 1.6.9 -->
  <script src="{{  asset('js/languages/vi.js') }}"></script>
  <script src="{{  asset('js/languages/en.js') }}"></script>
  <script src="{{  asset('js/languages/ko.js') }}"></script>
  <!-- jQuery -->  
  <script>
    let Toast = Swal.mixin({
      toast: true,
      position: 'center',
      showConfirmButton: false,
      timer: 3000
    });
    // Add Class Active Using URL
    let url = window.location.href;
    let cut = url.split('?')[0];
    let target = cut.split('/');
    $('.nav-link').removeClass('active');
    target.splice(0, 1);
    target.splice(0, 1);
    target.splice(0, 1);
    // console.log(target);
    for (let i = 0; i < target.length; i++) 
    {
      let myClass = target[i].split('#')[0];
      $('.'+ myClass).addClass('active');
    }

    $('input').prop('autocomplete', 'off');
  </script>
  @stack('scripts')
  
</body>
</html>
