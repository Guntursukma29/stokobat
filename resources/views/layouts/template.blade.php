<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="{{ asset('assets') }}/" data-template="vertical-menu-template-free">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <title>@yield('title', 'Dashboard') - Sneat Laravel</title>

        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

        <!-- Fonts -->

        <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap"
            rel="stylesheet" />

        <!-- Core CSS -->
        <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />

        <!-- Helpers -->
        <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
        <script src="{{ asset('assets/js/config.js') }}"></script>
        <!-- DataTables CSS -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />

    </head>

    <body>
        <!-- Layout wrapper -->
        <div class="layout-wrapper layout-content-navbar">
            <div class="layout-container">

                <!-- Sidebar -->
                @include('layouts.sidebar')
                <!-- / Sidebar -->

                <!-- Layout container -->
                <div class="layout-page">

                    <!-- Navbar -->
                    @include('layouts.navbar')
                    <!-- / Navbar -->

                    <!-- Content wrapper -->
                    <div class="content-wrapper">

                        <!-- Content -->
                        <div class="container-xxl flex-grow-1 container-p-y">
                            @yield('content')
                        </div>
                        <!-- / Content -->

                        <!-- Footer -->
                        <footer class="content-footer footer p-2 bg-menu-theme {">
                            <div class="container-xxl d-flex justify-content-between py-2 flex-md-row flex-column">
                                <div>
                                    ©
                                    <script>
                                        document.write(new Date().getFullYear());
                                    </script>, made with ❤️ by
                                    <a href="https://themeselection.com" class="footer-link fw-bolder">Nurrohman</a>
                                </div>
                            </div>
                        </footer>
                        <!-- / Footer -->

                    </div>
                    <!-- / Content wrapper -->

                </div>
                <!-- / Layout page -->

            </div>
            <!-- Overlay -->
            <div class="layout-overlay layout-menu-toggle"></div>
        </div>
        <!-- / Layout wrapper -->

        <!-- Core JS -->
        <!-- CSS Select2 -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

        <!-- Core JS (jQuery dari template) -->
        <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
        <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
        <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
        <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
        <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>

        <!-- Vendors JS -->
        <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>

        <!-- Main JS -->
        <script src="{{ asset('assets/js/main.js') }}"></script>

        <!-- Page JS -->
        <script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>

        <!-- DataTables -->
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

        <!-- Select2 (harus setelah jQuery) -->
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script>
            $(document).ready(function() {
                $('#obatTable').DataTable();
                $('#usersTable').DataTable();
                $('#jenisObatTable').DataTable();
                $('#obatRusakTable').DataTable();
            });
        </script>
        <script>
            $(document).ready(function() {
                initSelect2($('.select-obat'));

                window.tambahObat = function() {
                    let html = `
        <div class="row mb-2">
            <div class="col-md-6">
                <select name="obat_id[]" class="form-control select-obat" required></select>
            </div>
            <div class="col-md-4">
                <input type="number" name="jumlah[]" class="form-control" placeholder="Jumlah" required>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger" onclick="this.closest('.row').remove()">-</button>
            </div>
        </div>`;
                    $('#obatContainer').append(html);

                    initSelect2($('#obatContainer .select-obat').last());
                }

                function initSelect2(el) {
                    el.select2({
                        dropdownParent: $('#modalTambahKeluar'),
                        placeholder: '-- Cari Obat --',
                        allowClear: true,
                        ajax: {
                            url: '{{ route('obat.search') }}',
                            dataType: 'json',
                            delay: 250,
                            data: function(params) {
                                return {
                                    q: params.term // input dari user
                                };
                            },
                            processResults: function(data) {
                                return {
                                    results: data // harus [{id:1, text:"Paracetamol"}, ...]
                                };
                            },
                            cache: true
                        },
                        minimumInputLength: 1
                    });
                }
            });
        </script>
        <script>
            $(document).ready(function() {

                function initSelect2(el, selectedId = null, modalParent = null) {
                    el.select2({
                        dropdownParent: modalParent ? $(modalParent) : $(document.body),
                        theme: "bootstrap-5",
                        placeholder: '-- Cari Obat --',
                        allowClear: true,
                        ajax: {
                            url: "{{ route('obat.search') }}",
                            dataType: 'json',
                            delay: 250,
                            data: function(params) {
                                return {
                                    q: params.term
                                };
                            },
                            processResults: function(data) {
                                return {
                                    results: data.map(item => ({
                                        id: item.id,
                                        text: item.text || item.nama_obat
                                    }))
                                };
                            }
                        }
                    });

                    if (selectedId) {
                        $.ajax({
                            url: "{{ route('obat.search') }}",
                            dataType: 'json',
                            data: {
                                q: ''
                            },
                            success: function(data) {
                                let selected = data.find(x => x.id == selectedId);
                                if (selected) {
                                    let option = new Option(selected.text || selected.nama_obat, selected
                                        .id, true, true);
                                    el.append(option).trigger('change');
                                }
                            }
                        });
                    }
                }

                // Inisialisasi select2 di modal Tambah
                initSelect2($('#modalTambahObatMasuk .select2-obat'), null, '#modalTambahObatMasuk');

                // Inisialisasi select2 di setiap modal Edit
                $('.modal').on('shown.bs.modal', function() {
                    $(this).find('.select2-obat').each(function() {
                        let selectedId = $(this).data('selected') || null;
                        initSelect2($(this), selectedId, this.closest('.modal'));
                    });
                });
            });
        </script>
    </body>

</html>
