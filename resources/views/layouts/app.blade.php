<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard')</title>

    <!-- AdminLTE CSS -->
    <link href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
       <!-- Sidebar toggle button -->
       <ul class="navbar-nav">
         <li class="nav-item">
           <a class="nav-link" data-widget="pushmenu" href="#" role="button">
             <i class="fa fa-bars"></i>
           </a>
         </li>
       </ul>
            <span class="navbar-brand ml-3" >Admin Management</span>
            
        </nav>

        <!-- Sidebar -->
        <aside class="main-sidebar sidebar-dark-primary bg-dark elevation-3">
          
          @include('layouts.sidebar')
        </aside>

        <!-- Content Wrapper -->
        <div class="content-wrapper p-3">
            @yield('content')
        </div>

        <!-- Footer -->
        <footer class="main-footer text-center">
            <strong>Copyright © {{ date('Y') }}&nbsp;Ibrahim jamal</strong>
        </footer>
    </div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- general JS -->
<script src="{{asset('js/script.js')}}"></script>
<script src="{{asset('js/print.js')}}"></script>
@stack('scripts')
</body>
</html>
