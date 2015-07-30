<div style="padding-right: 0px !important; padding-left: 0px !important;" class="col-md-12">
    <div class="col-md-6" style="padding-right: 0px !important; padding-left: 0px !important;" class="col-md-12">
        <div class="input-group">
            <input id="datepicker_from" class="filter form-control" placeholder="{{ $placeHolder_from }}" name="{{ $field_start }}" type="text" value="{{ Request::only($field_start)[$field_start] }}">
        </div>
    </div>
    <div class="col-md-6" style="padding-right: 0px !important; padding-left: 0px !important;" class="col-md-12">
        <div class="input-group">
            <input id="datepicker_to" class="ll-skin-latoja filter form-control" placeholder="{{ $placeHolder_to }}" name="{{ $field_end }}" type="text" value="{{ Request::only($field_end)[$field_end] }}">
        </div>
    </div>
</div>
