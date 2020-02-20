<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>Notaris Application | @yield('title')</title>
<link rel="shortcut icon" href="https://alexandrettaconsulting.com/wp-content/uploads/2019/07/kisspng-cargill-organic-food-breakfast-french-toast-lawyer-5ad1218fb387c7.8447173515236550557354.png" />
<!-- Tell the browser to be responsive to screen width -->
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<!-- Bootstrap 3.3.7 -->
<link rel="stylesheet" href="{{asset('theme/bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
<!-- Font Awesome -->
<link rel="stylesheet" href="{{asset('theme/bower_components/font-awesome/css/font-awesome.min.css')}}">
<!-- Ionicons -->
<link rel="stylesheet" href="{{asset('theme/bower_components/Ionicons/css/ionicons.min.css')}}">
<!-- iCheck -->
<link rel="stylesheet" href="{{asset('theme/plugins/iCheck/all.css') }}">
<!-- Morris chart -->
{{-- <link rel="stylesheet" href="{{asset('theme/bower_components/morris.js/morris.css')}}"> --}}
<!-- jvectormap -->
{{-- <link rel="stylesheet" href="{{asset('theme/bower_components/jvectormap/jquery-jvectormap.css')}}"> --}}
<!-- Date Picker -->
<link rel="stylesheet" href="{{asset('theme/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
<!-- Daterange picker -->
<link rel="stylesheet" href="{{asset('theme/bower_components/bootstrap-daterangepicker/daterangepicker.css')}}">
<!-- bootstrap wysihtml5 - text editor -->
<link rel="stylesheet" href="{{asset('theme/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css')}}">

<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('theme/assets/vendor/datatables/datatables.min.css') }}">
<!-- IE10 -->
<link rel="stylesheet" href="{{ asset('theme/assets/css/ie10-viewport-bug-workaround.css') }}">
<!-- Emulation Modes Warning -->
<link rel="stylesheet" href="{{ asset('theme/assets/js/ie-emulation-modes-warning.js') }}">
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('theme/bower_components/select2/dist/css/select2.min.css') }}">
<!-- Theme style -->
<link rel="stylesheet" href="{{asset('theme/dist/css/AdminLTE.min.css')}}">
<!-- AdminLTE Skins. Choose a skin from the css/skins
    folder instead of downloading all of them to reduce the load. -->
<link rel="stylesheet" href="{{asset('theme/dist/css/skins/_all-skins.min.css')}}">

<link rel="stylesheet" href="{{ asset('js/dropzone.min.css') }}">

<link href="/vendors/summernote/summernote.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<link rel="stylesheet" href="{{ asset('vendors/tags_input/dist/bootstrap-tagsinput.css')}}">


<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ asset('css/style.css')}}">

<!-- <link href="{{ asset('vendors/fancytree/dist/skin-lion/ui.fancytree.min.css')}}" rel="stylesheet"> -->






<!-- Google Font -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
<style>
    .table { font-size: 14px; }
    .dropzone {
	    background: white;
	    border-radius: 5px;
	    border: 2px dashed rgb(228, 114, 151);
	    border-image: none;
	    max-width: 1200px;
	    margin-left: auto;
	    margin-right: auto;
	}

	.delete{
	  color:white;
	  background-color:rgb(231, 76, 60);
	  text-align:center;
	  margin-top:6px;
	  font-weight:700;
	  border-radius:5px;
	  min-width:20px;
	  cursor:pointer;
	}

	.add-one {
		cursor: pointer;
	}

	.tw-control{
		margin-right: 5px !important;
	}

</style>



@stack('css')