<script>
    function calc_product_total(price, qty, discount) {
        var sub_total = price * qty;
        if (discount && discount < 100) {
            discount = discount / 100;
            discount = sub_total * discount;
            sub_total = sub_total - discount;
        }
        return (Math.round(sub_total * 100) / 100).toFixed(2);
    }
</script>
