<script src="{{ asset('assets/tinymce/jquery.tinymce.min.js') }}"></script>
<script src="{{ asset('assets/tinymce/tinymce.min.js') }}"></script>


<script>
    console.log( document.documentElement.getAttribute('data-template'))

    var content_css ="";
    var skin ="oxide";
    if($("html").hasClass("dark-style")){
        content_css ="dark";
        skin ="oxide-dark";
    }
    $('.editor_style_ar').tinymce({
        language: 'ar',
        height: 600,
        skin:skin,
        content_css: content_css,
        plugins: [
            'advlist autolink lists link image  charmap print preview hr anchor pagebreak',
            'searchreplace wordcount visualblocks visualchars code fullscreen',
            'insertdatetime media  save table contextmenu directionality',
            'emoticons template paste textcolor colorpicker textpattern imagetools codesample toc help'
        ],
        toolbar1: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify  | bullist numlist outdent indent | link   | forecolor backcolor emoticons | print preview fullscreen'

    })

    if($(document).has($(".tox-tinymce"))){
        setTimeout(()=>{
            $('.editor_style_en').tinymce({
                language: 'en',
                skin: skin,
                content_css:content_css,
                height: 600,
                plugins: [
                    'advlist autolink lists link image  charmap print preview hr anchor pagebreak',
                    'searchreplace wordcount visualblocks visualchars code fullscreen',
                    'insertdatetime media  save table contextmenu directionality',
                    'emoticons template paste textcolor colorpicker textpattern imagetools codesample toc help'
                ],
                toolbar1: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link   | forecolor backcolor emoticons | print preview fullscreen',

            })
        },1500)

    }


</script>
