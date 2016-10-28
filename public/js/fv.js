$(document).ready(function() {
    if($(".form-horizontal")[0] && $(".form-control")[0]) {
        var obj = {};
        obj['framework'] = 'bootstrap';
        obj['fields'] = {};
        if(window['validation'] != undefined) {
            $.extend(true, obj, validation);
        }
        $('.form-horizontal').formValidation(
            obj
        );
    }
});
