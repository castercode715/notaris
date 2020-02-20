@extends('base.main')
@section('title') Data News @endsection
@section('page_icon') <i class="fa fa-image"></i> @endsection
@section('page_title') Create News @endsection

@section('content')
<div class="box box-solid">
	{!! Form::model($model, [
	    'route' => 'news.store',
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
		    <label for="subtitle" class="control-label">Subtitle*</label>
		    {!! Form::text('subtitle', null, ['class'=>'form-control', 'id'=>'subtitle']) !!}

		    @if($errors->has('subtitle'))
		    	<span class="invalid-feedback" role="alert">
		    		{{ $errors->first('subtitle') }}
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
			<label for="iframe" class="control-label">iFrame</label>
            {!! Form::textarea('iframe', null, ['class'=>'form-control', 'id'=>'iframe', 'rows'=>3]) !!}

            @if($errors->has('iframe'))
                <span class="invalid-feedback" role="alert">
                    {{ $errors->first('iframe') }}
                </span>
            @endif
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