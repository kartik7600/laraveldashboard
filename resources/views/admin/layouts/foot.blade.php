  <!-- plugins:js -->
  {!! Html::script('public/admin/vendors/js/vendor.bundle.base.js') !!}
  {!! Html::script('public/admin/vendors/js/vendor.bundle.addons.js') !!}
  <!-- endinject -->
  <!-- inject:js -->
  {!! Html::script('public/admin/js/off-canvas.js') !!}
  {!! Html::script('public/admin/js/misc.js') !!}

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>

  <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

  <script type="text/javascript">
    $(function(){
      $('.data-table').DataTable(
        /*{
          "lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
          "language": {
              searchPlaceholder: "Search..."
          }
        }*/
      );
      $('.dataTables_filter input').removeClass('form-control-sm').addClass('form-control-md');
    });
  </script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>
  <!-- endinject -->