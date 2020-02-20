@php
    $method = $model->exists ? 'PUT' : 'POST';
@endphp
{!! Form::model($model, [
    'route' => $model->exists ? ['position.update', $model->id] : 'position.store',
    'method'=> $method,
]) !!}

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="description" class="control-label">Name*</label>
            {!! Form::text('description', null, ['class'=>'form-control', 'id'=>'description']) !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="position_code" class="control-label">Code*</label>
            {!! Form::text('position_code', null, ['class'=>'form-control', 'id'=>'position_code']) !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="page_id" class="control-label">Page*</label>
            {!! Form::select('page_id', [''=>'- Select -']+$page, null, ['class'=>'form-control', 'id'=>'page_id']) !!}
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