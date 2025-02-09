<script>

  $(function(){

    $(".input_name").on("change keyup keydown focus" , function(){
        var text = $(this).val();
        var slug_name = $(this).data('slug_name');
        var new_text = text.replace(/\s+/g, '-').toLowerCase()
        $(`input[name=${slug_name}]`).val(new_text);

        if(text){
            $(".prefix_title_word").html(`-${text}`);
        }else{
            $(".prefix_title_word").html(null);
        }

    })

  })
</script>
