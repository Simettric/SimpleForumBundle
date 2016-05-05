$(document).ready(function(){

    $(".reply-to-reply").click(function(){


        $("#reply-form").attr("action", $(this).attr("data-reply-url"));
    });

});
