@php
    $method = $model->exists ? 'PUT' : 'POST';
@endphp
{!! Form::model($model, [
    'route' => $model->exists ? ['category.update', $model->id] : 'category.store',
    'method'=> $method,
]) !!}

	<div class="row">
		
		<div class="col-sm-12">
		    <div class="form-group">
		        <label for="name" class="control-label">Category Name*</label>
		        {!! Form::text('name', null, ['class'=>'form-control', 'id'=>'name']) !!}
		    </div>
		</div>

	</div>

{!! Form::close() !!}