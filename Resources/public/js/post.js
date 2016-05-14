$(document).ready(function(){

    $(".reply-to-reply").click(function(){


        $("#reply-form").attr("action", $(this).attr("data-reply-url"));
    });


    $('textarea').trumbowyg({
        removeformatPasted: true,
        btns: [['bold', 'italic', 'link', 'insertImage'],]
    });
});
