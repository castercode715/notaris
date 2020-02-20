<!-- jQuery 3 -->
<script src="{{ asset('theme/bower_components/jquery/dist/jquery.min.js') }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('theme/bower_components/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('theme/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- Morris.js charts -->
<script src="{{ asset('theme/bower_components/raphael/raphael.min.js') }}"></script>
<!-- chartjs 1.0.2 -->
{{-- <script src="{{ asset('theme/bower_components/chart.js/Chart.js') }}"></script> --}}
<!-- chartjs 2.7.3 -->
<script src="{{ asset('theme/bower_components/chart.js/Chart_2.7.3.js') }}"></script>
<script src="{{ asset('theme/bower_components/morris.js/morris.min.js') }}"></script>
<!-- Sparkline -->
<script src="{{ asset('theme/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js') }}"></script>
<!-- jvectormap -->
<script src="{{ asset('theme/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
<script src="{{ asset('theme/plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
<!-- jQuery Knob Chart -->
<script src="{{ asset('theme/bower_components/jquery-knob/dist/jquery.knob.min.js') }}"></script>
<!-- daterangepicker -->
<script src="{{ asset('theme/bower_components/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('theme/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<!-- datepicker -->
<script src="{{ asset('theme/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="{{ asset('theme/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>
<!-- CKEditor -->
<script src="{{ asset('theme/bower_components/ckeditor/ckeditor.js') }}"></script>
<!-- Slimscroll -->
<script src="{{ asset('theme/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
<!-- FastClick -->
<script src="{{ asset('theme/bower_components/fastclick/lib/fastclick.js') }}"></script>
<!-- iCheck -->
<script src="{{ asset('theme/plugins/iCheck/icheck.min.js') }}"></script>
<script src="{{ asset('js/dropzone.min.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('theme/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
<script>
    $(function () {
        // $('input[type="checkbox"]').iCheck({
        //     checkboxClass: 'icheckbox_square-blue',
        //     radioClass: 'iradio_square-blue',
        //     increaseArea: '20%' /* optional */
        // });
        //iCheck for checkbox and radio inputs
      // $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      //   checkboxClass: 'icheckbox_minimal-blue',
      //   radioClass   : 'iradio_minimal-blue'
      // })
      // //Red color scheme for iCheck
      // $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
      //   checkboxClass: 'icheckbox_minimal-red',
      //   radioClass   : 'iradio_minimal-red'
      // })
      // //Flat red color scheme for iCheck
      // $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      //   checkboxClass: 'icheckbox_flat-green',
      //   radioClass   : 'iradio_flat-green'
      // })
      $('.select2').select2();
      
    });
</script>  
<!-- DataTables -->
<script src="{{ asset('theme/assets/vendor/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('theme/assets/vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('theme/assets/js/ie10-viewport-bug-workaround.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('theme/dist/js/adminlte.min.js') }}"></script>
<script src="https://canvasjs.com/assets/script/jquery.canvasjs.min.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
{{-- <script src="{{ asset('theme/dist/js/pages/dashboard.js') }}"></script> --}}
<!-- AdminLTE for demo purposes -->
{{-- <script src="{{ asset('theme/dist/js/demo.js') }}"></script> --}}

<script src="/vendors/summernote/summernote.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<script src="{{ asset('vendors/tags_input/dist/bootstrap-tagsinput.min.js')}}"></script>
<script src="{{ asset('vendors/tags_input/dist/bootstrap-tagsinput/bootstrap-tagsinput-angular.min.js')}}"></script>
<!-- 
<script src="{{ asset('js/moment.js') }}"></script>
<script src="{{ asset('js/comboTreePlugin.js')}}"  type="text/javascript"></script> -->

<!-- <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script> -->
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script src="https://www.jquery-az.com/jquery/js/jquery-treeview/logger.js"></script>
<script src="https://www.jquery-az.com/jquery/js/jquery-treeview/treeview.js"></script>

<!-- <script src="{{ asset('vendors/fancytree/dist/jquery.fancytree-all-deps.min.js')}}"></script> -->

<script>
  // $(function () {
  //   $('#table1').DataTable()
  //   $('#table2').DataTable({
  //     'paging'      : true,
  //     'lengthChange': false,
  //     'searching'   : true,
  //     'ordering'    : true,
  //     'info'        : true,
  //     'autoWidth'   : false
  //   })
  // })
</script>
<script>
  $(function () {
    // Replace the <textarea id="editor1"> with a CKEditor
    // instance, using default configuration.
    // CKEDITOR.replace('editor1')
    $('#editor1').summernote({
      height: 300,
      callbacks: {
        onPaste: function (e) {
            var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
            e.preventDefault();
            document.execCommand('insertText', false, bufferText);
        }
      }
    });

    $('#editor2').summernote({
      height: 300,
      callbacks: {
        onPaste: function (e) {
            var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
            e.preventDefault();
            document.execCommand('insertText', false, bufferText);
        }
      }
    });
    //bootstrap WYSIHTML5 - text editor
    $('body .textarea').wysihtml5()
  })

  //Date picker
  $('#datepicker').datepicker({
    autoclose: true,
    format : 'dd-mm-yyyy'
  })
  $('#datepicker2').datepicker({
    autoclose: true,
    format : 'dd-mm-yyyy'
  })
  $('#datepicker3').datepicker({
    autoclose: true,
    format : 'dd-mm-yyyy'
  })
   $('.datepicker').datepicker({
    autoclose: true,
    format : 'dd-mm-yyyy'
  })

  
</script>
@stack('scripts')
<script src="{{ asset('js/app.js') }}"></script>