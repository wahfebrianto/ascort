@extends('layouts.master')

@section('head_extra')
    @include('partials._head_extra_bsdatepicker_css')
    @include('partials._head_extra_select2_css')
    @include('partials._head_extra_cities_add')
@endsection

@section('content')
    {!! Form::open( ['route' => 'customers.store', 'id' => 'form_create_customer', 'files' => true, 'class' => 'form-horizontal'] ) !!}
    {!! Form::hidden('id', null, ['class' => 'form-control', 'id' => 'id']) !!}
    {!! Form::hidden('branch_office_id', 1, ['class' => 'form-control', 'id' => 'id']) !!}
    <div class="box box-primary fa-bg fa-bg-briefcase">
      <div class="box-header with-border">
          <i class="fa fa-user"></i>
          <h3 class="box-title">{{ trans('customers/general.page.create.description') }}</h3>
          <div class="box-tools pull-right">
          </div>
      </div>
      <div class="box-body">
        <div class='row'>
          <div class='col-md-12 col-lg-6'>
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('customers/general.page.create.general-info') }}</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        {!! Form::label('NIK', trans('customers/general.columns.NIK'), ['class' => 'control-label col-sm-2'] ) !!}
                        <div class="col-sm-10">
                            {!! Form::text('NIK', null, ['class' => 'form-control', 'tabindex' => 1]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('name', trans('customers/general.columns.name'), ['class' => 'control-label col-sm-2'] ) !!}
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
                        {!! Form::label('gender', trans('customers/general.columns.gender'), ['class' => 'control-label col-sm-2'] ) !!}
                        <div class="col-sm-10">
                            <div class="checkbox icheck">
                                {!! Form::radio('gender', 'M', true) !!}&nbsp;&nbsp;Laki-Laki &nbsp;&nbsp;
                                {!! Form::radio('gender', 'F', false) !!}&nbsp;&nbsp;Perempuan
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('address', trans('customers/general.columns.address'), ['class' => 'control-label col-sm-2'] ) !!}
                        <div class="col-sm-10">
                            {!! Form::text('address', null, ['class' => 'form-control', 'tabindex' => 6]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('state', trans('customers/general.columns.state'), ['class' => 'control-label col-sm-2'] ) !!}
                        <div class="col-sm-10">
                            <div class="input-group select2-bootstrap-append">
                                {!! Form::select('state', array_keys(config('cities')), null, ['class' => 'form-control', 'id' => 'state',  'style' => "width: 100%", 'tabindex' => 7]) !!}
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
                                {!! Form::select('city', [], null, ['class' => 'form-control',  'style' => "width: 100%", 'tabindex' => 8]) !!}
                                <span class="input-group-addon">
                                    <span class="fa fa-fw fa-map-marker"></span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('zipcode', trans('customers/general.columns.zipcode'), ['class' => 'control-label col-sm-2'] ) !!}
                        <div class="col-sm-10">
                            {!! Form::text('zipcode', null, ['class' => 'form-control', 'tabindex' => 9]) !!}
                        </div>
                    </div>

                  </div>
            </div>
          </div>
          <div class='col-md-12 col-lg-6'>
            <div class="box box-default">
              <div class="box-header with-border">
                  <h3 class="box-title">{{ trans('customers/general.page.create.correspondence-info') }}</h3>
              </div>
              <div class="box-body">
                <div class="form-group">
                    {!! Form::label('cor_address', trans('customers/general.columns.cor_address'), ['class' => 'control-label col-sm-2'] ) !!}
                    <div class="col-sm-10">
                        {!! Form::text('cor_address', null, ['class' => 'form-control', 'tabindex' => 10]) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('cor_state', trans('customers/general.columns.cor_state'), ['class' => 'control-label col-sm-2'] ) !!}
                    <div class="col-sm-10">
                        <div class="input-group select2-bootstrap-append">
                            {!! Form::select('cor_state', array_keys(config('cities')), null, ['class' => 'form-control', 'id' => 'cor_state',  'style' => "width: 100%", 'tabindex' => 11]) !!}
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
                            {!! Form::select('cor_city', [], null, ['class' => 'form-control',  'style' => "width: 100%", 'tabindex' => 12]) !!}
                            <span class="input-group-addon">
                                <span class="fa fa-fw fa-map-marker"></span>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('cor_zipcode', trans('customers/general.columns.cor_zipcode'), ['class' => 'control-label col-sm-2'] ) !!}
                    <div class="col-sm-10">
                        {!! Form::text('cor_zipcode', null, ['class' => 'form-control', 'tabindex' => 13]) !!}
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-12">
              {!! Form::submit( trans('general.button.save'), ['class' => 'btn btn-primary btn-submit', 'id' => 'btn-submit-save', 'tabindex' => 14] ) !!}
              <a id="resetFormAnchor" disabled onclick="resetForm($('#form_save_menu'))"  title="{{ trans('general.button.clear') }}" class='btn btn-default'><span class="fa fa-eraser"></span> {{ trans('general.button.clear') }}</a>
              <div class="pull-right">
                  <a id="cancelForm" title="{{ trans('general.button.cancel') }}" class='btn btn-danger' href="{!! route('customers.index') !!}"><span class="fa fa-times"></span> {{ trans('general.button.cancel') }}</a>
              </div>
          </div>
      </div>
    </div>
    {!! Form::close() !!}

    @endsection


            <!-- Optional bottom section for modals etc... -->
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
    {!! JsValidator::formRequest('App\Http\Requests\CreateCustomerRequest', '#form_create_customer') !!}

    @endsection
