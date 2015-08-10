<ol class="breadcrumb">
    {{-- */$url='';/* --}}
    <li><a href="/"{!! $crumbs[0] !!}>Dashboard</a></li>
    @foreach($crumbs as $k => $v)
        @if(end($crumbs) !== $v)<li><a href="{!!$url!!}">{!! ucwords(isset($over[$k])?$over[$k]:$v) !!}</a></li>
        @else<li class="active">{!! ucwords(isset($over[$k])?$over[$k]:$v) !!}</li>
        @endif
        {{-- */$url.='/'.$v;/* --}}
    @endforeach
</ol>
