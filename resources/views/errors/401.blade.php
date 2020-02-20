@extends('base.main')
@section('title') Error 401 @endsection

@section('content')
<div class="error-page">
    <h2 style='margin-top: -30px;' class="headline text-yellow"> 401</h2>

    <div style='margin-left: ' class="error-content">
      <h3><i class="fa fa-warning text-yellow"></i> {{ $exception->getMessage() }}</h3>

      <p>
        We could not find the page you were looking for.<br>
        
      </p>
    </div>
    <!-- /.error-content -->
  </div>
<h2>
@endsection