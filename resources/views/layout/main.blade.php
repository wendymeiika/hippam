<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title')</title>
        <meta content="Hippam Dashboard" name="description" />
        <meta content="Hippam Kaligondo" name="author" />
        <link rel="shortcut icon" href="assets/images/favicon.ico">

        <!-- DataTables -->
        <link href="{{ asset('plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- Responsive datatable examples -->
        <link href="{{ asset('plugins/datatables/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- Sweet Alert -->
        <link href="../plugins/sweet-alert2/sweetalert2.min.css" rel="stylesheet" type="text/css">

        <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css">

        @yield('css')

    </head>

    <body>

        <!-- Navigation Bar-->
        <header id="topnav">
            <div class="topbar-main">
                <div class="container-fluid">

                    <!-- Logo container-->
                    <div class="logo">

                        <!-- <a href="{{ url('/home') }}" class="logo">
                            <img src="assets/images/logo-sm-light.png" alt="" class="logo-small">
                            <img src="assets/images/logo-light.png" alt="" class="logo-large">
                        </a> -->
                        <a href="{{ url('/home') }}" class="d-flex flex-row align-items-center">
                            <img src="assets/images/logo/2.jpg" alt="" class="img-logo">
                            <h3 class="ml-3 text-white">Hippam Kaligondo</h3>
                        </a>

                    </div>

                    <div class="clearfix"></div>

                </div>
            </div>

            <div class="navbar-custom">
                <div class="container-fluid">
                    <div id="navigation">
                        <ul class="navigation-menu">

                            <li class="has-submenu {{ (request()->is('home*')) ? 'active' : '' }}">
                                <a href="{{ url('/home') }}"><i class="mdi mdi-home"></i>Home</a>
                            </li>

                            {{-- PELANGGAN & Ketua RT --}}
                            <x-pelanggan-sidebar :permissions="Auth::user()->role->permissions" />

                            {{-- PETUGAS --}}
                            <x-petugas-sidebar :permissions="Auth::user()->role->permissions" />

                            {{-- ADMIN --}}
                            <x-admin-sidebar :permissions="Auth::user()->role->permissions" />

                            <li class="has-submenu {{ (request()->is('profile*')) ? 'active' : '' }}">
                                <a href="#"><i class="mdi mdi-account"></i>Profile</a>
                                <ul class="submenu megamenu">
                                    <li>
                                        <ul>
                                            <li><a href="{{ url('/profile/edit-profile') }}">Edit profile</a></li>
                                            <li><a href="{{ url('/profile/ganti-password') }}">Ganti Password</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>


                            <li class="has-submenu">
                                <a href="#" onclick="$('#logout').submit();"><i class="mdi mdi-logout"></i>Keluar</a>
                                <form method="POST" action="{{ route('logout') }}" id="logout">
                                    @csrf
                                </form>
                            </li>

                        </ul>

                    </div>
                </div>
            </div>
        </header>

        <div class="mt-4"></div>
        @yield('content')

        <!-- Footer -->
        <footer class="footer">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-12">
                        2022 © Hippam Kaligondo</span>
                    </div>
                </div>
            </div>
        </footer>
        <!-- End Footer -->


        <!-- jQuery  -->
        <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('assets/js/jquery.slimscroll.js') }}"></script>
        <script src="{{ asset('assets/js/waves.min.js') }}"></script>

        <script src="{{ asset('plugins/jquery-sparkline/jquery.sparkline.min.js') }}"></script>

        <!-- Required datatable js -->
        <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
        <!-- Buttons examples -->
        <script src="{{ asset('plugins/datatables/dataTables.buttons.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables/buttons.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables/jszip.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables/pdfmake.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables/vfs_fonts.js') }}"></script>
        <script src="{{ asset('plugins/datatables/buttons.html5.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables/buttons.print.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables/buttons.colVis.min.js') }}"></script>
        <!-- Responsive examples -->
        <script src="{{ asset('plugins/datatables/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables/responsive.bootstrap4.min.js') }}"></script>

        <!-- Datatable init js -->
        <script src="{{ asset('assets/pages/datatables.init.js') }}"></script>

        <!--DateRangePicker -->
        <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
        <script type="text/javascript" src="//cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

        <!-- Sweet-Alert  -->
        <script src="../plugins/sweet-alert2/sweetalert2.min.js"></script>

        <!-- App js -->
        <script src="{{ asset('assets/js/app.js') }}"></script>

        <script>
            function toMonthName(monthNumber) {
                const date = new Date();
                date.setMonth(monthNumber - 1);

                return date.toLocaleString('id-ID', {
                    month: 'long',
                });
            }
        </script>

        @if($message = Session::get('success'))
        <script>
            swal({
                title: 'Berhasil',
                text: '{{ $message }}',
                type: 'success',
                confirmButtonClass: 'btn btn-success',
            })
        </script>
        @endif

        @if($message = Session::get('error'))
        <script>
            swal({
                title: 'Error',
                type: 'error',
                html: '{{ $message }}',
                showCloseButton: true,
            })
        </script>
        @endif

        @yield('js')

    </body>

</html>
