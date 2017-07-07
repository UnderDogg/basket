<ol class="breadcrumb">
    @php $url=''; @endphp
    @php $crumbs=Request::segments(); @endphp
    <li><a href="/"{!! $crumbs[0] !!}>Dashboard</a></li>
    @foreach($crumbs as $k => $v)

        {{-- */$url.='/'.$v;/* --}}
        <li>
        @if(end($crumbs) == $v || (isset($permission[$k]) && (!$permission[$k])))
            {!! ucwords(str_replace('-', ' ', isset($over[$k])?$over[$k]:$v)) !!}
        @else
        <a href="{!!$url!!}">{!! ucwords(str_replace('-', ' ', isset($over[$k])?$over[$k]:$v)) !!}</a>
        @endif
        </li>
    @endforeach
</ol>
