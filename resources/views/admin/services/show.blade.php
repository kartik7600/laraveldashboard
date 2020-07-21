@extends('admin/layouts/master')

@section('title', 'Service')

@section('content')

	<div class="content-wrapper">
		<div class="row">
			<div class="col-12">
		      	<h3>Service Detail</h3>
			</div>
		</div>

		<div class="row">
	        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
	        	
	        	@if(Session::has('message'))
	                <div class="row">
	                    <div class="alert @if(Session::get('messageType') == 'success') alert-success @else alert-danger @endif">
	                        {{ Session::get('message') }}
	                    </div>
	                </div>
            	@endif
            	
	        	<div class="table-responsive">
	            	<table class="table table-striped">
	            		<tr>
		                  <th width="10%">Service</th>
		                  <td>{{ $serviceData->service_name }}</td>

		                  <th width="10%">Amount (AED)</th>
		                  <td>{{ $serviceData->service_amount }}</td>
		                </tr>
	            	</table>
	            </div>
	        		
	        </div>
	    </div>

		<div class="row">             
		    <div class="col-12 grid-margin">
		      <div class="card">
		        <div class="card-body">
		          	<h4 class="card-title">Client Detail</h4>
		          	
		          	<div class="table-responsive">
			            <table class="table table-striped data-table">
			              <thead>
			                <tr>
			                  <th>Company</th>
			                  <th>Amout (AED)</th>
			                  <th>Purchase Date</th>
			                  <th>Expirty Date</th>
			                  <th>Duration</th>
			                </tr>
			              </thead>
			              <tbody>

			              	@forelse( $ClientRows as $ClientRow )
				                <tr>
				                  <td>{{ $ClientRow->user_company }}</td>
				                  <td>{{ $ClientRow->client_service_amount }}</td>
				                  <td>{{ Carbon\Carbon::parse($ClientRow->purchase_date)->format('F d, Y') }}</td>
				                  <td>{{ Carbon\Carbon::parse($ClientRow->expiry_date)->format('F d, Y') }}</td>
				                  <td>{{ $ClientRow->service_duration }}</td>
				                </tr>
			                @empty
			                	<tr><td colspan="4">No data found.</td></tr>
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