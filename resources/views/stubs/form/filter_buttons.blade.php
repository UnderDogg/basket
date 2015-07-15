<div class="btn-group pull-right">
    <button type="submit" class="filter btn btn-info btn-xs"> FILTER </button>
    <button type="button" class="filter btn btn-info dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="caret"></span>
        <span class="sr-only">Toggle Dropdown</span>
    </button>
    <ul class="dropdown-menu dropdown-menu-right">
        <li><a href="{{ Request::url() }}" onclick="">Clear All Filters</a></li>
        <li><a href="{{ URL::full() }}">Reset Current Changes</a></li>
        <li role="separator" class="divider"></li>
    </ul>
</div>