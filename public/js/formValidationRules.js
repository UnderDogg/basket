/* ==========================================================================
 Custom Form Validation Rules - Custom rules for formvalidation.io plugin
 ========================================================================== */

function getPhoneValidationRules() {
    var atLeastOnePhoneNumberIsEntered = function (validator) {
        var isEmpty = true;
        var mobile = validator.getFieldElements('phone_mobile');
        if (mobile.eq(0).val() !== '') {
            isEmpty = false;
        }
        var home = validator.getFieldElements('phone_home');
        if (home.eq(0).val() !== '') {
            isEmpty = false;
        }

        if (!isEmpty) {
            validator.updateStatus('phone_mobile', validator.STATUS_VALID, 'callback');
            validator.updateStatus('phone_home', validator.STATUS_VALID, 'callback');
            return true;
        }
        return false;
    };

    return {
        regexp: {
            message: 'A mobile number must be at least 11 characters long',
            regexp: /(?:(?=^07.*$)(?:^\d{2,9}$|^\d{11}$)|(?!^07.*$)^.*$)/
        },
        callback: {
            message: 'You must enter at least one contact phone number',
            callback: function (value, validator) {
                return  atLeastOnePhoneNumberIsEntered(validator)
            }
        }
    };
}