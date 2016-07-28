<div class="col-md-12 col-lg-12 col-sm-12" style="margin: 0 auto;">
    <div class="col-lg-4 col-md-12 col-sm-12">
        <h2>Email Customisation</h2>
        <div class="form-group">
            {!! Form::label('email_subject', 'Email Subject', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('email_subject', $emailConfigHelper->getSafe('email_subject'), ['class' => 'email-customisation form-control']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('email_reply_to', 'Reply-to address', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('email_reply_to', $emailConfigHelper->getSafe('email_reply_to'), ['class' => 'email-customisation form-control']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('email_from_name', 'Email From (Name)', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('email_from_name', $emailConfigHelper->getSafe('email_from_name'), ['class' => 'email-customisation form-control']) !!}
            </div>
        </div>
        <hr>
        <h2>Retailer Specific Configuration</h2>
        <div class="form-group">
            {!! Form::label('retailer_name', 'Retailer Name', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('retailer_name', $emailConfigHelper->getSafe('retailer_name'), ['class' => 'email-customisation form-control']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('retailer_query_email', 'Query Email', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('retailer_query_email', $emailConfigHelper->getSafe('retailer_name'), ['class' => 'email-customisation form-control']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('retailer_url', 'Retailer URL', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('retailer_url', $emailConfigHelper->getSafe('retailer_url'), ['class' => 'email-customisation form-control']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('retailer_telephone', 'Retailer Telephone', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('retailer_telephone', $emailConfigHelper->getSafe('retailer_telephone'), ['class' => 'email-customisation form-control']) !!}
            </div>
        </div>
        <hr>
        <h2>Email visual customisation</h2>
        <div class="form-group">
            {!! Form::label('custom_colour_hr', 'Horizontal Rule Colour', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('custom_colour_hr', $emailConfigHelper->getSafe('custom_colour_hr'), ['class' => 'email-customisation form-control']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('custom_colour_button', 'Button Colour', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('custom_colour_button', $emailConfigHelper->getSafe('custom_colour_button'), ['class' => 'email-customisation form-control']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('custom_colour_header', 'Header Text Colour', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('custom_colour_header', $emailConfigHelper->getSafe('custom_colour_h2'), ['class' => 'email-customisation form-control']) !!}
            </div>
        </div>
    </div>
    <div class="col-lg-8 col-md-12 col-sm-12">
        <h2>Email Preview</h2>
        <style>
            iframe {
                width: 100%;
                height: 100vh;
                border: none;
            }
        </style>
        <div class="well well-lg">
            <iframe id="email-prev" src="https://basket.paybreak.com/installations/1/preview-email"></iframe>
        </div>
    </div>
</div>
<script>
    window.onload = function(){
        var previewUrl = '/installations/1/preview-email';

        $(function() {
            $('.email-customisation').change(function(){
                console.log('reached');
                console.log(buildPreviewUrl(getElementJson('.email-customisation')));
            });

            function getElementJson(cssClass){
                var formElements = {};

                $(cssClass).each(function(){
                    var value = $(this).val();
//                    if (value != '') {

                        formElements[$(this).attr('name')] = value;
//                    }
                });

                return JSON.stringify(formElements);
            }

            function buildPreviewUrl(paramsJson){
                var urlParams = [];

                $.each(JSON.parse(paramsJson), function (name, value) {
                    urlParams.push(name + '=' + encodeURIComponent(value));
                });

                console.log(previewUrl + '?' + urlParams.join('&'));

                loadIframe('email-prev', previewUrl + '?' + urlParams.join('&'));
            }

            function loadIframe(iframeName, url) {
                var iframe = $('#' + iframeName);
                if ( iframe.length ) {
                    iframe.attr('src',url);
                    return false;
                }
                return true;
            }
        });
    };
</script>