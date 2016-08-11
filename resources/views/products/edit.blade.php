@extends('main')

@section('content')
    <br>
    <br>
    {{--<input type="text" name="product-BNPL-06">--}}



    {!! Form::open(['method' => 'POST','action' => ['ProductLimitsController@updateProducts', $installation->id]]) !!}
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
                <td colspan="20"><strong>{{$group->getName()}}</strong></td>
            </tr>
            @forelse($group->getProducts() as $productData)
                <tr class="{{$group->getId()}}">
                    <td><code>{{$productData->getId()}}</code></td>
                    <td>{{$productData->getName()}}</td>
                    <td>{{number_format($productData->getDeposit()->getMinimumPercentage(), 2)}}%</td>
                    <td>{{number_format($productData->getDeposit()->getMaximumPercentage(), 2)}}%</td>
                    <td>{{'&pound;' . number_format($productData->getDeposit()->getMinimumAmount()/100, 2)}}</td>
                    <td>{{'&pound;' . number_format($productData->getDeposit()->getMaximumAmount()/100, 2)}}</td>
                    <td><input name="min-{{$productData->getId()}}"></td>
                    <td><input name="max-{{$productData->getId()}}"></td>
                <tr>
            @empty
                <tr>
                    <td>There are no products for {{$group->getName()}}</td>
                <tr>
            @endforelse
        @empty
            <tr>There are no products to display</tr>
        @endforelse
        </tbody>
    </table>
    {!! Form::submit('save son') !!}
    {!! Form::close() !!}


@endsection
