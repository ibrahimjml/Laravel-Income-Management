<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard')</title>

    <!-- AdminLTE CSS -->
    <link href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.0.0/css/flag-icons.min.css">
      <!-- datepicker  CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
    <!-- rtl CSS -->
    @unless($dir === 'ltr')
    <link rel="stylesheet" href="{{ asset('css/rtl.css') }}">
    @endunless

    <style>
      .arrow {font-size: 12px;margin-left: 5px;}
      .arrow.asc::after {content: "▲";}
      .arrow.desc::after { content: "▼";}

    @media (400px <= width <= 600px) {
    .small-box {display: flex;flex-direction: row;justify-content: space-between;align-items: center;}    
    .small-box .icon {  display: block ;}
    .small-box-footer {position: absolute !important;bottom: 0;width: 100%;}}
    </style>
    
    @stack('styles')
</head>

<body class="hold-transition sidebar-mini layout-fixed " dir="{{ $dir }}">
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
          <span class="navbar-brand ml-3" >{{__('message.Admin Management')}}</span>

           <!-- lang drop down -->
          @include('layouts.dropdown-lang')
        </nav>

        <!-- Sidebar -->
        <aside class="main-sidebar sidebar-dark-primary bg-dark elevation-4">
          
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- general JS -->
<script src="{{asset('js/script.js')}}"></script>
@stack('scripts')
</body>
</html>
