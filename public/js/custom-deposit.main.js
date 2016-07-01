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

    fetchUpdatedCreditInformation($(changedElement).data('product'), changedElement.value);
}

function fetchUpdatedCreditInformation(product, deposit){
    $.ajax(
        {
            type: "POST",
            url: "https://basket.dev/ajax/products/BNPL-06/get-credit-info",
            data: {},
            dataType: "JSON",
            success: function(response){
                console.log(response);
            },
            error: function(){
                swal(
                    {
                        title: "An Error Occured!",
                        text: "We were unable to recalculate information for the requested order. Please refresh the page.",
                        type: "error",
                        showCancelButton: false,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Refresh",
                        closeOnConfirm: false }
                    ,
                    function(){
                        location.reload();
                    }
                );
            }
        }
    );
}

function updateDataFromCalculationResponse(responseData){
    swal('updated');
}