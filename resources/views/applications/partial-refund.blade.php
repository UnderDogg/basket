@extends('master')

@section('content')

    {{-- OVERLAY MESSAGES --}}
    @include('includes.message.action_response', ['messages' => $messages, 'errors' => $errors])

    <h2>Partial Refund</h2>

    @include('includes.page.breadcrumb')

    <p>&nbsp;</p>
    {!! Form::open( ['method'=>'post'] ) !!}

    <div class="form-group">
        <div class="col-sm-8 col-sm-offset-2 jumbotron">
            <p style="font-size: 18px;">
                Please confirm that you would like to request a partial refund of this application.<br />
                Please note that you will not be able to reverse this later.
            </p>

            <div class="form-group">
                <label>Refund Amount</label>
                <div class="input-group">
                    <div class="input-group-addon">&pound;</div>
                    {!! Form::text('refund_amount', null, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="form-group">
                <label>Effective Date</label>
                <div class="input-group">
                    <input name="effective_date" type="text" class="form-control" id="datepicker_to">
                    <div class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Description</label>
                <input name="description" type="text" class="form-control">
            </div>

            <p>&nbsp;</p>
            <div class="form-group">
                <div style="right: 15px" class="pull-right col-sm-3 col-xs-4">
                    {!! Form::submit('Request', [
                    'class' => 'btn btn-danger form-control',
                    'name' => 'confirmRequest'
                    ]) !!}
                </div>
                <div style="right: 15px" class="pull-right col-sm-3 col-xs-4">
                    <a href="{{Request::server('HTTP_REFERER')}}" class="btn btn-info form-control">Cancel</a>
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}

@endsection
