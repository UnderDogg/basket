if($(".form-horizontal")[0] && $(".form-control")[0]) {
    var obj = {};
    obj['framework'] = 'bootstrap';
    obj['icon'] = {valid: 'glyphicon glyphicon-ok', invalid: 'glyphicon glyphicon-remove', validating: 'glyphicon glyphicon-refresh'};
    obj['fields'] = {};
    //obj['err'] = {container: 'tooltip'};
    $('.form-control').each(function(i){
        var name = $(this)[0].getAttribute('name');
        obj['fields'][name] = {validators: { notEmpty: { message: 'The '+((name == 'email') ? 'email address' : name.replace(/_/g, ' '))+' field is required'}}};
    });
    if(window['validation'] != undefined) {
        if(validation['fields']) {
            $.extend(true, obj['fields'], validation['fields']);
        }
        if(validation['remove']) {
            for(var objectType in validation['remove']) {
                console.log(objectType);
                if(objectType == 'fields') {
                    for(var property in validation['remove'][objectType]) {
                        for(var remove in validation['remove'][objectType][property]) {
                            for(var rem in validation['remove'][objectType][property]['validators']) {
                                delete obj['fields'][property]['validators'][rem];
                            }
                        }
                    }
                }
                if(objectType == 'icon') {
                    delete obj['icon'];
                }
            }
        }
    }

    $(document).ready(function() {
        $('.form-horizontal').formValidation(
            obj
        );
    });

}
