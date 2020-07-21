@extends('admin/layouts/master')

@section('title', 'Add New Client')

@section('content')

	<div class="content-wrapper">
	  <div class="row">
	             
	    <div class="col-12 grid-margin">
	      <div class="card">
	        <div class="card-body">
	          	<h4 class="card-title">Add New Client</h4>

	          	@include('admin/layouts/messages')
	          
	        	{!! Form::open(array('route'=>'clients.store', 'enctype'=>'multipart/form-data')) !!}
	        	
		            <div class="row">
		              <div class="col-md-6">
		                <div class="form-group row">
							<div class="col-sm-12">
								{!! Form::label('email', 'Email', array('class'=>'col-form-label')) !!}

								{!! Form::text('email', '', array('class'=>'form-control')) !!}
							</div>
		                </div>
		              </div>

		              <div class="col-md-6">
		                <div class="form-group row">
							<div class="col-sm-12">
								{!! Form::label('client_number', 'Client Number', array('class'=>'col-form-label')) !!}

								{!! Form::text('client_number', '', array('class'=>'form-control')) !!}
							</div>
		                </div>
		              </div>

		              <div class="col-md-6">
		                <div class="form-group row">
							<div class="col-sm-12">
								{!! Form::label('password', 'Password', array('class'=>'col-form-label')) !!}

								{!! Form::password('password', array('class'=>'form-control')) !!}
							</div>
		                </div>
		              </div>

		              <div class="col-md-6">
		                <div class="form-group row">
							<div class="col-sm-12">
								{!! Form::label('confirmpassword', 'Confirm Password', array('class'=>'col-form-label')) !!}

								{!! Form::password('confirmpassword', array('class'=>'form-control')) !!}
							</div>
		                </div>
		              </div>

		              <div class="col-md-6">
		                <div class="form-group row">
							<div class="col-sm-12">
								{!! Form::label('user_first_name', 'First Name', array('class'=>'col-form-label')) !!}

								{!! Form::text('user_first_name', '', array('class'=>'form-control')) !!}
							</div>
		                </div>
		              </div>

		              <div class="col-md-6">
		                <div class="form-group row">
							<div class="col-sm-12">
								{!! Form::label('user_last_name', 'Last Name', array('class'=>'col-form-label')) !!}

								{!! Form::text('user_last_name', '', array('class'=>'form-control')) !!}
							</div>
		                </div>
		              </div>

		              <div class="col-md-6">
		                <div class="form-group row">
							<div class="col-sm-12">
								{!! Form::label('client_name', 'Client name', array('class'=>'col-form-label')) !!}

								{!! Form::text('client_name', '', array('class'=>'form-control')) !!}
							</div>
		                </div>
		              </div>

		              <div class="col-md-6">
		                <div class="form-group row">
							<div class="col-sm-12">
								{!! Form::label('user_mobile', 'Mobile Number', array('class'=>'col-form-label')) !!}

								{!! Form::text('user_mobile', '', array('class'=>'form-control')) !!}
							</div>
		                </div>
		              </div>

		              <div class="col-md-6">
		                <div class="form-group row">
							<div class="col-sm-12">
								{!! Form::label('user_dob', 'Date of Birth', array('class'=>'col-form-label')) !!}

								{!! Form::text('user_dob', '', array('class'=>'form-control', 'id'=>'user_dob', 'readonly'=>'readonly', 'placeholder'=>'YYYY-MM-DD')) !!}
							</div>
		                </div>
		              </div>
		              
		              <div class="col-md-6">
		                <div class="form-group row">
							<div class="col-sm-12">
								{!! Form::label('user_profile_image', 'Profile Image', array('class'=>'col-form-label')) !!}

								{!! Form::file('user_profile_image', array('class'=>'form-control')) !!}
							</div>
		                </div>
		              </div>

		              {{--<div class="col-md-12 mt-3 mb-2">
		              	<h4 class="card-title">Company Detail</h4>
		              </div>

		              <div class="col-md-6">
		                <div class="form-group row">
							<div class="col-sm-12">
								{!! Form::label('client_name', 'Client name (Company name)', array('class'=>'col-form-label')) !!}

								{!! Form::text('client_name', '', array('class'=>'form-control')) !!}
							</div>
		                </div>
		              </div>

		              <div class="col-md-6">
		                <div class="form-group row">
							<div class="col-sm-12">
								{!! Form::label('client_email', 'Comapny E-mail', array('class'=>'col-form-label')) !!}

								{!! Form::text('client_email', '', array('class'=>'form-control')) !!}
							</div>
		                </div>
		              </div>--}}

		            </div>

		            <div class="row">
			            <div class="col-md-12">
			                <div class="form-group row">
								{!! Form::submit('Submit', array('class'=>'btn btn-success mr-2 mb-2', 'name'=>'btnSubmit'))  !!}
								{!! Form::submit('Save Draft', array('class'=>'btn btn-info mr-2  mb-2', 'name'=>'btnSubmit'))  !!}
					            <a href="{{ route('clients.index') }}" class="btn btn-light mb-2">Cancel</a>
					        </div>
					    </div>
					</div>

	        	{!! Form::close() !!}

	        </div>
	      </div>
	    </div>
	  </div>
	</div>

@endsection

@section('js')
<script>
	$('#user_dob').datepicker({
		format: 'yyyy-mm-dd',
	});

	$('#client_tax_register_date').datepicker({
		format: 'yyyy-mm-dd',
	});

	$('#client_payment_date').datepicker({
		format: 'yyyy-mm-dd',
	});

	$('#contract_start_date').datepicker({
		format: 'yyyy-mm-dd',
	});

	$('#contract_end_date').datepicker({
		format: 'yyyy-mm-dd',
	});
</script>
@endsection