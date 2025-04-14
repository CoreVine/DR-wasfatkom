<script src="{{ asset('assets/vendor/libs/toastr/toastr.js') }}"></script>
<script>


    $(function(){

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



            // send request add or remove favorite
            $(document).on("click" , ".btn_add_or_remove_item_from_favorite" ,  function(){
            var this_el = $(this);
            var product_id = $(this).data("product_id").toString();
            if(product_id){

                $.ajax({
                    url:"{{ route('admin.products.ajax-add-or-remove-favorite') }}?product_id="+product_id,
                    success:function(res){

                        
                        toastr["success"](res.message)
                        var product_res = res.data.product;
                        if(product_res){
                            if(this_el.hasClass("icon_favorite")){
                                this_el.removeClass("fa-regular").addClass("fa-solid")
                            }
                            var assets_path_project = "{{ asset('') }}";
                            $("#container_list_favorite").prepend(`
                                    <li class="list-group-item  " >

                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <div class="text-md-end">
                                                    <button type="button" data-product_id="${product_id}" class="btn-close btn-pinned btn_add_or_remove_item_from_favorite"
                                                        aria-label="Close"></button>
                                                </div>
                                            </div>

                                            <div class="cursor-pointer item_product_favorite" data-price ="${product_res.price}" data-id="${product_id}" id="item_product_favorite_${product_id}">
                                                <div class="col-12 text-center ">
                                                    <img width="100" height="80"
                                                        src="${assets_path_project+product_res.image}" alt=""
                                                        class="">
                                                </div>
                                                <div class="col-12 text-center">
                                                   ${product_res.name}
                                                </div>
                                                <div class="col-12 text-center">
                                                   ${product_res.price} SAR
                                                </div>
                                            </div>

                                        </div>
                                    </li>
                            `)
                        }else{
                            $("#item_product_favorite_"+product_id).parent().parent().remove();
                            if(this_el.hasClass("icon_favorite")){
                                this_el.addClass("fa-regular").removeClass("fa-solid")
                            }
                        }

                    }
                })
            }


        })
    })
</script>
