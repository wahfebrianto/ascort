@extends('layouts.master')

@section('content')
    <div class='row'>
        <div class='col-md-12'>
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('sales/general.page.show.section-title') }}</h3>
                    <div class="box-tools pull-right">
                    </div>
                </div>
                <div class="box-body">

                    {!! Form::model( $sale, ['route' => ['sales.index'], 'method' => 'GET', 'id' => 'form_edit_sale', 'class' => 'form-horizontal'] ) !!}
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_basic" data-toggle="tab" aria-expanded="true">{!! trans('sales/general.tabs.basic') !!}</a></li>
                        </ul>
                        <div class="tab-content">

                            <div class="tab-pane active" id="tab_basic">
								<div class="form-group">
									{!! Form::label('branch_office_name', trans('sales/general.columns.branch_office_name'), ['class' => 'control-label col-sm-2'] ) !!}
									<div class="col-sm-10">
										<div class="input-group select2-bootstrap-append">
											{!! Form::text('branch_office_name', $branchOfficeName, ['class' => 'form-control', 'tabindex' => 2, 'disabled']) !!}
											<span class="input-group-addon">
												<span class="fa fa-fw fa-fw fa-building-o"></span>
											</span>
										</div>
									</div>
								</div>
                                <div class="form-group">
                                    {!! Form::label('AgentName', trans('sales/general.columns.AgentName'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        <div class="input-group">
                                            {!! Form::text('AgentName', null, ['class' => 'form-control', 'tabindex' => 2, 'disabled']) !!}
                                            <span class="input-group-btn">
                                                <a class="btn btn-default" href="{{ route('agents.show', ['id' => $sale->agent_id]) }}"><i class="fa fa-eye"></i> View</a>
                                            </span>
                                        </div>
                                    </div>

                                </div>

                                <div class="form-group">
                                    {!! Form::label('ProductName', trans('sales/general.columns.ProductName'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('ProductName', null, ['class' => 'form-control', 'tabindex' => 3, 'disabled']) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('number', trans('sales/general.columns.number'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('number', null, ['class' => 'form-control', 'tabindex' => 4, 'disabled']) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('CustomerName', trans('sales/general.columns.CustomerName'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        <div class="input-group">
                                            {!! Form::text('CustomerName', $customerName, ['class' => 'form-control', 'tabindex' => 5, 'disabled']) !!}
                                            <span class="input-group-btn">
                                                <a class="btn btn-default" href="{{ route('customers.show', ['id' => $sale->customer_id]) }}"><i class="fa fa-eye"></i> View</a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
								<div class="form-group">
									{!! Form::label('tenor', trans('sales/general.columns.Tenor'), ['class' => 'control-label col-sm-2'] ) !!}
									<div class="col-sm-10">
										<div class="input-group select2-bootstrap-append">
											{!! Form::select('Tenor', $Tenors, null, ['class' => 'form-control', 'id' => 'religion_select',  'style' => "width: 100%", 'tabindex' => 10, 'disabled']) !!}
											<span class="input-group-addon">
										<span class="fa fa-fw fa-fw fa-dollar"></span>
									</span>
										</div>
									</div>
								</div>
                                <div class="form-group">
                                    {!! Form::label('MGI', trans('sales/general.columns.MGI'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        <div class="input-group select2-bootstrap-append">
                                            {!! Form::select('MGI', $MGIs, null, ['class' => 'form-control', 'id' => 'religion_select',  'style' => "width: 100%", 'tabindex' => 10, 'disabled']) !!}
                                            <span class="input-group-addon">
                                        <span class="fa fa-fw fa-dollar"></span>
                                    </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('MGI_start_date', trans('sales/general.columns.MGI_start_date'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        <div class="input-group">
                                            {!! Form::text('MGI_start_date', $sale->MGI_start_date, ['class' => 'form-control datepicker', 'data-date-format' => 'dd/mm/yyyy', 'tabindex' => 4, 'disabled']) !!}
                                            <span class = "input-group-addon"><span class="fa fa-fw fa-calendar"></span></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('nominal', trans('sales/general.columns.nominal'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('nominal', $sale->formattedNominal, ['class' => 'form-control', 'tabindex' => 2, 'disabled']) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('interest', trans('sales/general.columns.interest'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('interest', null, ['class' => 'form-control', 'tabindex' => 13, 'disabled']) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('additional', trans('sales/general.columns.additional'), ['class' => 'control-label col-sm-2'] ) !!}
                                    <div class="col-sm-10">
                                        {!! Form::text('additional', $sale->formattedAdditional, ['class' => 'form-control', 'tabindex' => 2, 'disabled']) !!}
                                    </div>
                                </div>
								
								<div class="form-group">
									{!! Form::label('bank', trans('sales/general.columns.bank'), ['class' => 'control-label col-sm-2'] ) !!}
									<div class="col-sm-10">
										{!! Form::text('bank', null, ['class' => 'form-control', 'tabindex' => 1,'disabled']) !!}
									</div>
								</div>
								<div class="form-group">
									{!! Form::label('bank_branch', trans('sales/general.columns.bank_branch'), ['class' => 'control-label col-sm-2'] ) !!}
									<div class="col-sm-10">
										{!! Form::text('bank_branch', null, ['class' => 'form-control', 'tabindex' => 1,'disabled']) !!}
									</div>
								</div>
								<div class="form-group">
									{!! Form::label('account_name', trans('sales/general.columns.account_name'), ['class' => 'control-label col-sm-2'] ) !!}
									<div class="col-sm-10">
										{!! Form::text('account_name', null, ['class' => 'form-control', 'tabindex' => 1,'disabled']) !!}
									</div>
								</div>
								<div class="form-group">
									{!! Form::label('account_number', trans('sales/general.columns.account_number'), ['class' => 'control-label col-sm-2'] ) !!}
									<div class="col-sm-10">
										{!! Form::text('account_number', null, ['class' => 'form-control', 'tabindex' => 1,'disabled']) !!}
									</div>
								</div>

                            </div><!-- /.tab-pane -->


                        </div><!-- /.tab-content -->
                    </div>

                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            {!! Form::submit(trans('general.button.close'), ['class' => 'btn btn-primary btn-submit']) !!}
                            <a href="{!! route('sales.edit', $sale->id) !!}" title="{{ trans('general.button.edit') }}" class='btn btn-default'><span class="fa fa-fw fa-edit"></span> {{ trans('general.button.edit') }}</a>
                            <a href="{!! route('sales.interest', $sale->id) !!}" title="{{ trans('general.button.interest') }}" class='btn btn-success pull-right'><span class="fa fa-fw fa-print"></span> {{ trans('general.button.interest') }}</a>
                        </div>
                    </div>

                    {!! Form::close() !!}

                </div><!-- /.box-body -->
            </div><!-- /.box-body -->
        </div><!-- /.col -->

    </div><!-- /.row -->
@endsection
