@php
    $method = $model->exists ? 'PUT' : 'POST';
@endphp
{!! Form::model($model, [
    'route' => $model->exists ? ['atribute-product.update', $model->id] : 'atribute-product.store',
    'method'=> $method,
]) !!}
	
	<div class="form-group">
        <label for="name" class="control-label">Attribute Name</label>
        {!! Form::text('name', null, ['class'=>'form-control', 'id'=>'name']) !!}
  </div>
  <div class="form-group">
        <label for="active" class="control-label">Active</label>
        <div>
            {!! Form::checkbox('active', null, null, ['id'=>'active']) !!} 
        </div>
        @if($errors->has('active'))
          <span class="invalid-feedback" role="alert">
            {{ $errors->first('active') }}
          </span>
        @endif
    </div> 

    
{!! Form::close() !!}