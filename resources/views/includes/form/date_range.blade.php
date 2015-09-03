<div style="padding-right: 0px !important; padding-left: 0px !important;" class="col-md-12">
    <div style="padding-right: 0px !important; padding-left: 2px !important; padding-bottom: 2px !important;">
        <div class="datepicker">
            {!! Form::text($field_start, Request::only($field_start)[$field_start], ['id' => 'datepicker_from', 'class' => 'filter form-control', 'placeholder' => $placeHolder_from]) !!}
        </div>
    </div>
    <div style="padding-right: 0px !important; padding-left: 2px !important;" class="col-md-12">
        <div class="datepicker">
            {!! Form::text($field_end, Request::only($field_end)[$field_end], ['id' => 'datepicker_to', 'class' => 'filter form-control', 'placeholder' => $placeHolder_to]) !!}
        </div>
    </div>
</div>
