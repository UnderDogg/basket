<div class="btn-group">
    <a href="{{Request::URL()}}/{{$id}}" type="button" class="btn btn-default btn-xs"> View </a>
    <button type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="caret"></span>
        <span class="sr-only">Toggle Dropdown</span>
    </button>
    <ul class="dropdown-menu dropdown-menu-right">
        @foreach($actions as $k => $action)
            <li><a href="{{Request::URL()}}/{{$id}}/{{$k}}">{{$action}}</a></li>
        @endforeach
    </ul>
</div>
