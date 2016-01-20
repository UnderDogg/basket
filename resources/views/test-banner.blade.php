@if (App::environment('test'))
    <div id="testing-banner" >
        {{env('TEST_BANNER_TEXT','TEST SITE')}}
    </div>
@endif
