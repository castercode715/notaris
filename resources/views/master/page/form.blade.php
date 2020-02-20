@php
    $method = $model->exists ? 'PUT' : 'POST';
@endphp
{!! Form::model($model, [
    'route' => $model->exists ? ['page.update', $model->id] : 'page.store',
    'method'=> $method,
]) !!}

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="description" class="control-label">Name</label>
            {!! Form::text('description', null, ['class'=>'form-control', 'id'=>'description']) !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="active" class="control-label">Active*</label>
            {!! Form::select('active', [1=>'Active',0=>'Inactive'], null, ['class'=>'form-control', 'id'=>'active']) !!}
        </div>
    </div>
</div>

{!! Form::close() !!}