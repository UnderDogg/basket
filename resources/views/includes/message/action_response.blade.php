<br>

{{--LARAVEL STANDARD ERRORS--}}
@if( $errors->any())
    <div id="actionMessage" hidden="hidden">
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close message_close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
        </div>
    </div>
@endif

{{--CUSTOM ASSIGNED ERRORS--}}

@if( $messages['error'] !== null )
    <div id="actionMessage" hidden="hidden">
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close message_close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <p>{{ $messages['error'] }}</p>
        </div>
    </div>
@endif
@if( $messages['info'] !== null )
    <div id="actionMessage" hidden="hidden">
        <div class="alert alert-info alert-dismissible" role="alert">
            <button type="button" class="close message_close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <p>{{ $messages['info'] }}</p>
        </div>
    </div>
@endif
@if( $messages['success'] !== null )
    <div id="actionMessage" hidden="hidden">
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close message_close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <p>{{ $messages['success'] }}</p>
        </div>
    </div>
@endif
