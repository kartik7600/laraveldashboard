@extends('admin/layouts/master')

@section('title', 'Contract Installment Detail')

@section('content')

	<div class="content-wrapper">
		<div class="row">
			<div class="col-12">
		      	<h5 style="margin-bottom: 25px;">Contract Installment Detail</h5>
			</div>
		</div>

		<div class="row">
	        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
	        	
	        	@include('admin/layouts/messages')
            	
	        	<div class="row">
	              <div class="col-md-12">
	                <div class="form-group row">
	                	<div class="col-sm-4">
	                		<label><b>Contract Amount (AED) *VAT inclusive:</b> {{ $contractDetail->contract_amount }}</label>
						</div>

						<div class="col-sm-3">
							<label><b>Start Date:</b> {{ Carbon\Carbon::parse($contractDetail->contract_start_date)->format('F d, Y') }}</label>
						</div>

						<div class="col-sm-3">
							<label><b>End Date:</b> {{ Carbon\Carbon::parse($contractDetail->contract_end_date)->format('F d, Y') }}</label>
						</div>
	                </div>
	              </div>
	            </div>
	        		
	        </div>
	    </div>

		<div class="row">             
		    <div class="col-12 grid-margin">
		      <div class="card">
		        <div class="card-body">
		          	<h4 class="card-title">Installment</h4>
		          	
		          	<div class="table-responsive">
			            <table class="table table-striped">
			              <thead>
			                <tr>
			                  <th>No.</th>
			                  <th>Amout (AED) *VAT inclusive</th>
			                  <th>Installment Date</th>
			                </tr>
			              </thead>
			              <tbody>

			              	@forelse( $contractInstallments as $Key => $contractInstallment )
			              		@if( $contractInstallment->contract_installment_amount > 0)
					                <tr>
					                  <td>{{ $Key+1 }}</td>
					                  <td>{{ $contractInstallment->contract_installment_amount }}</td>
					                  <td>{{ Carbon\Carbon::parse($contractInstallment->contract_installment_date)->format('F d, Y') }}</td>
					                </tr>
				                @endif
			                @empty
			                	<tr><td colspan="4">No data found.</td></tr>
			                @endforelse

			              </tbody>
			            </table>
			          </div>

		        </div>
		      </div>
		    </div>
		</div>

	</div>

@endsection