/**
 * Created by evanbarbour on 24/10/2016.
 */

/**
 * Ajax save for different profile forms
 *
 */
$(document).ready(function() {
    $('a[data-target="save"][data-source="ajax"]').on('click', function (event) {

        var formId = jQuery(event.currentTarget).parents('form').attr("id");
        var formValidation = jQuery("#"+formId).data('formValidation');
        formValidation.validate();

        if (formValidation.isValid()) {
            var location = $('input[name="location"]').val();
            var formData = $("#"+formId).serializeArray();
            $.ajax({
                url: '/ajax/locations/' + location + '/profile/'+formId,
                type: 'POST',
                data: formData,
                dataType: 'JSON',
                encode: true,
                beforeSend: function() {
                    showLoading();
                },
                success: function (response) {
                    if (formId == 'address') updateAddressPanelForAddition(response, formData);
                    hideLoading();
                    // Enable the form from being submitted again
                    formValidation.disableSubmitButtons(false);
                    updateFormStatus(formId, true);
                },
                error: function (response) {
                    hideLoading();
                    try {
                        var errorText = JSON.parse(response.responseText).error;
                    } catch (e) {
                        console.log('Error Encountered: ' + e);
                        errorText = 'There was a problem with the API';
                    }
                    // Enable the form to be submitted again
                    formValidation.disableSubmitButtons(false);
                    updateFormStatus(formId, false);
                    swal({
                        title: 'An Error Occurred!',
                        text: 'We were unable to save the information provided.' +
                        '</br></br>[' + errorText[0].toUpperCase() + errorText.slice(1) + ']',
                        type: 'error',
                        html: true,
                        showCancelButton: false,
                        confirmButtonColor: '#DD6B55',
                        confirmButtonText: 'Close',
                        closeOnConfirm: false
                    });
                },
                complete: function() {
                    hideLoading();
                }
            });

            return false;
        }

        return false;
    });

    $('a[data-target="removeAddress"][data-source="ajax"]').on('click', function (event) {
        var form = jQuery(event.currentTarget).parents('form');
        var formId = $(form).attr("id");

        var location = $('input[name="location"]').val();
        var formData = $("#"+formId).serializeArray();
        $.ajax({
            url: '/ajax/locations/' + location + '/profile/removeAddress',
            type: 'POST',
            data: formData,
            dataType: 'JSON',
            encode: true,
            beforeSend: function() {
                showLoading();
            },
            success: function () {
                updateAddressPanelForRemoval(formData);
                hideLoading();
                // Remove the form from the view
                $(form).remove();
            },
            error: function (response) {
                hideLoading();
                try {
                    var errorText = JSON.parse(response.responseText).error;
                } catch (e) {
                    console.log('Error Encountered: ' + e);
                    errorText = 'There was a problem with the API';
                }
                swal({
                    title: 'An Error Occurred!',
                    text: 'We were unable to remove the address.' +
                    '</br></br>[' + errorText[0].toUpperCase() + errorText.slice(1) + ']',
                    type: 'error',
                    html: true,
                    showCancelButton: false,
                    confirmButtonColor: '#DD6B55',
                    confirmButtonText: 'Close',
                    closeOnConfirm: false
                });
            },
            complete: function() {
                hideLoading();
            }
        });

        return false;
    });

    function updateAddressPanelForAddition(response, formData) {
        // We have to add the address
        var addressArr = {};
        $.each(formData, function(i, data) {
            if (data.name == 'abode') {
                addressArr['abode'] = data.value;
            }
            if (data.name == 'building_name') {
                addressArr['building_name'] = data.value;
            }
            if (data.name == 'building_number') {
                addressArr['building_number'] = data.value;
            }
            if (data.name == 'street') {
                addressArr['street'] = data.value;
            }
            if (data.name == 'locality') {
                addressArr['locality'] = data.value;
            }
            if (data.name == 'town') {
                addressArr['town'] = data.value;
            }
            if (data.name == 'postcode') {
                addressArr['postcode'] = data.value;
            }
            if (data.name == 'address') {
                addressArr['address'] = data.value;
            }
            if (data.name == 'user') {
                addressArr['user'] = data.value;
            }
            if (data.name == 'moved_in') {
                addressArr['moved_in'] = data.value;
            }
        });

        console.log(addressArr);
        console.log(getAddressAsString(addressArr));

        // We have to move the 'remove address' button down a notch

        // We also have to hide the input ONLY IF we have sufficient history
    }

    function updateAddressPanelForRemoval(formData) {
        var addressArr = {};
        $.each(formData, function(i, data) {
            if (data.name == 'abode') {
                addressArr['abode'] = data.value;
            }
            if (data.name == 'building_name') {
                addressArr['building_name'] = data.value;
            }
            if (data.name == 'building_number') {
                addressArr['building_number'] = data.value;
            }
            if (data.name == 'street') {
                addressArr['street'] = data.value;
            }
            if (data.name == 'locality') {
                addressArr['locality'] = data.value;
            }
            if (data.name == 'town') {
                addressArr['town'] = data.value;
            }
            if (data.name == 'postcode') {
                addressArr['postcode'] = data.value;
            }
        });

        console.log(addressArr.street);
        console.log(getAddressAsString(addressArr));

    }

    function getAddressAsString(addressArr) {
        var string = '';
        if (!(addressArr.abode == '')) string = string + addressArr.abode + ', ';
        if (!(addressArr.building_name == '')) string = string + addressArr.building_name + ', ';
        if (!(addressArr.building_number == '')) string = string + addressArr.building_number + ' ';
        if (!(addressArr.street == '')) string = string + addressArr.street + ', ';
        if (!(addressArr.locality == '')) string = string + addressArr.locality + ', ';
        if (!(addressArr.town == '')) string = string + addressArr.town + ', ';
        if (!(addressArr.postcode == '')) string = string + addressArr.postcode;

        return string;
    }

    function updateAddition() {

    }

    console.log(getLastAddressDate());

    function getLastAddressDate() {
        var el = $('form[data-address-number]').last()
        if ($(el).html() == null) {
            return (new Date()).toDateString();
        }

        return $(el).find('input[name="moved_in"]').attr('value');

    }

    function checkAddressHistorySatisfied(date) {

    }

    function updateFormStatus(formType, success) {
        var glyph = 'remove';
        var colour = 'danger';
        if (success) {
            glyph = 'ok';
            colour = 'success'
        }
        $('.' + formType + '-status').html('<small class="text-' + colour + '"><span class="glyphicon glyphicon-' + glyph + '" aria-hidden="true"></span></small>');
    }

    function showLoading() {
        $('.loading').show();
    }

    function hideLoading() {
        $('.loading').hide();
    }

    // Personal
    var phoneValidation = {
        callback: {
            message: 'You must enter at least one contact phone number',
            callback: function (value, validator) {
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
            }
        }
    };

    $('#personal').on('change', '#dob_day, #dob_month, #dob_year', function(e) {
        var y = $('#personal').find('#dob_year').val(),
            m = $('#personal').find('#dob_month').val(),
            d = $('#personal').find('#dob_day').val();

        $('#personal').find('[name="date_of_birth"]').val(y === '' && m === '' && d === '' ? '' : [y, m, d].join('-'));
        $('#personal').formValidation('revalidateField', 'date_of_birth');
    });

    $('#personal').formValidation({
        framework: 'bootstrap',
        fields: {
            date_of_birth: {
                err: '.dob-error',
                excluded: false,
                validators: {
                    date: {
                        format: 'YYYY-MM-DD',
                        message: 'Please fully enter the date of birth'
                    }
                }
            },
            phone_home: {validators: phoneValidation},
            phone_mobile: {validators: phoneValidation}
        }
    });

    $('#dob_day').prepend( '<option value="">-- Day --</option>');
    $('#dob_month').prepend( '<option value="">-- Month --</option>');
    $('#dob_year').prepend( '<option value="">-- Year --</option>');
    $('#dob_day :nth-child(1)').prop('selected', true);
    $('#dob_month :nth-child(1)').prop('selected', true);
    $('#dob_year :nth-child(1)').prop('selected', true);

    $("#number_of_dependents option[value='10']").html('10 +');
    $("#number_of_dependents").prepend( '<option value="">-- Please select --</option>');
    $('#number_of_dependents :nth-child(1)').prop('selected', true);

    // Address
    capturePlus.listen("load", function(control) {
        control.listen("populate", function() {
            $('#address').data('formValidation').resetForm();
        });
    });

    $('#address').formValidation({
        framework: 'bootstrap',
        button: {
            selector: '[data-target="save"]'
        },
        fields: {
            moved_in: {
                err: '.moved-in-error',
                icon: false,
                excluded: false,
                validators: {
                    date: {
                        format: 'YYYY-MM-DD',
                        message: 'Please fully enter the moved in date'
                    },
                    notEmpty: {
                        message: 'Please fully enter the moved in date'
                    }
                }
            }
        }
    });

    $('#address').on('change', '#moved_in_month, #moved_in_year', function(e) {
        var y = $('#moved_in_year').val(),
            m = $('#moved_in_month').val(),
            d = '1';

        $('#address').find('[name="moved_in"]').val(y === '' && m === '' ? '' : [y, m, d].join('-'));
        $('#address').formValidation('revalidateField', 'moved_in');
    });

    $('#moved_in_month').prepend( '<option value="">-- Month --</option>');
    $('#moved_in_year').prepend( '<option value="">-- Year --</option>');
    $('#moved_in_year option:last').text($('#moved_in_year option:last').text() + ' and earlier');
    $('#moved_in_month :nth-child(1)').prop('selected', true);
    $('#moved_in_year :nth-child(1)').prop('selected', true);

    // Employment
    $('#employment').formValidation({
        framework: 'bootstrap',
        button: {
            selector: '[data-target="save"]'
        },
        fields: {
            employment_start: {
                err: '.employment-start-error',
                icon: false,
                excluded: false,
                validators: {
                    date: {
                        format: 'YYYY-MM-DD',
                        message: 'Please fully enter the employment start date'
                    }
                }
            }
        }
    });

    $('#employment').on('change', '#employment_start_month, #employment_start_year', function(e) {
        var y = $('#employment_start_year').val(),
            m = $('#employment_start_month').val(),
            d = '1';

        $('#employment').find('[name="employment_start"]').val(y === '' && m === '' ? '' : [y, m, d].join('-'));
        $('#employment').formValidation('revalidateField', 'employment_start');
    });

    $('#employment_start_month').prepend( '<option value="">-- Month --</option>');
    $('#employment_start_year').prepend( '<option value="">-- Year --</option>');
    $('#employment_start_month :nth-child(1)').prop('selected', true);
    $('#employment_start_year :nth-child(1)').prop('selected', true);

    // Financial
    var financialValidators = {
        callback: {
            message: 'You must enter both the sort code and the account number, or neither',
            callback: function (value, validator) {
                var atLeastOne = false;
                var sortCode = validator.getFieldElements('bank_sort_code');
                var accountNumber = validator.getFieldElements('bank_account');

                if ((sortCode.val().length > 0 || accountNumber.val().length > 0)) {
                    atLeastOne = true;
                }

                if ((atLeastOne && (sortCode.val().length > 0 && accountNumber.val().length > 0)) || !atLeastOne) {
                    validator.updateStatus('bank_sort_code', validator.STATUS_VALID, 'callback');
                    validator.updateStatus('bank_account', validator.STATUS_VALID, 'callback');
                    return true;
                }

                return false;
            }
        }
    };

    $('#financial').formValidation({
        framework: 'bootstrap',
        button: {
            selector: '[data-target="save"]'
        },
        fields: {
            bank_sort_code: {validators: financialValidators},
            bank_account: {validators: financialValidators}
        }
    });
});
