@php
    $method = $model->exists ? 'PUT' : 'POST';
@endphp
{!! Form::model($model, [
    'route' => $model->exists ? ['role.update', $model->id] : 'role.store',
    'method'=> $method,
]) !!}

    <div class="form-group">
        <label for="code" class="control-label">Code</label>
        {!! Form::text('code', null, ['class'=>'form-control', 'id'=>'code', 'maxlength'=>5]) !!}
    </div>
    <div class="form-group">
        <label for="role" class="control-label">Role</label>
        {!! Form::text('role', null, ['class'=>'form-control', 'id'=>'role']) !!}
    </div>

    <div class="form-group">
        <label for="active" class="control-label">Active</label>
        <div>
            {!! Form::checkbox('active', null, null, ['id'=>'active']) !!} 
        </div>
    </div>

{!! Form::close() !!}