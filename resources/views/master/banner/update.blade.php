@extends('base.main')
@section('title') Banner @endsection
@section('page_icon') <i class="fa fa-image"></i> @endsection
@section('page_title') Edit Banner @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <a href="{{ route('banner.show', base64_encode($model->id) ) }}" class="btn btn-success" title="Detail">
                <i class="fa fa-search"></i> Detail
            </a>
            <a href="{{ route('banner.delete', base64_encode($model->id)) }}" class="btn btn-danger btn-delete2" title="Delete">
                <i class="fa fa-trash"></i> Delete
            </a>
            <a href="{{ route('banner.create') }}" class="btn btn-success" title="Create Banner">
                <i class="fa fa-plus"></i> Create
            </a>
            <a href="{{ route('banner.index') }}" class="btn btn-success" title="Manage">
                <i class="fa fa-list"></i> Manage
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="box box-solid">
	{!! Form::model($model, [
	    'route' => ['banner.update', base64_encode($model->id)],
	    'method'=> 'put',
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

			<p class="note"><i>Fields with <span class="required">*</span> are required.</i></p>

			<div class="row">
				<div class="col-md-8">
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

			<div class="form-group">
				<table class="table table-bordered table-hovered">
					<tr>
						<th>Position</th>
						<th>Order</th>
						<th>Action</th>
					</tr>
					@foreach($position as $p)
					<tr class="pos-row">
						<td>{{ $p->description }}</td>
						<td>{{ $p->order }}</td>
						<td width="80px">
							{{ Form::hidden('position[]', $p->position_id, []) }}
							<center><a href="javascript:void(0);" class="rm-pos btn btn-xs btn-danger" title="Remove"><i class="fa fa-close"></i></a></center>
						</td>
					</tr>
					@endforeach
				</table>
			</div>
			<div class="form-group">
				<a href="javascript:void(0);" class="btn btn-sm btn-success btn-add" title="Add Field"><i class="fa fa-plus"></i> Add Field</a>
			</div>
			<div class="position_wrapper"></div>

        	@if($errors->has('iframes'))
                <span class="invalid-feedback" role="alert">
                    {{ $errors->first('iframes') }}
                </span>
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
var maxField = 10,
    addButton = $('.btn-add'),
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

$('.rm-pos').click(function(e){
	e.preventDefault();

	$(this).closest('.pos-row').remove();
});	
</script>
@endpush