@extends('base.main')
@section('title') gallery @endsection
@section('page_icon') <i class="fa fa-picture-o"></i> @endsection
@section('page_title') Edit Gallery {{ $model->title }} @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <a href="{{ route('gallery.show', base64_encode($model->id)) }}" class="btn btn-success" title="Detail">
                <i class="fa fa-search"></i> Detail
            </a>
            <a href="{{ route('gallery.delete', base64_encode($model->id)) }}" class="btn btn-danger btn-delete2" title="Delete">
                <i class="fa fa-trash"></i> Delete
            </a>
            <a href="{{ route('gallery.create') }}" class="btn btn-success" title="Create">
                <i class="fa fa-plus"></i> Create
            </a>
            <a href="{{ route('gallery.index') }}" class="btn btn-success" title="Manage">
                <i class="fa fa-list"></i> Manage
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="box box-solid">
    {!! Form::model($model, [
        'route' => ['gallery.update', base64_encode($model->id)],
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

            {!! Form::hidden('flag', null, []) !!}

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="sort" class="control-label">Sort*</label>
                        {!! Form::text('sort', null, ['class'=>'form-control', 'id'=>'sort', 'required'=>'required']) !!}

                        @if($errors->has('sort'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('sort') }}
                            </span>
                        @endif
                    </div>
                </div>
                @if( $model->isComplete() )
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="active" class="control-label">Active*</label>
                        {!! Form::select('active', [1=>'Active', 0=>'Inactive'], null, ['class'=>'form-control', 'id'=>'active', 'required'=>'required']) !!}

                        @if($errors->has('active'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('active') }}
                            </span>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <hr>

            @if( $model->flag == 'I' )
                <div class="row">
                    <div class="col-md-4">
                        <label for="featured_img" class="control-label">Featured Image</label>
                        <input type="file" name="featured_img" id="featured_img" accept="image/x-png, image/jpeg, image/jpg" />
                        <p style="font-style: italic;">File type: .jpg, .jpeg, .png</p>
                        @if($errors->has('featured_img'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('featured_img') }}
                            </span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <img src="/images/gallery/{{ $featured->photo }}" class="img-responsive">
                    </div>
                </div>
                
                <hr>

                <div class="row">
                    <div class="col-md-4">
                        <div class="fimage_wrapper">
                            <div class="form-group fimage-form-group row">
                                <div class="col-md-10">
                                    <label for="images" class="control-label">Image</label>
                                    <input type="file" name="images[]" class="img" accept="image/x-png, image/jpeg, image/jpg" />
                                </div>
                                <div class="col-md-2">
                                    <a href="javascript:void(0);" class="add_img_button btn btn-sm btn-success" title="Add Field" style="margin-top: 25px;"><i class="fa fa-plus"></i></a>
                                </div>
                            </div>
                        </div>
                        <p style="font-style: italic;">File type: .jpg, .jpeg, .png</p>
                        @if($errors->has('images'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('images') }}
                            </span>
                        @endif
                    </div>
                    <div class="col-md-8 row">
                        @foreach($images as $a)
                        <div class="col-sm-4">
                            <div class="img-wrap">
                                <input type="hidden" name="images_id[]" value="{{ $a->id }}" />
                                <img src="/images/gallery/{{ $a->photo }}" class="" />
                                <button class="btn btn-xs btn-danger img-remove"><i class="fa fa-close"></i></button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @if($errors->has('images.*'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('images.*') }}
                        </span>
                    @endif
                </div>
            @else
                <div class="row">
                    <div class="col-md-4">
                        <label for="featured_iframe" class="control-label">iFrame</label>
                        <textarea class="form-control" rows="4" name="featured_iframe"></textarea>
                    </div>
                    <div class="col-md-6">
                        <center>{!! $featured->iframe !!}</center>
                    </div>
                </div>
                
                <hr>

                <div class="row">
                    <div class="col-md-4">
                        <label for="images" class="control-label">Image</label>
                        <p>
                            <a href="javascript:void(0);" class="add_iframe_button btn btn-sm btn-success" title="Add Field"><i class="fa fa-plus"></i></a>
                        </p>
                        <div class="fiframe_wrapper">
                        </div>
                        @if($errors->has('iframes.*'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('iframes.*') }}
                            </span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <div class="box-body">
                            <ul>
                            @php $no = 1; @endphp
                            @foreach($images as $a)
                                <li class="iframe-wrap" style="margin-bottom: 10px;">
                                    <a href="{{ route('gallery.iframe', base64_encode($a->id) ) }}" class="btn-show" title="Show Video">Show Video {{ $no }}</a>
                                    <input type="hidden" name="iframes_id[]" value="{{ $a->id }}" />
                                    <button class="btn btn-xs btn-danger iframe-remove" style="margin-left: 15px;"><i class="fa fa-close"></i></button>
                                </li>
                                @php $no++; @endphp
                            @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
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
    $('.img-remove').click(function(e){
        e.preventDefault();
        var ithis = $(this);
        ithis.closest('.col-sm-4').remove();
    });

    $('.iframe-remove').click(function(e){
        e.preventDefault();
        var ithis = $(this);
        ithis.closest('.iframe-wrap').remove();
    });

    var maxField = 10,
        addImgButton = $('.add_img_button'),
        addIFrameButton = $('.add_iframe_button'),
        imgWrapper = $('.fimage_wrapper'),
        iframeWrapper = $('.fiframe_wrapper'),
        x = 0,
        y = 0;

    $(addImgButton).click(function(){
        if(x < maxField){
            x++;
            $(imgWrapper).append('<div class="form-group fimage-form-group row"><div class="col-md-10"><input type="file" name="images[]" class="img" accept="image/x-png, image/jpeg, image/jpg" /></div><div class="col-md-2"><a href="javascript:void(0);" class="remove_fimage_button btn btn-sm btn-danger" title="Remove"><i class="fa fa-close"></i></a></div></div>');
        }
    });

    $(imgWrapper).on('click', '.remove_fimage_button', function(e){
        e.preventDefault();
        $(this).closest('.fimage-form-group').remove();
        x--; 
    });

    $(addIFrameButton).click(function(){
        if(y < maxField){
            y++;
            $(iframeWrapper).append('<div class="form-group fiframe-form-group row"><div class="col-md-10"><textarea class="form-control iframe" rows="2" name="iframes[]"></textarea></div><div class="col-md-2"><a href="javascript:void(0);" class="remove_fiframe_button btn btn-sm btn-danger" title="Remove"><i class="fa fa-close"></i></a></div></div>');
        }
    });

    $(iframeWrapper).on('click', '.remove_fiframe_button', function(e){
        e.preventDefault();
        $(this).closest('.fiframe-form-group').remove();
        y--; 
    });
</script>
@endpush

@push('css')
<style>
.img-gallery-wrapper {
    /*border: 1px solid #d2d6de;*/
    /*background: #f1f1f1;*/
    margin-bottom: 10px;
}   
.img-wrap {
    width: 100%;
    position: relative;
    margin: 0px 5px 5px 0px;
    float: left;
    background-color: #fafafa;
    border-radius: 4px;
    border: 1px solid #e6e4e4;
    padding: 3px;
}
.featured {
    background-color: #f39c12;
}
.img-wrap img {
    width: 80%;
    height: 130px;
    margin-right: 10px;
}
.hide {
    display: none;
}
</style>
@endpush