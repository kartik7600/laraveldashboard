@extends('admin/layouts/master')

@section('title', 'Edit TRN')

@section('content')

	<div class="content-wrapper">
	  <div class="row">
	             
	    <div class="col-12 grid-margin">
	      <div class="card">
	        <div class="card-body">
	          	<h4 class="card-title">Edit TRN</h4>

	          	@include('admin/layouts/messages')
	          
	        	{!! Form::open(['route'=>['trns.update', $TrnDetail->tid], 'enctype'=>'multipart/form-data']) !!}
	        		{!! Form::hidden('_method', 'PUT') !!}
	        		
	        		<div class="row">
		              
		              <div class="col-md-12">
		                <div class="form-group row">
		                	{!! Form::label('trn_tax_register_number', 'Tax Registration Number', array('class'=>'col-sm-3 col-form-label')) !!}
							<div class="col-sm-9">
								{!! Form::text('trn_tax_register_number', old('trn_tax_register_number', $TrnDetail->trn_tax_register_number), array('class'=>'form-control')) !!}
							</div>
		                </div>
		              </div>

		              <div class="col-md-12">
		                <div class="form-group row">
		                	{!! Form::label('trn_tax_register_date', 'Effective Registration Date', array('class'=>'col-sm-3 col-form-label')) !!}
							<div class="col-sm-9">
								{!! Form::text('trn_tax_register_date', old('trn_tax_register_date', $TrnDetail->trn_tax_register_date), array('class'=>'form-control', 'id'=>'trn_tax_register_date', 'readonly'=>'readonly', 'placeholder'=>'YYYY-MM-DD')) !!}
							</div>
						</div>
					  </div>

		              <div class="col-md-12">
		                <div class="form-group row">
		                	{!! Form::label('trn_tax_period', 'Type of Tax Period', array('class'=>'col-sm-3 col-form-label')) !!}

							<div class="col-sm-9">
								<label class="col-sm-2">
									<div class="form-radio">
		                              <label class="form-check-label">
		                              	<input name="trn_tax_period" type="radio" value="Monthly" id="trn_tax_period" class="form-check-input" {{ isset($TrnDetail->trn_tax_period) && $TrnDetail->trn_tax_period == 'Monthly' ? 'checked' : '' }} > Monthly
		                              	<i class="input-helper"></i>
		                              </label>
		                            </div>
								</label>

								<label class="col-sm-2">
									<div class="form-radio">
		                              <label class="form-check-label">
		                              	<input name="trn_tax_period" type="radio" value="Quarterly" id="trn_tax_period" class="form-check-input" {{ isset($TrnDetail->trn_tax_period) && $TrnDetail->trn_tax_period == 'Quarterly' ? 'checked' : '' }} > Quarterly
		                              	<i class="input-helper"></i>
		                              </label>
		                            </div>
								</label>
							</div>
		                </div>
		              </div>

		              <div class="col-md-12">
		                <div class="form-group row">
		                	{!! Form::label('trn_first_tax_period_start', 'First Tax Period Start', array('class'=>'col-sm-3 col-form-label')) !!}
							<div class="col-sm-9">
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
								{!! Form::select('trn_first_tax_period_start',  $firstTaxPeriodStart, $TrnDetail->trn_first_tax_period_start, array('class'=>'form-control', 'id'=>'trn_first_tax_period_start')) !!}
							</div>
						</div>
					  </div>

					  <div class="col-md-12">
		                <div class="form-group row">
		                	{!! Form::label('trn_first_tax_period_start_year', 'First Tax Period Start Year', array('class'=>'col-sm-3 col-form-label')) !!}
							<div class="col-sm-9">
								<select class="form-control" id="trn_first_tax_period_start_year" name="trn_first_tax_period_start_year">
									<option value="">-- Year --</option>

									@for( $y=date('Y'); $y>=2011; $y-- )
										<option value="{{ $y }}" @if( $TrnDetail->trn_first_tax_period_start_year == $y ) selected @endif>{{ $y }}</option>
									@endfor
									
								</select>
							</div>
						</div>
					  </div>

					  <div class="col-md-12">
		                <div class="form-group row">
		                	{!! Form::label('trn_first_tax_period_end', 'First Tax Period End', array('class'=>'col-sm-3 col-form-label')) !!}
							<div class="col-sm-9">
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
								{!! Form::select('trn_first_tax_period_end',  $firstTaxPeriodEnd, $TrnDetail->trn_first_tax_period_end, array('class'=>'form-control', 'id'=>'trn_first_tax_period_end')) !!}
							</div>
						</div>
					  </div>

					  <div class="col-md-12">
		                <div class="form-group row">
		                	{!! Form::label('trn_first_tax_period_end_year', 'First Tax Period End Year', array('class'=>'col-sm-3 col-form-label')) !!}
							<div class="col-sm-9">
								<select class="form-control" id="trn_first_tax_period_end_year" name="trn_first_tax_period_end_year">
									<option value="">-- Year --</option>

									@for( $y=date('Y'); $y>=2011; $y-- )
										<option value="{{ $y }}" @if( $TrnDetail->trn_first_tax_period_end_year == $y ) selected @endif>{{ $y }}</option>
									@endfor
									
								</select>
							</div>
						</div>
					  </div>

		              <div class="col-md-12">
		                <div class="form-group row">
		                	{!! Form::label('trn_vat_certificate', 'VAT certificate', array('class'=>'col-sm-3 col-form-label')) !!}
							<div class="col-sm-9">
								{!! Form::file('trn_vat_certificate', array('class'=>'form-control')) !!}

								@if($TrnDetail->trn_vat_certificate)
								<span>
									<a href="{{ url('public/uploads/documents/'.$TrnDetail->trn_vat_certificate)}}" target="_blank"> 
			                  			{{ $TrnDetail->trn_vat_certificate }}
			                  		</a>
								</span>
								@endif
							</div>
		                </div>
		              </div>

		              <div class="col-md-12">
		                <div class="form-group row">
		                	{!! Form::label('trn_trade_license', 'Trade License', array('class'=>'col-sm-3 col-form-label')) !!}
							<div class="col-sm-9">
								{!! Form::file('trn_trade_license', array('class'=>'form-control')) !!}

								@if($TrnDetail->trn_trade_license)
								<span>
									<a href="{{ url('public/uploads/documents/'.$TrnDetail->trn_trade_license)}}" target="_blank"> 
			                  			{{ $TrnDetail->trn_trade_license }}
			                  		</a>
								</span>
								@endif
							</div>
		                </div>
		              </div>

		              <div class="col-md-12">
		                <div class="form-group row">
		                	{!! Form::label('trn_company_type', 'Company Type', array('class'=>'col-sm-3 col-form-label')) !!}

							<div class="col-sm-9">
								<label class="col-sm-3">
									<div class="form-radio">
		                              <label class="form-check-label">
		                              	<input name="trn_company_type" type="radio" value="Single Company" id="trn_company_type" class="form-check-input" {{ isset($TrnDetail->trn_company_type) && $TrnDetail->trn_company_type == 'Single Company' ? 'checked' : '' }} > Single Company
		                              	<i class="input-helper"></i>
		                              </label>
		                            </div>
								</label>

								<label class="col-sm-3">
									<div class="form-radio">
		                              <label class="form-check-label">
		                              	<input name="trn_company_type" type="radio" value="Tax Group" id="trn_company_type" class="form-check-input" {{ isset($TrnDetail->trn_company_type) && $TrnDetail->trn_company_type == 'Tax Group' ? 'checked' : '' }} > Tax Group
		                              	<i class="input-helper"></i>
		                              </label>
		                            </div>
								</label>
							</div>
		                </div>
		              </div>

		            </div>

					{!! Form::submit('Submit', array('class'=>'btn btn-success mr-2'))  !!}

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