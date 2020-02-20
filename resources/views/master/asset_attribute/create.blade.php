@extends('base.main')
@section('title') Asset Attribute @endsection
@section('page_icon') <i class="fa fa-folder"></i> @endsection
@section('page_title') Create Asset Attribute @endsection
@section('page_subtitle') list @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <a href="{{ route('asset-attribute.index') }}" class="btn btn-success" title="Manage">
                <i class="fa fa-list"></i> Manage
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="box box-solid">
	{!! Form::model($model, [
	    'route' => 'asset-attribute.store',
	    'method'=> 'post'
	]) !!}

	<div class="box-body">
	    @if(count($errors) > 0)
	        <div class="alert alert-danger alert-dismissible">
	            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	            <ul>
	                @foreach($errors->all() as $error)
	                <li>{{  $error }}</li>
	                @endforeach
	            </ul>
	        </div>
	    @endif

	    <p class="note"><i>Fields with <span class="required">*</span> are required.</i></p>

	    <div class="box-body row">
	    	<div class="col-md-4">
		        <div class="form-group">
		            <label for="category_asset_id" class="control-label">Category*</label>
		            {!! Form::select('category_asset_id', ['- Select -']+$category, null, ['class'=>'form-control', 'id'=>'category_asset_id']) !!}

		            @if($errors->has('category_asset_id'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('category_asset_id') }}
                        </span>
                    @endif
	    		</div>
	    	</div>
	    	<div class="col-md-2">
		        <div class="form-group">
		            <label for="code" class="control-label">Active*</label>
		            <div>
		                {!! Form::checkbox('active', null, null, ['id'=>'active']) !!} 
		            </div>
		            @if($errors->has('active'))
		                <span class="invalid-feedback" role="alert">
		                    {{ $errors->first('active') }}
		                </span>
		            @endif
		        </div>
	    	</div>
	    </div>
	    <hr>
	    @php $no = 0; @endphp
        @foreach($language as $r)
        <div class="box-body row">
        	<div class="col-md-2">
                <div class="form-group">
                    @if($no < 1) <label for="code" class="control-label">Language</label> @endif
                    <input type="text" name="bahasa" value="{{ $r->language }}" disabled="disabled" class="form-control">
                    {!! Form::hidden('code[]', $r->code, []) !!}
                </div>
            </div>
            <div class="col-md-10">
                <div class="form-group">
                    @if($no < 1)<label for="description" class="control-label">Name*</label> @endif
                    {!! Form::text('description[]', null, ['class'=>'form-control', 'id'=>'description']) !!}

                    @if($errors->has('description.*'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('description.*') }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
        @php $no++; @endphp
        @endforeach

	</div>
    <div class="box-footer">
        {!! Form::submit('Create', ['class'=>'btn btn-primary pull-right']) !!}
    </div>
	{!! Form::close() !!}
</div>
@endsection