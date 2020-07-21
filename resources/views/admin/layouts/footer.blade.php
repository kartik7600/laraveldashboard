	@include('admin/layouts/foot')
	@yield('js')

	@if( Auth::check() )
	<!--Start of Tawk.to Script-->
	<script type="text/javascript">
	var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
	(function(){
	var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
	s1.async=true;
	s1.src='https://embed.tawk.to/5c5558d07cf662208c93bcfb/default';
	s1.charset='UTF-8';
	s1.setAttribute('crossorigin','*');
	s0.parentNode.insertBefore(s1,s0);
	})();
	</script>
	<!--End of Tawk.to Script-->
	@endif

	@if( Auth::check() )
		@if( Auth::user()->user_role == 'admin' )
			<script>
			//jQuery(document).ready(function(){
				jQuery('#notificationDropdown').click(function(e){
				   	e.preventDefault();
				   	jQuery.ajaxSetup({
				      headers: {
				          'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
				      }
				  	});

			   		jQuery.ajax({
				      url: "{{ route('notofications.read') }}",
				      method: 'get',
				      success: function(result) {
				      		jQuery('#notification_count').hide();
				        	console.log(result);
			      		}
			      	});
			   	});
			//});
			</script>
		@endif
	@endif
</body>
</html>