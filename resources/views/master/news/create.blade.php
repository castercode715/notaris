@extends('base.main')
@section('title') News @endsection
@section('page_icon') <i class="fa fa-newspaper-o"></i> @endsection
@section('page_title') News @endsection

@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <a href="{{ route('news.index') }}" class="btn btn-success" title="Manage">
                <i class="fa fa-list"></i> Manage
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="box box-solid">
	{!! Form::model($model, [
	    'route' => 'news.store',
	    'method'=> 'post',
	    'enctype'=> 'multipart/form-data'
	]) !!}
	<div class="box-body">
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
			<div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="language" class="control-label">Language</label>
                        <input type="text" name="bahasa" value="{{ $language->language }}" disabled="disabled" class="form-control">
                        {!! Form::hidden('code', $language->code, []) !!}
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="form-group">
                        <label for="title" class="control-label">Title*</label>
                        {!! Form::text('title', null, ['class'=>'form-control', 'id'=>'title', 'required'=>'required']) !!}

                        @if($errors->has('title'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('title') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="sub_title" class="control-label">Subtitle*</label>
                        {!! Form::text('sub_title', null, ['class'=>'form-control', 'id'=>'sub_title', 'required'=>'required']) !!}

                        @if($errors->has('sub_title'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('sub_title') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="desc" class="control-label">Description*</label>
                        {!! Form::textarea('desc', null, ['class'=>'form-control', 'id'=>'editor1', 'rows'=>6, 'required'=>'required']) !!}

                        @if($errors->has('desc'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('desc') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="category" class="control-label">Category*</label>
                        {!! Form::select('category[]', $category, null, ['class'=>'form-control select2','multiple'=>'multiple']) !!}

                        @if($errors->has('category'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('category') }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tag" class="control-label">Tag*</label>
                        {!! Form::select('tag[]', $tag, null, ['class'=>'form-control select2','multiple'=>'multiple']) !!}

                        @if($errors->has('tag'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('tag') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="iframe" class="control-label">iFrame</label>
                        {!! Form::textarea('iframe', null, ['class'=>'form-control', 'id'=>'iframe', 'rows'=>3]) !!}

                        @if($errors->has('iframe'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('iframe') }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="image" class="control-label">Image</label>
                        {!! Form::file('image', ['accept'=>'image/x-png, image/jpeg, image/jpg']) !!}
                        <p style="font-style: italic;">File Ext. : jpg, jpeg, png</p>
                        @if($errors->has('image'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('image') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            
		</div>
	</div>
	<div class="box-footer">
		{!! Form::submit('Create', ['class'=>'btn btn-primary pull-right']) !!}
	</div>
	{!! Form::close() !!}
</div>
@endsection