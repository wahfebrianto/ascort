@extends('layouts.master')

@section('head_extra')
    @include('partials._head_extra_bsdatepicker_css')
    @include('partials._head_extra_select2_css')
    @include('partials._head_extra_cities_add')
@endsection

@section('content')
    {!! Form::open( ['route' => 'agents.store', 'id' => 'form_create_agent', 'files' => true, 'class' => 'form-horizontal'] ) !!}
    {!! Form::hidden('id', null, ['class' => 'form-control', 'id' => 'id']) !!}
    {!! Form::hidden('branch_office_id', 1, ['class' => 'form-control', 'id' => 'id']) !!}
    <div class="box box-primary fa-bg fa-bg-briefcase">
        <div class="box-header with-border">
            <i class="fa fa-briefcase"></i>
            <h3 class="box-title">{{ trans('agents/general.page.create.description') }}</h3>
            <div class="box-tools pull-right">
            </div>
        </div>
        <div class="box-body">
            <div class='row'>
                <div class='col-md-12 col-lg-6'>
                    <!-- Box -->

                    <div class="box box-default">
                        <div class="box-header with-border">
                            <h3 class="box-title">{{ trans('agents/general.page.create.general-info') }}</h3>
                        </div>
                        <div class="box-body">

                            <div class="form-group">
                                {!! Form::label('agent_code', trans('agents/general.columns.agent_code'), ['class' => 'control-label col-sm-3'] ) !!}
                                <div class="col-sm-9">
                                    {!! Form::text('agent_code', null, ['class' => 'form-control', 'tabindex' => 0]) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('NIK', trans('agents/general.columns.NIK'), ['class' => 'control-label col-sm-3'] ) !!}
                                <div class="col-sm-9">
                                    {!! Form::text('NIK', null, ['class' => 'form-control', 'tabindex' => 1]) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('name', trans('agents/general.columns.name'), ['class' => 'control-label col-sm-3'] ) !!}
                                <div class="col-sm-9">
                                    {!! Form::text('name', null, ['class' => 'form-control', 'tabindex' => 2]) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('phone_number', trans('customers/general.columns.phone_number'), ['class' => 'control-label col-sm-3'] ) !!}
                                <div class="col-sm-9">
                                    {!! Form::text('phone_number', null, ['class' => 'form-control', 'tabindex' => 3]) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('handphone_number', trans('customers/general.columns.handphone_number'), ['class' => 'control-label col-sm-3'] ) !!}
                                <div class="col-sm-9">
                                    {!! Form::text('handphone_number', null, ['class' => 'form-control', 'tabindex' => 4]) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('email', trans('customers/general.columns.email'), ['class' => 'control-label col-sm-3'] ) !!}
                                <div class="col-sm-9">
                                    {!! Form::text('email', null, ['class' => 'form-control', 'tabindex' => 5]) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('birth_place_state', trans('customers/general.columns.birth_place_state'), ['class' => 'control-label col-sm-3'] ) !!}
                                <div class="col-sm-9">
                                    <div class="input-group select2-bootstrap-append">
                                        {!! Form::select('birth_place_state', [], null, ['class' => 'form-control',  'style' => "width: 100%", 'tabindex' => 6]) !!}
                                        <span class="input-group-addon">
                                            <span class="fa fa-fw fa-globe"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('birth_place', trans('customers/general.columns.birth_place'), ['class' => 'control-label col-sm-3'] ) !!}
                                <div class="col-sm-9">
                                    <div class="input-group select2-bootstrap-append">
                                        {!! Form::select('birth_place', [], null, ['class' => 'form-control', 'id' => 'birth_place',  'style' => "width: 100%", 'tabindex' => 7]) !!}
                                        <span class="input-group-addon">
                                            <span class="fa fa-fw fa-map-marker"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('DOB', trans('agents/general.columns.DOB'), ['class' => 'control-label col-sm-3'] ) !!}
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        {!! Form::text('DOB', null, ['class' => 'form-control datepicker', 'data-date-format' => 'dd/mm/yyyy', 'tabindex' => 8]) !!}
                                        <span class = "input-group-addon"><span class="fa fa-fw fa-calendar"></span></span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('gender', trans('agents/general.columns.gender'), ['class' => 'control-label col-sm-3'] ) !!}
                                <div class="col-sm-9">
                                    <div class="checkbox icheck">
                                        {!! Form::radio('gender', 'M', true) !!}&nbsp;&nbsp;Male &nbsp;&nbsp;
                                        {!! Form::radio('gender', 'F', false) !!}&nbsp;&nbsp;Female
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('address', trans('agents/general.columns.address'), ['class' => 'control-label col-sm-3'] ) !!}
                                <div class="col-sm-9">
                                    {!! Form::text('address', null, ['class' => 'form-control', 'tabindex' => 9]) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('state', trans('customers/general.columns.state'), ['class' => 'control-label col-sm-3'] ) !!}
                                <div class="col-sm-9">
                                    <div class="input-group select2-bootstrap-append">
                                        {!! Form::select('state', array_keys(config('cities')), null, ['class' => 'form-control', 'id' => 'state',  'style' => "width: 100%", 'tabindex' => 10]) !!}
                                        <span class="input-group-addon">
                                            <span class="fa fa-fw fa-globe"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('city', trans('customers/general.columns.city'), ['class' => 'control-label col-sm-3'] ) !!}
                                <div class="col-sm-9">
                                    <div class="input-group select2-bootstrap-append">
                                        {!! Form::select('city', [], null, ['class' => 'form-control',  'style' => "width: 100%", 'tabindex' => 11]) !!}
                                        <span class="input-group-addon">
                                            <span class="fa fa-fw fa-map-marker"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('zipcode', trans('customers/general.columns.zipcode'), ['class' => 'control-label col-sm-3'] ) !!}
                                <div class="col-sm-9">
                                    {!! Form::text('zipcode', null, ['class' => 'form-control', 'tabindex' => 12]) !!}
                                </div>
                            </div>

                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div><!-- /.col -->
                <div class='col-md-12 col-lg-6'>
                    <!-- Box -->
                    <div class="box box-default">
                        <div class="box-header with-border">
                            <h3 class="box-title">{{ trans('agents/general.page.create.agent-info') }}</h3>
                            <div class="box-tools pull-right">

                            </div>
                        </div>
                        <div class="box-body">

                            <div class="form-group">
                                {!! Form::label('NPWP', trans('agents/general.columns.NPWP'), ['class' => 'control-label col-sm-3'] ) !!}
                                <div class="col-sm-9">
                                    {!! Form::text('NPWP', null, ['class' => 'form-control', 'tabindex' => 13]) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('bank', trans('agents/general.columns.bank'), ['class' => 'control-label col-sm-3'] ) !!}
                                <div class="col-sm-9">
                                    {!! Form::text('bank', null, ['class' => 'form-control', 'tabindex' => 14]) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('bank_branch', trans('agents/general.columns.bank_branch'), ['class' => 'control-label col-sm-3'] ) !!}
                                <div class="col-sm-9">
                                    {!! Form::text('bank_branch', null, ['class' => 'form-control', 'tabindex' => 15]) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('account_number', trans('agents/general.columns.account_number'), ['class' => 'control-label col-sm-3'] ) !!}
                                <div class="col-sm-9">
                                    {!! Form::text('account_number', null, ['class' => 'form-control', 'tabindex' => 16]) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('account_name', trans('agents/general.columns.account_name'), ['class' => 'control-label col-sm-3'] ) !!}
                                <div class="col-sm-9">
                                    {!! Form::text('account_name', null, ['class' => 'form-control', 'tabindex' => 17]) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('agent_position_id', trans('agents/general.columns.agent_position_id'), ['class' => 'control-label col-sm-3'] ) !!}
                                <div class="col-sm-9">
                                    <div class="input-group select2-bootstrap-append">
                                        {!! Form::select('agent_position_id', $agent_position_lists, null, ['class' => 'form-control', 'id' => 'agent_position_id_select',  'style' => "width: 100%", 'tabindex' => 18]) !!}
                                        <span class="input-group-addon">
                                            <span class="fa fa-fw fa-briefcase"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('parent_id', trans('agents/general.columns.parent_id'), ['class' => 'control-label col-sm-3'] ) !!}
                                <div class="col-sm-9">
                                    <div class="input-group select2-bootstrap-append">
                                        {!! Form::select('parent_id', $agent_lists, 'none', ['class' => 'form-control select2', 'id' => 'parent_id_select',  'style' => "width: 100%", 'tabindex' => 19]) !!}
                                        <span class="input-group-addon">
                                            <span class="fa fa-fw fa-briefcase"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('join_date', trans('agents/general.columns.join_date'), ['class' => 'control-label col-sm-3'] ) !!}
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        {!! Form::text('join_date', null, ['class' => 'form-control datepicker', 'data-date-format' => 'dd/mm/yyyy', 'tabindex' => 20]) !!}
                                        <span class = "input-group-addon"><span class="fa fa-fw fa-calendar"></span></span>
                                    </div>
                                </div>
                            </div>
							<div class="form-group">
								{!! Form::label('type', trans('agents/general.columns.type'), ['class' => 'control-label col-sm-3'] ) !!}
								<div class="col-sm-9">
									<div class="input-group select2-bootstrap-append">
										{!! Form::select('type', $types, null, ['class' => 'form-control', 'style' => "width: 100%", 'tabindex' => 6]) !!}
										<span class="input-group-addon">
									<span class="fa fa-fw fa-fw fa-dollar"></span>
								</span>
									</div>
								</div>
							</div>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->

                </div><!-- /.col -->

            </div><!-- /.row -->
            <div class="form-group">
                <div class="col-sm-12">
                    {!! Form::submit( trans('general.button.save'), ['class' => 'btn btn-primary btn-submit', 'id' => 'btn-submit-save', 'tabindex' => 21] ) !!}
                    <a id="resetFormAnchor" disabled onclick="resetForm($('#form_save_menu'))"  title="{{ trans('general.button.clear') }}" class='btn btn-default'><span class="fa fa-fw fa-eraser"></span> {{ trans('general.button.clear') }}</a>
                    <div class="pull-right">
                        <a id="cancelForm" title="{{ trans('general.button.cancel') }}" class='btn btn-danger' href="{!! route('agents.index') !!}"><span class="fa fa-fw fa-times"></span> {{ trans('general.button.cancel') }}</a>
                    </div>
                </div>
            </div>
        </div><!-- /.box-body -->
    </div><!-- /.box -->

    {!! Form::close() !!}
    @endsection


            <!-- Optional bottom section for modals etc... -->
    @section('body_bottom')

            <!-- Select2 4.0.0 -->
    <script src="{{ asset ("/bower_components/admin-lte/select2/js/select2.min.js") }}" type="text/javascript"></script>

    <script src="{{ asset ("/bower_components/admin-lte/plugins/datepicker/bootstrap-datepicker.js") }}" type="text/javascript"></script>
    <!-- iCheck -->
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
    {!! JsValidator::formRequest('App\Http\Requests\CreateAgentRequest', '#form_create_agent') !!}

    @endsection
