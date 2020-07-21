@extends('admin/layouts/master')

@section('title', 'Uploaded Reports')

@section('content')

	<div class="content-wrapper">
	  <div class="row">
	    <div class="col-lg-12 grid-margin stretch-card">
	      <div class="card">
	        <div class="card-body">
	          <h4 class="card-title">Uploaded Reports</h4>

	          	@include('admin/layouts/messages')

	          <div class="table-responsive">
	            <table class="table table-striped data-table">
	              <thead>
	                <tr>
	                	<th>Service</th>
	                  	<th>Report File</th>
	                  	<th>Date</th>
	                  	<th data-orderable="false"><center>Action</center></th>
	                </tr>
	              </thead>
	              <tbody>

	              	@forelse( $clientReports as $clientReport )
		                <tr>
		                	<td>{{ $clientReport->service->service_name }}</td>
		                  	<td>
		                  		<a href="{{ url('public/uploads/clients_reports/'.$clientReport->report_file)}}" target="_blank"> 
		                  			{{ $clientReport->report_file }}
		                  		</a>
		                  	</td>

		                  	<td>
		                  		{{ Carbon\Carbon::parse($clientReport->created_at)->format('F d, Y') }}
		                  	</td>
		                  	
		                  	<td class="custom-action"><center>
			                  	<a data-original-title="Delete" data-placement="top" data-toggle="modal" href="#myModal{{ $clientReport->curid }}" class="btn btn-danger"><i class="menu-icon mdi mdi-delete"></i></a>

			                  	<!-- Delele Modal -->
	                            <div class="modal fade" id="myModal{{ $clientReport->curid }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	                              <div class="modal-dialog">
	                                <div class="modal-content">
	                                  <div class="modal-header">
	                                    <h4 class="modal-title text-center">Delete Report</h4>
	                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
	                                  </div>
	                                  <div class="modal-body">
	                                    <p>Are you sure you want to delete this report?</p>
	                                  </div>
	                                  <div class="modal-footer">
	                                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
	                                    {!! Form::open(['route'=>['uploaded-reports.destroy', $clientReport->curid], 'class'=>'delete-form']) !!}
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
	                	<tr><td colspan="3">No data found.</td></tr>
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