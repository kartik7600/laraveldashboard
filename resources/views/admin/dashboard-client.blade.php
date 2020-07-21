@extends('admin/layouts/master')

@section('title', 'Client Dashboard')

@section('content')

<div class="content-wrapper">

	{{-- <div class="row mb-4">

		<div class="table-responsive" style="margin-bottom: 20px;">
        	<table class="table table-striped">
        		<thead>
        			<tr>
        				<th colspan="2"><h4 style="text-decoration: underline;">Basic Detail</h4></th>
        			</tr>
        		</thead>
        		<tbody>
        			<tr>
	                	<th width="8%">Client Number</th>
	                  	<td>{{ $clientDetail->client_number }}</td>

	                  	<th width="8%">Client Name</th>
	                  	<td>{{ $clientDetail->client_name }}</td>
	                </tr>

	                <tr>
	                	<th width="8%">Email</th>
	                  	<td>{{ $userData->email }}</td>

	                  	<th width="8%">Name</th>
	                  	<td>{{ $userData->user_first_name }} {{ $userData->user_last_name }}</td>
	                </tr>
	            </tbody>
        	</table>
        </div>
        <hr>

	</div> --}}

	<div class="row">
		<div class="col-12">
	      	<h5 style="text-decoration: underline;">Contract Detail</h5>
		</div>

		<div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>TRN</th>
                  <th>Amount (AED) *VAT inclusive</th>
                  <th>Start Date</th>
                  <th>End Date</th>
                  <th>Services</th>
                </tr>
              </thead>
              <tbody>
              	@forelse( $clientContracts as $clientContract )
	              	<tr>
	              		<td>
	              			@if( isset($clientContract->cid) )
	              				@php
			        			$trns = DB::table('trns AS T')
				                        ->where('T.contract_id', '=', $clientContract->cid)
				                        ->get();
			            		@endphp

			            		<ul>
				            		@forelse( $trns as $trn)
				            			<li>{{ $trn->trn_tax_register_number }}</li>
				            		@empty
				            		@endforelse
			            		</ul>
	              			@endif
	              		</td>
	              		<td>{{ $clientContract->contract_amount }}</td>
	              		<td>{{ Carbon\Carbon::parse($clientContract->contract_start_date)->format('F d, Y') }}</td>
	              		<td>{{ Carbon\Carbon::parse($clientContract->contract_end_date)->format('F d, Y') }}</td>
	              		<td>
	              			@if( isset($clientContract->cd_id) )

			        			@php
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
			            		@endphp

			            		<ul>
				            		@forelse( $clientContractServices as $clientContractService)
				            			<li>{{ $clientContractService->service_name }}</li>
				            		@empty
				            		@endforelse
			            		</ul>
			        		@endif
	              		</td>
	              	</tr>
              	@empty
              		<tr>
	              		<td colspan="5">No data found.</td>
	              	</tr>
              	@endforelse
              </tbody>
          	</table>
        </div>

	</div>

</div>

@endsection