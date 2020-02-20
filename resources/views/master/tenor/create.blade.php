@php
    $method = $model->exists ? 'PUT' : 'POST';
@endphp
{!! Form::model($model, [
    'route' => $model->exists ? ['product-tenor.update', $model->id] : 'product-tenor.store',
    'method'=> $method,
]) !!}
	
	<div class="form-group">
        <label for="name" class="control-label">Tenor</label>
        {!! Form::text('tenor', null, ['class'=>'form-control', 'id'=>'tenor']) !!}
  </div>

  <div class="form-group">
        <label for="name" class="control-label">Bunga</label>
        {!! Form::text('bunga', null, ['class'=>'form-control', 'id'=>'bunga']) !!}
  </div>
    
{!! Form::close() !!}