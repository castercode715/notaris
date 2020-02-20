@php
    $method = $model->exists ? 'PUT' : 'POST';
@endphp
{!! Form::model($model, [
    'route' => $model->exists ? ['sec-accesslevel.update', $model->id] : 'sec-accesslevel.store',
    'method'=> $method,
]) !!}
	<div class="form-group">
        <label for="page_id" class="control-label">Module </label>
        {!! Form::select('module_id', [''=>'- Select -']+$page, null, ['class'=>'form-control', 'id'=>'module_id']) !!}
    </div>
	<div class="form-group">
        <label for="page_id" class="control-label">Role </label>
        {!! Form::select('role_id', [''=>'- Select -']+$page1, null, ['class'=>'form-control', 'id'=>'role_id']) !!}
    </div>
    <div class="row">
	
        <div class="col-sm-2">
		    <label for="active" class="control-label">Create</label>
		    <div>
		        {!! Form::checkbox('create', null, null, ['id'=>'create']) !!} 
		    </div>
		    @if($errors->has('create'))
		    	<span class="invalid-feedback" role="alert">
		    		{{ $errors->first('create') }}
		    	</span>
		    @endif
		</div>
		<div class="col-sm-2">
		    <label for="active" class="control-label">Read</label>
		    <div>
		        {!! Form::checkbox('read', null, null, ['id'=>'read']) !!} 
		    </div>
		    @if($errors->has('read'))
		    	<span class="invalid-feedback" role="alert">
		    		{{ $errors->first('read') }}
		    	</span>
		    @endif
		</div>
		<div class="col-sm-2">
		    <label for="active" class="control-label">Update</label>
		    <div>
		        {!! Form::checkbox('update', null, null, ['id'=>'update']) !!} 
		    </div>
		    @if($errors->has('active'))
		    	<span class="invalid-feedback" role="alert">
		    		{{ $errors->first('update') }}
		    	</span>
		    @endif
		</div>
		<div class="col-sm-2">
		    <label for="active" class="control-label">Delete</label>
		    <div>
		        {!! Form::checkbox('delete', null, null, ['id'=>'delete']) !!} 
		    </div>
		    @if($errors->has('delete'))
		    	<span class="invalid-feedback" role="alert">
		    		{{ $errors->first('delete') }}
		    	</span>
		    @endif
		</div>
		<div class="col-sm-2">
		    <label for="active" class="control-label">View</label>
		    <div>
		        {!! Form::checkbox('view', null, null, ['id'=>'view']) !!} 
		    </div>
		    @if($errors->has('view'))
		    	<span class="invalid-feedback" role="alert">
		    		{{ $errors->first('view') }}
		    	</span>
		    @endif
		</div>
    </div>
	 <div class="form-group">
        <label for="link" class="control-label"></label>
        
    </div>
	 
	

    

{!! Form::close() !!}