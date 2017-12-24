<a class="btn btn-primary btn-sm btn-table-head" href="{!! route($module_name . '.create') !!}" title="{{ $create_title }}">
    <i class="fa fa-plus-square"></i> {{ $create_title }}
</a>

@if($enabledOnly == 1)
    <a class="btn btn-default btn-sm btn-table-head" href="#" id="btnDisableSelected"
       title="{{ trans('general.button.delete-selected') }}">
        <i class="fa fa-trash"></i> {{ trans('general.button.delete-selected') }}
    </a>
@else
    <a class="btn btn-default btn-sm btn-table-head" href="#" id="btnEnableSelected"
       onclick="return confirm('{!! trans('general.confirm.restore-selected') !!}');"
       title="{{ trans('general.button.restore-selected') }}">
        <i class="fa fa-trash"></i> {{ trans('general.button.restore-selected') }}
    </a>
@endif

@if($enabledOnly == 1)
    <a class="btn btn-default btn-sm btn-table-head" href="{!! route($module_name . '.index', ['enabledOnly' => 0]) !!}" title="{{ trans('general.button.show-deleted') }}">
        <i class="fa fa-eye"></i> {{ trans('general.button.show-deleted') }}
    </a>
@else
    <a class="btn btn-default btn-sm btn-table-head" href="{!! route($module_name . '.index', ['enabledOnly' => 1]) !!}" title="{{ trans('general.button.show-active') }}">
        <i class="fa fa-eye"></i> {{ trans('general.button.show-active') }}
    </a>
@endif