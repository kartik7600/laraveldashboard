@extends('admin/layouts/master')

@section('title', 'Add TRN')

@section('content')

	<div class="content-wrapper">
	  <div class="row">
	             
	    <div class="col-12 grid-margin">
	      <div class="card">
	        <div class="card-body">
	          	<h4 class="card-title">Add TRN</h4>

	          	@include('admin/layouts/messages')
	          
	        	{!! Form::open(array('route'=>'trns.store', 'enctype'=>'multipart/form-data')) !!}
	        		<div class="row">
		              <div class="col-md-6">
		                <div class="form-group row">
							<div class="col-sm-12">
								{!! Form::label('trn_tax_register_number', 'Tax Registration Number', array('class'=>'col-form-label')) !!}

								{!! Form::text('trn_tax_register_number', '', array('class'=>'form-control')) !!}
							</div>
		                </div>
		              </div>

		              <div class="col-md-6">
		                <div class="form-group row">
							<div class="col-sm-12">
								{!! Form::label('trn_tax_register_date', 'Effective Registration Date', array('class'=>'col-form-label')) !!}

								{!! Form::text('trn_tax_register_date', '', array('class'=>'form-control', 'id'=>'trn_tax_register_date', 'readonly'=>'readonly', 'placeholder'=>'YYYY-MM-DD')) !!}
							</div>
						</div>
					  </div>

		              <div class="col-md-6">
		                <div class="form-group row">
							{!! Form::label('trn_tax_period', 'Type of Tax Period', array('class'=>'col-sm-12 col-form-label')) !!}

							<div class="col-sm-3">
								<div class="form-radio">
	                              <label class="form-check-label">
	                                {!! Form::radio('trn_tax_period', 'Monthly', false, array('class'=>'form-check-input')) !!} Monthly
	                              	<i class="input-helper"></i>
	                              </label>
	                            </div>
							</div>

							<div class="col-sm-3">
								<div class="form-radio">
	                              <label class="form-check-label">
	                                {!! Form::radio('trn_tax_period', 'Quarterly', false, array('class'=>'form-check-input')) !!} Quarterly
	                              	<i class="input-helper"></i>
	                              </label>
	                            </div>
							</div>
		                </div>
		              </div>

		              <div class="col-md-6">
		                <div class="form-group row">
							{!! Form::label('trn_company_type', 'Company Type', array('class'=>'col-sm-12 col-form-label')) !!}

							<div class="col-sm-4">
								<div class="form-radio">
	                              <label class="form-check-label">
	                                {!! Form::radio('trn_company_type', 'Single Company', false, array('class'=>'form-check-input')) !!} Single Company
	                              	<i class="input-helper"></i>
	                              </label>
	                            </div>
							</div>

							<div class="col-sm-4">
								<div class="form-radio">
	                              <label class="form-check-label">
	                                {!! Form::radio('trn_company_type', 'Tax Group', false, array('class'=>'form-check-input')) !!} Tax Group
	                              	<i class="input-helper"></i>
	                              </label>
	                            </div>
							</div>
		                </div>
		              </div>

		              <div class="col-md-3">
		                <div class="form-group row">
							<div class="col-sm-12">
								{!! Form::label('trn_first_tax_period_start', 'First Tax Period Start', array('class'=>'col-form-label')) !!}

								<?php
									$firstTaxPeriodStart = [
														''=>'-- Select Start Date --',
														'1 Jan' => '1 Jan',
														'1 Feb' => '1 Feb',
														'1 Mar' => '1 Mar',
														'1 Apr' => '1 Apr',
														'1 May' => '1 May',
														'1 Jun' => '1 Jun',
														'1 Jul' => '1 Jul',
														'1 Aug' => '1 Aug',
														'1 Sep' => '1 Sep',
														'1 Oct' => '1 Oct',
														'1 Nov' => '1 Nov',
														'1 Dec' => '1 Dec'
													];
								?>
								{!! Form::select('trn_first_tax_period_start',  $firstTaxPeriodStart, '', array('class'=>'form-control', 'id'=>'trn_first_tax_period_start')) !!}
							</div>
						</div>
					  </div>

					  <div class="col-md-3">
		                <div class="form-group row">
							<div class="col-sm-12">
								{!! Form::label('trn_first_tax_period_start_year', 'First Tax Period Start Year', array('class'=>'col-form-label')) !!}

								<?php
									$firstTaxPeriodStartYear = array(''=>'-- Year --');
								?>
								@for( $y=date('Y'); $y>=2011; $y-- )
									<?php
										$firstTaxPeriodStartYear[$y] = $y;
									?>
								@endfor
								
								{!! Form::select('trn_first_tax_period_start_year',  $firstTaxPeriodStartYear, '', array('class'=>'form-control', 'id'=>'trn_first_tax_period_start_year')) !!}

								{{-- <select class="form-control" id="trn_first_tax_period_start_year" name="trn_first_tax_period_start_year">
									<option value="">-- Year --</option>

									@for( $y=date('Y'); $y>=2011; $y-- )
										<option value="{{ $y }}">{{ $y }}</option>
									@endfor
									
								</select> --}}
							</div>
						</div>
					  </div>

					  <div class="col-md-3">
		                <div class="form-group row">
							<div class="col-sm-12">
								{!! Form::label('trn_first_tax_period_end', 'First Tax Period End', array('class'=>'col-form-label')) !!}

								<?php
									$firstTaxPeriodEnd = [
														''=>'-- Select End Date --',
														'31 Jan' => '31 Jan',
														'28 Feb' => '28 Feb',
														'31 Mar' => '31 Mar',
														'30 Apr' => '30 Apr',
														'31 May' => '31 May',
														'30 Jun' => '30 Jun',
														'31 Jul' => '31 Jul',
														'31 Aug' => '31 Aug',
														'30 Sep' => '30 Sep',
														'31 Oct' => '31 Oct',
														'30 Nov' => '30 Nov',
														'31 Dec' => '31 Dec'
													];
								?>
								{!! Form::select('trn_first_tax_period_end',  $firstTaxPeriodEnd, '', array('class'=>'form-control', 'id'=>'trn_first_tax_period_end')) !!}
							</div>
						</div>
					  </div>

					  <div class="col-md-3">
		                <div class="form-group row">
							<div class="col-sm-12">
								{!! Form::label('trn_first_tax_period_end_year', 'First Tax Period End Year', array('class'=>'col-form-label')) !!}

								<?php
									$firstTaxPeriodEndYear = array(''=>'-- Year --');
								?>
								@for( $y=date('Y'); $y>=2011; $y-- )
									<?php
										$firstTaxPeriodEndYear[$y] = $y;
									?>
								@endfor
								
								{!! Form::select('trn_first_tax_period_end_year',  $firstTaxPeriodEndYear, '', array('class'=>'form-control', 'id'=>'trn_first_tax_period_end_year')) !!}

								{{-- <select class="form-control" id="trn_first_tax_period_end_year" name="trn_first_tax_period_end_year">
									<option value="">-- Year --</option>

									@for( $y=date('Y'); $y>=2011; $y-- )
										<option value="{{ $y }}">{{ $y }}</option>
									@endfor
									
								</select> --}}
							</div>
						</div>
					  </div>

		              <div class="col-md-6">
		                <div class="form-group row">
							<div class="col-sm-12">
								{!! Form::label('trn_vat_certificate', 'VAT certificate', array('class'=>'col-form-label')) !!}

								{!! Form::file('trn_vat_certificate', array('class'=>'form-control', 'id'=>'trn_vat_certificate')) !!}
							</div>
		                </div>
		              </div>

		              <div class="col-md-6">
		                <div class="form-group row">
							<div class="col-sm-12">
								{!! Form::label('trn_trade_license', 'Trade License', array('class'=>'col-form-label')) !!}

								{!! Form::file('trn_trade_license', array('class'=>'form-control', 'id'=>'trn_trade_license')) !!}
							</div>
		                </div>
		              </div>

		            </div>

		            <div class="row">
			            <div class="col-md-12">
			                <div class="form-group row">
			                	{!! Form::hidden('client_detail_id', old('client_detail_id', $contract->client_detail_id), array('class'=>'form-control', 'id'=>'client_detail_id', 'readonly')) !!}

			                	{!! Form::hidden('contract_id', old('contract_id', $contract->cid), array('class'=>'form-control', 'id'=>'contract_id', 'readonly')) !!}

								{!! Form::submit('Submit', array('class'=>'btn btn-success mr-2 mb-2', 'name'=>'btnSubmit'))  !!}
					        </div>
					    </div>
					</div>

	        	{!! Form::close() !!}

	        </div>
	      </div>
	    </div>
	  </div>
	</div>

@endsection

@section('js')
<script>
	$('#trn_tax_register_date').datepicker({
		format: 'yyyy-mm-dd',
		orientation: "auto bottom"
	});
</script>
@endsection