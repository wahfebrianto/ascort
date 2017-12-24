<div class="form-group">
    <label for="data">{!! trans('admin/audit/general.columns.data') !!}</label>
    @if ( $dataArray )
        <pre>{!! json_encode($dataArray, JSON_PRETTY_PRINT) !!}</pre>
    @else
        <input class="form-control" readonly="readonly" name="data" type="text" value="{!! trans('admin/audit/general.error.no-data') !!}" id="data">
    @endif
</div>

