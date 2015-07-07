
$(document).ready(function(){
    $('.more_info_question').click(function(){
        $(this).parent().prev().slideToggle(250);
    });

    $('.more_info_close').click(function(){
        $(this).parent().slideUp(250);
    });
});
