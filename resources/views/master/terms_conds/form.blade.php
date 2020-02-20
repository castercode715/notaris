@php
    $method = $model->exists ? 'PUT' : 'POST';
@endphp
{!! Form::model($model, [
    'route' => $model->exists ? ['terms-conds.update', $model->id] : 'terms-conds.store',
    'method'=> $method,
]) !!}

    <div class="form-group">
        <label for="title" class="control-label">Title</label>
        {!! Form::text('title', null, ['class'=>'form-control', 'id'=>'title']) !!}
    </div>

    <div class="form-group">
        <label for="desc" class="control-label">Description</label>
        {!! Form::textarea('desc', null, ['class'=>'form-control textarea', 'id'=>'desc', 'rows'=>6]) !!}
    </div>

    <div class="form-group">
        <label for="active" class="control-label">Active</label>
        <div>
            {!! Form::checkbox('active', null, null, ['id'=>'active']) !!} 
        </div>
    </div>

{!! Form::close() !!}