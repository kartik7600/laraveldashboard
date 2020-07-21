@extends('admin/layouts/master')

@section('title', 'Edit Service')

@section('content')

	<div class="content-wrapper">
	  <div class="row">
	             
	    <div class="col-12 grid-margin">
	      <div class="card">
	        <div class="card-body">
	          	<h4 class="card-title">Edit Service</h4>

	          	@include('admin/layouts/messages')
	          
	        	{!! Form::open(['route'=>['services.update', $dataRow->sid]]) !!}
	        		{!! Form::hidden('_method', 'PUT') !!}
		            <div class="row">
		              <div class="col-md-12">
		                <div class="form-group row">
		                	{!! Form::label('service_name', 'Service Name', array('class'=>'col-sm-3 col-form-label')) !!}
							<div class="col-sm-9">
								{!! Form::text('service_name', old('service_name', $dataRow->service_name), array('class'=>'form-control')) !!}
							</div>
		                </div>
		              </div>

		            </div>

					{!! Form::submit('Update', array('class'=>'btn btn-success mr-2'))  !!}
		            <a href="{{ route('services.index') }}" class="btn btn-light">Cancel</a>

	        	{!! Form::close() !!}

	        </div>
	      </div>
	    </div>
	  </div>
	</div>

@endsection