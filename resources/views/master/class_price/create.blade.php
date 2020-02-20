@php
    $method = $model->exists ? 'PUT' : 'POST';
@endphp
{!! Form::model($model, [
    'route' => $model->exists ? ['class-price.update', $model->id] : 'class-price.store',
    'method'=> $method,
]) !!}
	<div class="form-group">
        <label for="page_id" class="control-label">Class Name </label>
        {!! Form::select('class_id', [''=>'- Select -']+$page, null, ['class'=>'form-control', 'id'=>'class_id']) !!}
    </div>
	<div class="form-group">
        <label for="name" class="control-label">Price Start</label>
        {!! Form::text('price_start', null, ['class'=>'form-control', 'id'=>'price_start']) !!}
    </div>
    <div class="form-group">
        <label for="name" class="control-label">Price End</label>
        {!! Form::text('price_end', null, ['class'=>'form-control', 'id'=>'price_end']) !!}
    </div>
     <div class="form-group">
        <label for="name" class="control-label">Interest</label>
        {!! Form::text('interest', null, ['class'=>'form-control', 'id'=>'interest']) !!}
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