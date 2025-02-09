<script>
    const _getElementByName = (name) => {
        $(document).ready(() => {
            $(input[name = '${name}']).addClass("border border-danger");
            $(select[name = '${name}']).addClass("border border-danger");
            $(textarea[name = '${name}']).addClass("border border-danger");
        })
    }




    // toastr.options = {
    //     "closeButton": false,
    //     "debug": false,
    //     "newestOnTop": false,
    //     "progressBar": false,
    //     "positionClass": "toast-top-right",
    //     "preventDuplicates": false,
    //     "onclick": null,
    //     "showDuration": "300",
    //     "hideDuration": "1000",
    //     "timeOut": "5000",
    //     "extendedTimeOut": "1000",
    //     "showEasing": "swing",
    //     "hideEasing": "linear",
    //     "showMethod": "fadeIn",
    //     "hideMethod": "fadeOut"
    // }

    $(function() {
        $("#form_submit").submit(function(e) {
            e.preventDefault();
            var actionUrl = $(this).attr("action")
            $(".border-danger").removeClass("border-danger")
            // $(".msg").html(null)
            $(".btn_with_load").attr('disabled', true)
            $(".btn_with_load .loader").removeClass("d-none")
            $.ajax({
                url: actionUrl,
                method: 'POST',
                dataType: 'json',
                data: new FormData(this),
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {



                    window.location.href = res.data.url;
                    console.log(res);

                },
                error: function(res) {
                    $(".btn_with_load").attr('disabled', false)
                    if (res.status == 422) {
                        var is_alert = false;
                        $.each(res.responseJSON.errors, function(key, value) {
                            toastr["error"](value)
                            var inp_name = "";
                            var inp_name_not_index = "";


                            var check_error_array = key.split(".");


                            if (check_error_array.length == 1) {


                                $("input[name=" + key + "]  , textarea[name=" +
                                        key + "] , select[name=" + key + "]")
                                    .addClass("border border-danger");

                            } else {

                                $.each(check_error_array, function(key, val_arr) {

                                    if (key == 0) {
                                        inp_name += val_arr
                                        inp_name_not_index += val_arr

                                    } else {
                                        inp_name += '[' + val_arr + ']';

                                    }


                                })

                                _getElementByName(inp_name);
                            }


                        })


                    } else if ((res.status == 500 || res.status == 405 || res.status ==
                            400) && res.responseJSON) {

                        toastr["error"](res.responseJSON.message)
                        // Phenix(document).notfications({ message: res.responseJSON.message, type: 'error', duration: 5000, });
                    } else if (res.status == 404) {
                        toastr["error"]('404 غير موجود')

                    } else {
                        toastr["error"]('حدث خطأ ما')
                    }

                    $(".btn_with_load .loader").addClass("d-none")
                }
            })

        })





        $('.repeater').repeater({
            initEmpty: false,
            isFirstItemUndeletable: false,
            repeaters: [{
                // (Required)enter code here
                // Specify the jQuery selector for this nested repeater
                selector: '.inner-repeater',
                isFirstItemUndeletable: true,

                show: function() {
                    $(this).slideDown();
                    $('.select2-container').remove()
                    $(".select2").select2()

                },

            }],

            show: function() {
                $(this).slideDown();

                $('.select2-container').remove()
                $(".select2").select2()
            },
            hide: function(deleteElement) {
                $(this).slideUp(deleteElement);
            }
        });







    })
</script>
