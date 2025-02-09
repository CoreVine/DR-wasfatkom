<script>
    $(function() {
        // set price
        $(document).on("change", ".product_select, .package_select", function() {
            var price = $('option:selected', this).data('price'); // get price
            $(this).parent().parent().find(".price").val(price); // set price

            calculateTotals();
        });


        $(document).on("change keyup keydown click focus",
            ".qty, .discount, .price, .product_select, .package_select",
            function() {
                calculateTotals();
            });

        // set total for products and packages
        function calculateTotals() {
            var invoice_total = 0;
            var invoice_total_before_discount = 0;
            var invoice_discount = 0;

            $(".repeater-wrapper").each(function() {
                var price = $(this).find(".price").val();
                var qty = $(this).find(".qty").val();
                var discount = $(this).find(".discount").length ? $(this).find(".discount").val() : 0;

                price = price ? Number(price) : 0;
                qty = qty ? Number(qty) : 1;
                discount = discount ? Number(discount) : 0;

                if (discount >= 100) {
                    discount = 0;
                    $(this).find(".discount").val(0);
                }

                var total_before_discount = price * qty;
                var total = calc_product_total(price, qty, discount);

                $(this).find(".total_befor_discount").val(total_before_discount.toFixed(2));
                $(this).find(".total").val(total);

                invoice_total_before_discount += total_before_discount;
                invoice_total += Number(total);
            });

            $(".invoice_sub_total").val(invoice_total_before_discount.toFixed(2));
            invoice_discount = invoice_total_before_discount - invoice_total;
            $(".invoice_discount").val(invoice_discount.toFixed(2));
            $(".invoice_total").val(invoice_total.toFixed(2));
        }

        $(document).on('click', '.favorite', function() {

            var parent = $(this).parent().parent()
            // var product_id = parent. // get price

        })


    })
</script>
