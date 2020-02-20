@php
    $method = $model->exists ? 'PUT' : 'POST';
@endphp
{!! Form::model($model, [
    'route' => $model->exists ? ['class.update', $model->id] : 'class.store',
    'method'=> $method,
]) !!}

    <div class="form-group">
        <label for="name" class="control-label">Nama Class</label>
        {!! Form::text('name', null, ['class'=>'form-control', 'id'=>'name']) !!}
    </div>

    <div class="form-group">
        <label for="active" class="control-label">Active</label>
        <div>
            {!! Form::checkbox('active', null, null, ['id'=>'active']) !!} 
        </div>
    </div>

{!! Form::close() !!}