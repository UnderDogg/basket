<ol class="breadcrumb">
    {{-- */$url='';/* --}}
    <li><a href="/"{!! $crumbs[0] !!}>Dashboard</a></li>
    @foreach($crumbs as $k => $v)

        {{-- */$url.='/'.$v;/* --}}
        @if(end($crumbs) == $v)
            <li>{!! ucwords(isset($over[$k])?$over[$k]:$v) !!}</li>
        @else
        <li><a href="{!!$url!!}">{!! ucwords(isset($over[$k])?$over[$k]:$v) !!}</a></li>
        @endif
    @endforeach
</ol>
