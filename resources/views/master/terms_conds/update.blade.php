@extends('base.main')
@section('title') Edit Terms & Conditions @endsection
@section('page_icon') <i class="fa fa-folder"></i> @endsection
@section('page_title') Edit Terms & Conditions @endsection
@section('page_subtitle') edit @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
           
            <a href="javascript:history.back()" class="btn btn-success" title="Manage">
                <i class="fa fa-list"></i> Manage
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="box box-solid">
    {!! Form::model($model, [
        'route' => ['terms-conds.update', base64_encode($model->id)],
        'method'=> 'put',
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
        <h3 style="margin-top: 2px;"><i class="fa fa-language"></i> Terms Conds ~ {{ $model->language }}</h3>


        <hr style="margin-top: -1px;">
		<div class="row">
			<div class="col-md-12">
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
				    <label for="desc" class="control-label">Deskripsi*</label>
		            {!! Form::textarea('description', null, ['class'=>'form-control', 'id'=>'editor1', 'rows'=>6]) !!}

		            @if($errors->has('description'))
		                <span class="invalid-feedback" role="alert">
		                    {{ $errors->first('description') }}
		                </span>
		            @endif
				</div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="gender" class="control-label">View*</label>
                            {!! Form::select('view', [''=>'- Select -']+['Private'=>'Private','Public'=>'Public'], null, ['class'=>'form-control', 'id'=>'view']) !!}

                            @if($errors->has('view'))
                                <span class="invalid-feedback" role="alert">
                                    {{ $errors->first('view') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="title" class="control-label">Sort*</label>
                            {!! Form::number('sort', null, ['class'=>'form-control', 'id'=>'sort']) !!}

                            @if($errors->has('sort'))
                                <span class="invalid-feedback" role="alert">
                                    {{ $errors->first('sort') }}
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
		</div>
	</div>
    <div class="box-footer">
        {!! Form::submit('Save', ['class'=>'btn btn-primary pull-right']) !!}
    </div>
    {!! Form::close() !!}
</div>
@endsection