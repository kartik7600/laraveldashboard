@if( $errors->all() )
	<div class="alert alert-danger">
		<ul>
			@foreach( $errors->all() as $error )
				<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
@endif

@if(Session::has('message'))
    <div class="row">
        <div class="alert @if( Session::get('messageType' ) == 'success') alert-success @elseif( Session::get('messageType' ) == 'info') alert-info @else alert-danger @endif">
            {{ Session::get('message') }}
        </div>
    </div>
@endif