@extends('admin/layouts/master')

@section('title', 'Edit Company')

@section('content')

	<div class="content-wrapper">
	  <div class="row">
	             
	    <div class="col-12 grid-margin">
	      <div class="card">
	        <div class="card-body">
	          	<h4 class="card-title">Edit Company</h4>

	          	@include('admin/layouts/messages')
	          
	        	{!! Form::open(['route'=>['companies.update', $companyDetail->comid], 'enctype'=>'multipart/form-data']) !!}
	        		{!! Form::hidden('_method', 'PUT') !!}

		            <div class="row">
		              <div class="col-md-12">
		                <div class="form-group row">
		                	{!! Form::label('company_name', 'Comapny', array('class'=>'col-sm-3 col-form-label')) !!}
							<div class="col-sm-9">
								{!! Form::text('company_name', old('company_name', $companyDetail->company_name), array('class'=>'form-control')) !!}
							</div>
		                </div>
		              </div>

		              <div class="col-md-12">
		                <div class="form-group row">
		                	{!! Form::label('company_work_designation', 'Work Designation', array('class'=>'col-sm-3 col-form-label')) !!}
							<div class="col-sm-9">
								{!! Form::text('company_work_designation', old('company_work_designation', $companyDetail->company_work_designation), array('class'=>'form-control')) !!}
							</div>
		                </div>
		              </div>

		              <div class="col-md-12">
		                <div class="form-group row">
		                	{!! Form::label('company_contact_person', 'Contact Person', array('class'=>'col-sm-3 col-form-label')) !!}
							<div class="col-sm-9">
								{!! Form::text('company_contact_person', old('company_contact_person', $companyDetail->company_contact_person), array('class'=>'form-control')) !!}
							</div>
		                </div>
		              </div>

		              <div class="col-md-12">
		                <div class="form-group row">
		                	{!! Form::label('company_mobile', 'Mobile No', array('class'=>'col-sm-3 col-form-label')) !!}
							<div class="col-sm-9">
								{!! Form::text('company_mobile', old('company_mobile', $companyDetail->company_mobile), array('class'=>'form-control')) !!}
							</div>
		                </div>
		              </div>

		              <div class="col-md-12">
		                <div class="form-group row">
		                	{!! Form::label('company_phone', 'Phone No', array('class'=>'col-sm-3 col-form-label')) !!}
							<div class="col-sm-9">
								{!! Form::text('company_phone', old('company_phone', $companyDetail->company_phone), array('class'=>'form-control')) !!}
							</div>
		                </div>
		              </div>

		              <div class="col-md-12">
		                <div class="form-group row">
		                	{!! Form::label('company_address', 'Address', array('class'=>'col-sm-3 col-form-label')) !!}
							<div class="col-sm-9">
								{!! Form::text('company_address', old('company_address', $companyDetail->company_address), array('class'=>'form-control')) !!}
							</div>
		                </div>
		              </div>

		              <div class="col-md-12">
		                <div class="form-group row">
		                	{!! Form::label('company_city', 'City', array('class'=>'col-sm-3 col-form-label')) !!}
							<div class="col-sm-9">
								{!! Form::text('company_city', old('company_city', $companyDetail->company_city), array('class'=>'form-control')) !!}
							</div>
		                </div>
		              </div>

		              <div class="col-md-12">
		                <div class="form-group row">
		                	{!! Form::label('company_state', 'State', array('class'=>'col-sm-3 col-form-label')) !!}
							<div class="col-sm-9">
								{!! Form::text('company_state', old('company_state', $companyDetail->company_state), array('class'=>'form-control')) !!}
							</div>
		                </div>
		              </div>

		              <div class="col-md-12">
		                <div class="form-group row">
		                	{!! Form::label('company_country', 'Country', array('class'=>'col-sm-3 col-form-label')) !!}
							<div class="col-sm-9">
								{!! Form::text('company_country', old('company_country', $companyDetail->company_country), array('class'=>'form-control')) !!}
							</div>
		                </div>
		              </div>

		              <div class="col-md-12">
		                <div class="form-group row">
		                	{!! Form::label('company_zip_code', 'Zip Code', array('class'=>'col-sm-3 col-form-label')) !!}
							<div class="col-sm-9">
								{!! Form::text('company_zip_code', old('company_zip_code', $companyDetail->company_zip_code), array('class'=>'form-control')) !!}
							</div>
		                </div>
		              </div>

		            </div>

		            {{-- Tax Register Detail --}}
		            <?php /*<h4 class="card-title" style="text-decoration: underline;">Tax Register Detail</h4>

		            @if( $TrnDetail['trn_company_type'] == 'Single Company' ) {{-- Single Company --}}
			            <div class="row">
			              
			              <div class="col-md-12">
			                <div class="form-group row">
			                	{!! Form::label('trn_company_type', 'Company Type', array('class'=>'col-sm-3 col-form-label')) !!}

								<div class="col-sm-9">
									{!! Form::text('trn_company_type', old('trn_company_type', $TrnDetail->trn_company_type), array('class'=>'form-control', 'readonly')) !!}
								</div>
			                </div>
			              </div>

			              <div class="col-md-12">
			                <div class="form-group row">
			                	{!! Form::label('trn_tax_register_number', 'Tax Registration Number', array('class'=>'col-sm-3 col-form-label')) !!}

								<div class="col-sm-9">
									{!! Form::text('trn_tax_register_number', old('trn_tax_register_number', $TrnDetail->trn_tax_register_number), array('class'=>'form-control', 'readonly', 'disabled')) !!}
								</div>
			                </div>
			              </div>

			              <div class="col-md-12">
			                <div class="form-group row">
			                	{!! Form::label('trn_tax_register_date', 'Effective Registration Date', array('class'=>'col-sm-3 col-form-label')) !!}
								<div class="col-sm-9">
									{!! Form::text('trn_tax_register_date', old('trn_tax_register_date', $TrnDetail->trn_tax_register_date), array('class'=>'form-control', 'id'=>'trn_tax_register_date', 'readonly'=>'readonly', 'disabled', 'placeholder'=>'YYYY-MM-DD')) !!}
								</div>
							</div>
						  </div>

			              <div class="col-md-12">
			                <div class="form-group row">
			                	{!! Form::label('trn_tax_period', 'Type of Tax Period', array('class'=>'col-sm-3 col-form-label')) !!}

								<div class="col-sm-9">
									{!! Form::text('trn_tax_period', old('trn_tax_period', $TrnDetail->trn_tax_period), array('class'=>'form-control', 'readonly', 'disabled')) !!}
								</div>
			                </div>
			              </div>

			              <div class="col-md-12">
			                <div class="form-group row">
			                	{!! Form::label('trn_first_tax_period_start', 'First Tax Period Start', array('class'=>'col-sm-3 col-form-label')) !!}

			                	<div class="col-sm-9">
			                		{!! Form::text('trn_first_tax_period_start', old('trn_first_tax_period_start', $TrnDetail->trn_first_tax_period_start), array('class'=>'form-control', 'readonly', 'disabled')) !!}
			                	</div>
							</div>
						  </div>

						  <div class="col-md-12">
			                <div class="form-group row">
			                	{!! Form::label('trn_first_tax_period_end', 'First Tax Period End', array('class'=>'col-sm-3 col-form-label')) !!}

								<div class="col-sm-9">
									{!! Form::text('trn_first_tax_period_end', old('trn_first_tax_period_end', $TrnDetail->trn_first_tax_period_end), array('class'=>'form-control', 'readonly', 'disabled')) !!}
								</div>
							</div>
						  </div>

			              <div class="col-md-12">
			                <div class="form-group row">
			                	{!! Form::label('trn_vat_certificate', 'VAT certificate', array('class'=>'col-sm-3 col-form-label')) !!}

								<div class="col-sm-9">
									<span class="form-control">
									@if($TrnDetail->trn_vat_certificate)
										<a href="{{ url('public/uploads/documents/'.$TrnDetail->trn_vat_certificate)}}" target="_blank"> 
				                  			{{ $TrnDetail->trn_vat_certificate }}
				                  		</a>
									@else
										-
									@endif
									</span>
								</div>
			                </div>
			              </div>

			              <div class="col-md-12">
			                <div class="form-group row">
			                	{!! Form::label('trn_trade_license', 'Trade License', array('class'=>'col-sm-3 col-form-label')) !!}

								<div class="col-sm-9">
									<span class="form-control">
									@if($TrnDetail->trn_trade_license)
										<a href="{{ url('public/uploads/documents/'.$TrnDetail->trn_trade_license)}}" target="_blank"> 
				                  			{{ $TrnDetail->trn_trade_license }}
				                  		</a>
									@else
										-
									@endif
									</span>
								</div>
			                </div>
			              </div>

			            </div>
		            @else {{-- Tax Group --}}
		            	<div class="row">
		              	
			              <div class="col-md-12">
			                <div class="form-group row">
			                	{!! Form::label('trn_company_type', 'Company Type', array('class'=>'col-sm-3 col-form-label')) !!}

								<div class="col-sm-9">
									{!! Form::text('trn_company_type', old('trn_company_type', $TrnDetail->trn_company_type), array('class'=>'form-control', 'readonly')) !!}
								</div>
			                </div>
			              </div>

			              <div class="col-md-12">
			                <div class="form-group row">
			                	{!! Form::label('company_tax_number', 'Tax Registration Number', array('class'=>'col-sm-3 col-form-label')) !!}
								<div class="col-sm-9">
									{!! Form::text('company_tax_number', old('company_tax_number', $companyDetail->company_tax_number), array('class'=>'form-control')) !!}
								</div>
			                </div>
			              </div>

			              <div class="col-md-12">
			                <div class="form-group row">
			                	{!! Form::label('company_tax_date', 'Effective Registration Date', array('class'=>'col-sm-3 col-form-label')) !!}
								<div class="col-sm-9">
									{!! Form::text('company_tax_date', old('company_tax_date', $companyDetail->company_tax_date), array('class'=>'form-control', 'id'=>'company_tax_date', 'readonly'=>'readonly', 'placeholder'=>'YYYY-MM-DD')) !!}
								</div>
							</div>
						  </div>

			              <div class="col-md-12">
			                <div class="form-group row">
			                	{!! Form::label('company_tax_period', 'Type of Tax Period', array('class'=>'col-sm-3 col-form-label')) !!}

								<div class="col-sm-9">
									<label class="col-sm-2">
										<div class="form-radio">
			                              <label class="form-check-label">
			                              	<input name="company_tax_period" type="radio" value="Monthly" id="company_tax_period" class="form-check-input" {{ isset($companyDetail->company_tax_period) && $companyDetail->company_tax_period == 'Monthly' ? 'checked' : '' }} > Monthly
			                              	<i class="input-helper"></i>
			                              </label>
			                            </div>
									</label>

									<label class="col-sm-2">
										<div class="form-radio">
			                              <label class="form-check-label">
			                              	<input name="company_tax_period" type="radio" value="Quarterly" id="company_tax_period" class="form-check-input" {{ isset($companyDetail->company_tax_period) && $companyDetail->company_tax_period == 'Quarterly' ? 'checked' : '' }} > Quarterly
			                              	<i class="input-helper"></i>
			                              </label>
			                            </div>
									</label>
								</div>
			                </div>
			              </div>

			              <div class="col-md-12">
			                <div class="form-group row">
			                	{!! Form::label('company_first_tax_period_start', 'First Tax Period Start', array('class'=>'col-sm-3 col-form-label')) !!}
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
									{!! Form::select('company_first_tax_period_start',  $firstTaxPeriodStart, $companyDetail->company_first_tax_period_start, array('class'=>'form-control', 'id'=>'company_first_tax_period_start')) !!}
								</div>
							</div>
						  </div>

						  <div class="col-md-12">
			                <div class="form-group row">
			                	{!! Form::label('company_first_tax_period_end', 'First Tax Period End', array('class'=>'col-sm-3 col-form-label')) !!}
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
									{!! Form::select('company_first_tax_period_end',  $firstTaxPeriodEnd, $companyDetail->company_first_tax_period_end, array('class'=>'form-control', 'id'=>'company_first_tax_period_end')) !!}
								</div>
							</div>
						  </div>

			              <div class="col-md-12">
			                <div class="form-group row">
			                	{!! Form::label('company_vat_certificate', 'VAT certificate', array('class'=>'col-sm-3 col-form-label')) !!}
								<div class="col-sm-9">
									{!! Form::file('company_vat_certificate', array('class'=>'form-control')) !!}

									@if($companyDetail->company_vat_certificate)
									<span>
										<a href="{{ url('public/uploads/documents/'.$companyDetail->company_vat_certificate)}}" target="_blank"> 
				                  			{{ $companyDetail->company_vat_certificate }}
				                  		</a>
									</span>
									@endif
								</div>
			                </div>
			              </div>

			              <div class="col-md-12">
			                <div class="form-group row">
			                	{!! Form::label('company_trade_license', 'Trade License', array('class'=>'col-sm-3 col-form-label')) !!}
								<div class="col-sm-9">
									{!! Form::file('company_trade_license', array('class'=>'form-control')) !!}

									@if($companyDetail->company_trade_license)
									<span>
										<a href="{{ url('public/uploads/documents/'.$companyDetail->company_trade_license)}}" target="_blank"> 
				                  			{{ $companyDetail->company_trade_license }}
				                  		</a>
									</span>
									@endif
								</div>
			                </div>
			              </div>

			            </div>
		            @endif */?>
		            {{-- Tax Register Detail --}}

					{!! Form::submit('Submit', array('class'=>'btn btn-success mr-2'))  !!}
		            <a href="{{ route('companies.index', ['id'=>$TrnDetail['tid']]) }}" class="btn btn-light">Cancel</a>

	        	{!! Form::close() !!}

	        </div>
	      </div>
	    </div>
	  </div>
	</div>

@endsection

@section('js')
<script>
	$('#user_dob').datepicker({
		format: 'yyyy-mm-dd',
	});

	$('#client_tax_register_date').datepicker({
		format: 'yyyy-mm-dd',
	});

	$('#client_payment_date').datepicker({
		format: 'yyyy-mm-dd',
	});

	$('#company_tax_date').datepicker({
		format: 'yyyy-mm-dd',
	});
</script>
@endsection