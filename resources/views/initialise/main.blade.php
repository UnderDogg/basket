@extends('master')

@section('content')

    <h1>Make Application</h1>
    {!! Form::open(['id' => 'order-form']) !!}
        <div class="form-group">
            <label>Price</label>

            <div class="input-group">
                <div class="input-group-addon">£</div>
                <input type="text" id="amount" class="form-control" maxlength="10" >
            </div>

        </div>

        <div class="form-group">
            <label>Description</label>

            <input type="text" id="description" class="form-control" value="Goods & services" maxlength="255" >

        </div>

        <div class="form-group">
            <label>Reference</label>

            <input type="text" id="reference" class="form-control" maxlength="255" >

        </div>

        <button type="submit" class="btn btn-default">Submit</button>

    {!! Form::close() !!}

    <script type="text/javascript">

        document.addEventListener('DOMContentLoaded', function() {
            $('#order-form').submit( function(e) {
                e.preventDefault();

                return false;
            });
        }, false);
    </script>

@endsection
