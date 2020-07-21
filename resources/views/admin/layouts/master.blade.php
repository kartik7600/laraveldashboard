@include('admin/layouts/header')

<div class="container-scroller">

	@if( Auth::check() )
		{{-- Start Header menu --}}
			@include('admin/layouts/header-menu')
		{{-- End Header menu --}}
	@endif

	<div class="container-fluid page-body-wrapper @if( !Auth::check() )full-page-wrapper auth-page @endif">
		@if( Auth::check() )
			{{-- Start Left sidebar --}}
			@include('admin/layouts/left-menu')
	    	{{-- End Left sidebar --}}
	    @endif
		
		@if( Auth::check() )
			<div class="main-panel">
				@yield('content')

				<footer class="footer">
	     
	    		</footer>
			</div>
		@else
			@yield('content')
		@endif
		
	</div>

</div>

@include('admin/layouts/footer')