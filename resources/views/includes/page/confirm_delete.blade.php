@extends('main')

@section('content')

    {{-- OVERLAY MESSAGES --}}
    @include('includes.message.action_response', ['messages' => $messages, 'errors' => $errors])

    <h2>{{ 'Delete ' . ucfirst(str_singular(Request::segment(1))) }}</h2>

    @if($object !== null)

        @include('includes.page.breadcrumb', ['crumbs' => Request::segments(), 'over' => [1  => $object->name]])

        {{--<p>&nbsp;</p>--}}
        {{--{!! Form::open( ['method'=>'delete','action'=>[--}}
            {{--$object->controller.'Controller@destroy',--}}
            {{--$object->id--}}
        {{--]] ) !!}--}}

        {{--<div class="form-group">--}}
            {{--<div class="col-sm-8 col-sm-offset-2 jumbotron">--}}
                {{--<p style="font-size: 18px;">--}}
                    {{--Please confirm that you would like to permanently delete the--}}
                        {{--@if(ends_with($object->type, 's'))--}}
                            {{--{!! str_limit($object->type,strlen($object->type) - 1, $end='') !!}--}}
                        {{--@else--}}
                            {{--{{$object->type}}--}}
                        {{--@endif--}}
                        {{--&#39;{{$object->name}}&#39;.--}}
                    {{--Please note that you will not be able to reverse this later.--}}
                {{--</p>--}}
                {{--<p>&nbsp;</p>--}}
                {{--<div class="form-group">--}}
                    {{--<div style="right: 15px" class="pull-right col-sm-3 col-xs-4">--}}
                        {{--{!! Form::submit('Confirm', [--}}
                            {{--'class' => 'btn btn-danger form-control',--}}
                            {{--'name' => 'confirmDelete'--}}
                        {{--]) !!}--}}
                    {{--</div>--}}
                    {{--<div style="right: 15px" class="pull-right col-sm-3 col-xs-4">--}}
                        {{--<a href="{{Request::server('HTTP_REFERER')}}" class="btn btn-info form-control">Cancel</a>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
        {{--{!! Form::close() !!}--}}

        {!! Form::open( ['method'=>'delete','action'=>[
            $object->controller.'Controller@destroy',
            $object->id
        ]] ) !!}
        <div class="panel panel-default">
            <div class="panel-heading"><strong>Key Information</strong></div>
            <div class="panel-body">
                <dl class="dl-horizontal">
                    @if(isset($object->reference))
                        <dt>Reference</dt>
                        <dd>{{$object->reference}}</dd>
                    @endif
                    @if(isset($object->name))
                        <dt>Name</dt>
                        <dd>{{$object->name}}</dd>
                    @endif
                    @if(isset($object->email))
                        <dt>Email</dt>
                        <dd>{{$object->email}}</dd>
                    @endif
                    @if(isset($object->address))
                        <dt>Address</dt>
                        <dd>{{$object->address}}</dd>
                    @endif
                    @if(isset($object->display_name))
                        <dt>Display Name</dt>
                        <dd>{{$object->display_name}}</dd>
                    @endif
                    @if(isset($object->description))
                        <dt>Description</dt>
                        <dd>{{$object->description}}</dd>
                    @endif
                </dl>
            </div>
        </div>
        <div class="alert alert-danger">
            <p>Are you sure that you want to permanently <strong>delete</strong> the
                @if(ends_with($object->type, 's'))
                    {!! str_limit($object->type,strlen($object->type) - 1, $end='') !!}
                @else
                    {{$object->type}}
                @endif
                <strong>{{$object->name}}</strong>?
                You will not be able to reverse this delete later.
            </p>
        </div>
        <div class="pull-right">
            {!! Form::submit('Confirm', [
                'class' => 'btn btn-danger',
                'name' => 'confirmDelete'
                ]) !!}
            <a href="{{Request::server('HTTP_REFERER')}}" class="btn btn-info">Cancel</a>
        </div>

        {!! Form::close() !!}
    @endif

@endsection
