@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')
    @include('partials._head_extra_bsdatepicker_css')
    @include('partials._head_extra_cities_edit', ["addressState" => $customer->state, "addressCity" => $customer->city, "corAddressState" => $customer->cor_state, "corAddressCity" => $customer->cor_city, "birthCity" => ""])
@endsection

@section('content')
    <div class='row'>
        <div class='col-md-12'>
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('customers/general.page.edit.section-title') }}</h3>
                    <div class="box-tools pull-right">
                    </div>
                </div>
                <div class="box-body">

                    {!! Form::model( $customer, ['route' => ['customers.update', $customer->id], 'method' => 'PATCH', 'id' => 'form_edit_customer', 'class' => 'form-horizontal', 'files' => true] ) !!}
                    {!! Form::hidden('id', null, ['class' => 'form-control', 'id' => 'id']) !!}
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_basic" data-toggle="tab" aria-expanded="true">{!! trans('customers/general.tabs.basic') !!}</a></li>
                            <li class=""><a href="#tab_details" data-toggle="tab" aria-expanded="false">{!! trans('customers/general.tabs.details') !!}</a></li>
                        </ul>
                        <div class="tab-content">

                            <div class="tab-pane active" id="tab_basic">
								<div class="form-group">
									{!! Form::label('branch_office_id', trans('sales/general.columns.branch_office_id'), ['class' => 'control-label col-sm-2'] ) !!}
									<div class="col-sm-10">
										<div class="input-group select2-bootstrap-append">
											{!! Form::select('branch_office_id', App\BranchOffice::getBranchOffices_ForDropDown(), null, ['class' => 'form-control select2', 'style' => "width: 100%", 'tabindex' => 999]) !!}
											<span class="input-group-addon">
										<span class="fa fa-fw fa-fw fa-building-o"></span>
									</span>
										</div>
									</div>
								</div>
                                <div class="form-group">
                                    {!! Form::label('NIK', trans('customers/general.columns.NIK'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('NIK', null, ['class' => 'form-control', 'tabindex' => 1]) !!}
                                    </div>
                                </div>

								<div class="form-group">
									{!! Form::label('NPWP', trans('customers/general.columns.NPWP'), ['class' => 'control-label col-sm-2'] ) !!}
									<div class="col-sm-10">
										{!! Form::text('NPWP', null, ['class' => 'form-control', 'tabindex' => 2]) !!}
									</div>
								</div>
								
                                <div class="form-group">
                                    {!! Form::label('name', trans('customers/general.columns.name'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('name', null, ['class' => 'form-control', 'tabindex' => 3]) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('phone_number', trans('customers/general.columns.phone_number'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('phone_number', null, ['class' => 'form-control', 'tabindex' => 4]) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('handphone_number', trans('customers/general.columns.handphone_number'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('handphone_number', null, ['class' => 'form-control', 'tabindex' => 5]) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('email', trans('customers/general.columns.email'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('email', null, ['class' => 'form-control', 'tabindex' => 6]) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('gender', trans('customers/general.columns.gender'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        <div class="checkbox icheck">
                                            {!! Form::radio('gender', 'M', false) !!}&nbsp;&nbsp;Male &nbsp;&nbsp;
                                            {!! Form::radio('gender', 'F', false) !!}&nbsp;&nbsp;Female
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('address', trans('customers/general.columns.address'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('address', null, ['class' => 'form-control', 'tabindex' => 7]) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('state', trans('customers/general.columns.state'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        <div class="input-group select2-bootstrap-append">
                                            {!! Form::select('state', array_keys(config('cities')), null, ['class' => 'form-control', 'id' => 'state',  'style' => "width: 100%", 'tabindex' => 8]) !!}
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
                                            {!! Form::select('city', [], null, ['class' => 'form-control',  'style' => "width: 100%", 'tabindex' => 9]) !!}
                                            <span class="input-group-addon">
                                                <span class="fa fa-fw fa-map-marker"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('zipcode', trans('customers/general.columns.zipcode'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('zipcode', null, ['class' => 'form-control', 'tabindex' => 10]) !!}
                                    </div>
                                </div>
                            </div><!-- /.tab-pane -->

                            <div class="tab-pane" id="tab_details">
                              <div class="form-group">
                                  {!! Form::label('cor_address', trans('customers/general.columns.cor_address'), ['class' => 'control-label col-sm-2'] ) !!}
                                  <div class="col-sm-10">
                                      {!! Form::text('cor_address', null, ['class' => 'form-control', 'tabindex' => 11]) !!}
                                  </div>
                              </div>

                              <div class="form-group">
                                  {!! Form::label('cor_state', trans('customers/general.columns.cor_state'), ['class' => 'control-label col-sm-2'] ) !!}
                                  <div class="col-sm-10">
                                      <div class="input-group select2-bootstrap-append">
                                          {!! Form::select('cor_state', array_keys(config('cities')), null, ['class' => 'form-control', 'id' => 'cor_state',  'style' => "width: 100%", 'tabindex' => 12]) !!}
                                          <span class="input-group-addon">
                                              <span class="fa fa-fw fa-globe"></span>
                                          </span>
                                      </div>
                                  </div>
                              </div>

                              <div class="form-group">
                                  {!! Form::label('cor_city', trans('customers/general.columns.cor_city'), ['class' => 'control-label col-sm-2'] ) !!}
                                  <div class="col-sm-10">
                                      <div class="input-group select2-bootstrap-append">
                                          {!! Form::select('cor_city', [], null, ['class' => 'form-control',  'style' => "width: 100%", 'tabindex' => 13]) !!}
                                          <span class="input-group-addon">
                                              <span class="fa fa-fw fa-map-marker"></span>
                                          </span>
                                      </div>
                                  </div>
                              </div>

                              <div class="form-group">
                                  {!! Form::label('cor_zipcode', trans('customers/general.columns.cor_zipcode'), ['class' => 'control-label col-sm-2'] ) !!}
                                  <div class="col-sm-10">
                                      {!! Form::text('cor_zipcode', null, ['class' => 'form-control', 'tabindex' => 14]) !!}
                                  </div>
                              </div>
                            </div><!-- /.tab-pane -->

                        </div><!-- /.tab-content -->
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12">
                            {!! Form::submit( trans('general.button.save'), ['class' => 'btn btn-primary btn-submit', 'id' => 'btn-submit-save', 'tabindex' => 16] ) !!}
                            <a id="resetFormAnchor" disabled onclick="resetForm($('#form_save_menu'))"  title="{{ trans('general.button.clear') }}" class='btn btn-default'><span class="fa fa-fw fa-eraser"></span> {{ trans('general.button.clear') }}</a>
                            <div class="pull-right">
                                <a id="cancelForm" title="{{ trans('general.button.cancel') }}" class='btn btn-danger' href="{!! route('customers.index') !!}"><span class="fa fa-fw fa-times"></span> {{ trans('general.button.cancel') }}</a>
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
        });
    </script>
    <script type="text/javascript" src="{!! asset('vendor/jsvalidation/js/jsvalidation.js') !!}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\EditCustomerRequest', '#form_edit_customer') !!}
@endsection
