@php
    $method = $model->exists ? 'PUT' : 'POST';
@endphp
{!! Form::model($model, [
    'route' => $model->exists ? ['document.update', $model->id] : 'document.store',
    'method'=> $method,
]) !!}

	<div class="row">
		
		<div class="col-sm-9">
		    <div class="form-group">
		        <label for="name" class="control-label">Document Name*</label>
		        {!! Form::text('name', null, ['class'=>'form-control', 'id'=>'name']) !!}
		    </div>
		</div>

		<div class="col-sm-3">
		    <div class="form-group">
		        <label for="name" class="control-label">Sort*</label>
		        {!! Form::number('sort', null, ['class'=>'form-control', 'id'=>'sort']) !!}
		    </div>
		</div>
		
	</div>

{!! Form::close() !!}