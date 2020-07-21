@extends('admin/layouts/master')

@section('title', 'Client Contract')

@section('content')

	<div class="content-wrapper">

	    <div class="row">             
		    <div class="col-12 grid-margin">
		      <div class="card">
		        <div class="card-body">
		        	
	          		<div class="text-right">
	          			@if( Gate::allows('admin-auth', Auth::user()) )
							<a href="{{ route('contracts.create', ['id'=>$contractClientID]) }}" class="btn btn-info">Add New Contract</a>
						@endif
						
					</div>
					
		          	<h4 class="card-title">Client Contract</h4>
		          	
		          	@include('admin/layouts/messages')

		          	<div class="table-responsive">
			            <table class="table table-striped data-table">
			              <thead>
			                <tr>
			                  <th>Service Name</th>

			                  @if( Gate::denies('manager-auth', Auth::user()) )
			                  	<th>Amout (AED) *VAT inclusive</th>
			                  @endif

			                  <th>Start Date</th>
			                  <th>End Date</th>
			                  <th><center>Action</center></th>
			                </tr>
			              </thead>
			              <tbody>

			              	@forelse( $clientContracts as $clientContract )
				                <tr>
				                  <td>
				                  	<?php
				        			$clientContractServices = DB::table('contract_services AS CS')
					                        ->select('S.service_name')
					                        ->join('contract_details AS CD', function($join)
					                            {
					                                $join->on('CS.contract_detail_id', '=', 'CD.cd_id');
					                            })
					                        ->join('services AS S', function($join)
					                            {
					                                $join->on('CS.service_id', '=', 'S.sid');
					                            })
					                        ->where('CS.contract_detail_id', '=', $clientContract->cd_id)
					                        ->orderBy('S.service_name', 'ASC')
					                        ->get();
				            		?>
				            		<ul>
					            		@forelse( $clientContractServices as $clientContractService)
					            			<li>{{ $clientContractService->service_name }}</li>
					            		@empty
					            		@endforelse
				            		</ul>
				                  </td>

				                  @if( Gate::denies('manager-auth', Auth::user()) )
				                  	<td>{{ $clientContract->contract_amount }}</td>
				                  @endif

				                  <td>{{ Carbon\Carbon::parse($clientContract->contract_start_date)->format('F d, Y') }}</td>
				                  <td>{{ Carbon\Carbon::parse($clientContract->contract_end_date)->format('F d, Y') }}</td>

				                  	
				                  <td class="custom-action"><center>
				                  	@if( Auth::user()->user_role == 'admin' )
				                  		@if( $clientContract->contract_end_date >= date('Y-m-d') )
				                  			<a href="{{ route('contracts.edit', ['id'=>$clientContract->cd_id]) }}" title="Edit" class="btn btn-primary"><i class="menu-icon mdi mdi-table-edit"></i></a>
				                  		@endif
				                  	@endif

				                  	<a href="{{ route('contracts.show', ['id'=>$clientContract->cd_id]) }}" title="Show" class="btn btn-info"><i class="menu-icon mdi mdi-magnify-plus"></i></a>

				                  	<a href="{{ route('trns.index', ['contract_id'=>$clientContract->cd_id]) }}" class="btn btn-success">TRN</a>

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