@extends('admin/layouts/master')

@section('title', 'Upload Reports')

@section('content')

	<div class="content-wrapper">
	  <div class="row">
	             
	    <div class="col-12 grid-margin">
	      <div class="card">
	        <div class="card-body">
	          	<h4 class="card-title">Upload Reports</h4>

	          	@include('admin/layouts/messages')
	          
	        	{!! Form::open(array('route'=>'uploaded-reports.store', 'enctype'=>'multipart/form-data')) !!}

		            <div class="row">

		              <div class="col-md-12">
		                <div class="form-group row">
		                	{!! Form::label('service_id', 'Services', array('class'=>'col-sm-2 col-form-label')) !!}
							<div class="col-sm-10">
								{!! Form::select('service_id', [''=>'-- Select Service --'] + $services->toArray(), '', array('class'=>'form-control', 'id'=>'service_id')) !!}
							</div>
		                </div>
		              </div>

		              <div class="col-md-12">
		                <div class="form-group row">
		                	{!! Form::label('report_file', 'Report File', array('class'=>'col-sm-2 col-form-label')) !!}
							<div class="col-sm-10">
								{!! Form::file('report_file', array('class'=>'form-control')) !!}
							</div>
		                </div>
		              </div>

		            </div>

					{!! Form::submit('Submit', array('class'=>'btn btn-success mr-2'))  !!}
		            <a href="{{ route('uploaded-reports.index') }}" class="btn btn-light">Cancel</a>

	        	{!! Form::close() !!}

	        </div>
	      </div>
	    </div>
	  </div>
	</div>

@endsection