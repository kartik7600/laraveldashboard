@extends('admin/layouts/master')

@section('title', 'Client Detail')

@section('content')

	<div class="content-wrapper">
		@if( Gate::denies('client-auth', Auth::user()) )
		<div class="row">
			<div class="col-12">
		      	<h3>Client Detail</h3>
			</div>
		</div>
		@endif

		<div class="row">
	        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
	        	
	        	@include('admin/layouts/messages')
            	
            	
            	<div class="table-responsive" style="margin-bottom: 20px;">
	            	<table class="table table-striped">
	            		<thead>
	            			<tr>
	            				<th colspan="2"><h4 style="text-decoration: underline;">Basic Detail</h4></th>
	            			</tr>
	            		</thead>
	            		<tbody>
	            			<tr>
			                	<th>Client Number</th>
			                  	<td>{{ $clientDetail->client_number }}</td>

			                  	<th width="10%">Name</th>
			                  	<td>{{ $userData->user_first_name }} {{ $userData->user_last_name }}</td>
			                </tr>

			                <tr>
			                  <th width="10%">Email</th>
			                  <td>{{ $userData->email }}</td>

			                  <th width="10%">Client name</th>
			                  <td>{{ $clientDetail->client_name }}</td>
			                </tr>

			                <tr>
			                  @if( Auth::user()->user_role != 'account_manager' )
		                  		<th>Manager</th>
		                  		<td>
		                  			@if( isset($managerDetail->user_first_name) )
		                  				{{ $managerDetail->user_first_name }}
		                  			@endif
		                  		</td>
		                  	  @endif
			                </tr>
			            </tbody>
	            	</table>

	            	{{--<table class="table table-striped">
	            		<thead>
	            			<tr>
	            				<th colspan="2"><h4 style="text-decoration: underline;">Company Detail</h4></th>
	            			</tr>
	            		</thead>
	            		<tbody>
	            			<tr>
			                	<th width="10%">Client name (Company name)</th>
			                  	<td>{{ $clientDetail->client_name }}</td>

			                  	<th width="10%">Comapny E-mail</th>
			                  	<td>{{ $clientDetail->client_email }}</td>
			                </tr>
			            </tbody>
	            	</table>--}}
	            </div>
	            <hr>

	            @if( isset($contractDetail->contract_amount) )
	            <div class="row mb-4">
	        		<div class="col-md-12 mb-3">
	        			<h4 style="text-decoration: underline;">Contract Detail</h4>
	        		</div>

	        		<div class="col-md-3 col-sm-6 mb-3">
	        			<h5>Services</h5>
	        			<ul>
		            		@forelse( $contractServices as $contractService)
		            			<li>
		            				{{ $contractService->service_name }}
		            			</li>
		            		@empty
		            		@endforelse
	            		</ul>
	        		</div>

	        		<div class="col-md-3 col-sm-6 mb-3">
	        			<h5>Amount(AED) *VAT inclusive</h5>
	        			<span>{{ $contractDetail->contract_amount }}</span>
	        		</div>

	        		<div class="col-md-3 col-sm-6 mb-3">
	        			<h5>Start Date</h5>
        				<span>
        					{{ Carbon\Carbon::parse($contractDetail->contract_start_date)->format('F d, Y') }}
        				</span>
	        		</div>

	        		<div class="col-md-3 col-sm-6 mb-3">
	        			<h5>End Date</h5>
        				<span>
        					{{ Carbon\Carbon::parse($contractDetail->contract_end_date)->format('F d, Y') }}
        				</span>
	        		</div>

	        	</div>
	            <hr>
	            @endif

	        	<div class="table-responsive">
	        		<div class="col-md-12 mb-3">
	        			<h4 style="text-decoration: underline;">TRN Detail</h4>
	        		</div>

	            	<table class="table table-striped">
	            		<thead>
		            		<tr>
			                	<th>Tax Register Number</th>
			                  	<th>Tax Register Date</th>
			                  	<th>Type of Tax Period</th>
			                  	<th>Company Type</th>

			                  	@if( Gate::allows('client-auth', Auth::user()) )
			                  		<th><center>Action</center></th>
			                  	@endif
			                </tr>
			            </thead>
	            		<tbody>
	            			@forelse( $trnDetails as $trnDetail )
	            				<tr>
	            					<td>{{ $trnDetail->trn_tax_register_number }}</td>
	            					<td>{{ $trnDetail->trn_tax_register_date }}</td>
	            					<td>{{ $trnDetail->trn_tax_period }}</td>
	            					<td>{{ $trnDetail->trn_company_type }}</td>

	            					@if( Gate::allows('client-auth', Auth::user()) )
		            					<td><center>
		            						<a href="{{ route('trns.show', ['id'=>$trnDetail->tid]) }}" title="TRN View" class="btn btn-info"><i class="menu-icon mdi mdi-magnify-plus"></i></a>

					                  		<a href="{{ route('companies.index', ['id'=>$trnDetail->tid]) }}" title="Companies" class="btn btn-success">Company</a>

					                  		<a href="{{ route('documents.index', ['id'=>$trnDetail->tid]) }}" title="Documents" class="btn btn-primary">Documents</a>

					                  		<a href="{{ route('vat-report-submitted-file.index', ['id'=>$trnDetail->tid]) }}" title="Vat Reports" class="btn btn-light">Vat Reports</a>
		            					</center></td>
		            				@endif
	            				</tr>
	            			@empty
	            				<tr>
	            					<td colspan="2">No data found.</td>
	            				</tr>
	            			@endforelse
	            		</tbody>
	            	</table>
	            </div>
	        		
	        </div>
	    </div>

	</div>

@endsection