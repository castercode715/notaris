@php
    $method = $model->exists ? 'PUT' : 'POST';
@endphp

{!! Form::model($model, [
    'route' => $model->exists ? ['asset-category.update', $model->id] : 'asset-category.store',
    'method'=> $method,
]) !!}

	<div class="form-group">
        <label for="desc" class="control-label">Name</label>
        {!! Form::text('desc', null, ['class'=>'form-control', 'id'=>'desc']) !!}
    </div>

    <div class="form-group">
        <label for="active" class="control-label">Active</label>
        <div>
            {!! Form::checkbox('active', null, null, ['id'=>'active']) !!} 
        </div>
    </div>

{!! Form::close() !!}