<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @stack('css')
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">

    <link rel="icon" type="image/png" href="{{ asset('faviconn.png') }}">

    <!-- Tambahkan Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            color: #2d2d2d;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-weight: 600;
        }

        /* 🔹 Samakan font dan ukuran di semua elemen form */
        input,
        select,
        textarea,
        .form-control,
        .form-select {
            font-family: 'Inter', sans-serif !important;
            font-size: 13.5px !important;
            color: #2d2d2d;
        }

        /* 🔹 Samakan juga untuk tombol dan link */
        .btn,
        .nav-link,
        .dropdown-item {
            font-family: 'Inter', sans-serif;
            font-size: 13.5px;
        }

        /* 🔹 Jika ingin input lebih kompak */
        .form-control {
            padding: 0.35rem 0.6rem;
            height: auto;
        }
    </style>

</head>

<body>
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">

        <!-- Main content -->
        <section class="content">
            @yield('content')
        </section>
        <!-- /.content -->
    </div>


    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('adminlte/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap -->
    <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>


    <!-- overlayScrollbars -->
    <script src="{{ asset('adminlte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/chart.js/Chart.min.js') }}"></script>

    <!-- AdminLTE App -->
    <script src="{{ asset('adminlte/dist/js/adminlte.js') }}"></script>

    {{-- validator --}}
    <script src="{{ asset('js/validator.min.js') }}"></script>

    @stack('js')
    @yield('scripts')
</body>

</html>
