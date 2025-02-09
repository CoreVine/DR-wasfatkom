<script>
    $(function(){

        // add new product from favorite

        $(".item_product_favorite").click(function(){

            // get price and product id
            var product_id = $(this).data("id");
            var product_price = $(this).data("price");




            // get first item
            var first_item = $(".repeater_item").first();


            // check not select first item and one item
            if($('.repeater_item').length == 1  &&   !first_item.find(".product_select").val()){
                var this_item = first_item;

            }else{
                $(".add_another_product").click() // add new product
                var last_item = $(".repeater_item").last(); // get last item
                var this_item = last_item;

            }





            console.log(this_item);
            this_item.find(".product_select").val(product_id) // select this product
            this_item.find(".qty").val(1) // set 1 qty
            this_item.find(".price").val(product_price) // set price
            this_item.find(".price").click() // calc total



            $('.select2').select2();

            this_item.find(".icon_favorite").removeClass("fa-regular").addClass("fa-solid")


        })



         // check is select product favorite
         $(document).on("change" , ".product_select" ,  function(){

            var favorites_ids = "{{ $favorites_ids_str }}".split(",");

            var product_id = $(this).val().toString();

            var icon_favorite =  $(this).parent().parent().parent().find(".icon_favorite");
            if(product_id && favorites_ids.includes(product_id)){


                icon_favorite.removeClass("fa-regular").addClass("fa-solid")
                icon_favorite.data("product_id" ,  product_id)

            }else{
                icon_favorite.addClass("fa-regular").removeClass("fa-solid")
                icon_favorite.data("product_id" ,  product_id)


            }



        })




    })
</script>

