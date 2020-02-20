@extends('base.main')
@section('title') Banner @endsection
@section('page_icon') <i class="fa fa-drivers-license"></i> @endsection
@section('page_title') Add Banner Description <i>({{ $language->language }})</i> @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
        	<a href="{{ route('banner.show', base64_encode($model->banner_id)) }}" class="btn btn-success" title="Detail banner">
                <i class="fa fa-search"></i> Detail
            </a>
            <a href="{{ route('banner.index') }}" class="btn btn-success" title="Manage Banner">
                <i class="fa fa-list"></i> Manage
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="box box-solid">
    {!! Form::model($model, [
        'route' => 'banner.store-new',
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

            {!! Form::hidden('banner_id', $id, []) !!}

            <p class="note"><i>Fields with <span class="required">*</span> are required.</i></p>

            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="code" class="control-label">Language</label>
                        <input type="text" name="bahasa" value="{{ $language->language }}" disabled="disabled" class="form-control">
                        {!! Form::hidden('code', $language->code, []) !!}
                    </div>
                </div>
                <div class="col-md-10">
                    <div class="form-group">
                        <label for="title" class="control-label">Title*</label>
                        {!! Form::text('title', null, ['class'=>'form-control', 'id'=>'title']) !!}

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
                        {!! Form::text('sub_title', null, ['class'=>'form-control', 'id'=>'sub_title']) !!}

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
                        <label for="description" class="control-label">Description*</label>
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
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="flag" class="control-label">Type*</label>
                        {!! Form::select('flag', [''=>'- Select -', 'I'=>'Image', 'V'=>'Video'], null, ['class'=>'form-control flag']) !!}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="image" class="control-label">Image</label>
                        {!! Form::file('image', ['accept'=>'image/x-png, image/jpeg, image/jpg', 'id'=>'images']) !!}

                        @if($errors->has('image'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('image') }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="iframe" class="control-label">iFrame</label>
                        {!! Form::textarea('iframe', null, ['class'=>'form-control', 'id'=>'iframe', 'rows'=>2]) !!}

                        @if($errors->has('iframe'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('iframe') }}
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

@push('scripts')
<script>
document.getElementById("images").disabled = true;
document.getElementById("iframe").disabled = true;

$('.flag').change(function(){
    if($(this).val() == 'I')
        iframeDisabled();
    else if($(this).val() == 'V')
        imageDisabled()
    else {
        document.getElementById("images").disabled = true;
        document.getElementById("iframe").disabled = true;
    }
});

function imageDisabled()
{
    // image disabled
    document.getElementById("images").disabled = true;
    // iframe enable
    document.getElementById("iframe").disabled = false;
}

function iframeDisabled()
{
    // iframe disabled
    document.getElementById("iframe").disabled = true;
    // image enable
    document.getElementById("images").disabled = false;
}
</script>
@endpush