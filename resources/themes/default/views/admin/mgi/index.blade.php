@extends('layouts.master')
@section('head_extra')
    @include('partials._head_extra_table_action_css')
@endsection
@section('content')
    <div class='row'>
        <div class='col-sm-12 col-md-12 col-lg-12'>

            <!-- Box -->
                <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">List of MGI</h3>
                    &nbsp;|&nbsp;
                    <a class="btn btn-primary btn-sm btn-table-head" href="{!! route('admin.mgi.create') !!}" title="Create">
                        <i class="fa fa-plus-square"></i> Create new
                    </a>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body" id="form-container">
                    <table class="table table-striped" width="100%">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Duration (month)</th>
                                <th class="column-action">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($data as $code => $value)
                                <tr>
                                    <td><a href="{{ url('admin/mgi/' . $code . '/edit') }}">{{ $code }}</a></td>
                                    <td><a href="{{ url('admin/mgi/' . $code . '/edit') }}">{{ $value[0] }}</a></td>
                                    <td><a href="{{ url('admin/mgi/' . $code . '/edit') }}">{{ $value[1] }}</a></td>
                                    <td>
                                        <a href="{{ url('admin/mgi/' . $code . '/edit') }}" class="btn btn-action btn-sm bg-purple"><i class="fa fa-edit"></i> {{ trans('general.button.edit') }}</a>
                                        <a href="{{ url('admin/mgi/delete/' . $code) }}" class="btn btn-action btn-sm btn-danger" onclick="return confirm('Are you sure want to delete this MGI?');"><i class="fa fa-times"></i> {{ trans('general.button.delete') }}</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection

