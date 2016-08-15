/* ==========================================================================
 Application Initialise - Custom Deposit JS
 ========================================================================== */

$(document).ready(function(){

    $('div.slider-range').each(function(){
        var rangeSlider = $(this);
        var inputNumber = rangeSlider.parent().parent().find('.input-number');

        var startPosition = 0;

        noUiSlider.create(rangeSlider[0], {
            start: startPosition,
            tooltips: [ true ],
            format: wNumb({decimals: 0}),
            connect: "lower",
            orientation: "horizontal",
            range: {
                'min': [ parseInt($(inputNumber).attr('min')) ],
                'max': [ parseInt($(inputNumber).attr('max')) ]
            },
            pips: {
                mode: 'range',
                density: 100
            }
        });

        rangeSlider[0].noUiSlider.on('update', function( values, handle ) {
            inputNumber.val(values[handle]);
        });

        rangeSlider[0].noUiSlider.on('change', function(){
            $(inputNumber).trigger('change');
        });

        inputNumber.on('change', function(){
            rangeSlider[0].noUiSlider.set(this.value);
            depositValueHasChanged(this);
        });
    });

});

function depositValueHasChanged(changedElement){
    // Update all field values
    $('input[data-product="'+$(changedElement).data('product')+'"]').each(function(index){
        $(this).val(depositValueWithinRange(changedElement));
    });

    $(changedElement).parent().parent().parent().find('.slider-range')[0].noUiSlider.set(depositValueWithinRange(changedElement))

    $('#pay-today').html('Pay Today £' + parseFloat(changedElement.value).toFixed(2));

    fetchUpdatedCreditInformation(
        $(changedElement).data('product'),
        changedElement.value * 100,
        $(changedElement).data('orderamt') * 100,
        $(changedElement).data('installation'),
        $(changedElement).data('token')
    );
}

function depositValueWithinRange(changedElement) {
    return Math.ceil(
        Math.max(
            Math.floor(
                Math.min(
                    changedElement.value,
                    $(changedElement).attr('max')
                )
            ),
            $(changedElement).attr('min')
        )
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
