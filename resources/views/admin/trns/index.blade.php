@extends('admin/layouts/master')

@section('title', 'TRN\'s List')

@section('content')

	<div class="content-wrapper">
	  <div class="row">
	    <div class="col-lg-12 grid-margin stretch-card">
	      <div class="card">
	        <div class="card-body">

	        	<div class="row mb-4">
	        		<div class="col-md-4 col-sm-6">
	        			<h5>Client Number :- {{ $clientDetail->client_number }}</h5>
	        		</div>
	        		<div class="col-md-4 col-sm-6">
	        			<h5>Client Name :- {{ $clientDetail->client_name }}</h5>
	        		</div>
	        		<div class="col-md-4 col-sm-6">
	        			<h5>Name :- {{ $clientUser->user_first_name }} {{ $clientUser->user_last_name }}</h5>
	        		</div>
	        	</div>

	        	<div class="row mb-4">
	        		<div class="col-md-12 mb-3">
	        			<h4 style="text-decoration: underline;">Contract Detail</h4>
	        		</div>

	        		<div class="col-md-4 col-sm-6 mb-3">
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

	        		@if( Gate::denies('manager-auth', Auth::user()) )
	        		<div class="col-md-4 col-sm-6 mb-3">
	        			<h5>Amount(AED) *VAT inclusive</h5>
	        			<span>{{ $contractDetail->contract_amount }}</span>
	        		</div>
	        		@endif

	        		<div class="col-md-2 col-sm-6 mb-3">
	        			<h5>Start Date</h5>
        				<span>
        					{{ Carbon\Carbon::parse($contractDetail->contract_start_date)->format('F d, Y') }}
        				</span>
	        		</div>

	        		<div class="col-md-2 col-sm-6 mb-3">
	        			<h5>End Date</h5>
        				<span>
        					{{ Carbon\Carbon::parse($contractDetail->contract_end_date)->format('F d, Y') }}
        				</span>
	        		</div>

	        	</div>

	        	<div class="row">
	        		<div class="col-md-6">
	          			<h4 style="text-decoration: underline;">TRN List</h4>
	          		</div>
	          		<div class="col-md-6">
		          		<div class="text-right">
		          			@if( Gate::allows('admin-auth', Auth::user()) )
		          				@if( $contractDetail->contract_end_date >= date('Y-m-d') )
			          				<a href="{{ route('contracts.edit', ['id'=>$contractDetail->cd_id]) }}" class="btn btn-primary" title="Edit Contract">Edit Contract</a>

									<a href="{{ route('trns.create', ['id'=>$contractDetail->contract_id]) }}" class="btn btn-info" title="Add New TRN">Add New TRN</a>
								@endif
							@endif
						</div>
					</div>
				</div>

	          	@include('admin/layouts/messages')

	          <div class="table-responsive mt-4">
	            <table class="table table-striped data-table">
	              <thead>
	                <tr>
	                  <th>Tax Register Number</th>
	                  <th>Tax Register Date</th>
	                  <th>Type of Tax Period</th>
	                  <th>Company Type</th>

	                  <th data-orderable="false"><center>Action</center></th>
	                </tr>
	              </thead>
	              <tbody>

	              	@forelse( $TrnDatas as $TrnData )
	              		<tr>
		                  <td>
		                  	{{ $TrnData->trn_tax_register_number }}
		                  </td>
		                  <td>
		                  	{{ Carbon\Carbon::parse($TrnData->trn_tax_register_date)->format('F d, Y') }}
		                  </td>
		                  <td>
		                  	{{ $TrnData->trn_tax_period }}
		                  </td>

		                  <td>
		                  	{{ $TrnData->trn_company_type }}
		                  </td>

		                  <td><center>
		                  		@if( Gate::allows('admin-auth', Auth::user()) )
		                  			@if( $contractDetail->contract_end_date >= date('Y-m-d') )
		                  				<a href="{{ route('trns.edit', ['id'=>$TrnData->tid]) }}" title="Edit" class="btn btn-primary"><i class="menu-icon mdi mdi-table-edit"></i></a>
		                  			@endif
		                  		@endif

		                  		<a href="{{ route('trns.show', ['id'=>$TrnData->tid]) }}" title="TRN View" class="btn btn-info"><i class="menu-icon mdi mdi-magnify-plus"></i></a>

		                  		<a href="{{ route('companies.index', ['id'=>$TrnData->tid]) }}" title="Companies" class="btn btn-success">Company</a>

		                  		<a href="{{ route('documents.index', ['id'=>$TrnData->tid]) }}" title="Documents" class="btn btn-primary">Documents</a>

		                  		@if( Gate::allows('admin-auth', Auth::user()) )
            						<a href="{{ route('vat-report-submitted-file.index', ['id'=>$TrnData->tid]) }}" class="btn btn-light">VAT Reports</a>
            					@endif

            					@if( Gate::allows('manager-auth', Auth::user()) )
            						<a href="{{ route('vat-report-submitted.create', ['id'=>$TrnData->tid]) }}" class="btn btn-light">VAT Report Submit</a>
            					@endif
		                  </center></td>
		                </tr>
		                
	                @empty
	                	<tr><td colspan="5">No data found.</td></tr>
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