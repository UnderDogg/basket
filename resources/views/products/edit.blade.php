@extends('main')

@section('content')

    <h1>Edit Product Limits</h1>
    @include('includes.page.breadcrumb', ['crumbs' => Request::segments(), 'over' => [1  => $installation->name]])
    <p>&nbsp;</p>
    {!! Form::open(['method' => 'POST','action' => ['ProductLimitsController@updateProducts', $installation->id], 'class' => 'form-horizontal']) !!}
    <table class="table table-bordered">
        <thead>
        <tr>
            <th colspan="2">Product</th>
            <th colspan="4">Defined Deposit Limit</th>
            <th colspan="2">Retailer Deposit Limit</th>
        </tr>
        <tr>
            <th>Product ID</th>
            <th>Full Product Name</th>
            <th>Min %</th>
            <th>Max %</th>
            <th>Min £</th>
            <th>Max £</th>
            <th>Min %</th>
            <th>Max %</th>
        </tr>
        </thead>
        <tbody>
        @forelse($products as $group)
            <tr id="{{$group->getId()}}">
                <td colspan="8"><strong>{{$group->getName()}}</strong></td>
            </tr>
            @forelse($group->getProducts() as $productData)
                <tr class="{{$productData->getId()}}">
                    <td><code>{{$productData->getId()}}</code></td>
                    <td>{{$productData->getName()}}</td>
                    <td id="min" data-value="{{number_format($productData->getDeposit()->getMinimumPercentage(), 2)}}">{{number_format($productData->getDeposit()->getMinimumPercentage(), 2)}}%</td>
                    <td id="max" data-value="{{number_format($productData->getDeposit()->getMaximumPercentage(), 2)}}">{{number_format($productData->getDeposit()->getMaximumPercentage(), 2)}}%</td>
                    <td>{{'&pound;' . number_format($productData->getDeposit()->getMinimumAmount()/100, 2)}}</td>
                    <td>{{'&pound;' . number_format($productData->getDeposit()->getMaximumAmount()/100, 2)}}</td>
                    <td><input type="text" class="form-control" data-fv-digits="true" data-fv-stringlength-max="2" maxlength="2" data-fv-notempty data-fv-between-inclusive="true" min="{{number_format($productData->getDeposit()->getMinimumPercentage(), 0)}}" max="{{$productData->getDeposit()->getMaximumPercentage()}}" name="min-{{$productData->getId()}}" data-fv-lessthan="true" data-fv-lessthan-value="max-{{$productData->getId()}}" value="@if(array_has($limits, $productData->getId())){{number_format($limits[$productData->getId()]['min_deposit_percentage'], 0)}}@else{{number_format($productData->getDeposit()->getMinimumPercentage(), 0)}}@endif"></td>
                    <td><input type="text" class="form-control" data-fv-digits="true" data-fv-stringlength-max="2" maxlength="2" data-fv-notempty data-fv-between-inclusive="true" min="{{number_format($productData->getDeposit()->getMinimumPercentage(), 0)}}" max="{{$productData->getDeposit()->getMaximumPercentage()}}" name="max-{{$productData->getId()}}" data-fv-greaterthan="true" data-fv-greaterthan-value="min-{{$productData->getId()}}" value="@if(array_has($limits, $productData->getId())){{number_format($limits[$productData->getId()]['max_deposit_percentage'], 0)}}@else{{number_format($productData->getDeposit()->getMaximumPercentage(), 0)}}@endif"></td>
                <tr>
            @empty
                <tr>
                    <td colspan="8">There are no editable products for {{$group->getName()}}</td>
                <tr>
            @endforelse
        @empty
            <tr><td colspan="8">There are no editable products to display</td></tr>
        @endforelse
        </tbody>
    </table>
    {!! Form::submit('Save Product Limits', ['class' => 'btn btn-info pull-right']) !!}
    {!! Form::close() !!}

@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.form-horizontal').formValidation({
                framework: 'bootstrap',
                icon: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                err: {
                    container: 'tooltip'
                },
                row: {
                    selector: 'td'
                }
            }).on('success.validator.fv', function(e, data) {
                var type = data.field.split('-')[0];
                var product = data.field.split('-').slice(1).join('-');
                var opposite = (type == 'min') ? 'max' : 'min';

                if (data.fv.isValidField(type + '-' + product) && !data.fv.isValidField(opposite + '-' + product)) {
                    data.fv.revalidateField(opposite + '-' + product);
                }
            });
        });
    </script>
@endsection
