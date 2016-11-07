<br>

{{--LARAVEL STANDARD ERRORS--}}
@if( $errors->any())
    <div id="actionMessage">
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close message_close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            @foreach ($errors->all() as $error)<p>{!! HTML::decode($error) !!}</p>@endforeach
        </div>
    </div>
@endif

{{--CUSTOM ASSIGNED ERRORS--}}

@foreach($messages as $k => $v)
    @if($v)
        <div id="actionMessage">
            <div class="alert alert-{{ ($k == 'error')?'danger':$k }} alert-dismissible" role="alert">
                <button type="button" class="close message_close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <p>{!! HTML::decode($v) !!}</p>
            </div>
        </div>
    @endif
@endforeach
