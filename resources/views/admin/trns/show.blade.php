@extends('admin/layouts/master')

@section('title', 'TRN Detail')

@section('content')

	<div class="content-wrapper">
		<div class="row">
			<div class="col-12">
		      	<h5 style="margin-bottom: 25px;">TRN Detail</h5>
			</div>
		</div>

		<div class="row">
	        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
	        	
	        	@include('admin/layouts/messages')
            	
	        	<div class="row">
	              <div class="col-md-12">
	                <div class="form-group row">
	                	<div class="col-sm-12 mb-2">
							<b>Company Type:</b> {{ $TrnDetail->trn_company_type }}
						</div>

	                	<div class="col-sm-12 mb-2">
	                		<b>Tax Register Number:</b> {{ $TrnDetail->trn_tax_register_number }}
						</div>

						<div class="col-sm-12 mb-2">
							<b>Tax Register Date:</b> {{ Carbon\Carbon::parse($TrnDetail->trn_tax_register_date)->format('F d, Y') }}
						</div>

						<div class="col-sm-12 mb-2">
							<b>Type of Tax Period:</b> {{ $TrnDetail->trn_tax_period }}
						</div>

						<div class="col-sm-12 mb-2">
							<b>First Tax Period Start:</b> {{ $TrnDetail->trn_first_tax_period_start }} {{ $TrnDetail->trn_first_tax_period_start_year }}
						</div>

						<div class="col-sm-12 mb-2">
							<b>First Tax Period End:</b> {{ $TrnDetail->trn_first_tax_period_end }} {{ $TrnDetail->trn_first_tax_period_end_year }}
						</div>

						<div class="col-sm-12 mb-2">
							<b>VAT Certificate:</b> 
							@if($TrnDetail->trn_vat_certificate)
								<a href="{{ url('public/uploads/documents/'.$TrnDetail->trn_vat_certificate)}}" target="_blank"> 
			                  			{{ $TrnDetail->trn_vat_certificate }}
			                  	</a>
		                  	@endif
						</div>

						<div class="col-sm-12 mb-2">
							<b>Trade License:</b> 
							@if($TrnDetail->trn_trade_license)
								<a href="{{ url('public/uploads/documents/'.$TrnDetail->trn_trade_license)}}" target="_blank"> 
			                  			{{ $TrnDetail->trn_trade_license }}
			                  	</a>
		                  	@endif
						</div>

	                </div>
	              </div>
	            </div>
	        		
	        </div>
	    </div>

	</div>

@endsection