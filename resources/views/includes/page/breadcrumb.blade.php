<ol class="breadcrumb">
    <li><a href="/{{ Request::segment(0) }}">Dashboard</a></li>
    @if(Request::segment(1))
        @if(!Request::segment(2)) {{-- */$last=true;/* --}} @else {{-- */$last=false;/* --}} @endif
        @if($last == true) <li class="active"> @else <li><a href="/{{ Request::segment(1) }}"> @endif

            @if(isset($override1)) {{ $override1 }} @else All {{ str_plural(ucwords(Request::segment(1))) }} @endif

        @if($last == false) </a> @endif </li>
    @endif
    @if(Request::segment(2))
        @if(!Request::segment(3)) {{-- */$last=true;/* --}} @else {{-- */$last=false;/* --}} @endif
            @if($last == true) <li class="active"> @else <li><a href="/{{ Request::segment(1) }}/{{ Request::segment(2) }}"> @endif

            @if(isset($override2)) {{ $override2 }} @else
                @if(is_numeric(Request::segment(2)))
                    {{ ucwords(Request::segment(1)) }} ID
                @endif
                {{ ucwords(Request::segment(2)) }}
            @endif
        @if($last == false) </a> @endif </li>
    @endif
    @if(Request::segment(3))
        @if(!Request::segment(4)) {{-- */$last=true;/* --}} @else {{-- */$last=false;/* --}} @endif
        @if($last == true) <li class="active"> @else <li><a href="/{{ Request::segment(1) }}/{{ Request::segment(2) }}/{{ Request::segment(3) }}"> @endif

            @if(isset($override3)) {{ $override3 }} @else
                {{ ucwords(Request::segment(3)) }}
                @if(is_string(Request::segment(2)))
                    {{ str_singular(ucwords(Request::segment(1))) }}
                @endif
            @endif
        @if($last == false) </a> @endif </li>
    @endif
    @if(Request::segment(4))
        <li class="active">
            @if(isset($override4)) {{ $override4 }} @else
                @if(is_numeric(Request::segment(4))){{ ucwords(Request::segment(3)) }} ID @endif {{ Request::segment(4) }}
            @endif
        </li>
    @endif
</ol>
