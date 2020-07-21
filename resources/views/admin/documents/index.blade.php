@extends('admin/layouts/master')

@section('title', 'Documents List')

@section('content')

	<div class="content-wrapper">
	  <div class="row">
	    <div class="col-lg-12 grid-margin stretch-card">
	      <div class="card">
	        <div class="card-body">
	        	@if( Gate::allows('admin-auth', Auth::user()) )
	          		<div class="text-right">
						<a href="{{ route('documents.create', ['id'=>$TrnID]) }}" class="btn btn-primary">Add New Document</a>
					</div>
				@endif

	          <h4 class="card-title">Documents List</h4>

	          	@include('admin/layouts/messages')

	          <div class="table-responsive">
	            <table class="table table-striped data-table">
	              <thead>
	                <tr>
	                  <th>Document Name</th>
	                  @if( Gate::allows('admin-auth', Auth::user()) )
	                  	<th data-orderable="false"><center>Action</center></th>
	                  @endif
	                </tr>
	              </thead>
	              <tbody>

	              	@forelse( $documents as $document )
		                <tr>
		                  	<td>
		                  		<a href="{{ Url('public/uploads/documents/'.$document->document_file) }}" target="_blank">{{ $document->document_name }}</a>
		                  	</td>

		                  	@if( Gate::allows('admin-auth', Auth::user()) )
			                  	<td class="custom-action"><center>
			                  		<a href="{{ route('documents.edit', ['id'=>$document->did]) }}" title="Edit" class="btn btn-primary"><i class="menu-icon mdi mdi-table-edit"></i></a>

			                  		<a data-original-title="Delete" data-placement="top" data-toggle="modal" href="#myModal{{ $document->did }}" class="btn btn-danger"><i class="menu-icon mdi mdi-delete"></i></a>

				                  	<!-- Delele Modal -->
		                            <div class="modal fade" id="myModal{{ $document->did }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		                              <div class="modal-dialog">
		                                <div class="modal-content">
		                                  <div class="modal-header">
		                                    <h4 class="modal-title text-center">Delete Document</h4>
		                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
		                                  </div>
		                                  <div class="modal-body">
		                                    <p>Are you sure you want to delete this document?</p>
		                                  </div>
		                                  <div class="modal-footer">
		                                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
		                                    {!! Form::open(['route'=>['documents.destroy', $document->did], 'class'=>'delete-form']) !!}
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
			                @endif
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