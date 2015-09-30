@extends('main')

@section('content')

    <h1>Create Merchant</h1>
    @include('includes.page.breadcrumb')

    {!! Form::open(['url' => 'merchants', 'class' => 'form-horizontal']) !!}
    <p>&nbsp;</p>
    <div class="col-xs-12">
        <div class="form-group">
            {!! Form::label('name', 'Name: ', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('name', null, ['class' => 'form-control']) !!}
            </div>
        </div><div class="form-group">
            {!! Form::label('token', 'Token: ', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('token', null, ['class' => 'form-control']) !!}
            </div>
        </div>
    </div>
    <p>&nbsp;</p>
    <div class="form-group">
        <div style="right: 15px" class="pull-right col-sm-3 col-xs-4">
            {!! Form::submit('Create Merchant', ['class' => 'btn btn-info form-control', 'name' => 'createMerchantButton']) !!}
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
                            message: 'The full name must not be greater than 255 characters'
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
