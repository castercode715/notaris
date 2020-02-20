@extends('base.main')
@section('title') Gallery @endsection
@section('page_icon') <i class="fa fa-picture-o"></i> @endsection
@section('page_title') Create Gallery @endsection
@section('page_subtitle') list @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <a href="{{ route('gallery.index') }}" class="btn btn-success" title="Manage Asset">
                <i class="fa fa-list"></i> Manage
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="box box-solid">
    {!! Form::model($model, [
        'route' => 'gallery.store',
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
            	<div class="col-md-4">
            		<div class="form-group">
                        <label for="flag" class="control-label">Flag*</label>
                        {!! Form::select('flag', [''=>'- Select -','I'=>'Image','V'=>'iFrame'], null, ['class'=>'form-control flag', 'id'=>'flag']) !!}

                        @if($errors->has('flag'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('flag') }}
                            </span>
                        @endif
                    </div>
            	</div>
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
            </div>

            <hr>

            <div class="row">
            	<div class="col-md-6">
            		<label for="featured_img" class="control-label">Featured Image*</label>
            		<input type="file" name="featured_img" id="featured_img" accept="image/x-png, image/jpeg, image/jpg" />
            		<p style="font-style: italic;">File type: .jpg, .jpeg, .png</p>
            		@if($errors->has('featured_img'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('featured_img') }}
                        </span>
                    @endif
            	</div>
            	<div class="col-md-6">
            		<label for="featured_iframe" class="control-label">Featured iFrame*</label>
            		<textarea class="form-control" rows="2" name="featured_iframe" id="featured_iframe"></textarea>
            		@if($errors->has('featured_iframe'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('featured_iframe') }}
                        </span>
                    @endif
            	</div>
            </div>
            
            <hr>
            
            <div class="row">
            	<div class="col-md-6">
            		<div class="fimage_wrapper">
                        <div class="form-group fimage-form-group row">
                            <div class="col-md-10">
                                <label for="images" class="control-label">Image*</label>
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
            	<div class="col-md-6">
            		<div class="fiframe_wrapper">
                        <div class="form-group fiframe-form-group row">
                            <div class="col-md-10">
                                <label for="iframes" class="control-label">iFrame*</label>
                                <textarea class="form-control iframe" rows="2" name="iframes[]"></textarea>
                            </div>
                            <div class="col-md-2">
                                <a href="javascript:void(0);" class="add_iframe_button btn btn-sm btn-success" title="Add Field" style="margin-top: 25px;"><i class="fa fa-plus"></i></a>
                            </div>
                        </div>
                    </div>
                    @if($errors->has('iframes'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('iframes') }}
                        </span>
                    @endif
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

@push('scripts')
<script>
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

$('.flag').change(function(){
	if($(this).val() == 'I')
		iframeDisabled();
	else if($(this).val() == 'V')
		imageDisabled()
	else {
		document.getElementById("featured_img").disabled = false;
		document.getElementById("featured_iframe").disabled = false;
		$('.img').prop('disabled', false);
		$('.iframe').prop('disabled', false);
		$('.remove_fimage_button').removeAttr('disabled');
		$('.remove_fiframe_button').removeAttr('disabled');
		$('.add_img_button').removeAttr('disabled');
		$('.add_iframe_button').removeAttr('disabled');
	}
});

function imageDisabled()
{
	// image disabled
	document.getElementById("featured_img").disabled = true;
	$('.img').prop('disabled', true);
	$('.add_img_button').attr('disabled', 'disabled');
	$('.remove_fimage_button').attr('disabled', 'disabled');
	// iframe enable
	document.getElementById("featured_iframe").disabled = false;
	$('.iframe').prop('disabled', false);
	$('.add_iframe_button').removeAttr('disabled');
	$('.remove_fiframe_button').removeAttr('disabled');
}

function iframeDisabled()
{
	// iframe disabled
	document.getElementById("featured_iframe").disabled = true;
	$('.iframe').prop('disabled', true);
	$('.add_iframe_button').attr('disabled', 'disabled');
	$('.remove_fiframe_button').attr('disabled', 'disabled');
	// image enable
	document.getElementById("featured_img").disabled = false;
	$('.img').prop('disabled', false);
	$('.add_img_button').removeAttr('disabled');
	$('.remove_fimage_button').removeAttr('disabled');
}
</script>
@endpush