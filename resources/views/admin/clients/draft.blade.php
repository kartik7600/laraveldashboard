@extends('admin/layouts/master')

@section('title', 'Darft Clients List')

@section('content')

	<div class="content-wrapper">
	  <div class="row">
	    <div class="col-lg-12 grid-margin stretch-card">
	      <div class="card">
	        <div class="card-body">
	          <h4 class="card-title">Darft Clients List</h4>

	          	@include('admin/layouts/messages')

	          <div class="table-responsive">
	            <table class="table table-striped data-table">
	              <thead>
	                <tr>
	                  <th data-orderable="false">Logo</th>
	                  <th>Email</th>
	                  <th>Client Number</th>
	                  <th>Name</th>
	                  <th data-orderable="false"><center>Action</center></th>
	                </tr>
	              </thead>
	              <tbody>

	              	@forelse( $clientUsers as $clientUser )
	              	
		                <tr>
		                  <td>
		                  	@if( !empty($clientUser->user_profile_image) )
		                  		{!! Html::image('public/uploads/profile_images/'.$clientUser->user_profile_image, 'Profile Image') !!}
		                  	@else
		                  		{!! Html::image('public/uploads/default-profile-image.png', 'Company Logo') !!}
		                  	@endif
		                  </td>
		                  <td>{{ $clientUser->email }}</td>
		                  <td>{{ $clientUser->client_number }}</td>
		                  <td>{{ $clientUser->user_first_name }} {{ $clientUser->user_last_name }}</td>

		                  <td class="custom-action"><center>
		                  	@if( Gate::allows('admin-auth', Auth::user()) )
		                  		<a href="{{ route('clients.draftedit', ['id'=>$clientUser->uid]) }}" title="Edit" class="btn btn-primary"><i class="menu-icon mdi mdi-table-edit"></i></a>
		                  	@endif
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