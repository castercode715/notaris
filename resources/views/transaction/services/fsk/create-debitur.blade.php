@php
    $method = $model->exists ? 'PUT' : 'POST';
@endphp
{!! Form::model($model, [
    'route' => $model->exists ? ['fsk-debitur.update', $model->id] : 'fsk-debitur.store',
    'method'=> $method,
]) !!}

	<div class="row">
		
		<div class="col-sm-12">
		    <div class="form-group">
		        <label for="name" class="control-label">Nama Lengkap*</label>
		        {!! Form::text('name', null, ['class'=>'form-control', 'id'=>'name']) !!}
		    </div>
		</div>

		<div class="col-sm-12">
		    <div class="form-group">
		        <label for="email" class="control-label">Email*</label>
		        {!! Form::text('email', null, ['class'=>'form-control', 'id'=>'email']) !!}
		    </div>
		</div>

		<div class="col-sm-6">
		    <div class="form-group">
		        <label for="telephone_number" class="control-label">No Telephone*</label>
		        {!! Form::number('telephone_number', null, ['class'=>'form-control', 'id'=>'telephone_number']) !!}
		    </div>
		</div>

		<div class="col-sm-6">
		    <div class="form-group">
		        <label for="handphone_number" class="control-label">No Handphone*</label>
		        {!! Form::number('handphone_number', null, ['class'=>'form-control', 'id'=>'handphone_number']) !!}
		    </div>
		</div>

		<input type="hidden" name="client_flag" value="perorangan">

	</div>

{!! Form::close() !!}