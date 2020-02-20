@php
    $method = $model->exists ? 'PUT' : 'POST';
@endphp
{!! Form::model($model, [
    'route' => $model->exists ? ['tag-news.update', $model->id] : 'tag-news.store',
    'method'=> $method,
]) !!}

    <div class="form-group">
        <label for="name" class="control-label">Name</label>
        {!! Form::text('name', null, ['class'=>'form-control', 'id'=>'name']) !!}
    </div>

    

{!! Form::close() !!}