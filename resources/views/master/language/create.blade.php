@php
    $method = $model->exists ? 'PUT' : 'POST';
@endphp
{!! Form::model($model, [
    'route' => $model->exists ? ['language.update', $model->code ] : 'language.store',
    'method'=> $method,
]) !!}

    <div class="form-group">
        <label for="name" class="control-label">Code</label>
        {!! Form::text('code', null, ['class'=>'form-control', 'id'=>'code']) !!}
    </div>
    <div class="form-group">
        <label for="name" class="control-label">Language</label>
        {!! Form::text('language', null, ['class'=>'form-control', 'id'=>'language']) !!}
    </div>

{!! Form::close() !!}