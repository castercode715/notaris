@extends('base.main')
@section('title') Gallery @endsection
@section('page_icon') <i class="fa fa-cube"></i> @endsection
@section('page_title') Edit Gallery Description <i>({{ $language->language }})</i> @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
        	<a href="{{ route('gallery.show', base64_encode($model->gallery_id)) }}" class="btn btn-success" title="Detail gallery">
                <i class="fa fa-search"></i> Detail
            </a>
            <a href="{{ route('gallery.index') }}" class="btn btn-success" title="Manage gallery">
                <i class="fa fa-list"></i> Manage
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="box box-solid">
    {!! Form::model($model, [
        'route' => ['gallery.update-new', base64_encode($model->id) ],
        'method'=> 'put'
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
                        <label for="asset_name" class="control-label">Language</label>
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
                    url : "{{ route('gallery.remove-image') }}",
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