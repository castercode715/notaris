{!! Form::model($model, [
    'route' => 'order.log.save',
    'method'=> 'post',
    'enctype'   => 'multipart/form-data'
]) !!}
	<div class="callout callout-warning">
	    <p>Please fill this form if necessary!</p>
	</div>

	<input type="hidden" name="order_id" value="{{ $data_id }}">
	<input type="hidden" name="status" id="status_log">
	<input type="hidden" name="investor_id" id="investor_id">
	
	<div id="conten">
		<div class="form-group" >
	        <label for="name" class="control-label">No Resi:</label>
	       {!! Form::text('no_resi', null, ['class'=>'form-control', 'id'=>'no_resi']) !!}

	    </div>

	    <div class="form-group" >
	        <label for="name" class="control-label">Ekspedition Name:</label>
	        <input type="text" name="ex_name" id="ex_name" class="form-control" required>

	        @if($errors->has('ex_name'))
	          <span class="invalid-feedback" role="alert">
	            {{ $errors->first('ex_name') }}
	          </span>
	        @endif
	    </div>
	</div>

    <div class="form-group" >
        <label for="name" class="control-label">Note:</label>
        <textarea name="note" class="form-control" rows="5"></textarea>
    </div>
	
{!! Form::close() !!}