@extends('base.main')
@section('title') About Us @endsection
@section('page_icon') <i class="fa fa-folder"></i> @endsection
@section('page_title') Add About Us Description <i>({{ $language->language }})</i> @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
        	<a href="{{ route('about-us.show', base64_encode($model->about_us_id)) }}" class="btn btn-success" title="Detail About Us">
                <i class="fa fa-search"></i> Detail
            </a>
            <a href="{{ route('about-us.index') }}" class="btn btn-success" title="Manage">
                <i class="fa fa-list"></i> Manage
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="box box-solid">
    {!! Form::model($model, [
        'route' => 'about-us.store-new',
        'method'=> 'post',
        'enctype'   => 'multipart/form-data'
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

            {!! Form::hidden('about_us_id', $id, []) !!}

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
                        <label for="description" class="control-label">Description</label>
                        {!! Form::textarea('description', null, ['class'=>'form-control', 'id'=>'editor1', 'rows'=>6, 'required'=>'required']) !!}

                        @if($errors->has('description'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('description') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
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
                        <p style="font-style: italic;">File Ext. : .jpg, .jpeg, .png</p>
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
        {!! Form::submit('Add', ['class'=>'btn btn-primary pull-right']) !!}
    </div>
    {!! Form::close() !!}
</div>
@endsection