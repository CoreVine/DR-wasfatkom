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


    $(function(){
        $(".change_payment").click(function(){

            var invoice_id = $(this).data("invoice_id");
            var new_value = $(this).data("value");
            var old_value = $(this).data("old_value");
            var _this = $(this);
            $.ajax({
                url:`{{ route('admin.invoices.change_invoice_payment') }}?id=${invoice_id}&new_value=${new_value}`,
                success:function(res){
                   $(`#btn_payment_invoice_${invoice_id}`).html(res.value)
                //    $(`#btn_status_invoice_${invoice_id}`).removeClass(`btn-outline-${res.color_old}`).addClass(`btn-outline-${res.color}`)
                   toastr["success"]("{{ __('messages.done successfully') }}")
                //    $(".change_status").removeClass("bg bg-primary")
                   _this.addClass("bg bg-primary")
                }
            })
            console.log(invoice_id , old_value ,  new_value)
        })
    })
</script>

