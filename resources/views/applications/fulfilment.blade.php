@extends('master')

@section('content')

    {{-- OVERLAY MESSAGES --}}
    {{--@include('includes.message.action_response', ['messages' => $messages, 'errors' => $errors])--}}

    <h2>Fulfil Application</h2>

    {{--@if($object !== null)--}}

        {{--@include('includes.page.breadcrumb', ['override2'=>$object->name])--}}

        <p>&nbsp;</p>
        {!! Form::open( ['method'=>'delete'] ) !!}

        <div class="form-group">
            <div class="col-sm-8 col-sm-offset-2 jumbotron">
                <p style="font-size: 18px;">
                    Please confirm that you would like to Fulfil Application.<br />
                    Please note that you will not be able to reverse this later.
                </p>
                <p>&nbsp;</p>
                <div class="form-group">
                    <div style="right: 15px" class="pull-right col-sm-3 col-xs-4">
                        {!! Form::submit('Fulfil', [
                        'class' => 'btn btn-danger form-control',
                        'name' => 'confirmDelete'
                        ]) !!}
                    </div>
                    <div style="right: 15px" class="pull-right col-sm-3 col-xs-4">
                        <a href="{{Request::server('HTTP_REFERER')}}" class="btn btn-info form-control">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    {{--@endif--}}

@endsection
