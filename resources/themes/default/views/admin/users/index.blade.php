@extends('layouts.master')
@section('head_extra')
    @include('partials._head_extra_table_action_css')
@endsection
@section('content')
    <div class='row'>
        <div class='col-md-12'>
            <!-- Box -->
            {!! Form::open( array('route' => 'admin.users.enable-selected', 'id' => 'frmUserList') ) !!}
                <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('admin/users/general.page.index.table-title') }}</h3>
                    &nbsp;|&nbsp;
                    <a class="btn btn-primary btn-sm" href="{!! route('admin.users.create') !!}" title="{{ trans('admin/users/general.button.create') }}">
                        <i class="fa fa-plus-square"></i> {{ trans('admin/users/general.button.create') }}
                    </a>
                    &nbsp;
                    <a class="btn btn-default btn-sm" href="#" onclick="document.forms['frmUserList'].action = '{!! route('admin.users.enable-selected') !!}';  document.forms['frmUserList'].submit(); return false;" title="{{ trans('general.button.enable') }}">
                        <i class="fa fa-check-circle-o"></i> {{ trans('general.button.enable') }}
                    </a>
                    &nbsp;
                    <a class="btn btn-default btn-sm" href="#" onclick="document.forms['frmUserList'].action = '{!! route('admin.users.disable-selected') !!}';  document.forms['frmUserList'].submit(); return false;" title="{{ trans('general.button.disable') }}">
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
                                    <th>{{ trans('admin/users/general.columns.username') }}</th>
                                    <th>{{ trans('admin/users/general.columns.name') }}</th>
                                    <th>{{ trans('admin/users/general.columns.roles') }}</th>
                                    <th>{{ trans('admin/users/general.columns.email') }}</th>
                                    <th>{{ trans('admin/users/general.columns.type') }}</th>
                                    <th class="column-action">{{ trans('admin/users/general.columns.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td align="center">
                                            @if ($user->canBeDisabled())
                                                {!! Form::checkbox('chkUser[]', $user->id); !!}
                                            @endif
                                        </td>
                                        <td>{!! link_to_route('admin.users.show', $user->username, [$user->id], []) !!}</td>
                                        <td>{!! link_to_route('admin.users.show', $user->full_name, [$user->id], []) !!}</td>
                                        <td>
                                            @foreach($user->roles as $role)
                                                {{ $role->name }}
                                            @endforeach
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->auth_type }}</td>
                                        <td>
                                            @if ( $user->isEditable() )
                                                <a href="{!! route('admin.users.edit', $user->id) !!}" title="{{ trans('general.button.edit') }}" class="btn btn-sm btn-default btn-action">
                                                    <i class="fa fa-pencil-square-o"></i> {{ trans('general.button.edit') }}
                                                </a>
                                            @else
                                                <a class="btn btn-sm btn-default disabled btn-action">
                                                    <i class="fa fa-pencil-square-o text-muted" title="{{ trans('admin/users/general.error.cant-be-edited') }}"></i> {{ trans('general.button.edit') }}
                                                </a>
                                            @endif

                                            @if ($user->canBeDisabled())
                                                @if ( $user->enabled )
                                                    <a href="{!! route('admin.users.disable', $user->id) !!}" title="{{ trans('general.button.disable') }}" class="btn btn-sm btn-warning btn-action">
                                                        <i class="fa fa-check-circle-o"></i> {{ trans('general.button.disable') }}
                                                    </a>
                                                @else
                                                    <a href="{!! route('admin.users.enable', $user->id) !!}" title="{{ trans('general.button.enable') }}" class="btn btn-sm btn-success btn-action">
                                                        <i class="fa fa-ban"></i> {{ trans('general.button.enable') }}
                                                    </a>
                                                @endif
                                            @else
                                                    <a class="btn btn-sm btn-warning disabled btn-action"><i class="fa fa-ban" title="{{ trans('admin/users/general.error.cant-be-disabled') }}">
                                                        </i> {{ trans('general.button.disable') }}
                                                    </a>
                                            @endif

                                            @if ( $user->isDeletable() )
                                                <a href="{!! route('admin.users.delete', $user->id) !!}"  class="btn btn-sm btn-danger btn-action" onclick = "return confirm('{{ trans('general.confirm.delete',['ID' => $user->id]) }}')">
                                                    <i class="fa fa-trash-o"></i> {{ trans('general.button.delete') }}
                                                </a>
                                            @else
                                                <a class="btn btn-sm btn-danger disabled btn-action">
                                                    <i class="fa fa-trash-o" title="{{ trans('admin/users/general.error.cant-be-deleted') }}"></i> {{ trans('general.button.delete') }}
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {!! $users->render() !!}
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
            checkboxes = document.getElementsByName('chkUser[]');
            for(var i=0, n=checkboxes.length;i<n;i++) {
                checkboxes[i].checked = !checkboxes[i].checked;
            }
        }
    </script>
@endsection
