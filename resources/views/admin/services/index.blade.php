@extends('admin/layouts/master')

@section('title', 'Services List')

@section('content')

	<div class="content-wrapper">
	  <div class="row">
	    <div class="col-lg-12 grid-margin stretch-card">
	      <div class="card">
	        <div class="card-body">
	          <h4 class="card-title">Services List</h4>

	          	@include('admin/layouts/messages')

	          <div class="table-responsive">
	            <table class="table table-striped data-table">
	              <thead>
	                <tr>
	                  <th>Service Name</th>
	                  {{-- <th>Amount (AED)</th>
	                  <th>Clients</th>  --}}
	                  <th data-orderable="false"><center>Action</center></th>
	                </tr>
	              </thead>
	              <tbody>

	              	@forelse( $services as $service )
	              		<?php
	              			/*$Clients = DB::table('contract_services')
	              						->groupBy('service_id')
	              						->select(DB::raw('count(service_id) AS total_client'))
	              						->where('service_id', $service->sid)
	              						->first();*/
	              		?>
		                <tr>
		                  	<td>{{ $service->service_name }}</td>
		                  	{{-- <td>{{ $service->service_amount }}</td>
		                  	<td>
		                  		@if( isset($Clients->total_client) )
		                  			<a href="{{ route('services.show', ['id'=>$service->id]) }}" title="Show" class="btn btn-default">
		                  				{{ $Clients->total_client }}
		                  			</a>
		                  		@else
		                  			<a class="btn btn-default">0</a>
		                  		@endif
		                  	</td>  --}}
		                  	<td class="custom-action"><center>
		                  		<a href="{{ route('services.edit', ['id'=>$service->sid]) }}" title="Edit" class="btn btn-primary"><i class="menu-icon mdi mdi-table-edit"></i></a>

		                  		<a data-original-title="Delete" data-placement="top" data-toggle="modal" href="#myModal{{ $service->sid }}" class="btn btn-danger"><i class="menu-icon mdi mdi-delete"></i></a>

			                  	<!-- Delele Modal -->
	                            <div class="modal fade" id="myModal{{ $service->sid }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	                              <div class="modal-dialog">
	                                <div class="modal-content">
	                                  <div class="modal-header">
	                                    <h4 class="modal-title text-center">Delete Service</h4>
	                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
	                                  </div>
	                                  <div class="modal-body">
	                                    <p>Are you sure you want to delete this service?</p>
	                                  </div>
	                                  <div class="modal-footer">
	                                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
	                                    {!! Form::open(['route'=>['services.destroy', $service->sid], 'class'=>'delete-form']) !!}
					                  		{!! Form::hidden('_method', 'DELETE') !!}
					                  		{!! Form::button('Confirm', ['type'=>'submit', 'class'=>'btn btn-danger', 'data-toggle'=>'confirmation']) !!}
					                  	{!! Form::close() !!}
	                                  </div>
	                                </div>
	                                <!-- /.modal-content -->
	                              </div>
	                            </div>
	                            <!-- Delele Modal -->
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