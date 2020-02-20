@php
    $method = $model->exists ? 'PUT' : 'POST';
@endphp
{!! Form::model($model, [
    'route' => $model->exists ? ['menu.update', $model->id] : 'menu.store',
    'method'=> $method,
]) !!}

    <div class="form-group">
        <label for="name" class="control-label">Name</label>
        {!! Form::text('module', null, ['class'=>'form-control', 'id'=>'module']) !!}
    </div>
	 <div class="form-group">
        <label for="link" class="control-label">Link</label>
        {!! Form::text('link', null, ['class'=>'form-control', 'id'=>'link']) !!}
    </div>
	 <div class="form-group">
        <label for="icon" class="control-label">Icon</label>
        {!! Form::text('icon', null, ['class'=>'form-control', 'id'=>'icon']) !!}
    </div>
	 <div class="form-group">
        <label for="sort" class="control-label">Sort</label>
        {!! Form::text('sort', null, ['class'=>'form-control', 'id'=>'sort']) !!}
    </div>
<div class="form-group">
        <label for="page_id" class="control-label">Parent Name</label>
        {!! Form::select('parent_id', [''=>'- Select -']+$page, null, ['class'=>'form-control', 'id'=>'parent_id']) !!}
    </div>
	<div class="form-group">
		<label for="active" class="control-label">Active</label>
		<div>
			{!! Form::checkbox('active', null, null, ['id'=>'active']) !!} 
		</div>
		@if($errors->has('active'))
			<span class="invalid-feedback" role="alert">
				{{ $errors->first('active') }}
			</span>
		@endif
	</div>

    

{!! Form::close() !!}