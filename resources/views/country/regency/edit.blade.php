﻿@extends('base.main')
@section('title') Regency  @endsection
@section('page_icon') <i class="fa fa-book"></i> @endsection
@section('page_title') Regency @endsection
@section('page_subtitle') Edit @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <a href="/master/regency" class="btn btn-success"><i class="fa fa-list"></i> Manage</a>
			<a href="/master/regency/create" class="btn btn-success" title="Create">
                <i class="fa fa-plus"></i> Create
            </a>
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
	
        <form action="/master/regency/regency_edit/{{$data->id}}" method="post" >
            @csrf
            <div class="box-body">
                
				<div class="form-group">
                    <label for="name"> Province <span class="required">*</span></label>
                    <select name="province_id" id="province_id" class="form-control">
						@foreach($regency as $row)
							@if($row->id == $data->provinces_id)
							<option value="{{$row->id}}" selected>{{$row->name}}</option>
							@else
							<option value="{{$row->id}}">{{$row->name}}</option>
							@endif
						@endforeach
					</select>
                </div>	
				
				
				<div class="form-group">
                    <label for="name"> Regency Name <span class="required">*</span></label>
                    <input type="text" name="name" id="name" value="{{$data->name}}" class="form-control">
                </div>


				<div class="box-footer" style="text-align:left;">
                    <input type="submit" value="Update" class="btn btn-primary">
                    <input type="button" value="Cancel" class="btn btn-primary" onclick="javascript:history.go(-1)">
                </div>
	
				
            </div>
        
		</div>
		
		
	</form>		
		
    </div>
@endsection