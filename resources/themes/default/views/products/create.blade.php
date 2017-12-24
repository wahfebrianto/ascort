@extends('layouts.master')

@section('content')

    <div class='row'>
        <div class='col-md-12'>
            <!-- Box -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{!! trans('products/general.page.create.title') !!}</h3>
                    <div class="box-tools pull-right">
                    </div>
                </div>
                <div class="box-body">
                    {!! Form::open( ['route' => 'products.store', 'id' => 'form_create_product', 'class' => 'form-horizontal'] ) !!}
                    {!! Form::hidden('id', null, ['class' => 'form-control', 'id' => 'id']) !!}

                    <div class="form-group">
                        {!! Form::label('product_code', trans('products/general.columns.product_code'), ['class' => 'control-label col-sm-2'] ) !!}
                        <div class="col-sm-10">
                            {!! Form::text('product_code', null, ['class' => 'form-control', 'tabindex' => 1]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('product_name', trans('products/general.columns.product_name'), ['class' => 'control-label col-sm-2'] ) !!}
                        <div class="col-sm-10">
                            {!! Form::text('product_name', null, ['class' => 'form-control', 'tabindex' => 2]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            {!! Form::submit( trans('general.button.save'), ['class' => 'btn btn-primary btn-submit', 'id' => 'btn-submit-save', 'tabindex' => 3] ) !!}
                            <a id="resetFormAnchor" disabled onclick="resetForm($('#form_save_menu'))"  title="{{ trans('general.button.clear') }}" class='btn btn-default'><span class="fa fa-eraser"></span> {{ trans('general.button.clear') }}</a>
                            <div class="pull-right">
                                <a id="cancelForm" title="{{ trans('general.button.cancel') }}" class='btn btn-danger' href="{!! route('products.index') !!}"><span class="fa fa-times"></span> {{ trans('general.button.cancel') }}</a>
                            </div>
                        </div>
                    </div>

                    {!! Form::close() !!}
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->

    </div><!-- /.row -->

    @endsection

    @section('body_bottom')

    <script type="text/javascript" src="{!! asset('vendor/jsvalidation/js/jsvalidation.js') !!}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\CreateProductRequest', '#form_create_product') !!}

    @endsection


