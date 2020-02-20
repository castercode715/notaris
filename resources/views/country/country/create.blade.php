@extends('base.main')
@section('title') Country @endsection
@section('page_icon') <i class="fa fa-book"></i> @endsection
@section('page_title') Country @endsection
@section('page_subtitle') create @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <a href="/master/country" class="btn btn-success"><i class="fa fa-list"></i> Manage</a>
        </div>
    </div>
@endsection

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="box box-solid" >
	
		<div class="col-md-12" style="background-color:#fff;">
	
        <form action="/master/country/post_country" method="post" >
            @csrf
            <div class="box-body">
                
				
				<div class="form-group">
                    <label for="name"> Country Name <span class="required">*</span></label>
                    <input type="text" name="name" id="name" class="form-control">
                </div>


				<div class="box-footer" style="text-align:left;">
                    <input type="submit" value="Save" class="btn btn-primary">
                    <input type="button" value="Cancel" class="btn btn-primary" onclick="javascript:history.go(-1)">
                </div>
				
				
            </div>
        
		</form>		
	   </div>
		
    </div>
@endsection