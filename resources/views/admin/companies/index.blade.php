@extends('admin/layouts/master')

@section('title', 'Companies List')

@section('content')

	<div class="content-wrapper">
	  <div class="row">
	    <div class="col-lg-12 grid-margin stretch-card">
	      <div class="card">
	        <div class="card-body">

	        	<div class="row mb-4">
	        		<div class="col-md-4 col-sm-6">
	        			<h5>TRN :- {{ $TRNDetail->trn_tax_register_number }}</h5>
	        		</div>

	        		<div class="col-md-4 col-sm-6">
	        			<h5>Comapy Type :- {{ $TRNDetail->trn_company_type }}</h5>
	        		</div>
	        	</div>

	        	<div class="row">
	        		<div class="col-md-6">
		          		<h4 class="card-title">Companies List</h4>
		          	</div>
		          	<div class="col-md-6">
			          	<div class="text-right">
			          		@if( Gate::allows('admin-auth', Auth::user()) )
			          			@if( $TRNDetail->trn_company_type != 'Single Company' || count($CompanyDetails) < 1 )
			          				@if( $ContractDetails->contract_end_date >= date('Y-m-d') )
										<a href="{{ route('companies.create', ['id'=>$TRNDetail->tid]) }}" class="btn btn-info">Add New Company</a>
									@endif
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
	                  <th>Company</th>
	                  {{--<th>Tax Register Number</th>--}}
	                  <th>Contact Person</th>
	                  <th>Mobile</th>

	                  <th data-orderable="false"><center>Action</center></th>
	                </tr>
	              </thead>
	              <tbody>

	              	@forelse( $CompanyDetails as $CompanyDetail )
	              		<tr>
		                  <td>
		                  	{{ $CompanyDetail->company_name }}
		                  </td>

		                  {{--<td>
		                  	{{ $CompanyDetail->company_tax_number ?? $TRNDetail->trn_tax_register_number }}
		                  </td>--}}

		                  <td>
		                  	{{ $CompanyDetail->company_contact_person }}
		                  </td>
		                  <td>
		                  	{{ $CompanyDetail->company_mobile }}
		                  </td>

		                  <td><center>
		                  		@if( Gate::allows('admin-auth', Auth::user()) )
		                  			@if( $ContractDetails->contract_end_date >= date('Y-m-d') )
		                  				<a href="{{ route('companies.edit', ['id'=>$CompanyDetail->comid]) }}" title="Edit" class="btn btn-primary"><i class="menu-icon mdi mdi-table-edit"></i></a>
		                  			@endif
		                  		@endif

		                  		<a href="{{ route('companies.show', ['id'=>$CompanyDetail->comid]) }}" title="View" class="btn btn-info"><i class="menu-icon mdi mdi-magnify-plus"></i></a>
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