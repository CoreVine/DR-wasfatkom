<!doctype html>

<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact"
  dir="{{ App::getLocale() == 'ar' ? 'rtl' : 'ltr' }}" data-theme="theme-default"
  data-assets-path="{{ asset('') }}assets/" data-template="vertical-menu-template-no-customizer">

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
    z-index: 10;
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

      <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
        @include('inc.sidebar')
      </aside>
      <!-- / Menu -->

      <!-- Layout container -->
      <div class="layout-page">
        <!-- Navbar -->

        <nav
          class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
          id="layout-navbar">
          @include('inc.navbar')
        </nav>

        <!-- / Navbar -->

        <!-- Content wrapper -->
        <div class="content-wrapper">
          <!-- Content -->
          @yield('content')

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
                  <a href="https://nofalseo.com/" target="_blank" class="footer-link text-primary fw-medium">Nofal
                    SEO</a> --}}

                  <a href="https://nofalseo.com/" target="_blank"
                    class="footer-link text-primary fw-medium">Wasftkom</a>
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

  <script>
    $(function() {
      $(".btn-action").click(function() {
        var url = $(this).data("url");
        var method = $(this).data("method");
        var message = $(this).data("message");
        var text_btn_confirm = $(this).data("text_btn_confirm");
        var text_btn_cancel = $(this).data("text_btn_cancel");


        console.log(url, method)

        const swalWithBootstrapButtons = Swal.mixin({
          customClass: {
            confirmButton: "btn btn-danger",
            cancelButton: "btn btn-primary"
          },
          buttonsStyling: false
        });
        swalWithBootstrapButtons.fire({
          text: message,
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: text_btn_confirm,
          cancelButtonText: text_btn_cancel,
          reverseButtons: true
        }).then((result) => {

          if (result.isConfirmed) {
            $("#form_action_" + method).attr("action", url).submit();
            console.log("Done")
          } else if (result.dismiss === Swal.DismissReason.cancel) {
            console.log("else Done")

          }
        });
      })


    })
  </script>

</body>

</html>