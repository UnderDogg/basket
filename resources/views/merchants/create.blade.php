@extends('main')

@section('content')

    <h1>Create Merchant</h1>
    @include('includes.page.breadcrumb')

    {!! Form::open(['url' => 'merchants', 'class' => 'form-horizontal']) !!}
    <div class="col-xs-12">
        <div class="form-group">
            {!! Form::label('name', 'Name: ', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('name', null, ['class' => 'form-control']) !!}
            </div>
        </div><div class="form-group">
            {!! Form::label('token', 'Token: ', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('token', null, ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-8">
                {!! Form::submit('Create Merchant', ['class' => 'btn btn-info', 'name' => 'createMerchantButton']) !!}
            </div>
        </div>
    </div>
    {!! Form::close() !!}

@endsection

@section('scripts')
    <script>
        validation = {
            fields: {
                name: {
                    validators: {
                        notEmpty: {
                            message: 'The name cannot be empty'
                        },
                        stringLength: {
                            max: 255,
                            message: 'The name must not be greater than 255 characters'
                        }
                    }
                },
                token: {
                    validators: {
                        notEmpty: {
                            message: 'The name cannot be empty'
                        },
                        stringLength: {
                            min: 32,
                            max: 32,
                            message: 'The token must be 32 characters'
                        }
                    }
                }
            }
        };
    </script>
@endsection
