<script>
  $(function() {

    $(document).on('change', function () {
      updateProductsTitle()
      updatePackagesTitle()
      updateFormulationsTitle()
    })

    function updateProductsTitle() {
      $('.change-title-m').each(function(index) {
        var baseText = $(this).attr('text-show');
        $(this).text(baseText + ' #' + (index + 1));
      });
    }

    function updatePackagesTitle() {
      $('.change-package-m').each(function(index) {
        var baseText = $(this).attr('text-show');
        $(this).text(baseText + ' #' + (index + 1));
      });
    }

    function updateFormulationsTitle() {
      $('.change-formulation-m').each(function(index) {
        var baseText = $(this).attr('text-show');
        $(this).text(baseText + ' #' + (index + 1));
      });
    }

    updateProductsTitle();
    updatePackagesTitle();
    updateFormulationsTitle();

    $(document).on("change", ".product_select, .formulation_select, .package_select", function() {
      var price = $('option:selected', this).data('price');
      $(this).parent().parent().find(".price").val(price);
      calculateTotals();
    });

    $(document).on("change keyup keydown click focus",
      ".qty, .discount, .price, .product_select, .formulation_select, .package_select",
      function() {
        calculateTotals();
      });

    $('.add_another_product').on('click', function () {
      updateProductsTitle();
    })

    $('.add-another-package').on('click', function () {
      updatePackagesTitle();
    })

    $('.add-another-formulation').on('click', function () {
      updateFormulationsTitle();
    })

    function calculateTotals() {
      var invoice_total = 0;
      var invoice_total_before_discount = 0;
      var invoice_discount = 0;

      $(".repeater-wrapper").each(function() {
        var price = $(this).find(".price").val();
        var qty = $(this).find(".qty").val() ?? 1;
        var discount = $(this).find(".discount").length ? $(this).find(".discount").val() : 0;

        /* if (!qty) {
          $(this).find(".qty").val(1);
        }

        if (!discount) {
          $(this).find(".discount").val("0.00");
        } */

        price = price ? Number(price) : 0;
        qty = qty ? Number(qty) : 1;
        discount = discount ? Number(discount) : 0;

        if (discount > 100 || isNaN(+discount)) {
          discount = 0;
          $(this).find(".discount").val(0.00);
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

      $('#overall_discount').on('change keyup keydown', function() {
        var overall_discount = $(this).val();
        if (overall_discount >= 100) {
          overall_discount = 0;
          $(this).val(0);
        }
        var total_after_overall_discount = invoice_total - (invoice_total * (overall_discount / 100));
        $(".invoice_total").val(total_after_overall_discount.toFixed(2));
      });

    }

    $('[data-repeater-delete]').on('click', function () {
      calculateTotals();
      updateProductsTitle(); 
      updatePackagesTitle(); 
      updateFormulationsTitle(); 
    });

    $(document).on('repeaterCreate', function() {
      $(".repeater-wrapper:last .qty").val(0);
      $(".repeater-wrapper:last .discount").val(0);
    });

    $(document).on('click', '.favorite', function() {
      var parent = $(this).parent().parent()
    });

  });
</script>