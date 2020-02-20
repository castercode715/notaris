@extends('base.main')
@section('title') District @endsection
@section('page_icon') <i class="fa fa-book"></i> @endsection
@section('page_title') District @endsection
@section('page_subtitle') create @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <a href="/master/district" class="btn btn-success"><i class="fa fa-list"></i> Manage</a>
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
	
        <form action="/master/district/post_district" method="post" >
            @csrf
            <div class="box-body">
                
				
				<div class="form-group">
                    <label for="name"> Regency <span class="required">*</span></label>
                    <select name="regency_id" id="regency_id" class="form-control">
						<option value="--">Choose File</option>
						@foreach($regency as $data)
						<option value="{{$data->id}}">{{$data->name}}</option>
						@endforeach
					</select>
                </div>	
				
				
				<div class="form-group">
                    <label for="name"> District Name <span class="required">*</span></label>
                    <input type="text" name="name" id="name" class="form-control">
                </div>	
				
				
				<div class="box-footer" style="text-align:left;">
                    <input type="submit" value="Save" class="btn btn-primary">
                    <input type="button" value="Cancel" class="btn btn-primary" onclick="javascript:history.go(-1)">
                </div>
				
            </div>
        
		</div>
		
		
	</form>		
		
    </div>
@endsection