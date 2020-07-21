@extends('admin/layouts/master')

@section('title', 'Clients List')

@section('content')

	<div class="content-wrapper">
	  <div class="row">
	    <div class="col-lg-12 grid-margin stretch-card">
	      <div class="card">
	        <div class="card-body">
	          <h4 class="card-title">Clients List</h4>

	          	@include('admin/layouts/messages')

	          	<div class="row">
		          	<div class="col-lg-12 grid-margin stretch-card">
		          		<div class="card">
	        				<div class="card-body">
			          			<h4 class="card-title">Search By</h4>

				          		{!! Form::open(array('route'=>'clients.index', 'method'=>'GET')) !!}
				          		<?php
			          				$search_TaxPeriod = '';
			          				if( isset($_GET['search_tax_period']) )
			          				{
			          					$search_TaxPeriod = $_GET['search_tax_period'];
			          				}

			          				$search_ManagerId = '';
			          				if( isset($_GET['search_manager_id']) )
			          				{
			          					$search_ManagerId = $_GET['search_manager_id'];
			          				}

			          				$search_ServiceId = '';
			          				if( isset($_GET['search_service_id']) )
			          				{
			          					$search_ServiceId = $_GET['search_service_id'];
			          				}

			          				$search_StartDate = '';
			          				if( isset($_GET['search_start_date']) )
			          				{
			          					$search_StartDate = $_GET['search_start_date'];
			          				}

			          				$search_EndDate = '';
			          				if( isset($_GET['search_end_date']) )
			          				{
			          					$search_EndDate = $_GET['search_end_date'];
			          				}

			          				$search_City = '';
			          				if( isset($_GET['search_city']) )
			          				{
			          					$search_City = $_GET['search_city'];
			          				}
				          		?>
				          			<div class="row">
						              <div class="col-md-12">
						                <div class="form-group row">
						                	<div class="col-sm-4 mb-2">
						                		<?php
												$searchTaxPeriod = [
														''=>'-- Select Tax Period --',
														'Monthly' => 'Monthly',
														'Quarterly' => 'Quarterly'
													];
												?>

												{!! Form::label('seach_tax_period', 'Tax Period', array('class'=>'col-form-label')) !!}

												{!! Form::select('search_tax_period', $searchTaxPeriod , $search_TaxPeriod, array('class'=>'form-control')) !!}
											</div>

											@if( Auth::user()->user_role == 'admin' )
											<div class="col-sm-4 mb-2">
												{!! Form::label('seach_manager_id', 'Manager', array('class'=>'col-form-label')) !!}

												{!! Form::select('search_manager_id', [''=>'-- Select Manager --'] + $managers->toArray(), $search_ManagerId, array('class'=>'form-control')) !!}
											</div>
											@endif

											<div class="col-sm-4 mb-2">
												{!! Form::label('seach_service_id', 'Service', array('class'=>'col-form-label')) !!}

												{!! Form::select('search_service_id', [''=>'-- Select Service --'] + $services->toArray(), $search_ServiceId, array('class'=>'form-control')) !!}
											</div>

											@if( Auth::user()->user_role == 'account_manager' )
												<div class="col-sm-4 mb-2"></div>
											@endif
											<div class="col-sm-4 mb-2">
												{!! Form::label('search_start_date', 'Start Date', array('class'=>'col-form-label')) !!}

												{!! Form::text('search_start_date', $search_StartDate, array('class'=>'form-control', 'id'=>'search_start_date', 'readonly'=>'readonly', 'placeholder'=>'YYYY-MM-DD')) !!}
											</div>

											<div class="col-sm-4 mb-2">
												{!! Form::label('search_end_date', 'End Date', array('class'=>'col-form-label')) !!}

												{!! Form::text('search_end_date', $search_EndDate, array('class'=>'form-control', 'id'=>'search_end_date', 'readonly'=>'readonly', 'placeholder'=>'YYYY-MM-DD')) !!}
											</div>

											<div class="col-sm-4 mb-2">
												{!! Form::label('search_city', 'City', array('class'=>'col-form-label')) !!}

												{!! Form::text('search_city', $search_City, array('class'=>'form-control', 'id'=>'search_city')) !!}
											</div>

											<div class="col-sm-12 text-right">
												{!! Form::submit('Search', array('class'=>'btn btn-info'))  !!}
												<a href="{{ route('clients.index') }}" class="btn btn-primary">All</a>
											</div>
						                </div>
						              </div>
						          	</div>
				          		{!! Form::close() !!}
			          		</div>
			          	</div>
		          	</div>
	          	</div>

	          <div class="table-responsive">
	            <table class="table table-striped data-table">
	              <thead>
	                <tr>
	                  <th data-orderable="false">Logo</th>
	                  <th>Client Name</th>
	                  <th>Client Number</th>
	                  <th>Name</th>

	                  @if( Auth::user()->user_role == 'admin' )
	                  	<th>Manager</th>
	                  @endif

	                  <th>Status</th>
	                  <th data-orderable="false"><center>Action</center></th>
	                </tr>
	              </thead>
	              <tbody>

	              	@forelse( $clientUsers as $clientUser )
	              	
		                <?php
		                // manager detail
	                	$managerDetail = DB::table('users AS U')
				        					->join('client_details AS CD', 'U.uid', '=', 'CD.manager_id')
				    						->where([
					                                ['user_id', '=', $clientUser->uid],
					                               ])
				                            ->first();
	                    ?>
	              	
		                <tr>
		                  <td>
		                  	@if( !empty($clientUser->user_profile_image) )
		                  		{!! Html::image('public/uploads/profile_images/'.$clientUser->user_profile_image, 'Profile Image') !!}
		                  	@else
		                  		{!! Html::image('public/uploads/default-profile-image.png', 'Profile Image') !!}
		                  	@endif
		                  </td>
		                  <td>
		                  	{{ $clientUser->client_name ?? '-' }}
		                  </td>
		                  <td>
		                  	{{ $clientUser->client_number }}
		                  </td>
		                  <td>
		                  		{{ $clientUser->user_first_name }} {{ $clientUser->user_last_name }}
		                  </td>
		                  {{-- <td>
		                  		<ul>
			                      @forelse($clientContractServices as $clientContractService)
			                      	@if( isset($clientContractService->service_name) )
				                      	<li>
				                      		{{ $clientContractService->service_name }}
				                      	</li>
			                      	@endif
			                      @empty
			                      @endforelse
			                    </ul>
		                  </td> --}}

		                  @if( Auth::user()->user_role == 'admin' )
			                  {{-- <td>
		                  		@if( isset($clientContracts->contract_amount) )
		                  			{{ $clientContracts->contract_amount }}
		                  		@endif
			                  </td> --}}

			                  <td>
			                  	@if( isset($managerDetail->user_first_name) )
			                  		{{ $managerDetail->user_first_name }}
			                  	@endif
			                  </td>
		                  @endif

		                  <td>
		                  	{{ $clientUser->user_status }}
		                  </td>

		                  <td class="custom-action"><center>
		                  	@if( Gate::allows('admin-auth', Auth::user()) )
		                  		<a href="{{ route('clients.edit', ['id'=>$clientUser->uid]) }}" title="Edit" class="btn btn-primary"><i class="menu-icon mdi mdi-table-edit"></i></a>
		                  	@endif

		                  	<a href="{{ route('clients.show', ['id'=>$clientUser->uid]) }}" title="Show" class="btn btn-info"><i class="menu-icon mdi mdi-magnify-plus"></i></a>

		                  	<a href="{{ route('contracts.index', ['client_detail_id'=>$clientUser->cdid]) }}" title="Contract" class="btn btn-success">Contract</a>

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

@section('js')
<script>
	$('#search_start_date').datepicker({
		format: 'yyyy-mm-dd',
		orientation: "auto bottom"
	});

	$('#search_end_date').datepicker({
		format: 'yyyy-mm-dd',
		orientation: "auto bottom"
	});
</script>
@endsection