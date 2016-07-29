<div class="col-md-12 col-lg-12 col-sm-12" style="margin: 0 auto;">
    <div class="col-lg-4 col-md-12 col-sm-12">
        <h3>Metadata Customisation</h3>
        <div class="form-group">
            {!! Form::label('email_subject', 'Subject', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('email_subject', $emailConfigHelper->getSafe('email_subject'), ['placeholder' => env('EMAIL_TEMPLATE_DEFAULT_SUBJECT'), 'class' => 'email-customisation form-control']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('email_reply_to', 'Reply To', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('email_reply_to', $emailConfigHelper->getSafe('email_reply_to'), ['placeholder' => env('EMAIL_TEMPLATE_DEFAULT_REPLY_TO'), 'class' => 'email-customisation form-control']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('email_from_name', 'From Name', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('email_from_name', $emailConfigHelper->getSafe('email_from_name'), ['placeholder' => env('EMAIL_TEMPLATE_DEFAULT_FROM_NAME'), 'class' => 'email-customisation form-control']) !!}
            </div>
        </div>
        <hr>
        <h3>Footer Customisation</h3>
        <div class="form-group">
            {!! Form::label('retailer_url', 'Website url', ['class' => 'col-sm-2 control-label']) !!}
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
                {!! Form::hidden('custom_colour_highlight', $emailConfigHelper->getSafe('custom_colour_highlight'), ['class' => 'email-customisation color-picker preview-refresh form-control']) !!}
                <div class="colorSelectorBlock" id="colorSelectorHighlight"><div></div></div>
                <a class="btn btn-small btn-secondary colorSelectorReset" data-colorSelector="colorSelectorHighlight" data-default="#29ABE2">Reset</a>
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('custom_colour_button', 'Button', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::hidden('custom_colour_button', $emailConfigHelper->getSafe('custom_colour_button'), ['class' => 'email-customisation color-picker preview-refresh form-control']) !!}
                <div class="colorSelectorBlock" id="colorSelectorButton"><div></div></div>
                <a class="btn btn-small btn-secondary colorSelectorReset" data-colorSelector="colorSelectorButton" data-default="#39B54A">Reset</a>
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

        <div class="well well-lg">
            <iframe id="email-prev" src="/installations/{{ $installations->id }}/preview-email"></iframe>
        </div>
    </div>
</div>
