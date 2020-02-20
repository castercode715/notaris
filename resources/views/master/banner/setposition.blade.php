@extends('base.main')
@section('title') Banner @endsection
@section('page_icon') <i class="fa fa-image"></i> @endsection
@section('page_title') Set Banner Position @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <a href="{{ route('banner.show', $model->id) }}" class="btn btn-success" title="Detail">
                <i class="fa fa-search"></i> Detail
            </a>
            <a href="{{ route('banner.edit', $model->id) }}" class="btn btn-success" title="Edit Banner">
                <i class="fa fa-edit"></i> Update
            </a>
            <a href="{{ route('banner.delete', $model->id) }}" class="btn btn-danger btn-delete2" title="Delete">
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
	<div class="box-body">
		{!! Form::open(['method'=> 'post', 'route'=>'banner.set', 'id'=>'setposition-form']) !!}
			{!! Form::hidden('banner',$model->id,['id'=>'banner']) !!}
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<label for="page" class="control-label">Page*</label>
					{!! Form::select('page', [''=>'- Select -']+$page, null, ['class'=>'form-control dynamic', 'id'=>'page']) !!}

					@if($errors->has('page'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('page') }}
                        </span>
                    @endif
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label for="position" class="control-label">Position*</label>
					<select name="position" id="position" class="form-control">
                        <option value="">- Select -</option>
                    </select>

                    @if($errors->has('position'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('position') }}
                        </span>
                    @endif
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label for="order" class="control-label">Order</label>
					{!! Form::text('order', null, ['class'=>'form-control', 'id'=>'order']) !!}

					@if($errors->has('order'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('order') }}
                        </span>
                    @endif
				</div>
			</div>
			<div class="col-md-3">
				{!! Form::submit('Add', ['class'=>'btn btn-primary btn-add','style'=>'margin-top:25px;']) !!}
			</div>
		</div>
		{!! Form::close() !!}
	</div>
	<div class="box-footer">
		<table id="datatable" class="table table-hover table-condensed">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Page</th>
                    <th>Position</th>
                    <th>Order</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
	</div>
	<div class="box-footer">
		<a href="{{ route('banner.show', $model->id) }}" class="btn btn-success pull-right"><i class="fa fa-check"></i> Finish</a>
	</div>
</div>
@endsection

@push('scripts')
<script>

    $('.dynamic').change(function(){
        if($(this).val() != ''){
            var value = $(this).val(),
                token = $('input[name="_token"]').val();

            $.ajax({
                url : "{{ route('banner.fetch') }}",
                method : 'post',
                data : {
                    value : value,
                    _token : token
                },
                success : function(result){
                    $('#position').html(result);
                }
            });
        }
    });

    $('.btn-add').click(function(e){
    	e.preventDefault();

    	var url = $('#setposition-form').attr('action');

    	$.ajax({
    		url : url,
    		method : 'post',
    		data : $('#setposition-form').serialize(),
    		success : function(response){
    			$('#setposition-form').trigger('reset');
    			$('#datatable').DataTable().ajax.reload();

	            swal({
	                type : 'success',
	                title : 'Success',
	                text : 'Berhasil'
	            });
    		},
    		error : function(e){
	            var response = e.responseJSON;
	            if($.isEmptyObject(response) == false)
	            {
	                $.each(response.errors, function(key, value) {
	                    $('#' + key)
	                        .closest('.form-group')
	                        .addClass('has-error')
	                        .append('<span class="help-block">'+ value +'</span>')
	                });

	                swal({
		                type : 'error',
		                title : 'Failed',
		                text : response.errors
		            });
	            }
	        }
    	});
    });

    $('#datatable').DataTable({
        responsive : true,
        processing : true,
        serverSide : true,
        ajax: "{{ route('table.banner.position', $model->id) }}",
        columns: [
            {data : 'DT_Row_Index', name : 'position_id'},
            {data : 'page', name : 'page'},
            {data : 'position', name : 'position'},
            {data : 'order', name : 'order'},
            {data : 'action', name : 'action'}
        ]
    });
</script>
@endpush