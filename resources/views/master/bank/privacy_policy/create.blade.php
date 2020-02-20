@extends('base.main')
@section('title') Privacy Policy @endsection
@section('page_icon') <i class="fa fa-image"></i> @endsection
@section('page_title') Privacy Policy  @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <a href="{{ route('privacy-policy.index') }}" class="btn btn-success" title="Manage">
                <i class="fa fa-list"></i> Manage
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="box box-solid">
	{!! Form::model($model, [
	    'route' => 'privacy-policy.store',
	    'method'=> 'post',
	    'enctype'	=> 'multipart/form-data'
	]) !!}
	<div class="box-body">
		@if(count($errors) > 0)
			<div class="alert alert-danger">
				<ul>
					@foreach($errors->all() as $error)
					<li>{{  $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif
		<div class="form-group">
		    <label for="title" class="control-label">Title*</label>
		    {!! Form::text('title', null, ['class'=>'form-control', 'id'=>'title']) !!}

		    @if($errors->has('title'))
		    	<span class="invalid-feedback" role="alert">
		    		{{ $errors->first('title') }}
		    	</span>
		    @endif
		</div>
		
		<div class="form-group">
            <label for="desc" class="control-label">Description*</label>
            {!! Form::textarea('desc', null, ['class'=>'textarea form-control', 'id'=>'desc', 'rows'=>6]) !!}

            @if($errors->has('desc'))
                <span class="invalid-feedback" role="alert">
                    {{ $errors->first('desc') }}
                </span>
            @endif
        </div>
        <div class="row">
        	<div class="col-md-6">
        		<div class="form-group">
				    <label for="image" class="control-label">Image</label>
				    {!! Form::file('image', ['accept'=>'image/x-png, image/jpeg']) !!}

				    @if($errors->has('image'))
				    	<span class="invalid-feedback" role="alert">
				    		{{ $errors->first('image') }}
				    	</span>
				    @endif
				</div>
        	</div>
        	
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
	</div>
	<div class="box-footer">
		{!! Form::submit('Create', ['class'=>'btn btn-primary pull-right']) !!}
	</div>
	{!! Form::close() !!}
</div>
@endsection