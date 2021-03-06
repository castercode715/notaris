@extends('base.main')
@section('title') Country @endsection
@section('page_icon') <i class="fa fa-book"></i> @endsection
@section('page_title') Country @endsection
@section('page_subtitle') Edit @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <a href="#" class="btn btn-default"><i class="fa fa-list"></i> Manage</a>
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
	
        <form action="/master/country/edit_country/{{$data->id}}" method="post" >
            @csrf
            <div class="box-body">
                
				<div class="form-group">
                    <label for="price_start">Country Name<span class="required">*</span></label>
                    <input type="text" name="name" id="name" class="form-control" value="{{$data->name}}">
                </div>	
				
				
				<div class="box-footer" style="text-align:left;">
                    <input type="submit" value="Save" class="btn btn-success">
                    <input type="button" value="Cancel" class="btn btn-success" onclick="javascript:history.go(-1)">
                </div>
				
            </div>
        
		</div>
		
			</form>		
				
    </div>
@endsection