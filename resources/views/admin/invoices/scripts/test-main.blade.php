<script>
  $(function() {


    $(document).on("change", ".product_select, .formulation_select, .package_select", function() {
      var price = $('option:selected', this).data('price');
      $(this).parent().parent().find(".price").val(price);
      updateProductNumbers();
      updatePackageNumbers();
      updateFormulationNumbers();
      calculateTotals();
    });

    $(document).on("change keyup keydown click focus",
      ".qty, .discount, .price, .product_select, .formulation_select, .package_select",
      function() {
        updateProductNumbers();
        updatePackageNumbers()
        updateFormulationNumbers();
        calculateTotals();
      });

    $('.add_another_product').on('click', function () {
      updateProductNumbers();
    })

    $('.add-new-package').on('click', function () {
      updatePackageNumbers()
    })

    $('.add-new-formulation').on('click', function () {
      updateFormulationNumbers()
    })

    function calculateTotals() {
      var invoice_total = 0;
      var invoice_total_before_discount = 0;
      var invoice_discount = 0;

      $(".repeater-wrapper").each(function() {
        var price = $(this).find(".price").val();
        var qty = $(this).find(".qty").val();
        var discount = $(this).find(".discount").length ? $(this).find(".discount").val() : 0;

        if (!qty) {
          $(this).find(".qty").val(1);
        }

        if (!discount) {
          $(this).find(".discount").val("0.00");
        }

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
      /* $(".invoice_discount").val(invoice_discount.toFixed(2));
      $(".invoice_total").val(invoice_total.toFixed(2)); */

      $('.invoice_discount').on('keyup keydown change', function () {
        var invoice_discount = $(this).val();
        if (invoice_discount >= 100) {
          invoice_discount = 0;
          $(this).val(0);
        }
        invoice_total = invoice_total_before_discount - (invoice_total_before_discount * (invoice_discount / 100));
        $(".invoice_total").val(invoice_total.toFixed(2));
      })

    }

    function updateProductNumbers() {
      $(".change-title-m").each(function (index) {
        let newNumber = index + 1;
        let t = $(this).attr('text-show');
        let text = t + " #" + newNumber;
        $(this).text(text);
      });
    }

    function updatePackageNumbers() {
      $(".change-package-m").each(function (index) {
        let newNumber = index;
        let t = $(this).attr('text-show');
        let text = t + " #" + newNumber;
        $(this).text(text);
      });
    }

    function updateFormulationNumbers() {
      $(".change-formulation-m").each(function (index) {
        let newNumber = index + 1;
        let t = $(this).attr('text-show');
        let text = t + " #" + newNumber;
        $(this).text(text);
      });
    }

    $(document).on('repeaterCreate', function() {
      $(".repeater-wrapper:last .qty").val(0);
      $(".repeater-wrapper:last .discount").val(0);
      updateProductNumbers();
      updateFormulationNumbers();
      updatePackageNumbers();
    });

    $(document).on('click', '.favorite', function() {
      var parent = $(this).parent().parent()
    });

  });
</script>