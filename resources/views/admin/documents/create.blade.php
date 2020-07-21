@extends('admin/layouts/master')

@section('title', 'Add New Document')

@section('content')

	<div class="content-wrapper">
	  <div class="row">
	             
	    <div class="col-12 grid-margin">
	      <div class="card">
	        <div class="card-body">
	          	<h4 class="card-title">Add New Document</h4>

	          	@include('admin/layouts/messages')
	          	
	        	{!! Form::open(['route'=>['documents.store'], 'enctype'=>'multipart/form-data']) !!}

		            <div class="row">
		            	{!! Form::hidden('trn_id', old('trn_id', $TrnID), array('class'=>'form-control')) !!}

		              <div class="col-md-12">
		                <div class="form-group row">
		                	{!! Form::label('document_name', 'Document Name', array('class'=>'col-sm-2 col-form-label')) !!}
							<div class="col-sm-10">
								{!! Form::text('document_name', '', array('class'=>'form-control')) !!}
							</div>
		                </div>
		              </div>

		              <div class="col-md-12">
		                <div class="form-group row">
		                	{!! Form::label('document_file', 'Document', array('class'=>'col-sm-2 col-form-label')) !!}
							<div class="col-sm-10">
								{!! Form::file('document_file', array('class'=>'form-control')) !!}
							</div>
		                </div>
		              </div>

		            </div>

					{!! Form::submit('Submit', array('class'=>'btn btn-success mr-2'))  !!}
		            <a href="{{ route('documents.index', ['id'=>$TrnID]) }}" class="btn btn-light">Cancel</a>

	        	{!! Form::close() !!}

	        </div>
	      </div>
	    </div>
	  </div>
	</div>

@endsection