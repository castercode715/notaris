@php $method = $model->exists ? 'PUT' : 'POST'; @endphp

{!! Form::model($model, [
    'route' => $model->exists ? ['bank.update', $model->id] : 'bank.store',
    'method'=> $method,
]) !!}

	<div class="form-group">
        <label for="name" class="control-label">Nama Class</label>
        {!! Form::text('name', null, ['class'=>'form-control', 'id'=>'name']) !!}
    </div>
	<div class="form-group">
        <label for="card_type" class="control-label">Tipe Kartu</label>
        {!! Form::select('card_type', $model->cardTypeList()) !!}
    </div>
	<div class="form-group">
        <label for="image_logo" class="control-label">Logo</label>
        {!! Form::file('image_logo') !!}
    </div>
    <div class="form-group">
        <label for="active" class="control-label">Active</label>
        <div>
            {!! Form::checkbox('active', null, null, ['id'=>'active']) !!} 
        </div>
    </div>

{!! Form::close() !!}