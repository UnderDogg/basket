/* ==========================================================================
 Application Initialise - Custom Deposit JS
 ========================================================================== */

$(document).ready(function(){

    $('input[type="range"]').on('change', function() {
        depositValueHasChanged(this);
    });

    $('input[name="deposit"]').on('change', function() {
        depositValueHasChanged(this);
    });

});

function depositValueHasChanged(changedElement){
    // Update all field values
    $('input[data-product="'+$(changedElement).data('product')+'"').each(function(index){
        $(this).val(changedElement.value);
    });

    $('#pay-today').html('Pay Today £' + parseFloat(changedElement.value).toFixed(2));

    fetchUpdatedCreditInformation(
        $(changedElement).data('product'),
        changedElement.value * 100,
        $(changedElement).data('orderamt') * 100,
        $(changedElement).data('installation'),
        $(changedElement).data('token')
    );
}

function showLoading() {
    $('.loading-container').show();
}

function hideLoading() {
    $('.loading-container').hide();
}

function fetchUpdatedCreditInformation(product, deposit, orderAmount, installation, token){
    $.ajax(
        {
            type: "POST",
            url: "/ajax/installations/" + installation + "/products/" + product + "/get-credit-info",
            beforeSend: function( xhr ) {
                xhr.overrideMimeType('Content-Type: application/json');
                showLoading();
            },
            data: {
                _token: token,
                deposit: deposit,
                order_amount: orderAmount
            },
            dataType: "JSON",
            success: function(response){
                hideLoading();
                updateFinanceOfferFields(response, product);
            },
            error: function(response){
                console.log("Error Encountered: " + JSON.parse(response.responseText).error);
                swal(
                    {
                        title: "An Error Occurred!",
                        text: "We were unable to recalculate information for the requested order. Please refresh the page.",
                        type: "error",
                        showCancelButton: false,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Refresh",
                        closeOnConfirm: false
                    },
                    function(){
                        hideLoading();
                        location.reload();
                    }
                );
            },
            complete: function() {
                hideLoading();
            }
        }
    );
}

function updateFinanceOfferFields(response, product){
    fields = $('#prod-' + product + ' [data-ajaxfield]');

    $(fields).each(function(index, element){
        ajaxField = $(element).data('ajaxfield');

        switch($(element).data('fieldtype')){
            case 'currency':
                updateFinancialField($(element), response[ajaxField]);
                break;
            default:
                updateSubmitCurrencyField($(element), response[ajaxField]);
                break;
        }
    });
}

function updateFinancialField(field, amount){
    field.html('£' + (amount / 100).toFixed(2));
}

function updateSubmitCurrencyField(field, value){
    field.html(value);
    field.attr('value', (value / 100).toFixed(2));
}
