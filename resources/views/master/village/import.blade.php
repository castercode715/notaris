@extends('base.main')
@section('title') Village @endsection
@section('page_icon') <i class="fa fa-book"></i> @endsection
@section('page_title') Village @endsection
@section('page_subtitle') import @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <a href="/master/village" class="btn btn-success"><i class="fa fa-list"></i> Manage</a>
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
	
        <form action="/master/village/import_village" method="post" >
            @csrf
            <div class="box-body">
                
				
				<div class="col-md-12">
				<div class="form-group">
                    <label for="name"> Choose District <span class="required">*</span></label>
                    
					<select name="district_id" id="name" class="form-control">
						@foreach($district as $data)
						<option value="{{$data->id}}">{{$data->name}}</option>
						@endforeach
					</select>
					
					
                </div>	
				</div>
				
				<div class="col-md-12">
				<div class="form-group">
                    <label for="name"> Import File Excel <span class="required">*</span></label>
                    <input type="file" name="file" id="name" class="form-control">
					
                </div>	
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