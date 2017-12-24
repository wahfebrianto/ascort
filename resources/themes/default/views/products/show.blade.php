@extends('layouts.master')

@section('content')
    <div class='row'>
        <div class='col-md-12'>
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('products/general.page.show.section-title') }}</h3>
                    <div class="box-tools pull-right">
                    </div>
                </div>
                <div class="box-body">

                    {!! Form::model( $product, ['route' => ['products.index'], 'method' => 'GET', 'id' => 'form_edit_product', 'class' => 'form-horizontal'] ) !!}
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_basic" data-toggle="tab" aria-expanded="true">{!! trans('products/general.tabs.basic') !!}</a></li>
                        </ul>
                        <div class="tab-content">

                            <div class="tab-pane active" id="tab_basic">
                                <div class="form-group">
                                    {!! Form::label('product_code', trans('products/general.columns.product_code'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('product_code', null, ['class' => 'form-control', 'tabindex' => 1, 'disabled']) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('product_name', trans('products/general.columns.product_name'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('product_name', null, ['class' => 'form-control', 'tabindex' => 2, 'disabled']) !!}
                                    </div>
                                </div>

                            </div><!-- /.tab-pane -->

                        </div><!-- /.tab-content -->
                    </div>

                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            {!! Form::submit(trans('general.button.close'), ['class' => 'btn btn-primary']) !!}
                            <a href="{!! route('products.edit', $product->id) !!}" title="{{ trans('general.button.edit') }}" class='btn btn-default'><span class="fa fa-edit"></span> {{ trans('general.button.edit') }}</a>
                        </div>
                    </div>

                    {!! Form::close() !!}

                </div><!-- /.box-body -->
            </div><!-- /.box-body -->
        </div><!-- /.col -->

    </div><!-- /.row -->
@endsection