<script>
    $(function () {
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
    };

    $(".change_status").click(function () {
        var invoice_id = $(this).data("invoice_id");
        var new_value = $(this).data("value");
        var old_value = $(this).data("old_value");
        var _this = $(this);

        if (new_value === "cancel") {
            // Show modal for cancel reason
            $("#invoice_id").val(invoice_id);
            new bootstrap.Modal($("#cancelReasonModal")).show();
        } else {
            // Send request immediately for other statuses
            changeInvoiceStatus(invoice_id, new_value, null, _this);
        }
    });

    // Handle cancel reason form submission
    $("#cancelForm").submit(function (e) {
        e.preventDefault();
        var invoice_id = $("#invoice_id").val();
        var cancel_reason = $("#cancel_reason").val().trim();

        if (cancel_reason === "") {
            toastr["error"]("{{ __('messages.please enter a cancellation reason') }}");
            return;
        }

        // Send request with cancel reason
        changeInvoiceStatus(invoice_id, "cancel", cancel_reason, null);
        $("#cancelReasonModal").modal("hide");
    });

    function changeInvoiceStatus(invoice_id, new_value, cancel_reason = null, _this = null) {
        var url = `{{ route('admin.invoices.change_invoice_status') }}?id=${invoice_id}&new_value=${new_value}`;
        if (cancel_reason) {
            url += `&cancel_reason=${encodeURIComponent(cancel_reason)}`;
        }

        $.ajax({
            url: url,
            success: function (res) {
                $(`#btn_status_invoice_${invoice_id}`).html(res.value);
                $(`#btn_status_invoice_${invoice_id}`)
                    .removeClass(`btn-outline-${res.color_old}`)
                    .addClass(`btn-outline-${res.color}`);

                toastr["success"]("{{ __('messages.done successfully') }}");

                if (_this) {
                    $(".change_status").removeClass("bg bg-primary");
                    _this.addClass("bg bg-primary");
                }
            },
            error: function () {
                toastr["error"]("{{ __('messages.something went wrong') }}");
            }
        });
    }
});

</script>