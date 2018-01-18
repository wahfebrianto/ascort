@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')
    @include('partials._head_extra_bsdatepicker_css')
    @include('partials._head_extra_cities_edit_agent', ["birthCity" => $agent->birth_place, "addressState" => $agent->state, "addressCity" => $agent->city])
@endsection

@section('content')
    <div class='row'>
        <div class='col-md-12'>
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('agents/general.page.edit.section-title') }}</h3>
                    <div class="box-tools pull-right">
                    </div>
                </div>
                <div class="box-body">

                    {!! Form::model( $agent, ['route' => ['agents.update', $agent->id], 'method' => 'PATCH', 'id' => 'form_edit_agent', 'class' => 'form-horizontal', 'files' => true] ) !!}
                    {!! Form::hidden('id', null, ['class' => 'form-control', 'id' => 'id']) !!}
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_basic" data-toggle="tab" aria-expanded="true">{!! trans('agents/general.tabs.basic') !!}</a></li>
                            <li class=""><a href="#tab_agent_details" data-toggle="tab" aria-expanded="false">{!! trans('agents/general.tabs.agent_detail') !!}</a></li>
                        </ul>
                        <div class="tab-content">

                            <div class="tab-pane active" id="tab_basic">

                                <div class="form-group">
                                    {!! Form::label('agent_code', trans('agents/general.columns.agent_code'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('agent_code', null, ['class' => 'form-control', 'tabindex' => 0, 'disabled']) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('NIK', trans('agents/general.columns.NIK'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('NIK', null, ['class' => 'form-control', 'tabindex' => 1]) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('name', trans('agents/general.columns.name'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('name', null, ['class' => 'form-control', 'tabindex' => 2]) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('phone_number', trans('customers/general.columns.phone_number'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('phone_number', null, ['class' => 'form-control', 'tabindex' => 3]) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('handphone_number', trans('customers/general.columns.handphone_number'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('handphone_number', null, ['class' => 'form-control', 'tabindex' => 4]) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('email', trans('customers/general.columns.email'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('email', null, ['class' => 'form-control', 'tabindex' => 5]) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('birth_place_state', trans('customers/general.columns.birth_place_state'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        <div class="input-group select2-bootstrap-append">
                                            {!! Form::select('birth_place_state', [], null, ['class' => 'form-control',  'style' => "width: 100%", 'tabindex' => 3]) !!}
                                            <span class="input-group-addon">
                                                <span class="fa fa-fw fa-globe"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('birth_place', trans('customers/general.columns.birth_place'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        <div class="input-group select2-bootstrap-append">
                                            {!! Form::select('birth_place', [], null, ['class' => 'form-control', 'id' => 'birth_place',  'style' => "width: 100%", 'tabindex' => 4]) !!}
                                            <span class="input-group-addon">
                                                <span class="fa fa-fw fa-map-marker"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('DOB', trans('agents/general.columns.DOB'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        <div class="input-group">
                                            {!! Form::text('DOB', null, ['class' => 'form-control datepicker', 'data-date-format' => 'dd/mm/yyyy', 'tabindex' => 5]) !!}
                                            <span class = "input-group-addon"><span class="fa fa-fw fa-calendar"></span></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('gender', trans('customers/general.columns.gender'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        <div class="checkbox icheck">
                                            {!! Form::radio('gender', 'M', true) !!}&nbsp;&nbsp;Male &nbsp;&nbsp;
                                            {!! Form::radio('gender', 'F', false) !!}&nbsp;&nbsp;Female
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('address', trans('agents/general.columns.address'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('address', null, ['class' => 'form-control', 'tabindex' => 8]) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('state', trans('customers/general.columns.state'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        <div class="input-group select2-bootstrap-append">
                                            {!! Form::select('state', array_keys(config('cities')), null, ['class' => 'form-control', 'id' => 'state',  'style' => "width: 100%", 'tabindex' => 9]) !!}
                                            <span class="input-group-addon">
                                                <span class="fa fa-fw fa-globe"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('city', trans('customers/general.columns.city'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        <div class="input-group select2-bootstrap-append">
                                            {!! Form::select('city', [], null, ['class' => 'form-control',  'style' => "width: 100%", 'tabindex' => 10]) !!}
                                            <span class="input-group-addon">
                                                <span class="fa fa-fw fa-map-marker"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('zipcode', trans('customers/general.columns.zipcode'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('zipcode', null, ['class' => 'form-control', 'tabindex' => 12]) !!}
                                    </div>
                                </div>
                            </div><!-- /.tab-pane -->

                            <div class="tab-pane" id="tab_agent_details">

                                <div class="form-group">
                                    {!! Form::label('NPWP', trans('agents/general.columns.NPWP'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('NPWP', null, ['class' => 'form-control', 'tabindex' => 19]) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('bank', trans('agents/general.columns.bank'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('bank', null, ['class' => 'form-control', 'tabindex' => 20]) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('bank_branch', trans('agents/general.columns.bank_branch'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('bank_branch', null, ['class' => 'form-control', 'tabindex' => 21]) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('account_number', trans('agents/general.columns.account_number'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('account_number', null, ['class' => 'form-control', 'tabindex' => 22]) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('account_name', trans('agents/general.columns.account_name'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('account_name', null, ['class' => 'form-control', 'tabindex' => 17]) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('agent_position_id', trans('agents/general.columns.agent_position_id'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        <div class="input-group select2-bootstrap-append">
                                            {!! Form::select('agent_position_id', $agent_position_lists, null, ['class' => 'form-control', 'id' => 'agent_position_id_select',  'style' => "width: 100%", 'tabindex' => 23 ]) !!}
                                            <span class="input-group-addon">
                                                <span class="fa fa-fw fa-briefcase"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('parent_id', trans('agents/general.columns.parent_id'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        <div class="input-group select2-bootstrap-append">
                                            {!! Form::select('parent_id', $agent_lists, ($agent->parent_id == null? 'none' : $agent->parent_id), ['class' => 'form-control select2', 'id' => 'parent_id_select',  'style' => "width: 100%", 'tabindex' => 23]) !!}
                                            <span class="input-group-addon">
                                            <span class="fa fa-fw fa-briefcase"></span>
                                        </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('join_date', trans('agents/general.columns.join_date'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        <div class="input-group">
                                            {!! Form::text('join_date', null, ['class' => 'form-control datepicker', 'data-date-format' => 'dd/mm/yyyy', 'tabindex' => 24]) !!}
                                            <span class = "input-group-addon"><span class="fa fa-fw fa-calendar"></span></span>
                                        </div>
                                    </div>
                                </div>
								<div class="form-group">
									{!! Form::label('type', trans('agents/general.columns.type'), ['class' => 'control-label col-sm-2'] ) !!}
									<div class="col-sm-10">
										<div class="input-group select2-bootstrap-append">
											{!! Form::select('type', $types, null, ['class' => 'form-control', 'style' => "width: 100%", 'tabindex' => 6]) !!}
											<span class="input-group-addon">
										<span class="fa fa-fw fa-fw fa-dollar"></span>
									</span>
										</div>
									</div>
								</div>
							</div>
                            </div><!-- /.tab-pane -->

                        </div><!-- /.tab-content -->
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12">
                            {!! Form::submit( trans('general.button.save'), ['class' => 'btn btn-primary btn-submit', 'id' => 'btn-submit-save', 'tabindex' => 25] ) !!}
                            <a id="resetFormAnchor" disabled onclick="resetForm($('#form_save_menu'))"  title="{{ trans('general.button.clear') }}" class='btn btn-default'><span class="fa fa-fw fa-eraser"></span> {{ trans('general.button.clear') }}</a>
                            <div class="pull-right">
                                <a id="cancelForm" title="{{ trans('general.button.cancel') }}" class='btn btn-danger' href="{!! route('agents.index') !!}"><span class="fa fa-fw fa-times"></span> {{ trans('general.button.cancel') }}</a>
                            </div>
                        </div>
                    </div>

                    {!! Form::close() !!}

                </div><!-- /.box-body -->
            </div><!-- /.box-body -->
        </div><!-- /.col -->

    </div><!-- /.row -->
@endsection

@section('body_bottom')

    <div class="modal fade modal-default" tabindex="-1" role="dialog" id="fileModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Image</h4>
                </div>
                <div class="modal-body">
                    {!! Html::image(config('filestorage.agents.id_card_image_dir') . $agent->id_card_image_filename, 'id_card', ['style' => 'width:100%;']) !!}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <script src="{{ asset ("/bower_components/admin-lte/select2/js/select2.min.js") }}" type="text/javascript"></script>

    <script src="{{ asset ("/bower_components/admin-lte/plugins/datepicker/bootstrap-datepicker.js") }}" type="text/javascript"></script>
    <script src="{{ asset("/bower_components/admin-lte/plugins/iCheck/icheck.min.js") }}" type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue'
            });
            $('.datepicker').datepicker().attr('placeholder', 'dd/mm/yyyy');
            $('.select2').select2();
        });
    </script>
    <script type="text/javascript" src="{!! asset('vendor/jsvalidation/js/jsvalidation.js') !!}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\EditAgentRequest', '#form_edit_agent') !!}
@endsection
