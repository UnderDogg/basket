@extends('master')

@section('page')
    <body>
    @if(env('ENV_BANNER', false))
        @include('env-banner')
    @endif
    <div class="container-fluid">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6">
                    <div class="pull-left">
                        <a href="/">
                            {!! HTML::image('image/ain-logo-standard-medium.svg', 'afforditNOW') !!}
                        </a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="pull-right">
                        @if($location->installation->custom_logo_url)
                            {!! HTML::image($location->installation->custom_logo_url, 'logo') !!}
                        @endif
                    </div>
                </div>
            </div>
            <br/>
            <div class="col-sm-8 col-sm-offset-2">
                <h2>New Application Link</h2>
                <div class="alert alert-info" role="alert">
                    <em>Amount and Finance Information goes here...</em>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading"><p class="panel-title">Order Information</p></div>
                    <div class="panel-body">
                        <div class="col-xs-12">
                            {!! Form::open(['action' => ['InitialisationController@performAssisted', $location->id], 'class' => 'form-horizontal', 'method' => 'POST']) !!}
                            <div class="form-group">
                                {!! Form::label('reference', 'Reference', ['class' => 'col-sm-2 control-label text-right']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('reference', $input['reference'], ['class' => 'form-control col-xs-12', 'data-fv-notempty' => 'true', 'data-fv-notempty-message' => 'Please enter a reference', 'maxlength' => 255]) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('description', 'Description', ['class' => 'col-sm-2 control-label text-right']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('description', 'Goods & Services', ['class' => 'form-control col-xs-12', 'data-fv-notempty' => 'true', 'data-fv-notempty-message' => 'Please enter a description for this order', 'maxlength' => 255]) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('email', 'Email', ['class' => 'col-sm-2 control-label text-right']) !!}
                                <div class="col-sm-8">
                                    {!! Form::email('email', null, ['class' => 'form-control col-xs-12', 'data-fv-notempty' => 'true', 'data-fv-notempty-message' => 'Please enter an email address', 'data-fv-emailaddress' => 'true', 'maxlength' => 255]) !!}
                                </div>
                            </div>

                            <!-- Previous input values -->
                            @foreach($input as $key => $value)
                                @if(!is_null($value) && !in_array($key, ['reference', 'description']))
                                    {!! Form::hidden($key, $value) !!}
                                @endif
                            @endforeach
                            {!! Form::hidden('installation', $location->installation->id) !!}

                            <div class="form-group">
                                <div class="col-sm-8 col-xs-12 col-sm-offset-2">
                                    <div class="pull-right">
                                        <a href="/" class="btn btn-default btn-bottom-margin">Cancel</a>
                                        {!! Form::submit('Create', ['class' => 'btn btn-success btn-bottom-margin', 'name' => 'assisted', 'value' => true]) !!}
                                    </div>
                                </div>
                            </div>

                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>

@endsection

@section('stylesheets')
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script>window.jQuery || document.writex('<script src="/js/jquery-1.9.1.min.js"><\/script>')</script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script src="{!! Bust::cache('/formvalidation/dist/js/formValidation.min.js') !!}"></script>
    <script src="{!! Bust::cache('/formvalidation/dist/js/framework/bootstrap.min.js') !!}"></script>

    <script src="{!! asset(Bust::cache('/js/fv.js')) !!}"></script>
@endsection
