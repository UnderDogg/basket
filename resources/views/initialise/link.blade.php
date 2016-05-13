@extends('main')

@section('content')

    <h1>Application Details</h1>
    @include('includes.page.breadcrumb', ['crumbs' => Request::segments(), 'over' => [1  => $location->name]])

    <div class="panel panel-default">
        <div class="panel-heading"><strong>Application Information</strong></div>
        <div class="panel-body">
            <dl class="dl-horizontal">

                <dt>Application ID</dt>
                <dd>{{ $data->getId() }}</dd>

                <dt>Order Reference</dt>
                <dd>{{ $data->getOrder()->getReference() }}</dd>

                <dt>Location</dt>
                <dd><a href="{{Request::segment(0)}}/locations/{{$location->id}}">{{ ucwords($location->name) }}</a></dd>

                <dt>Installation</dt>
                <dd><a href="{{Request::segment(0)}}/installations/{{$location->installation->id}}">{{ ucwords($location->installation->name) }}</a></dd>

                <dt>Resume URL</dt>
                <dd><a href="" id="return" data-clipboard-text="{{$data->getResumeUrl()}}">{{$data->getResumeUrl()}}</a></dd>
            </dl>
        </div>

        <div class="pull-right">
            <br/>
            <a href="/" class="btn btn-info">Return Home</a>
        </div>

        <div class='toast' style='display:none'>Copied to clipboard!</div>
    </div>

    <style>
        .toast {
            position: absolute;
            top: 50px;
            right: 20px;
            -webkit-box-shadow: 0px 0px 24px -1px rgba(56, 56, 56, 1);
            -moz-box-shadow: 0px 0px 24px -1px rgba(56, 56, 56, 1);
            box-shadow: 0px 0px 24px -1px rgba(56, 56, 56, 1);
            background-color: #383838;
            color: #F0F0F0;
            padding:10px;
        }
    </style>
@endsection

@section('scripts')
    <script>
        $('#return').click(function(e) {
            e.preventDefault();
            copyToClipboard(document.getElementById("return"));
            $('.toast').text('Copied to clipboard!').fadeIn(400).delay(3000).fadeOut(400);
            return false;
        });

        function copyToClipboard(elem) {
            var hidden = document.createElement("textarea");
            hidden.style.position = "absolute";
            hidden.style.left = "-9999px";
            hidden.style.top = "0";
            document.body.appendChild(hidden);

            hidden.textContent = elem.textContent;
            hidden.focus();
            hidden.setSelectionRange(0, hidden.value.length);
            return document.execCommand("copy");
        }
    </script>
@endsection
