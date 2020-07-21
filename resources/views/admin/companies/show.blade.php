@extends('admin/layouts/master')

@section('title', 'Company Detail')

@section('content')

	<div class="content-wrapper">
		<div class="row">
			<div class="col-12">
		      	<h3>Company Detail</h3>
			</div>
		</div>

		<div class="row">
	        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
	        	
	        	@include('admin/layouts/messages')

            	<div class="table-responsive" style="margin-bottom: 20px;">
	            	<table class="table table-striped">
	            		<tbody>
	            			<tr>
			                	<th width="10%">Company</th>
			                  	<td>{{ $companyDetail->company_name }}</td>

			                  	<th width="10%">Work Designation</th>
			                  	<td>{{ $companyDetail->company_work_designation }}</td>
			                </tr>

			                <tr>
			                  <th width="10%">Contact Person</th>
			                  <td>{{ $companyDetail->company_contact_person }}</td>

			                  <th width="10%">Mobile</th>
			                  <td>{{ $companyDetail->company_mobile }}</td>
			                </tr>

			                <tr>
			                  <th width="10%">Address</th>
			                  <td>
			                  	<span>{{ $companyDetail->company_address }}</span>
		                  		<span>{{ $companyDetail->company_city }}</span>
		                  		<span>{{ $companyDetail->company_state }}</span>
		                  		<span>{{ $companyDetail->company_country }}</span>
		                  		<span>{{ $companyDetail->company_zip_code }}</span>
			                  </td>

			                  <th width="10%">Phone</th>
			                  <td>{{ $companyDetail->company_phone }}</td>
			                </tr>
			            </tbody>
	            	</table>
	            </div>
	        		
	        </div>
	    </div>

	    {{-- <div class="row">
			<div class="col-12 mb-2">
		      	<h4 style="text-decoration: underline;">Tax Register Detail</h4>
			</div>

			@if( $TrnDetail->trn_company_type == 'Single Company' )
				<div class="col-md-4 mb-3">
					<b>Company Type: </b> {{ $TrnDetail->trn_company_type }}
				</div>

				<div class="col-md-4 mb-3">
					<b>Tax Register Number: </b> {{ $TrnDetail->trn_tax_register_number }}
				</div>

				<div class="col-md-4 mb-3">
					<b>Tax Register Date: </b> 
					{{ Carbon\Carbon::parse($TrnDetail->trn_tax_register_date)->format('F d, Y') }}
				</div>

				<div class="col-md-4 mb-3">
					<b>Type of Tax Period: </b> {{ $TrnDetail->trn_tax_period }}
				</div>

				<div class="col-md-4 mb-3">
					<b>First Tax Period Start: </b> {{ $TrnDetail->trn_first_tax_period_start }}
				</div>

				<div class="col-md-4 mb-3">
					<b>First Tax Period End: </b> {{ $TrnDetail->trn_first_tax_period_end }}
				</div>

				<div class="col-md-4 mb-3">
					<b>VAT certificate: </b>
					@if($TrnDetail->trn_vat_certificate)
						<a href="{{ url('public/uploads/documents/'.$TrnDetail->trn_vat_certificate)}}" target="_blank"> 
	              			{{ $TrnDetail->trn_vat_certificate }}
	              		</a>
					@else
						-
					@endif
				</div>

				<div class="col-md-4 mb-3">
					<b>Trade License: </b>
					@if($TrnDetail->trn_trade_license)
						<a href="{{ url('public/uploads/documents/'.$TrnDetail->trn_trade_license)}}" target="_blank"> 
	              			{{ $TrnDetail->trn_trade_license }}
	              		</a>
					@else
						-
					@endif
				</div>
			@else
				<div class="col-md-4 mb-3">
				<b>Company Type: </b> {{ $TrnDetail->trn_company_type }}
				</div>

				<div class="col-md-4 mb-3">
					<b>Tax Register Number: </b> {{ $companyDetail->company_tax_number }}
				</div>

				<div class="col-md-4 mb-3">
					<b>Tax Register Date: </b> 
					{{ Carbon\Carbon::parse($companyDetail->company_tax_date)->format('F d, Y') }}
				</div>

				<div class="col-md-4 mb-3">
					<b>Type of Tax Period: </b> {{ $companyDetail->company_tax_period }}
				</div>

				<div class="col-md-4 mb-3">
					<b>First Tax Period Start: </b> {{ $companyDetail->company_first_tax_period_start }}
				</div>

				<div class="col-md-4 mb-3">
					<b>First Tax Period End: </b> {{ $companyDetail->company_first_tax_period_end }}
				</div>

				<div class="col-md-4 mb-3">
					<b>VAT certificate: </b>
					@if($companyDetail->company_vat_certificate)
						<a href="{{ url('public/uploads/documents/'.$companyDetail->company_vat_certificate)}}" target="_blank"> 
	              			{{ $companyDetail->company_vat_certificate }}
	              		</a>
					@else
						-
					@endif
				</div>

				<div class="col-md-4 mb-3">
					<b>Trade License: </b>
					@if($companyDetail->company_trade_license)
						<a href="{{ url('public/uploads/documents/'.$companyDetail->company_trade_license)}}" target="_blank"> 
	              			{{ $companyDetail->company_trade_license }}
	              		</a>
					@else
						-
					@endif
				</div>
			@endif

		</div> --}}

	</div>

@endsection