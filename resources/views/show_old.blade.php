@extends('layouts.app')
@section('title')
    {{ __('messages.Invoices') }}
@endsection
@section('style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/toastr/toastr.css') }}" />
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @include('inc.breadcrumb', [
            'breadcrumb_items' => [
                __('messages.Home') => route('home'),
                __('messages.Invoices') => 'active',
            ],
        ])
    </div>

    @include('admin.invoices.invoice_details_show', ['is_admin' => false])
@endsection
@section('script')
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
            console.log('1');

            $('#coupon_check').submit(function(e) {
                e.preventDefault();
                var invoice_id = {{ $item->id }};
                var coupon = $('#coupon').val();
                var url = "{{ route('check_coupon') }}" + "?code=" + coupon + "&invoice_id=" + invoice_id;
                $.ajax({
                    url,
                    method: "GET",
                    success: function(res) {
                        console.log(res.data.status)


                        console.log(res.data)
                        $(".coupon_discount").css({
                            textDecoration: 'line-through'
                        });

                        $('.coupon_total').show();
                        $('.coupon_total').append(`
                        {{ __('messages.Total after discount') }} : <span
                                class="h5">${res.data.total}</span>`);
                        toastr["success"]("Coupon used successfully")


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
@endsection
