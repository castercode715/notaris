@extends('base.main')
@section('title') Privacy Policy @endsection
@section('page_icon') <i class="fa fa-folder"></i> @endsection
@section('page_title') Edit Privacy Policy {{ $model->title }} @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <a href="{{ route('privacy-policy.show', base64_encode($model->id)) }}" class="btn btn-success" title="Show">
                <i class="fa fa-search"></i> Show
            </a>
            <a href="{{ route('privacy-policy.delete', base64_encode($model->id)) }}" class="btn btn-danger btn-delete2" title="Delete">
                <i class="fa fa-trash"></i> Delete
            </a>
        	<a href="{{ route('privacy-policy.create') }}" class="btn btn-success" title="Create">
                <i class="fa fa-plus"></i> Create
            </a>
            <a href="{{ route('privacy-policy.index') }}" class="btn btn-success" title="Manage">
                <i class="fa fa-list"></i> Manage
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="box box-solid">
	{!! Form::model($model, [
	    'route' => ['privacy-policy.update', base64_encode($model->id)],
	    'method'=> 'put'
	]) !!}
	<div class="box-body">
		<div class="box-body">
			@if(count($errors) > 0)
				<div class="alert alert-danger alert-dismissible">
		            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<ul>
						@foreach($errors->all() as $error)
						<li>{{  $error }}</li>
						@endforeach
					</ul>
				</div>
			@endif

			<div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="sort" class="control-label">Sort*</label>
                        {!! Form::text('sort', null, ['class'=>'form-control', 'id'=>'sort', 'required'=>'required']) !!}

                        @if($errors->has('sort'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('sort') }}
                            </span>
                        @endif
                    </div>
                </div>
                @if( $model->isComplete() )
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="active" class="control-label">Active*</label>
                        {!! Form::select('active', [1=>'Active', 0=>'Inactive'], null, ['class'=>'form-control', 'id'=>'active', 'required'=>'required']) !!}

                        @if($errors->has('active'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('active') }}
                            </span>
                        @endif
                    </div>
                </div>
                @endif
            </div>

		</div>
	</div>
	<div class="box-footer">
		{!! Form::submit('Save', ['class'=>'btn btn-primary pull-right']) !!}
	</div>
	{!! Form::close() !!}
</div>
@endsection