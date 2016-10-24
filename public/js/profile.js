/**
 * Created by evanbarbour on 24/10/2016.
 */

/**
 * Ajax save for different profile forms
 *
 */
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('a[data-target="save"][data-source="ajax"]').on('click', function (event) {

        var formId = jQuery(event.currentTarget).parents('form').attr("id");
        var formValidation = jQuery("#"+formId).data('formValidation');
        formValidation.validate();

        if (formValidation.isValid()) {
            var installation = $('input[name="installation"]').val();
            var formData = $("#"+formId).serializeArray();
            $.ajax({
                url: '/ajax/installations/' + installation + '/profile/'+formId,
                type: 'POST',
                data: formData,
                dataType: 'JSON',
                encode: true,
                beforeSend: function() {
                    showLoading();
                },
                success: function () {
                    hideLoading();
                    // Disable the form from being submitted again
                    formValidation.disableSubmitButtons(true);
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
});
