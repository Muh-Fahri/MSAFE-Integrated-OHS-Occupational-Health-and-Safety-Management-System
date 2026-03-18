    @include('layouts.header')
    @include('layouts.navbar')
    @include('layouts.sidebar')

    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                {{ $slot }}
            </div>
        </div>

        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <script>
                            document.write(new Date().getFullYear())
                        </script><a href="https://masmindo.co.id/" class="text-dark">
                            <strong>© PT Masmindo Dwi Area.</strong>
                        </a>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-sm-end d-none d-sm-block">
                            {{-- Crafted with <i class="mdi mdi-heart text-danger"></i> by Themesbrand --}}
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- end main content-->
    @include('layouts.footer')
