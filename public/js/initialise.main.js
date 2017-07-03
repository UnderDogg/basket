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
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        updatePayToday();
    });
    $(window).bind("load", function() {
        updatePayToday();
    });
    // Make sure the number input is parsed
    $('.form-finance-info').submit(function(e) {
        var uifield = $('.form-finance-info').first().find('input[name=ui_amount]');
        var field = $('.form-finance-info').first().find('input[name=amount_in_pounds]');
        var number = $(uifield).val();
        $(field).val(parseFloat(number.replace(',','')));
    });

    $('div.slider-deposit').each(function(){
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

function updatePayToday() {
    if($('div.tab-pane.active').length > 0) {
        var div = $('div.tab-pane.active').first();
        var form = $(div).find('.pay_today');
        document.getElementById('pay-today').innerHTML = 'Pay Today £' + parseFloat(($(form).attr('value')/100)).toFixed(2);
    }
}

function initialiseFlexibleFinanceSliders() {

    var rangeSliderHoliday = document.getElementById('slider-range-holiday');
    var rangeSliderTerm = document.getElementById('slider-range-term');

    var rangeHoliday = {
        'min': [ 1 ],
        'max': [ 3 ]
    };

    var rangeTerm = {
        'min': [  3 ],
        'max': [ 11 ]
    };

    noUiSlider.create(rangeSliderHoliday, {
        start: 1,
        step: 1,
        margin: 0,
        tooltips: false,
        behaviour: 'tap',
        connect: 'lower',
        format: wNumb({decimals: 0}),
        orientation: "horizontal",
        range: rangeHoliday,
        pips: {
            mode: 'values',
            values: range(1,3),
            density: 100
        }
    });

    noUiSlider.create(rangeSliderTerm, {
        start: 3,
        step: 1,
        margin: 0,
        tooltips: false,
        behaviour: 'tap',
        connect: 'lower',
        format: wNumb({decimals: 0}),
        orientation: "horizontal",
        range: rangeTerm,
        pips: {
            mode: 'values',
            values: range(3, 11),
            density: 100
        }
    });

    rangeSliderHoliday.noUiSlider.on('change', function(values){
        var min = 3;
        var max = 12 - values[0];
        updateTermSliderRange(range(min, max), min, max);
        sliderUpdated();
    });

    rangeSliderTerm.noUiSlider.on('change', function(values){

        sliderUpdated();
    });
}


function depositValueHasChanged(changedElement){
    // Update all field values
    $('input[data-product="'+$(changedElement).data('product')+'"]').each(function(index){
        $(this).val(depositValueWithinRange(changedElement));
    });

    var root = $(changedElement).parent().parent().parent().find('.slider-deposit')[0];
    root.noUiSlider.set(depositValueWithinRange(changedElement))
    $(root).parent().parent().parent().parent().find('input.pay_today').val(depositValueWithinRange(changedElement) * 100);

    fetchUpdatedCreditInformation(
        $(changedElement).data('product'),
        changedElement.value * 100,
        $(changedElement).data('orderamt') * 100,
        $(changedElement).data('installation'),
        $(changedElement).data('token')
    );

    updatePayToday();
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
    $('.loading').show();
}

function hideLoading() {
    $('.loading').hide();
}

function fetchUpdatedCreditInformation(product, deposit, orderAmount, installation, token){
    $.ajax(
        {
            type: "GET",
            url: "/ajax/installations/" + installation + "/products/" + product + "/credit-info",
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

function range(start, end) {
    var foo = [];
    for (var i = start; i <= end; i++) {
        foo.push(i);
    }
    return foo;
}

function updateView(params, holiday, term) {
    $("[data-product='FF'] [data-ajaxfield]").each(function(){

        var content = params[$(this).data('ajaxfield').replace('ff_', '')];

        switch ($(this).data('fieldtype')) {
            case 'hybriddate':
                $(this).html(formatDate(content, params[$(this).data('deltamonths')]));
                break;
            case 'date':
                $(this).html(formatDate(content, 0));
                break;
            case 'raw':
                $(this).html(content);
                break;
            case 'percent':
                $(this).html(content + "%");
                break;
            case 'currency':
                $(this).html("£" + (content / 100).toFixed(2));
                break;
        }
    });

    // Calculated Fields
    $("[data-product='FF'][data-calcfield]").each(function(){
        var value = 0;

        $($(this).data('calcfield').split('|')).each(function(){
            value+=parseInt(params[this]);
        });

        $(this).val(value);
    });

    // Product Fields
    $("[data-product='FF'][data-field='product']").each(function(){
        $(this).val('AIN' + holiday + '-' + term);
    });

    $(".credit-info[data-product='FF']").show();

    updatePayToday();
}

function updateTermSliderRange (values, min, max) {

    var rangeSliderTerm = document.getElementById('slider-range-term');

    var value = rangeSliderTerm.noUiSlider.get();

    rangeSliderTerm.noUiSlider.destroy();

    noUiSlider.create(rangeSliderTerm, {
        step: 1,
        start: 3,
        margin: 0,
        tooltips: false,
        behaviour: 'tap',
        connect: 'lower',
        format: wNumb({decimals: 0}),
        orientation: "horizontal",
        range: {
            'min': [ min ],
            'max': [ max ]
        },
        pips: {
            mode: 'values',
            values: values,
            density: 15,
        }
    });

    rangeSliderTerm.noUiSlider.set(value);

    rangeSliderTerm.noUiSlider.on('change', function(values){

        sliderUpdated();
    });
}

function formatDate(dateStartIso, deltaMonths) {
    var date = new Date(Date.parse(dateStartIso))

    date.setMonth(date.getMonth() + deltaMonths);

    return date.toDateString();
}

function sliderUpdated() {
    var rangeSliderHoliday = document.getElementById('slider-range-holiday').noUiSlider.get();
    var rangeSliderTerm = document.getElementById('slider-range-term').noUiSlider.get();

    getFlexibleFinanceQuote(rangeSliderHoliday, rangeSliderTerm);
}
