@extends('admin/layouts/master')

@section('title', 'Edit Contract')

@section('content')

	<div class="content-wrapper">
	  <div class="row">
	             
	    <div class="col-12 grid-margin">
	      <div class="card">
	        <div class="card-body">
	          	<h4 class="card-title">Edit Contract</h4>

	          	@include('admin/layouts/messages')
	          
	        	{!! Form::open(['route'=>['contracts.update', $contractDetail->cd_id], 'id'=>'frmContract']) !!}
	        		{!! Form::hidden('_method', 'PUT') !!}

		            <div class="row">
		            	<div class="col-md-12">
		                <div class="form-group row">
		                	{!! Form::label('contract_amount', '
		                	Amount (AED) *VAT inclusive', array('class'=>'col-sm-3 col-form-label')) !!}
							<div class="col-sm-9">
								{!! Form::text('contract_amount', old('contract_amount', $contractDetail->contract_amount), array('class'=>'form-control', 'id'=>'contract_amount')) !!}
							</div>
		                </div>
		              </div>

		              <div class="col-md-12">
		                <div class="form-group row">
		                	{!! Form::label('contract_start_date', 'Start date', array('class'=>'col-sm-3 col-form-label')) !!}
							<div class="col-sm-9">
								{!! Form::text('contract_start_date', old('contract_start_date', $contractDetail->contract_start_date), array('class'=>'form-control', 'id'=>'contract_start_date', 'readonly'=>'readonly', 'placeholder'=>'YYYY-MM-DD')) !!}
							</div>
		                </div>
		              </div>

		              <div class="col-md-12">
		                <div class="form-group row">
		                	{!! Form::label('contract_end_date', 'End date', array('class'=>'col-sm-3 col-form-label')) !!}
							<div class="col-sm-9">
								{!! Form::text('contract_end_date', old('contract_end_date', $contractDetail->contract_end_date), array('class'=>'form-control', 'id'=>'contract_end_date', 'readonly'=>'readonly', 'placeholder'=>'YYYY-MM-DD')) !!}
							</div>
		                </div>
		              </div>

		              {{--<div class="col-md-12">
		                <div class="form-group row">
		                	{!! Form::label('contract_services_id', 'Services', array('class'=>'col-sm-3 col-form-label')) !!}
							<div class="col-sm-9">
								{!! Form::select('contract_services_id', $services, $contractServicesSeleted, array('class'=>'form-control', 'id'=>'contract_services_id', 'multiple'=>'multiple', 'name'=>'contract_services_id[]')) !!}
							</div>
		                </div>
		              </div>--}}

		              <div class="col-md-12">
		                <div class="form-group row">
		                	{!! Form::label('contract_services', 'Services', array('class'=>'col-sm-3 col-form-label')) !!}

		                	<div class="col-sm-9">
			                	<div class="row">
				                	@forelse( $services as $service )
					                	<div class="col-sm-4">
							                <label class="form-check-label">
							                	<div class="form-check">
				                            	<label class="form-check-label">
							                    	<input type="checkbox" name="contract_services_id[]" value="{{ $service->sid }}" @if(in_array($service->sid, $contractServicesSeleted)) checked @endif class="form-check-input">
							                    	{{ $service->service_name }}
							                    	<i class="input-helper"></i>
												</label>
												</div>
							                </label>
								        </div>
								    @empty
								    @endforelse
								</div>
							</div>
		                </div>
		              </div>

		              <div class="col-md-12">
		                <div class="form-group row">
		                	{!! Form::label('contract_installments', 'Contract Installmests?', array('class'=>'col-sm-3 col-form-label')) !!}
							<div class="col-sm-9">
								<div class="form-check">
		                            <label class="form-check-label">
										<input type="checkbox" name="contract_installments" id="contract_installments"{{ $contractDetail->contract_installments == 'Yes' ? 'checked' : ''}} {{ old('contract_installments') ? 'checked' : '' }} class="form-check-input"> Yes
										<i class="input-helper"></i>
									</label>
								</div>
							</div>
		                </div>
		              </div>

		            </div>

		          {{-- Installments section start --}}
	              <span id="contractInstallmentsDetail" style="display: none;">
	              	<div class="row">
		              <h4 class="card-title" style="font-size: 16px;text-decoration: underline;">Installments Detail</h4>
		              <div class="col-md-12">
		                <div class="form-group row">
		                	{!! Form::label('contract_total_installments', '
		                	How many installments?', array('class'=>'col-sm-3 col-form-label')) !!}
							<div class="col-sm-9">
								{!! Form::text('contract_total_installments', old('contract_total_installments', $contractDetail->contract_total_installments), array('class'=>'form-control', 'id'=>'contract_total_installments')) !!}
								<span role="alert" id="max-error" class="invalid-feedback"><strong>Maximum installment is 12.</strong></span>

								<span role="alert" id="amount-error" class="invalid-feedback"><strong>You can't add installments total amount less/more than contract amount.</strong></span>
							</div>
		                </div>
		              </div>
		          	</div>

		          	<span id="installmentDetail" style="display: none;">
			          	<div class="row">
			              <div class="col-md-12">
			                <div class="form-group row">
			                	{!! Form::label('', '', array('class'=>'col-sm-3 col-form-label')) !!}
								<div class="col-sm-9">
									<table class="table">
										<thead>
											<tr>
												<th>No.</th>
												<th>Amount (AED) *VAT inclusive</th>
												<th>Date</th>
											</tr>
										</thead>

										<tbody>
										<?php
											$i = 1;
											$installment_total_amount = 0;
										?>
										@forelse( $contractInstallments as $contractInstallment)
											<?php
												$installment_total_amount += $contractInstallment->contract_installment_amount;
											?>
											<tr class="installmentRow" id="installmentRow_{{ $i }}" style="display: none;">
												<td>{{ $i }}</td>
												<td>
													<input type="hidden" name="installment_id[]" class='form-control' value="{{ $contractInstallment->ciid }}">

													<input type="text" name="contract_installment_amount[]" class='form-control totalAmount' value="{{ $contractInstallment->contract_installment_amount }}" {{ old('contract_installment_amount[$i]') }}>
												</td>
												<td>
													<input type="text" name="contract_installment_date[]" class='form-control contract_installment_date' value="{{ $contractInstallment->contract_installment_date }}" readonly placeholder="YYYY-MM-DD">
												</td>
											</tr>
											<?php $i++; ?>
										@empty
										@endforelse

										@for( $i=($contractDetail->contract_total_installments+1); $i<=12; $i++ )
											<tr class="installmentRow" id="installmentRow_{{ $i }}" style="display: none;">
												<td>{{ $i }}</td>
												<td>
													<input type="text" name="contract_installment_amount[]" class='form-control totalAmount'>
												</td>
												<td>
													<input type="text" name="contract_installment_date[]" class='form-control contract_installment_date' readonly placeholder="YYYY-MM-DD">
												</td>
											</tr>
										@endfor

										<input type="hidden" name="installment_total_amount" id="installment_total_amount" value="{{ $installment_total_amount }}" class='form-control'>

										</tbody>
									</table>
								</div>
			                </div>
			              </div>
			            </div>
		        	</span>

		          </span>
	              {{-- Installments section end --}}

					{!! Form::submit('Submit', array('class'=>'btn btn-success mr-2'))  !!}
		            {{-- <a href="{{ route('contracts.index') }}" class="btn btn-light">Cancel</a> --}}

	        	{!! Form::close() !!}

	        </div>
	      </div>
	    </div>
	  </div>
	</div>

@endsection

@section('js')
<script>
	//contract installments detail start
	jQuery(document).ready(function(){
		var TotalInstallments = '{{ $contractDetail->contract_total_installments }}';

		if( $('#contract_installments').is(":checked") )
		{
			$('#contractInstallmentsDetail').show();
			$('#contract_total_installments').val(TotalInstallments);
		}

		if( TotalInstallments > 0 )
		{
			$('#installmentDetail').show();

			for( $i=1; $i<=TotalInstallments; $i++ )
			{
				$('#installmentRow_'+$i).show();
			}
		}


		$('#contract_installments').click(function(){
			if( $(this).is(":checked") )
			{
				$('#contractInstallmentsDetail').show();
				//$('#contract_total_installments').val('');
			}
			else
			{
				$('#contractInstallmentsDetail').hide();
				//$('#contract_total_installments').val('0');
			}
		});


		// installment detail
		$('#contract_total_installments').keyup(function(){
			$('#max-error').hide();
			$('#amount-error').hide();
			$('.installmentRow').hide();

			var instTotal = $(this).val();

			if( instTotal > 12)
			{
				$('#max-error').show();
				$('#installmentDetail').hide();
				return false;
			}

			if( instTotal > 0 )
			{
				$('#installmentDetail').show();

				for( $i=1; $i<=instTotal; $i++ )
				{
					$('#installmentRow_'+$i).show();
				}
			}
			else
			{
				$('#installmentDetail').hide();
			}
		});
	});

	// total amount
	$(document).on("keyup", ".totalAmount", function(){
		$('#amount-error').hide();

		var totalAmt = 0;
		$(".totalAmount").each(function(){
			totalAmt += +$(this).val();
		});

		$('#installment_total_amount').val(totalAmt);

		var contractAmt = $('#contract_amount').val();

		if( totalAmt > contractAmt)
		{
			$('#amount-error').show();
		}
	});
	//contract installments detail end

	$(document).on("submit", "#frmContract", function()
	{
		if( $('#contract_installments').is(':checked') )
		{
			var totalAmt = $('#installment_total_amount').val();
			var contractAmt = $('#contract_amount').val();

			if( totalAmt == 0 || totalAmt != contractAmt )
			{
				$('#amount-error').show();
				return false;
			}
		}
	});

	$('.contract_installment_date').datepicker({
		format: 'yyyy-mm-dd',
		//orientation: "auto bottom"
	});

	$('#contract_start_date').datepicker({
		format: 'yyyy-mm-dd',
		orientation: "auto bottom"
	});

	$('#contract_end_date').datepicker({
		format: 'yyyy-mm-dd',
		orientation: "auto bottom"
	});
</script>
@endsection