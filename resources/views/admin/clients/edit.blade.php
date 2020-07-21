@extends('admin/layouts/master')

@section('title', 'Edit Client')

@section('content')

	<div class="content-wrapper">
	  <div class="row">
	             
	    <div class="col-12 grid-margin">
	      <div class="card">
	        <div class="card-body">
	          	<h4 class="card-title">Edit Client</h4>

	          	@include('admin/layouts/messages')
	          
	        	{!! Form::open(['route'=>['clients.update', $dataRow->uid], 'enctype'=>'multipart/form-data']) !!}
	        		{!! Form::hidden('_method', 'PUT') !!}
	        		
	        		{!! Form::hidden('client_detail_id', old('client_detail_id', $clientDetail->cdid), array('class'=>'form-control', 'id'=>'client_detail_id', 'readonly')) !!}

		            <div class="row">
		              <div class="col-md-12">
		                <div class="form-group row">
		                	{!! Form::label('email', 'Email', array('class'=>'col-sm-3 col-form-label')) !!}
							<div class="col-sm-9">
								{!! Form::text('email', old('email', $dataRow->email), array('class'=>'form-control', 'readonly', 'disabled')) !!}
							</div>
		                </div>
		              </div>

		              <div class="col-md-12">
		                <div class="form-group row">
		                	{!! Form::label('client_number', 'Client Number', array('class'=>'col-sm-3 col-form-label')) !!}
							<div class="col-sm-9">
								{!! Form::text('client_number', old('client_number', $clientDetail->client_number), array('class'=>'form-control', 'readonly', 'disabled')) !!}
							</div>
		                </div>
		              </div>

		              <div class="col-md-12">
		                <div class="form-group row">
		                	{!! Form::label('user_first_name', 'First Name', array('class'=>'col-sm-3 col-form-label')) !!}
							<div class="col-sm-9">
								{!! Form::text('user_first_name', old('user_first_name', $dataRow->user_first_name), array('class'=>'form-control')) !!}
							</div>
		                </div>
		              </div>

		              <div class="col-md-12">
		                <div class="form-group row">
		                	{!! Form::label('user_last_name', 'Last Name', array('class'=>'col-sm-3 col-form-label')) !!}
							<div class="col-sm-9">
								{!! Form::text('user_last_name', old('user_last_name', $dataRow->user_last_name), array('class'=>'form-control')) !!}
							</div>
		                </div>
		              </div>

		              <div class="col-md-12">
		                <div class="form-group row">
		                	{!! Form::label('client_name', 'Client name', array('class'=>'col-sm-3 col-form-label')) !!}
							<div class="col-sm-9">
								{!! Form::text('client_name', old('client_name', $clientDetail->client_name), array('class'=>'form-control')) !!}
							</div>
		                </div>
		              </div>

		              <div class="col-md-12">
		                <div class="form-group row">
		                	{!! Form::label('user_mobile', 'Mobile Number', array('class'=>'col-sm-3 col-form-label')) !!}
							<div class="col-sm-9">
								{!! Form::text('user_mobile', old('user_mobile', $dataRow->user_mobile), array('class'=>'form-control')) !!}
							</div>
		                </div>
		              </div>

		              <div class="col-md-12">
		                <div class="form-group row">
		                	{!! Form::label('user_dob', 'Date of Birth', array('class'=>'col-sm-3 col-form-label')) !!}
							<div class="col-sm-9">
								{!! Form::text('user_dob', old('user_dob', $dataRow->user_dob), array('class'=>'form-control', 'id'=>'user_dob', 'readonly'=>'readonly', 'placeholder'=>'YYYY-MM-DD')) !!}
							</div>
		                </div>
		              </div>

		              <div class="col-md-12">
		                <div class="form-group row">
		                	{!! Form::label('user_status', 'User Status', array('class'=>'col-sm-3 col-form-label')) !!}
		                	<div class="col-sm-9">
								<label class="col-sm-2">
									<div class="form-radio">
		                              <label class="form-check-label">
		                                <input name="user_status" type="radio" value="Active" id="user_status" class="form-check-input" {{ isset($dataRow->user_status) && $dataRow->user_status == 'Active' ? 'checked' : '' }} > Active
		                              	<i class="input-helper"></i>
		                              </label>
		                            </div>
		                        </label>
								<label class="col-sm-2">
									<div class="form-radio">
		                              <label class="form-check-label">
		                                <input name="user_status" type="radio" value="Inactive" id="user_status" {{ isset($dataRow->user_status) && $dataRow->user_status == 'Inactive' ? 'checked' : '' }} > Inactive
		                              	<i class="input-helper"></i>
		                              </label>
		                            </div>
								</label>
							</div>
		                </div>
		              </div>

		              <div class="col-md-12">
		                <div class="form-group row">
		                	{!! Form::label('user_profile_image', 'Profile Image', array('class'=>'col-sm-3 col-form-label')) !!}
							<div class="col-sm-9">
								{!! Form::file('user_profile_image', array('class'=>'form-control')) !!}

								@if($dataRow->user_profile_image)
								<div class="mt-2 float-left">
									{!! Html::image('public/uploads/profile_images/'.$dataRow->user_profile_image, 'Profile Image', array('class'=>'img-max-h-150')) !!}
								</div>
								@endif
							</div>
		                </div>
		              </div>

		              {{--<div class="col-md-12 mt-3 mb-2">
		              	<h4 class="card-title">Company Detail</h4>
		              </div>

		              <div class="col-md-12">
		                <div class="form-group row">
		                	{!! Form::label('client_name', 'Client name (Company name)', array('class'=>'col-sm-3 col-form-label')) !!}
							<div class="col-sm-9">
								{!! Form::text('client_name', old('client_name', $clientDetail->client_name), array('class'=>'form-control')) !!}
							</div>
		                </div>
		              </div>

		              <div class="col-md-12">
		                <div class="form-group row">
		                	{!! Form::label('client_email', 'Comapny E-mail', array('class'=>'col-sm-3 col-form-label')) !!}
							<div class="col-sm-9">
								{!! Form::text('client_email', old('client_email', $clientDetail->client_email), array('class'=>'form-control')) !!}
							</div>
		                </div>
		              </div>--}}

		              <div class="col-md-12">
		                <div class="form-group row">
		                	{!! Form::label('manager_id', 'Account Manager', array('class'=>'col-sm-3 col-form-label')) !!}
							<div class="col-sm-9">
								{!! Form::select('manager_id', [''=>'-- Select Account Manager --'] + $managers->toArray(), old('manager_id', $clientDetail->manager_id), array('class'=>'form-control')) !!}
							</div>
		                </div>
		              </div>

		            </div>

					{!! Form::submit('Submit', array('class'=>'btn btn-success mr-2'))  !!}
		            <a href="{{ route('clients.index') }}" class="btn btn-light">Cancel</a>

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
		orientation: "auto bottom"
	});

	$('#client_tax_register_date').datepicker({
		format: 'yyyy-mm-dd',
	});

	$('#client_payment_date').datepicker({
		format: 'yyyy-mm-dd',
	});
</script>
@endsection