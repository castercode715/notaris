@php
    $method = $model->exists ? 'PUT' : 'POST';
@endphp
{!! Form::model($model, [
    'route' => $model->exists ? ['notaris.update', $model->id] : 'notaris.store',
    'method'=> $method,
]) !!}

	<div class="row">
		
		<div class="col-sm-12">
		    <div class="form-group">
		        <label for="name" class="control-label">Notaris Name*</label>
		        {!! Form::text('name', null, ['class'=>'form-control', 'id'=>'name']) !!}
		    </div>
		</div>

		<div class="col-sm-12">
		    <div class="form-group">
		        <label for="name" class="control-label">Information</label>
		        {!! Form::textarea('information', null, ['class'=>'form-control', 'id'=>'information', 'rows' => '5']) !!}
		    </div>
		</div>
		
	</div>

{!! Form::close() !!}