@extends('admin/layouts/master')

@section('title', 'Add New Company')

@section('content')

	<div class="content-wrapper">
	  <div class="row">
	             
	    <div class="col-12 grid-margin">
	      <div class="card">
	        <div class="card-body">
	          	<h4 class="card-title">Add New Company</h4>

	          	@include('admin/layouts/messages')
	          
	        	{!! Form::open(array('route'=>'companies.store', 'enctype'=>'multipart/form-data')) !!}
	        		<div class="row">
		              <div class="col-md-6">
		                <div class="form-group row">
							<div class="col-sm-12">
								{!! Form::label('company_name', 'Company', array('class'=>'col-form-label')) !!}

								{!! Form::text('company_name', '', array('class'=>'form-control')) !!}
							</div>
		                </div>
		              </div>

		              <div class="col-md-6">
		                <div class="form-group row">
							<div class="col-sm-12">
								{!! Form::label('company_work_designation', 'Work Designation', array('class'=>'col-form-label')) !!}

								{!! Form::text('company_work_designation', '', array('class'=>'form-control', 'id'=>'company_work_designation')) !!}
							</div>
						</div>
					  </div>

		              <div class="col-md-6">
		                <div class="form-group row">
							<div class="col-sm-12">
								{!! Form::label('company_contact_person', 'Contact Person', array('class'=>'col-form-label')) !!}

								{!! Form::text('company_contact_person', '', array('class'=>'form-control', 'id'=>'company_contact_person')) !!}
							</div>
						</div>
		              </div>

		              <div class="col-md-6">
		                <div class="form-group row">
							<div class="col-sm-12">
								{!! Form::label('company_mobile', 'Mobile No', array('class'=>'col-form-label')) !!}

								{!! Form::text('company_mobile', '', array('class'=>'form-control', 'id'=>'company_mobile')) !!}
							</div>
						</div>
		              </div>

		              <div class="col-md-6">
		                <div class="form-group row">
							<div class="col-sm-12">
								{!! Form::label('company_phone', 'Phone No', array('class'=>'col-form-label')) !!}

								{!! Form::text('company_phone', '', array('class'=>'form-control', 'id'=>'company_phone')) !!}
							</div>
						</div>
		              </div>

		              <div class="col-md-6">
		                <div class="form-group row">
							<div class="col-sm-12">
								{!! Form::label('company_address', 'Address', array('class'=>'col-form-label')) !!}

								{!! Form::text('company_address', '', array('class'=>'form-control', 'id'=>'company_address')) !!}
							</div>
						</div>
		              </div>

		              <div class="col-md-6">
		                <div class="form-group row">
							<div class="col-sm-12">
								{!! Form::label('company_city', 'City', array('class'=>'col-form-label')) !!}

								{!! Form::text('company_city', '', array('class'=>'form-control', 'id'=>'company_city')) !!}
							</div>
						</div>
		              </div>

		              <div class="col-md-6">
		                <div class="form-group row">
							<div class="col-sm-12">
								{!! Form::label('company_state', 'State', array('class'=>'col-form-label')) !!}

								{!! Form::text('company_state', '', array('class'=>'form-control', 'id'=>'company_state')) !!}
							</div>
						</div>
		              </div>

		              <div class="col-md-6">
		                <div class="form-group row">
							<div class="col-sm-12">
								{!! Form::label('company_country', 'Country', array('class'=>'col-form-label')) !!}

								{!! Form::text('company_country', '', array('class'=>'form-control', 'id'=>'company_country')) !!}
							</div>
						</div>
		              </div>

		              <div class="col-md-6">
		                <div class="form-group row">
							<div class="col-sm-12">
								{!! Form::label('company_zip_code', 'Zip Code', array('class'=>'col-form-label')) !!}

								{!! Form::text('company_zip_code', '', array('class'=>'form-control', 'id'=>'company_zip_code')) !!}
							</div>
						</div>
		              </div>

		            </div>

		            {{-- Tax Register Detail --}}
		            <?php /*<h4 class="card-title" style="text-decoration: underline;">Tax Register Detail</h4>

		            @if( $TRNDetail['trn_company_type'] == 'Single Company' ) {{-- Single Company --}}
			            <div class="row">

			              <div class="col-md-4">
			                <div class="form-group row">
								<div class="col-sm-12">
									{!! Form::label('trn_company_type', 'TRN Company Type', array('class'=>'col-form-label')) !!}

									{!! Form::text('trn_company_type', old('trn_company_type', $TRNDetail['trn_company_type']), array('class'=>'form-control', 'readonly', 'disabled')) !!}
								</div>
			                </div>
			              </div>

			              <div class="col-md-4">
			                <div class="form-group row">
								<div class="col-sm-12">
									{!! Form::label('company_tax_number', 'Tax Registration Number', array('class'=>'col-form-label')) !!}

									{!! Form::text('company_tax_number', old('company_tax_number', $TRNDetail['trn_tax_register_number']), array('class'=>'form-control', 'readonly', 'disabled')) !!}
								</div>
			                </div>
			              </div>

			              <div class="col-md-4">
			                <div class="form-group row">
								<div class="col-sm-12">
									{!! Form::label('company_tax_date', 'Effective Registration Date', array('class'=>'col-form-label')) !!}

									{!! Form::text('company_tax_date', old('company_tax_number', $TRNDetail['trn_tax_register_date']), array('class'=>'form-control', 'id'=>'company_tax_date', 'readonly'=>'readonly', 'disabled',  'placeholder'=>'YYYY-MM-DD')) !!}
								</div>
							</div>
						  </div>

			              <div class="col-md-6">
			                <div class="form-group row">
								{!! Form::label('company_tax_period', 'Type of Tax Period', array('class'=>'col-sm-12 col-form-label')) !!}

								{!! Form::text('company_tax_period', old('company_tax_period', $TRNDetail['trn_tax_period']), array('class'=>'form-control', 'readonly', 'disabled')) !!}
			                </div>
			              </div>

			              <div class="col-md-3">
			                <div class="form-group row">
								<div class="col-sm-12">
									{!! Form::label('company_first_tax_period_start', 'First Tax Period Start', array('class'=>'col-form-label')) !!}

									{!! Form::text('company_first_tax_period_start', old('company_first_tax_period_start', $TRNDetail['trn_first_tax_period_start']), array('class'=>'form-control', 'readonly', 'disabled')) !!}
								</div>
							</div>
						  </div>

						  <div class="col-md-3">
			                <div class="form-group row">
								<div class="col-sm-12">
									{!! Form::label('company_first_tax_period_end', 'First Tax Period End', array('class'=>'col-form-label')) !!}

									{!! Form::text('company_first_tax_period_end', old('company_first_tax_period_end', $TRNDetail['trn_first_tax_period_end']), array('class'=>'form-control', 'readonly', 'disabled')) !!}
								</div>
							</div>
						  </div>

			              <div class="col-md-6">
			                <div class="form-group row">
								<div class="col-sm-12">
									{!! Form::label('company_vat_certificate', 'VAT certificate', array('class'=>'col-form-label')) !!}

									<span class="form-control">
									@if($TRNDetail->trn_vat_certificate)
										<a href="{{ url('public/uploads/documents/'.$TRNDetail->trn_vat_certificate)}}" target="_blank"> 
				                  			{{ $TRNDetail->trn_vat_certificate }}
				                  		</a>
									@else
										-
									@endif
									</span>
								</div>
			                </div>
			              </div>

			              <div class="col-md-6">
			                <div class="form-group row">
								<div class="col-sm-12">
									{!! Form::label('company_trade_license', 'Trade License', array('class'=>'col-form-label')) !!}

									<span class="form-control">
									@if($TRNDetail->trn_trade_license)
										<a href="{{ url('public/uploads/documents/'.$TRNDetail->trn_trade_license)}}" target="_blank"> 
				                  			{{ $TRNDetail->trn_trade_license }}
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

			              <div class="col-md-6">
			                <div class="form-group row">
								<div class="col-sm-12">
									{!! Form::label('company_tax_number', 'Tax Registration Number', array('class'=>'col-form-label')) !!}

									{!! Form::text('company_tax_number', '', array('class'=>'form-control')) !!}
								</div>
			                </div>
			              </div>

			              <div class="col-md-6">
			                <div class="form-group row">
								<div class="col-sm-12">
									{!! Form::label('company_tax_date', 'Effective Registration Date', array('class'=>'col-form-label')) !!}

									{!! Form::text('company_tax_date', '', array('class'=>'form-control', 'id'=>'company_tax_date', 'readonly'=>'readonly', 'placeholder'=>'YYYY-MM-DD')) !!}
								</div>
							</div>
						  </div>

			              <div class="col-md-6">
			                <div class="form-group row">
								{!! Form::label('company_tax_period', 'Type of Tax Period', array('class'=>'col-sm-12 col-form-label')) !!}

								<div class="col-sm-3">
									<div class="form-radio">
		                              <label class="form-check-label">
		                                {!! Form::radio('company_tax_period', 'Monthly', false, array('class'=>'form-check-input')) !!} Monthly
		                              	<i class="input-helper"></i>
		                              </label>
		                            </div>
								</div>

								<div class="col-sm-3">
									<div class="form-radio">
		                              <label class="form-check-label">
		                                {!! Form::radio('company_tax_period', 'Quarterly', false, array('class'=>'form-check-input')) !!} Quarterly
		                              	<i class="input-helper"></i>
		                              </label>
		                            </div>
								</div>
			                </div>
			              </div>

			              <div class="col-md-3">
			                <div class="form-group row">
								<div class="col-sm-12">
									{!! Form::label('company_first_tax_period_start', 'First Tax Period Start', array('class'=>'col-form-label')) !!}

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
									{!! Form::select('company_first_tax_period_start',  $firstTaxPeriodStart, '', array('class'=>'form-control', 'id'=>'company_first_tax_period_start')) !!}
								</div>
							</div>
						  </div>

						  <div class="col-md-3">
			                <div class="form-group row">
								<div class="col-sm-12">
									{!! Form::label('company_first_tax_period_end', 'First Tax Period End', array('class'=>'col-form-label')) !!}

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
									{!! Form::select('company_first_tax_period_end',  $firstTaxPeriodEnd, '', array('class'=>'form-control', 'id'=>'company_first_tax_period_end')) !!}
								</div>
							</div>
						  </div>

			              <div class="col-md-6">
			                <div class="form-group row">
								<div class="col-sm-12">
									{!! Form::label('company_vat_certificate', 'VAT certificate', array('class'=>'col-form-label')) !!}

									{!! Form::file('company_vat_certificate', array('class'=>'form-control')) !!}
								</div>
			                </div>
			              </div>

			              <div class="col-md-6">
			                <div class="form-group row">
								<div class="col-sm-12">
									{!! Form::label('company_trade_license', 'Trade License', array('class'=>'col-form-label')) !!}

									{!! Form::file('company_trade_license', array('class'=>'form-control')) !!}
								</div>
			                </div>
			              </div>

			            </div>
		            @endif*/ ?>
		            {{-- Tax Register Detail --}}

		            <div class="row">
			            <div class="col-md-12">
			                <div class="form-group row">
			                	{!! Form::hidden('trn_id', old('trn_id', $TRNDetail['tid']), array('class'=>'form-control', 'id'=>'trn_id', 'readonly')) !!}

			                	{!! Form::hidden('trn_company_type', old('trn_company_type', $TRNDetail['trn_company_type']), array('class'=>'form-control', 'id'=>'trn_company_type', 'readonly')) !!}

								{!! Form::submit('Submit', array('class'=>'btn btn-success mr-2 mb-2', 'name'=>'btnSubmit'))  !!}
					            <a href="{{ route('companies.index', ['id'=>$TRNDetail['tid']]) }}" class="btn btn-light mb-2">Cancel</a>
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
	$('#company_tax_date').datepicker({
		format: 'yyyy-mm-dd',
	});
</script>
@endsection