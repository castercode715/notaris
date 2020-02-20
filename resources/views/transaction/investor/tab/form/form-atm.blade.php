@php
    $method = $model->exists ? 'PUT' : 'POST';
@endphp
{!! Form::model($model, [
    'route' => $model->exists ? ['atm.update', $model->id] : 'atm.store',
    'method'=> $method,
]) !!}

	{!! Form::hidden('investor_id', null, []) !!}
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="bank_id" class="control-label">Bank Name*</label>
                {!! Form::select('bank_id', [''=>'- Select -']+$banklist, null, ['class'=>'form-control', 'id'=>'bank_id']) !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="account_holder_name" class="control-label">Account Name*</label>
                {!! Form::text('account_holder_name', null, ['class'=>'form-control', 'id'=>'account_holder_name']) !!}
            </div>
        </div>
    </div>

    @foreach($payment_methode as $payment)
        <input type="hidden" name="payment_methode" value="{{ $payment }}">
    @endforeach
    
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="account_number" class="control-label">Account Number*</label>
                {!! Form::text('account_number', null, ['class'=>'form-control', 'id'=>'account_number']) !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="active" class="control-label">Active*</label>
                {!! Form::select('active', [1=>'Active', 0=>'Inactive'], null, ['class'=>'form-control']) !!}
            </div>
        </div>
    </div>

{!! Form::close() !!}