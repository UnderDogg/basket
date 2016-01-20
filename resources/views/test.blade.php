@if (App::environment('test'))
    <div id="testing-banner" >
        {{env('TEST_BANNER_TEXT','This is a test site')}}
    </div>
@endif
