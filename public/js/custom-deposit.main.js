/* ==========================================================================
 Application Initialise - Custom Deposit JS
 ========================================================================== */

$(document).ready(function(){

    console.log('Sliders initialising.');
    // Initialise the range sliders
    $('input[type="range"]').rangeslider().on('change', function() {
        depositValueHasChanged(this);
    });

    $('input.deposit-input').on('change', function() {
        depositValueHasChanged(this);
    });

});

function depositValueHasChanged(changedElement, isSlider){

    console.log('Updating fields');

    // Update all field values
    $('input[data-product="'+$(changedElement).data('product')+'"').each(function(index){
        $(this).val(changedElement.value);
    })

    $('#pay-today').html('Pay Today £' + changedElement.value + '.00');

    fetchUpdatedCreditInformation(
        $(changedElement).data('product'),
        changedElement.value * 100,
        $(changedElement).data('orderamt') * 100,
        $(changedElement).data('installation')
    );
}

function fetchUpdatedCreditInformation(product, deposit, orderAmount, installation){
    $.ajax(
        {
            type: "POST",
            url: "/ajax/installations/" + installation + "/products/" + product + "/get-credit-info",
            beforeSend: function( xhr ) {
                xhr.overrideMimeType('Content-Type: application/json');
            },
            data: {
                deposit: deposit,
                order_amount: orderAmount
            },
            dataType: "JSON",
            success: function(response){
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
                        location.reload();
                    }
                );
            }
        }
    );
}

function updateFinanceOfferFields(response, product){
    console.log(response, product);

    fields = $('#prod-' + product + ' [data-ajaxfield]');

    $(fields).each(function(index, element){
        // This is the field.

        console.log(element);

        ajaxField = $(element).data('ajaxfield');

        console.log("Filling element " + ajaxField + " with response data " + response[ajaxField]);

        switch($(element).data('fieldtype')){
            case 'currency':
                updateFinancialField($(element), response[ajaxField]);
                break;
        }
    });
}

function updateFinancialField(field, amount){
    console.log('Filling financial field');

    field.html('£' + (amount / 100).toFixed(2));
}
