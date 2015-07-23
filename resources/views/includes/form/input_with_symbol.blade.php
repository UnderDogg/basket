@if(isset($symbol))
    <div class="input-group">
        <span class="input-group-addon" id="basic-addon1">{{$symbol}}</span>
        <input
            class="filter col-xs-12 pull-down"
            name="{{ $field  }}"
            type="text"
            value="{!! Request::only($field)[$field] !!}"
        >
    </div>
@endif
