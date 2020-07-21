@extends('admin/layouts/master')

@section('title', 'Edit Document')

@section('content')

	<div class="content-wrapper">
	  <div class="row">
	             
	    <div class="col-12 grid-margin">
	      <div class="card">
	        <div class="card-body">
	          	<h4 class="card-title">Edit Document</h4>

	          	@include('admin/layouts/messages')
	          
	        	{!! Form::open(['route'=>['documents.update', $document->did], 'enctype'=>'multipart/form-data']) !!}
	        		{!! Form::hidden('_method', 'PUT') !!}
		            <div class="row">

		              <div class="col-md-12">
		                <div class="form-group row">
		                	{!! Form::label('document_name', 'Document Name', array('class'=>'col-sm-2 col-form-label')) !!}
							<div class="col-sm-10">
								{!! Form::text('document_name', old('document_name', $document->document_name), array('class'=>'form-control')) !!}
							</div>
		                </div>
		              </div>

		              <div class="col-md-12">
		                <div class="form-group row">
		                	{!! Form::label('document_file', 'Document', array('class'=>'col-sm-2 col-form-label')) !!}
							<div class="col-sm-10">
								{!! Form::file('document_file', array('class'=>'form-control')) !!}

								@if( !empty($document->document_file) )
								<span>
									<a href="{{ Url('public/uploads/documents/'.$document->document_file)}}" target="_blank">
										{{ $document->document_file }}
									</a>
								</span>
								@endif
							</div>
		                </div>
		              </div>

		            </div>

					{!! Form::submit('Update', array('class'=>'btn btn-success mr-2'))  !!}
		            <a href="{{ route('documents.index', ['id'=>$document->trn_id]) }}" class="btn btn-light">Cancel</a>

	        	{!! Form::close() !!}

	        </div>
	      </div>
	    </div>
	  </div>
	</div>

@endsection