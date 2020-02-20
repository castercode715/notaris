@php
    $method = $model->exists ? 'PUT' : 'POST';
@endphp
{!! Form::model($model, [
    'route' => $model->exists ? ['asset-attribute.update', $model->id] : 'asset-attribute.store',
    'method'=> $method,
]) !!}

    <div class="form-group">
        <label for="name" class="control-label">Name</label>
        {!! Form::text('name', null, ['class'=>'form-control', 'id'=>'name']) !!}
    </div>

    <div class="form-group">
        <label for="category_asset_id" class="control-label">Category</label>
        {!! Form::select('category_asset_id', [''=>'- Select -']+$category, null, ['class'=>'form-control', 'id'=>'category_asset_id']) !!}
    </div>

    <div class="form-group">
        <label for="active" class="control-label">Active</label>
        <div>
            {!! Form::checkbox('active', null, null, ['id'=>'active']) !!} 
        </div>
    </div>

{!! Form::close() !!}