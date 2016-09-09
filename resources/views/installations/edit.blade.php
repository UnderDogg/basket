@extends('main')

@section('content')

    <h1>Edit Installation</h1>
    @include('includes.page.breadcrumb', ['crumbs' => Request::segments(), 'over' => [1  => $installations->name]])
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#generalSettings">General</a></li>
        <li><a data-toggle="tab" href="#emailSettings">Email Template</a></li>
        <li><a data-toggle="tab" href="#instoreSettings">In-store</a></li>
        <li><a data-toggle="tab" href="#integrationSettings">Integration</a></li>
    </ul>
    <p>&nbsp;</p>
    {!! Form::model($installations, ['method' => 'PATCH', 'action' => ['InstallationsController@update', $installations->id], 'class' => 'form-horizontal']) !!}
    <div class="col-xs-12 tab-content">
        <div id="generalSettings" class="tab-pane fade in active">
            <br/>
            <div class="form-group">
                {!! Form::label('name', 'Name', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-8">
                    {!! Form::text('name', null, ['class' => 'form-control', 'data-fv-notempty' => 'true', 'maxlength' => 255]) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('active', 'Active', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-8">
                    @if($installations->active == 1)
                        {!! Form::input('checkbox', 'active', null, ['checked' => true,'data-toggle' => 'toggle', 'data-on' => '<i class="glyphicon glyphicon-ok"></i> Active', 'data-off' => '<i class="glyphicon glyphicon-remove"></i> Inactive', 'data-onstyle' => 'success', 'data-offstyle' => 'danger', 'data-size' => 'small', 'value' => '1']) !!}
                    @else
                        {!! Form::input('checkbox', 'active', null, ['data-toggle' => 'toggle', 'data-on' => '<i class="glyphicon glyphicon-ok"></i> Active', 'data-off' => '<i class="glyphicon glyphicon-remove"></i> Inactive', 'data-onstyle' => 'success', 'data-offstyle' => 'danger', 'data-size' => 'small']) !!}
                    @endif
                </div>
            </div>
            <div class="form-group">
                {!! HTML::decode(Form::label('validity', '<abbr title="This is the number of days before an application will expire">Validity Period<abbr>', ['class' => 'col-sm-2 control-label'])) !!}
                <div class="col-sm-8">
                    {!! Form::text('validity', ceil($installations->validity / 60 / 60 / 24) , ['class' => 'form-control', 'placeholder' => 'in days', 'maxlength' => 2, 'data-fv-notempty' => 'true', 'data-fv-integer' => 'true', 'data-fv-integer-decimalseparator' => '', 'data-fv-digits' => 'true', 'data-fv-between' => 'true', 'data-fv-between-max' => 30, 'data-fv-between-min' => 1, 'data-fv-between-message' => 'The validity period must be at least 1 day, but must not exceed 30 days']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('custom_logo_url', 'Custom Logo (URL)', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-8">
                    {!! Form::text('custom_logo_url', null, ['class' => 'form-control', 'data-fv-uri' => 'true', 'data-fv-uri-message' => 'The custom logo url must be a valid url', 'maxlength' => 255]) !!}
                </div>
            </div>
            {!! Form::text('finance_offers', $installations->finance_offers, ['hidden' => 'hidden', 'id' => 'finance_offers']) !!}
            <div class="form-group">
                <label class="col-sm-2 control-label">Finance Offer: In-store</label>
                <div class="col-sm-8">
                    <input class="bitwise" type="checkbox" @if($bitwise->contains(2)) checked @endif data-toggle="toggle" data-on="<i class='glyphicon glyphicon-ok'></i> Active" data-off="<i class='glyphicon glyphicon-remove'></i> Inactive" data-onstyle="success" data-offstyle="danger" data-size="small" value="2">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Finance Offer: Link</label>
                <div class="col-sm-8">
                    <input class="bitwise" type="checkbox" @if($bitwise->contains(12)) checked @endif data-toggle="toggle" data-on="<i class='glyphicon glyphicon-ok'></i> Active" data-off="<i class='glyphicon glyphicon-remove'></i> Inactive" data-onstyle="success" data-offstyle="danger" data-size="small" value="12">
                </div>
            </div>
            <div class="form-group">
                {!! Form::hidden('merchant_payments', $installations->merchant_payments) !!}
                {!! Form::label('merchant_payments_toggle', 'Merchant Payments', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-8">
                    @if($installations->merchant_payments == 1)
                        {!! Form::input('checkbox', 'merchant_payments_toggle', null, ['class' => 'merchant-payment-toggle', 'checked' => true,'data-toggle' => 'toggle', 'data-on' => '<i class="glyphicon glyphicon-ok"></i> Active', 'data-off' => '<i class="glyphicon glyphicon-remove"></i> Inactive', 'data-onstyle' => 'success', 'data-offstyle' => 'danger', 'data-size' => 'small', 'value' => '1']) !!}
                    @else
                        {!! Form::input('checkbox', 'merchant_payments_toggle', null, ['class' => 'merchant-payment-toggle', 'data-toggle' => 'toggle', 'data-on' => '<i class="glyphicon glyphicon-ok"></i> Active', 'data-off' => '<i class="glyphicon glyphicon-remove"></i> Inactive', 'data-onstyle' => 'success', 'data-offstyle' => 'danger', 'data-size' => 'small']) !!}
                    @endif
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-8">
                    <button type="submit" class="btn btn-info" name="save" value="generalSettings">Save Changes</button>
                </div>
            </div>
        </div>

        <div id="instoreSettings" class="tab-pane fade">
            <br/>
            <div class="form-group">
                {!! Form::label('location_instruction', 'Additional Email Instruction', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-8">
                    {!! Form::textArea('location_instruction', null, ['class' => 'form-control', 'maxlength' => 50000]) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('disclosure', 'In Store Disclosure', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-8">
                    {!! Form::textArea('disclosure', null, ['class' => 'form-control', 'maxlength' => 50000]) !!}
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-8">
                    <button type="submit" class="btn btn-info" name="save" value="instoreSettings">Save Changes</button>
                </div>
            </div>
        </div>

        <div id="integrationSettings" class="tab-pane fade">
            <br/>
            <div class="form-group">
                {!! Form::label('ext_return_url', 'Return URL', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-8">
                    {!! Form::text('ext_return_url', $installations->ext_return_url, ['class' => 'form-control', 'data-fv-uri' => 'true', 'data-fv-uri-message' => 'The return url must not be greater than 255 characters', 'maxlength' => 255]) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('ext_notification_url', 'Notification URL', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-8">
                    {!! Form::text('ext_notification_url', $installations->ext_notification_url, ['class' => 'form-control', 'data-fv-uri' => 'true', 'data-fv-uri-message' => 'The notification url must not be greater than 255 characters', 'maxlength' => 255]) !!}
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-8">
                    <button type="submit" class="btn btn-info" name="save" value="integrationSettings">Save Changes</button>
                </div>
            </div>
        </div>

        <div id="emailSettings" class="tab-pane fade">
            <br/>
            @include('installations.email')
        </div>
    </div>

    {!! Form::close() !!}

@endsection

@section('scripts')
    <link rel="stylesheet" media="screen" type="text/css" href="{!! Bust::cache('/css/colorpicker.css') !!}" />
    <script type="text/javascript" src="{!! Bust::cache('/js/colorpicker.js') !!}"></script>

    <script>
        $(document).ready(function(){
            if(window.location.hash != '') {
                $('a[href$='+ window.location.hash + ']').click();
            }

            $('.merchant-payment-toggle').change(function(){
                $('input[name=merchant_payments]').val($(this).prop('checked') | 0);
            });

            $('.bitwise').change(function() {
                var finance_offers = $('#finance_offers');
                var finance_value = finance_offers.val();
                var bitwise = $(this);
                var value = bitwise.val();
                if(bitwise.attr('checked')) {
                    bitwise.removeAttr('checked');
                    finance_offers.attr('value', (parseInt(finance_value) - parseInt(value)));

                } else {
                    bitwise.attr('checked', true);
                    finance_offers.attr('value', (parseInt(finance_value) + parseInt(value)));
                }
            });

            $(function () {
                $('[data-toggle="popover"]').popover()
            });

            var highlightColour = (
                    $('input#custom_colour_highlight').val().length ?
                            $('input#custom_colour_highlight').val() :
                            $('[data-colorSelector="colorSelectorHighlight"]').data('default')
            );

            var buttonColour = (
                    $('input#custom_colour_button').val().length ?
                            $('input#custom_colour_button').val() :
                            $('[data-colorSelector="colorSelectorButton"]').data('default')
            );

            $('#colorSelectorHighlight').children().first().attr('style', 'background-color: ' + highlightColour);
            $('#colorSelectorButton').children().first().attr('style', 'background-color: ' + buttonColour);

            $('#colorSelectorHighlight').ColorPicker({
                color: highlightColour,
                onShow: function (colpkr) {
                    $('#colorSelectorHighlight').attr('data-colorpickerid', $(colpkr).attr('id'));
                    $(colpkr).fadeIn(500);
                    return false;
                },
                onHide: function (colpkr) {
                    $(colpkr).fadeOut(500);
                    return false;
                },
                onChange: function (hsb, hex, rgb) {
                    $('input#custom_colour_highlight').val('#' + hex);
                    $('#colorSelectorHighlight').children().first().attr('style', 'background-color: #' + hex);
                }
            });

            $('#colorSelectorButton').ColorPicker({
                color: buttonColour,
                onShow: function (colpkr) {
                    $('#colorSelectorButton').attr('data-colorpickerid', $(colpkr).attr('id'));
                    console.log($(colpkr).attr('id'));
                    $(colpkr).fadeIn(500);
                    return false;
                },
                onHide: function (colpkr) {
                    $(colpkr).fadeOut(500);
                    return false;
                },
                onChange: function (hsb, hex, rgb) {
                    $('input#custom_colour_button').val('#' + hex);
                    $('#colorSelectorButton').children().first().attr('style', 'background-color: #' + hex);
                }
            });

            $('.colorSelectorReset').on('click', function(){
                $('#' + $(this).data('colorselector')).ColorPickerSetColor($(this).data('default'));
                $(this).siblings('input').first().val($(this).data('default'));
                $(this)
                        .siblings('.colorSelectorBlock')
                        .children()
                        .first()
                        .attr(
                                'style',
                                'background-color: ' + $(this).data('default')
                        );
            });

            $('.colorpicker_current_color').on('click', function(){
                var pickerId = $(this).parent().attr('id');
                var descendant = $($('[data-colorpickerid="' + pickerId + '"]'));
                $(descendant).siblings('input').val(rgb2hex($(this).css('background-color')));
                $(descendant).children('div').first().css('background-color', rgb2hex($(this).css('background-color')));
            });

            function rgb2hex(rgb){
                rgb = rgb.match(/^rgba?[\s+]?\([\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?/i);
                return (rgb && rgb.length === 4) ? "#" +
                ("0" + parseInt(rgb[1],10).toString(16)).slice(-2) +
                ("0" + parseInt(rgb[2],10).toString(16)).slice(-2) +
                ("0" + parseInt(rgb[3],10).toString(16)).slice(-2) : '';
            }
        });
    </script>
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.0/css/bootstrap-toggle.min.css" rel="stylesheet">
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.0/js/bootstrap-toggle.min.js"></script>
@endsection
