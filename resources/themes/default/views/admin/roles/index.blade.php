@extends('layouts.master')

@section('content')
    <div class='row'>
        <div class='col-md-12'>
            <!-- Box -->
            {!! Form::open( array('route' => 'admin.roles.enable-selected', 'id' => 'frmRoleList') ) !!}
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ trans('admin/roles/general.page.index.table-title') }}</h3>
                        &nbsp;|&nbsp;
                        <a class="btn btn-sm btn-primary" href="{!! route('admin.roles.create') !!}" title="{{ trans('admin/roles/general.button.create') }}">
                            <i class="fa fa-plus-square"></i> {{ trans('admin/roles/general.button.create') }}
                        </a>
                        &nbsp;
                        <a class="btn btn-default btn-sm" href="#" onclick="document.forms['frmRoleList'].action = '{!! route('admin.roles.enable-selected') !!}';  document.forms['frmRoleList'].submit(); return false;" title="{{ trans('general.button.enable') }}">
                            <i class="fa fa-check-circle-o"></i> {{ trans('general.button.enable') }}
                        </a>
                        &nbsp;
                        <a class="btn btn-default btn-sm" href="#" onclick="document.forms['frmRoleList'].action = '{!! route('admin.roles.disable-selected') !!}';  document.forms['frmRoleList'].submit(); return false;" title="{{ trans('general.button.disable') }}">
                            <i class="fa fa-ban"></i> {{ trans('general.button.disable') }}
                        </a>

                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th style="text-align: center">
                                            <a class="btn" href="#" onclick="toggleCheckbox(); return false;" title="{{ trans('general.button.toggle-select') }}">
                                                <i class="fa fa-check-square-o"></i>
                                            </a>
                                        </th>
                                        <th>{{ trans('admin/roles/general.columns.name') }}</th>
                                        <th>{{ trans('admin/roles/general.columns.display_name') }}</th>
                                        <th>{{ trans('admin/roles/general.columns.description') }}</th>
                                        <th>{{ trans('admin/roles/general.columns.permissions') }}</th>
                                        <th>{{ trans('admin/roles/general.columns.users') }}</th>
                                        <th>{{ trans('admin/roles/general.columns.actions') }}</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th style="text-align: center">
                                            <a class="btn" href="#" onclick="toggleCheckbox(); return false;" title="{{ trans('general.button.toggle-select') }}">
                                                <i class="fa fa-check-square-o"></i>
                                            </a>
                                        </th>
                                        <th>{{ trans('admin/roles/general.columns.name') }}</th>
                                        <th>{{ trans('admin/roles/general.columns.display_name') }}</th>
                                        <th>{{ trans('admin/roles/general.columns.description') }}</th>
                                        <th>{{ trans('admin/roles/general.columns.permissions') }}</th>
                                        <th>{{ trans('admin/roles/general.columns.users') }}</th>
                                        <th>{{ trans('admin/roles/general.columns.actions') }}</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach($roles as $role)
                                        <tr>
                                            <td align="center">{!! Form::checkbox('chkRole[]', $role->id); !!}</td>
                                            <td>{!! link_to_route('admin.roles.show', $role->name, [$role->id], []) !!}</td>
                                            <td>{!! link_to_route('admin.roles.show', $role->display_name, [$role->id], []) !!}</td>
                                            <td>{{ $role->description }}</td>
                                            <td>{{ $role->perms->count() }}</td>
                                            <td>{{ $role->users->count() }}</td>
                                            <td>
                                                @if ( $role->isEditable() || $role->canChangePermissions() )
                                                    <a href="{!! route('admin.roles.edit', $role->id) !!}" title="{{ trans('general.button.edit') }}" class="btn btn-sm btn-default btn-action">
                                                        <i class="fa fa-pencil-square-o"></i> {{ trans('general.button.edit') }}
                                                    </a>
                                                @else
                                                    <a class="btn btn-sm btn-default btn-action disabled">
                                                        <i class="fa fa-pencil-square-o" title="{{ trans('admin/roles/general.error.cant-edit-this-role') }}"></i> {{ trans('general.button.edit') }}
                                                    </a>
                                                @endif

                                                @if ( $role->enabled )
                                                    <a href="{!! route('admin.roles.disable', $role->id) !!}" title="{{ trans('general.button.disable') }}" class="btn btn-sm btn-warning btn-action">
                                                        <i class="fa fa-check-circle-o"></i> {{ trans('general.button.disable') }}
                                                    </a>
                                                @else
                                                    <a href="{!! route('admin.roles.enable', $role->id) !!}" title="{{ trans('general.button.enable') }}" class="btn btn-sm btn-success btn-action">
                                                        <i class="fa fa-ban"></i> {{ trans('general.button.enable') }}
                                                    </a>
                                                @endif

                                                @if ( $role->isDeletable() )
                                                    <a href="{!! route('admin.roles.delete', $role->id) !!}"  class="btn btn-sm btn-danger btn-action" onclick = "return confirm('{{ trans('general.confirm.delete',['ID' => $role->id]) }}')">
                                                    <i class="fa fa-trash-o"></i> {{ trans('general.button.delete') }}
                                                </a>
                                                @else
                                                    <a class="btn btn-sm btn-default btn-action disabled">
                                                        <i class="fa fa-trash-o" title="{{ trans('admin/roles/general.error.cant-delete-this-role') }}"></i> {{ trans('general.button.delete') }}
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $roles->render() !!}
                        </div> <!-- table-responsive -->

                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            {!! Form::close() !!}
        </div><!-- /.col -->

    </div><!-- /.row -->
@endsection


            <!-- Optional bottom section for modals etc... -->
@section('body_bottom')
    <script language="JavaScript">
        function toggleCheckbox() {
            checkboxes = document.getElementsByName('chkRole[]');
            for(var i=0, n=checkboxes.length;i<n;i++) {
                checkboxes[i].checked = !checkboxes[i].checked;
            }
        }
    </script>
@endsection
