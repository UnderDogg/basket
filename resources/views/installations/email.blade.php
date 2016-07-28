<div class="col-md-12 col-lg-12 col-sm-12" style="margin: 0 auto;">
    <div class="col-lg-4 col-md-12 col-sm-12">
        <h3>Metadata Customisation</h3>
        <div class="form-group">
            {!! Form::label('email_subject', 'Subject', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('email_subject', $emailConfigHelper->getSafe('email_subject'), ['placeholder' => 'afforditNOW Finance Application', 'class' => 'email-customisation form-control']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('email_reply_to', 'Reply', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('email_reply_to', $emailConfigHelper->getSafe('email_reply_to'), ['placeholder' => 'hello@paybreak.com', 'class' => 'email-customisation form-control']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('email_from_name', 'Sender', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('email_from_name', $emailConfigHelper->getSafe('email_from_name'), ['placeholder' => 'afforditNOW Finance', 'class' => 'email-customisation form-control']) !!}
            </div>
        </div>
        <hr>
        <h3>Footer Customisation</h3>
        <div class="form-group">
            {!! Form::label('retailer_url', 'Website URL', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('retailer_url', $emailConfigHelper->getSafe('retailer_url'), ['class' => 'email-customisation preview-refresh form-control']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('retailer_telephone', 'Telephone', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('retailer_telephone', $emailConfigHelper->getSafe('retailer_telephone'), ['class' => 'email-customisation preview-refresh form-control']) !!}
            </div>
        </div>
        <hr>
        <h3>Colour Customisation</h3>
        <div class="form-group">
            {!! Form::label('custom_colour_highlight', 'Highlight', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('custom_colour_highlight', $emailConfigHelper->getSafe('custom_colour_highlight'), ['class' => 'email-customisation preview-refresh form-control']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('custom_colour_button', 'Button', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('custom_colour_button', $emailConfigHelper->getSafe('custom_colour_button'), ['class' => 'email-customisation preview-refresh form-control']) !!}
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-8">
                <button type="submit" class="btn btn-info" name="save" value="emailSettings">Save Changes</button>
            </div>
        </div>
    </div>
    <div class="col-lg-8 col-md-12 col-sm-12">
        <h3>Preview</h3>
        <style>
            iframe {
                width: 100%;
                height: 60vh;
                border: none;
            }
        </style>
        <div class="well well-lg">
            <iframe id="email-prev" src="/installations/1/preview-email"></iframe>
        </div>
    </div>
</div>
<script>
    window.onload = function(){
        var previewUrl = '/installations/1/preview-email';

        $(function() {
            $('.email-customisation.preview-refresh').on('change paste input', function(){
                console.log('reached');
                console.log(buildPreviewUrl(getElementJson('.email-customisation.preview-refresh')));
            });

            function getElementJson(cssClass){
                var formElements = {};

                $(cssClass).each(function(){
                    var value = $(this).val();
                    if (value != '') {
                        formElements[$(this).attr('name')] = value;
                    }
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