<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" prefix="og: https://ogp.me/ns#">

<head itemscope itemtype="https://schema.org/WebSite">
    <base href="{{ url('/') }}" />
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="favicon" src="" />
    <meta itemprop="author" content="http://guenanank.com" />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title itemprop="name">@yield('title', 'Home') </title>
    <!-- Styles -->
    <link href="{{ mix('/css/fontawesome.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <link href="{{ mix('/css/sweetalert2.css') }}" rel="stylesheet">
    <link href="{{ mix('/css/dataTables.bootstrap4.css') }}" rel="stylesheet">

    <link href="{{ mix('/css/sb-admin-2.css') }}" rel="stylesheet">
    <link href="{{ mix('/css/loader.css') }}" rel="stylesheet">

    @stack('styles')

    <style type="text/css">
        .table tbody>tr>th,
        .table tbody>tr>td {
            vertical-align: middle;
        }
    </style>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body id="page-top">
    <div id="wrapper">
        @include('components.sidebar')
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                @include('components.topbar')
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <!-- <h1 class="h3 mb-4 text-gray-800">@yield('page_header')</h1> -->
                    @yield('content')
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->
            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; {{ ucfirst(config('app.name')) }} {{ date('Y') }}</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="{{ route('logout') }}">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- /#spinner preload -->
    <div class="loader loader-default" data-text></div>

    <!-- scripts -->
    <script src="{{ mix('/js/jquery.js') }}"></script>
    <script>
        (function($) {
            "use strict";
            const baseUrl = $('base').attr('href');
            var token = $('meta[name="csrf-token"]').attr('content');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    _token: $('meta[name="csrf-token"]').attr('content')
                }
            });
        })(jQuery);
    </script>
    <script src="{{ mix('/js/bootstrap.bundle.js') }}"></script>
    <script src="{{ mix('/js/moment.js') }}"></script>
    <script src="{{ mix('/js/moment-timezone.js') }}"></script>
    <script src="{{ mix('/js/jquery.easing.js') }}"></script>
    <script src="{{ mix('/js/sweetalert2.js') }}"></script>

    <script src="{{ mix('/js/jquery.dataTables.js') }}"></script>
    <script src="{{ mix('/js/dataTables.bootstrap4.js') }}"></script>

    <script>
        moment.tz.setDefault('Asia/Jakarta');
        // moment.locale('id');
        // $.extend($.fn.dataTable.defaults, {
        //     keys: true,
        //     responsive: true,
        //     language: {
        //         "sEmptyTable": "Tidak ada data yang tersedia pada tabel ini",
        //         "sProcessing": "Sedang memproses...",
        //         "sLengthMenu": "Tampilkan _MENU_ entri",
        //         "sZeroRecords": "Tidak ditemukan data yang sesuai",
        //         "sInfo": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
        //         "sInfoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
        //         "sInfoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
        //         "sInfoPostFix": "",
        //         "sSearch": "Cari:",
        //         "sUrl": "",
        //         "oPaginate": {
        //             "sFirst": "Pertama",
        //             "sPrevious": "Sebelumnya",
        //             "sNext": "Selanjutnya",
        //             "sLast": "Terakhir"
        //         }
        //     }
        // });
        $('.dataTables_length').addClass('bs-select');
        var numberFormat = function(number) {
            var regex = parseFloat(number, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
            return 'Rp. ' + regex.slice(0, -3);
        };
    </script>

    <script src="{{ mix('/js/ajaxForm.js') }}"></script>
    <script src="{{ mix('/js/sb-admin-2.js') }}"></script>
    @stack('scripts')

    <script>
        $(document).ready(function() {

            $('form.ajaxForm').submit(function(e) {
                e.preventDefault();
                var form = e.target;
                var data = new FormData(form);
                $.each(form.files, function(k, v) {
                    data.append('photos', form.files[k]);
                });

                $(this).ajaxForm({
                    data: data,
                    beforeSend: function() {
                        $('.loader').addClass('is-active');
                    },
                    afterSend: function() {
                        $('.loader').removeClass('is-active');
                    }
                });
            });

            $('body').on('click', 'a.delete', function(e) {
                e.preventDefault();
                $(this).ajaxDelete();
            });

            if ($('.selectpicker')[0]) {
                $('.selectpicker').selectpicker();
            }

            if ($('.autosize')[0]) {
                autosize($('.autosize'));
            }

            if ($('.colorPicker')[0]) {
                $('.colorPicker').colorpicker();
            }

            if ($('.fileInput')[0]) {
                $('.fileInput').fileinput({
                    theme: 'fas',
                    browseLabel: 'Find',
                    showRemove: false,
                    showUpload: false,
                    showPreview: false
                });
            }

            if ($('#datetimepicker')[0]) {
                $('#datetimepicker').datetimepicker({
                    format: 'YYYY-MM-DD HH:mm:ss'
                });
            }
        });

        console.info('%cI\'m watching you bitch !', [
            'color: black',
            'font-size: 30px',
            'text-shadow: 2px 2px black',
            'padding: 10px',
        ].join(';'));
    </script>
</body>

</html>
