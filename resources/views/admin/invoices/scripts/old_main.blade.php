<script>
    $(function(){
        // set price
        $(document).on("change" , ".product_select" ,  function(){
            var price = $('option:selected', this).data('price'); // get price
            $(this).parent().parent().find(".price").val(price); //  set price

        })


        // set total

        $(document).on("change keyup keydown click focus" , ".qty , .discount , .price , .product_select" ,  function(){

            var parent = $(this).parent().parent()
              // get price

            var price = parent.find(".price").val();
            if(price){

                // get quantity
                var qty =parent.find(".qty").val();

                if(!qty){
                    qty = 1;
                }


                // get discount

                var discount = parent.find(".discount").val();

                if(!discount){
                    discount = 0 ;
                }





                // string to number
                price = Number(price)
                qty = Number(qty)
                discount = Number(discount)

                if(discount >= 100){
                    discount = 0 ;
                    parent.find(".discount").val(0)
                }

                // set product total before discount

                parent.find(".total_befor_discount").val(price * qty);

                // set product total
                parent.find(".total").val( calc_product_total(price , qty , discount ))

                // set total invoice


                var invoice_total = 0 ;

                $(".total").each(function(key ,  el){

                    if($(this).val()){
                        el_val = Number($(this).val())
                        invoice_total+= el_val
                    }

                })




                // set total invoice before discount


                var invoice_total_before_discount  = 0 ;

                $(".total_befor_discount").each(function(key ,  el){

                    if($(this).val()){
                        el_val = Number($(this).val())
                        invoice_total_before_discount+= el_val
                    }
                })

                $(".invoice_sub_total").val(invoice_total_before_discount);

                var invoice_discount = invoice_total_before_discount - invoice_total;
                invoice_discount = (Math.round(invoice_discount * 100) / 100).toFixed(2)
                $(".invoice_discount").val(invoice_discount );
                $(".invoice_total").val(invoice_total);



            }



        })


        $(document).on('click','.favorite',function(){

            var parent = $(this).parent().parent()
            // var product_id = parent. // get price

        })


    })
</script>

