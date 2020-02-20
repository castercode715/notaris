@php
    $method = $model->exists ? 'PUT' : 'POST';
@endphp
{!! Form::model($model, [
    'route' => $model->exists ? ['countries.update', $model->id] : 'countries.store',
    'method'=> $method,
]) !!}

	<div class="row">
		<div class="col-sm-3">
			<div class="form-group">
		        <label for="id" class="control-label">ID</label>
		        @if($model->exists)
		        	{!! Form::text('id', null, ['class'=>'form-control', 'id'=>'id', 'readonly']) !!}
		        @else
		        	{!! Form::text('id', null, ['class'=>'form-control', 'id'=>'id']) !!}
		        @endif
		    </div>
		</div>
		<div class="col-sm-3">
		    <div class="form-group">
		        <label for="language_code" class="control-label">Language</label>
		        {!! Form::select('language_code', [''=>'- Select -']+$language, null, ['class'=>'form-control', 'id'=>'language_code']) !!}
		    </div>
		</div>
		<div class="col-sm-3">
		    <div class="form-group">
		        <label for="currency_code" class="control-label">Currency</label>
		        {!! Form::select('currency_code', [''=>'- Select -']+$currency, null, ['class'=>'form-control', 'id'=>'currency_code']) !!}
		    </div>
		</div>
		<div class="col-sm-3">
		    <div class="form-group">
		        <label for="name" class="control-label">Name</label>
		        {!! Form::text('name', null, ['class'=>'form-control', 'id'=>'name']) !!}
		    </div>
		</div>
	</div>


{!! Form::close() !!}