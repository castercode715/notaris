@extends('base.main')
@section('title') Banner @endsection
@section('page_icon') <i class="fa fa-image"></i> @endsection
@section('page_title') Create Banner @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <a href="{{ route('banner.index') }}" class="btn btn-success" title="Manage">
                <i class="fa fa-list"></i> Manage
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="box box-solid">
	{!! Form::model($model, [
	    'route' => 'banner.store',
	    'method'=> 'post',
	    'enctype'	=> 'multipart/form-data'
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
				<div class="col-md-6">
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
	        	<div class="col-md-6">
			        <div class="form-group">
						<label for="link" class="control-label">Link*</label>
						{!! Form::text('link', null, ['class'=>'form-control', 'id'=>'link']) !!}

					    @if($errors->has('link'))
					    	<span class="invalid-feedback" role="alert">
					    		{{ $errors->first('link') }}
					    	</span>
					    @endif
			        </div>
	        	</div>
	        </div>
			
			<div class="form-group">
	            <label for="description" class="control-label">Description*</label>
	            {!! Form::textarea('description', null, ['class'=>'form-control', 'id'=>'editor1', 'rows'=>4]) !!}

	            @if($errors->has('description'))
	                <span class="invalid-feedback" role="alert">
	                    {{ $errors->first('description') }}
	                </span>
	            @endif
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

	        <hr>

    		<div class="position_wrapper">
    			<div class="form-group row">
    				<div class="col-md-5">
						<label for="position_id" class="control-label">Position*</label>
    					{!! Form::select('position_id[]', [''=>'- Select -']+$position, null, ['class'=>'form-control', 'required'=>'required']) !!}
    				</div>
    				<div class="col-md-3">
						<label for="order" class="control-label">Order*</label>
    					{!! Form::text('order[]', null, ['class'=>'form-control', 'required'=>'required']) !!}
    				</div>
    				<div class="col-md-2">
    					<a href="javascript:void(0);" class="add_position_button btn btn-sm btn-success" title="Add Field" style="margin-top: 25px;"><i class="fa fa-plus"></i></a>
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
	<div class="box-footer">
		{!! Form::submit('Create', ['class'=>'btn btn-primary pull-right']) !!}
	</div>
	{!! Form::close() !!}
</div>
@endsection

@push('scripts')
<script>
var maxField = 10,
    addButton = $('.add_position_button'),
    wrapper = $('.position_wrapper'),
    x = 0;

$(addButton).click(function(){
	if(x < maxField){
		x++;
		$(wrapper).append('<div class="form-group row">'
			+'<div class="col-md-5">'
				+'<select name="position_id[]" class="form-control select2 pos_'+x+'" required></select>'
			+'</div>'
			+'<div class="col-md-3">'
				+'<input type="text" name="order[]" class="form-control" required />'
			+'</div>'
			+'<div class="col-md-2">'
				+'<a href="javascript:void(0);" class="remove_button btn btn-sm btn-danger" title="Remove"><i class="fa fa-close"></i></a>'
			+'</div>'
		+'</div>');

		var token = $('input[name="_token"]').val();

        $.ajax({
            url : "{{ route('banner.get-position') }}",
            method : 'post',
            data : {
                _token : token
            },
            success : function(result){
                $('.pos_'+x).html(result);
            }
        });
	}
});

$(wrapper).on('click', '.remove_button', function(e){
    e.preventDefault();
    $(this).closest('.form-group').remove();
    // $(this).parent('div').remove(); //Remove field html
    // document.getElementsByClassName("form-group").remove();
    x--; //Decrement field counter
});

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