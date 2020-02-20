@extends('base.main')
@section('title') News @endsection
@section('page_icon') <i class="fa fa-newspaper-o"></i> @endsection
@section('page_title') Edit News Description <i>({{ $language->language }})</i> @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <a href="{{ route('news.show', base64_encode($model->news_id)) }}" class="btn btn-success" title="Detail news">
                <i class="fa fa-search"></i> Detail
            </a>
            <a href="{{ route('news.index') }}" class="btn btn-success" title="Manage news">
                <i class="fa fa-list"></i> Manage
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="box box-solid">
    {!! Form::model($model, [
        'route' => ['news.update-new', base64_encode($model->id) ],
        'method'=> 'put',
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
                        <label for="description" class="control-label">Description*</label>
                        {!! Form::textarea('description', $model->description, ['class'=>'form-control', 'id'=>'editor1', 'rows'=>6, 'required'=>'required']) !!}

                        @if($errors->has('description'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('description') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-8">
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
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="image" class="control-label">Image</label>
                        @if($model->image)
                        <p class="img-wrap">
                            <button class="btn btn-xs btn-danger img-close" data-key="1"><i class="fa fa-close"></i> Remove Image</button>
                            <img src="/images/news/{{ $model->image }}" class="img-responsive">
                        </p>
                        <input type="hidden" name="img" id="img" value="{{ $model->image }}" />
                        @endif
                        {!! Form::file('image', ['accept'=>'image/x-png, image/jpeg, image/jpg']) !!}
                        <p style="font-style: italic;">File Ext. : <b>.jpg</b>, <b>.jpeg</b>, <b>.png</b></p>
                        @if($errors->has('image'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('image') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

        <div class="box-footer">
        {!! Form::submit('Save', ['class'=>'btn btn-primary pull-right']) !!}
    </div>
    {!! Form::close() !!}
</div>
@endsection

@push('scripts')
<script>
    $('.img-close').click(function(e){
        var ithis = $(this);
        ithis.closest('.img-wrap').remove();
        $('#img').val('');
    });
    /*$('.img-close').click(function(e){
        e.preventDefault();
        var key = $(this).data('key'),
            token = $('input[name="_token"]').val();
        var ithis = $(this);
        
        swal({
            title : 'Are you sure ?',
            type : 'warning',
            showCancelButton : true,
            confirmButtonColor : '#3085d6',
            cancelButtonColor : '#d33',
            confirmButtonText : 'Yes, delete!'
        }).then((result)=>{
            if(result.value){

                $.ajax({
                    url : "{{ route('help.remove-image') }}",
                    method : 'post',
                    data : {
                        value : key,
                        _token : token
                    },
                    success : function(r){
                        status = true;

                        ithis.closest('.img-wrap').remove();

                        swal({
                            type : 'success',
                            title : 'Success',
                            text : 'Deleted'
                        });
                    },
                    error : function(er){
                        swal({
                            type : 'error',
                            title : 'Failed',
                            text : 'Failed'
                        });
                    }
                });
            }
        });

    });*/
</script>
@endpush