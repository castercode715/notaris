@extends('base.main')
@section('title') News Tag @endsection
@section('page_icon') <i class="fa fa-newspaper-o"></i> @endsection
@section('page_title') Edit News Tag @endsection

@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
        	<a href="{{ route('news-tag.show', base64_encode($model->id)) }}" class="btn btn-success" title="Detail">
                <i class="fa fa-search"></i> Detail
            </a>
            <a href="{{ route('news-tag.delete', base64_encode($model->id)) }}" class="btn btn-danger btn-delete2" title="Delete">
                <i class="fa fa-trash"></i> Delete
            </a>
        	<a href="{{ route('news-tag.create') }}" class="btn btn-success" title="Create">
                <i class="fa fa-plus"></i> Create
            </a>
            <a href="{{ route('news-tag.index') }}" class="btn btn-success" title="Manage">
                <i class="fa fa-list"></i> Manage
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="box box-solid">
    {!! Form::model($model, [
        'route' => ['news-tag.update', base64_encode($model->id)],
        'method'=> 'put'
    ]) !!}

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

        <p class="note"><i>Fields with <span class="required">*</span> are required.</i></p>
        @php $no = 0; @endphp
        @foreach($data as $key => $r)
        <div class="box-body row">
            {!! Form::hidden('id[]', $r->id, []) !!}
            <div class="col-md-2">
                <div class="form-group">
                    @if($no < 1) <label for="code" class="control-label">Language</label> @endif
                    <input type="text" name="bahasa" value="{{ $r->language }}" disabled="disabled" class="form-control">
                    {!! Form::hidden('code[]', $r->code, []) !!}
                </div>
            </div>
            <div class="col-md-10">
                <div class="form-group">
                    @if($no < 1)<label for="description" class="control-label">Name*</label> @endif
                    {!! Form::text('description[]', $r->description, ['class'=>'form-control', 'id'=>'description', 'required'=>'required']) !!}

                    @if($errors->has('description.*'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('description.*') }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
        @php $no++; @endphp
        @endforeach

        <hr>
        <div class="box-body">
            <div class="form-group">
                <label for="code" class="control-label">Active</label>
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
    <div class="box-footer">
        {!! Form::submit('Save', ['class'=>'btn btn-primary pull-right']) !!}
    </div>
    {!! Form::close() !!}
</div>
@endsection