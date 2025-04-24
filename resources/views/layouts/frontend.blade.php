<!DOCTYPE html>
<html lang="en">

<head>
    <title>Proyek SIA</title>
    @include('includes.style')
</head>

<body class="">
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->
    <!-- [ navigation menu ] start -->
    @include('includes.navbar')
    <!-- [ Header ] start -->
    @include('includes.header')
    <!-- [ Main Content ] start -->
    @yield('content')
    <!-- [ Main Content ] end -->


    @include('includes.footer')

    {{-- <script>
        document.addEventListener('DOMContentLoaded', function () {
            loadSettings(); // Jalankan loadSettings setiap halaman dimuat
        });
    </script> --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @yield('scripts')
</body>

</html>