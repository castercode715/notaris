@php
    $method = $model->exists ? 'PUT' : 'POST';
@endphp
{!! Form::model($model, [
    'route' => $model->exists ? ['internet-banking.update', $model->id] : 'internet-banking.store',
    'method'=> $method,
]) !!}
    {{-- {{ $model->exists }}
    {{ $method }} --}}
    {!! Form::hidden('investor_id', null, []) !!}
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="bank_id" class="control-label">Bank Name</label>
                {!! Form::select('bank_id', [''=>'- Select -']+$banklist, null, ['class'=>'form-control', 'id'=>'bank_id']) !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="account_holder_name" class="control-label">Account Name</label>
                {!! Form::text('account_holder_name', null, ['class'=>'form-control', 'id'=>'account_holder_name']) !!}
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="account_number" class="control-label">Account Number</label>
                {!! Form::text('account_number', null, ['class'=>'form-control', 'id'=>'account_number']) !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="payment_methode" class="control-label">Payment Methode</label>
                {!! Form::select('payment_methode', [''=>'- Select -']+$payment_methode, null, ['class'=>'form-control', 'id'=>'payment_methode']) !!}
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="validity_period" class="control-label">Validity Period</label>
                {!! Form::text('validity_period', null, ['class'=>'form-control', 'id'=>'validity_period']) !!}
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="ccv" class="control-label">CCV</label>
                {!! Form::text('ccv', null, ['class'=>'form-control', 'id'=>'ccv']) !!}
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="active" class="control-label">Active</label>
                <div>
                    {!! Form::checkbox('active', null, !$model->exists ? true: null, ['id'=>'active']) !!} 
                </div>
            </div>
        </div>
    </div>
    


{!! Form::close() !!}