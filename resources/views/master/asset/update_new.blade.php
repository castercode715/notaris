@extends('base.main')
@section('title') Asset @endsection
@section('page_icon') <i class="fa fa-cube"></i> @endsection
@section('page_title') Edit Asset Description <i>({{ $language->language }})</i> @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
        	<a href="{{ route('asset.show', base64_encode($model->asset_id)) }}" class="btn btn-success" title="Detail Asset">
                <i class="fa fa-search"></i> Detail
            </a>
            <a href="{{ route('asset.index') }}" class="btn btn-success" title="Manage Asset">
                <i class="fa fa-list"></i> Manage
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="box box-solid">
    {!! Form::model($model, [
        'route' => ['asset.update-new', base64_encode($model->id) ],
        'method'=> 'put',
        'enctype'   => 'multipart/form-data'
    ]) !!}
    <div class="box-body">
        <div class="box-body">
            @if(count($errors) > 0)
                <div class="alert alert-danger">
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
                        <label for="asset_name" class="control-label">Language</label>
                        <input type="text" name="bahasa" value="{{ $language->language }}" disabled="disabled" class="form-control">
                        {!! Form::hidden('code', $language->code, []) !!}
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="form-group">
                        <label for="asset_name" class="control-label">Name*</label>
                        {!! Form::text('asset_name', null, ['class'=>'form-control', 'id'=>'asset_name', 'required'=>'required']) !!}

                        @if($errors->has('asset_name'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('asset_name') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="desc" class="control-label">Description</label>
                        {{-- {!! Form::textarea('desc', $model->description, ['class'=>'form-control', 'id'=>'editor1', 'rows'=>4, 'required'=>'required']) !!} --}}
                        {!! Form::textarea('desc', $model->description, ['class'=>'form-control', 'id'=>'editor1', 'rows'=>3]) !!}

                        @if($errors->has('desc'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('desc') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-md-offset-2">
                    <div class="form-group">
                        <label for="file_resume" class="control-label">Resume File</label>
                        @if($model->file_resume != '')
                        <p><a href="/files/asset/{{ $model->file_resume }}" target="_blank" class="btn btn-xs btn-primary"><i class="fa fa-download"></i> Download Resume File</a></p>
                        @endif
                        {!! Form::file('file_resume', ['accept'=>'.pdf']) !!}
                        <p style="font-style: italic;">Ext. File : <b>.pdf</b></p>
                        @if($errors->has('file_resume'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('file_resume') }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="file_fiducia" class="control-label">Fiducia File</label>
                        @if($model->file_fiducia != '')
                        <p><a href="/files/asset/{{ $model->file_fiducia }}" target="_blank" class="btn btn-xs btn-primary"><i class="fa fa-download"></i> Download Fiducia File</a></p>
                        @endif
                        {!! Form::file('file_fiducia', ['accept'=>'.pdf']) !!}
                        <p style="font-style: italic;">Ext. File : <b>.pdf</b></p>
                        @if($errors->has('file_fiducia'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('file_fiducia') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    <div class="box-footer">
        {!! Form::submit('Save', ['class'=>'btn btn-primary pull-right']) !!}
    </div>
    {!! Form::close() !!}
</div>
@endsection