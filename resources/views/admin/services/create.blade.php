@extends('admin/layouts/master')

@section('title', 'Add New Service')

@section('content')

	<div class="content-wrapper">
	  <div class="row">
	             
	    <div class="col-12 grid-margin">
	      <div class="card">
	        <div class="card-body">
	          	<h4 class="card-title">Add New Service</h4>

	          	@if( $errors->all() )
	          		<div class="alert alert-danger">
	          			<ul>
	          				@foreach( $errors->all() as $error )
	          					<li>{{ $error }}</li>
	          				@endforeach
	          			</ul>
	          		</div>
	          	@endif
	          
	        	{!! Form::open(array('route'=>'services.store')) !!}

		            <div class="row">
		              <div class="col-md-12">
		                <div class="form-group row">
		                	{!! Form::label('service_name', 'Service Name', array('class'=>'col-sm-3 col-form-label')) !!}
							<div class="col-sm-9">
								{!! Form::text('service_name', '', array('class'=>'form-control')) !!}
							</div>
		                </div>
		              </div>

		            </div>

					{!! Form::submit('Submit', array('class'=>'btn btn-success mr-2'))  !!}
		            <a href="{{ route('services.index') }}" class="btn btn-light">Cancel</a>

	        	{!! Form::close() !!}

	        </div>
	      </div>
	    </div>
	  </div>
	</div>

@endsection