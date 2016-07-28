<h2>Retailer Specific Configuration</h2>
<div class="form-group">
    {!! Form::label('retailer_name', 'Retailer Name', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-8">
        {!! Form::text('retailer_name', $emailConfigHelper->getSafe('retailer_name'), ['class' => 'form-control']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('retailer_query_email', 'Query Email', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-8">
        {!! Form::text('retailer_query_email', $emailConfigHelper->getSafe('retailer_name'), ['class' => 'form-control']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('retailer_url', 'Retailer URL', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-8">
        {!! Form::text('retailer_url', $emailConfigHelper->getSafe('retailer_url'), ['class' => 'form-control']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('retailer_telephone', 'Retailer Telephone', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-8">
        {!! Form::text('retailer_telephone', $emailConfigHelper->getSafe('retailer_telephone'), ['class' => 'form-control']) !!}
    </div>
</div>
<hr>
<h2>Email visual customisation</h2>
<div class="form-group">
    {!! Form::label('custom_colour_hr', 'Horizontal Rule Colour', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-8">
        {!! Form::text('custom_colour_hr', $emailConfigHelper->getSafe('custom_colour_hr'), ['class' => 'form-control']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('custom_colour_button', 'Button Colour', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-8">
        {!! Form::text('custom_colour_button', $emailConfigHelper->getSafe('custom_colour_button'), ['class' => 'form-control']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('custom_colour_h2', 'Header Text Colour', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-8">
        {!! Form::text('custom_colour_h2', $emailConfigHelper->getSafe('custom_colour_h2'), ['class' => 'form-control']) !!}
    </div>
</div>


<hr>
<h2>Email Preview</h2>
