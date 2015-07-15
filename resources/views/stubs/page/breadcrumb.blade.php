<hr>
<ol style="font-size: 16px" class="breadcrumb well-lg">
    <li><a href="/{{ Request::segment(0) }}">Basket Dashboard</a></li>
    @if(Request::segment(1))
        @if(!Request::segment(2)) {{-- */$last=true;/* --}} @else {{-- */$last=false;/* --}} @endif
        @if($last == true) <li class="active"> @else <li><a href="/{{ Request::segment(1) }}"> @endif
            List {{ str_plural(ucwords(Request::segment(1))) }}
        @if($last == false) </a> @endif </li>
    @endif
    @if(Request::segment(2))
        @if(!Request::segment(3)) {{-- */$last=true;/* --}} @else {{-- */$last=false;/* --}} @endif
            @if($last == true) <li class="active"> @else <li><a href="/{{ Request::segment(1) }}/{{ Request::segment(2) }}"> @endif
            @if(is_numeric(Request::segment(2)))
                {{ ucwords(Request::segment(1)) }} ID @endif {{ Request::segment(2) }}
        @if($last == false) </a> @endif </li>
    @endif
    @if(Request::segment(3))
        @if(!Request::segment(4)) {{-- */$last=true;/* --}} @else {{-- */$last=false;/* --}} @endif
        @if($last == true) <li class="active"> @else <li><a href="/{{ Request::segment(1) }}/{{ Request::segment(2) }}/{{ Request::segment(3) }}"> @endif
            {{ ucwords(Request::segment(3)) }}
            @if(is_string(Request::segment(2)))
                {{ ucwords(Request::segment(1)) }}
            @endif
        @if($last == false) </a> @endif </li>
    @endif
    @if(Request::segment(4))
        <li class="active">
            @if(is_numeric(Request::segment(4))){{ ucwords(Request::segment(3)) }} ID @endif {{ Request::segment(4) }}
        </li>
    @endif
</ol>
<hr>