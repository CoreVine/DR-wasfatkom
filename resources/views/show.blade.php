<!doctype html>

<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact" dir="rtl"
    data-theme="theme-default" data-assets-path="{{ asset('') }}assets/"
    data-template="vertical-menu-template-no-customizer">

<head>
    @include('inc.head')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&family=Noto+Kufi+Arabic:wght@100..900&display=swap"
        rel="stylesheet">

</head>
@yield('style')
<style>
    @media (max-width: 1199.98px) {

        .layout-overlay {
            z-index: 1030;
        }
    }



    body {
        font-family: "Cairo", sans-serif !important;
    }

    .layout-navbar-fixed body:not(.modal-open) .layout-content-navbar .layout-navbar,
    .layout-menu-fixed body:not(.modal-open) .layout-content-navbar .layout-navbar,
    .layout-menu-fixed-offcanvas body:not(.modal-open) .layout-content-navbar .layout-navbar {
        z-index: 1043;
    }

    .layout-navbar-fixed body:not(.modal-open) .layout-content-navbar .layout-menu,
    .layout-menu-fixed body:not(.modal-open) .layout-content-navbar .layout-menu,
    .layout-menu-fixed-offcanvas body:not(.modal-open) .layout-content-navbar .layout-menu {
        z-index: 1043;
    }

    i {
        margin: 0px 5px 0px 5px
    }
</style>
</style>


<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->

            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->


                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="">
                    <!-- Content -->
                    @include('admin.invoices.invoice_details_show', ['is_admin' => false])


                    <!-- / Content -->

                    <!-- Footer -->
                    <footer class="content-footer footer bg-footer-theme">
                        <div class="container-xxl">
                            <div
                                class="footer-container d-flex align-items-center justify-content-between py-2 flex-md-row flex-column">
                                <div>
                                    {{-- ©
                                    <script>
                                        document.write(new Date().getFullYear());
                                    </script>
                                    , made with ❤️ by
                                    <a href="https://nofalseo.com/" target="_blank"
                                        class="footer-link text-primary fw-medium">Wasftkom</a> --}}

                                    <a href="https://nofalseo.com/" target="_blank"
                                        class="footer-link text-primary fw-medium">Wasafatkom</a>
                                    Made with by

                                </div>

                            </div>
                        </div>
                    </footer>
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>

        <!-- Drag Target Area To SlideIn Menu On Small Screens -->
        <div class="drag-target"></div>
    </div>
    <!-- / Layout wrapper -->
    <form id="form_action_delete" method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>
    <form id="form_action_post" method="POST" class="d-none">
        @csrf

    </form>
    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->

    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/node-waves/node-waves.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/hammer/hammer.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/i18n/i18n.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/typeahead-js/typeahead.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/swiper/swiper.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <!-- endbuild -->



    <!-- Main JS -->


    <!-- Page JS -->
    <script src="{{ asset('assets/js/app-ecommerce-order-list.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('assets/js/extended-ui-sweetalert2.js') }}"></script>




    @include('sweetalert::alert')
    @yield('script')
    <script src="{{ asset('assets/vendor/libs/toastr/toastr.js') }}"></script>

    <script>
        toastr.options = {
            "closeButton": false,
            "debug": false,
            "newestOnTop": false,
            "progressBar": false,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }

        $(document).ready(function() {




            $('.btn-check-coupon').click(function(e) {

                e.preventDefault();
                var invoice_id = {{ $item->id }};
                var coupon = $('#coupon').val();
                var url = "{{ route('check_coupon') }}" + "?code=" + coupon + "&invoice_id=" + invoice_id;
                $.ajax({
                    url,
                    method: "GET",
                    success: function(res) {
                        $('.btn-check-coupon').remove();
                         $("#coupon").attr('readonly' , true);
                        $(".coupon_discount").remove()
                        // $(".coupon_discount").css({
                        //     textDecoration: 'line-through'
                        // });

                        $('.coupon_total').show();
                        $('.coupon_total').html(`
                        {{ __('messages.Total after discount') }} : <span
                                class="h5">${res.data.total}</span>`);
                        toastr["success"]("Coupon used successfully")
                        setTimeout(function(){
                            window.location.href = window.location.href
                        }, 2000)

                    },
                    error: function(xhr, status, error) {
                        console.log(xhr)

                        var errorMessage = xhr.responseJSON.message;
                        toastr["error"](errorMessage);
                    }
                })
            })
        })
    </script>
</body>

</html>