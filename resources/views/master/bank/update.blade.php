@extends('base.main')
@section('title') Bank @endsection
@section('page_icon') <i class="fa fa-bank"></i> @endsection
@section('page_title') Edit Bank @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
        	{{-- <a href="{{ route('bank.destroy') }}" class="btn btn-danger" title="Delete">
                <i class="fa fa-trash"></i> Delete
            </a> --}}
        	<a href="{{ route('bank.show', $model->id) }}" class="btn btn-success btn-show" title="Detail">
                <i class="fa fa-search"></i> Detail
            </a>
        	<a href="{{ route('bank.create') }}" class="btn btn-success" title="Create">
                <i class="fa fa-plus"></i> Create
            </a>
            <a href="{{ route('bank.index') }}" class="btn btn-success" title="Manage">
                <i class="fa fa-list"></i> Manage
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="box box-solid">
    {!! Form::model($model, [
        'route' => ['bank.update', $model->id],
        'method'=> 'put',
        'enctype'   => 'multipart/form-data'
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

        <p class="note"><i>Fields with <span class="required">*</span> are required.</i></p>

            <div class="row">
                <div class="col-md-8 row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name" class="control-label">Nama*</label>
                            {!! Form::text('name', null, ['class'=>'form-control', 'id'=>'name']) !!}

                            @if($errors->has('name'))
                                <span class="invalid-feedback" role="alert">
                                    {{ $errors->first('name') }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="card_type" class="control-label">Card Type*</label>
                            {!! Form::select('card_type', [''=>'- Select -'] + $model->cardTypeList(), null, ['class'=>'form-control','id'=>'card_type']) !!}

                            @if($errors->has('card_type'))
                                <span class="invalid-feedback" role="alert">
                                    {{ $errors->first('card_type') }}
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="countries_id" class="control-label">Country*</label>
                            {!! Form::select('countries_id', [''=>'- Select -'] + $country, null, ['class'=>'form-control','id'=>'countries_id']) !!}

                            @if($errors->has('countries_id'))
                                <span class="invalid-feedback" role="alert">
                                    {{ $errors->first('countries_id') }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
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
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="image_logo" class="control-label">Logo*</label>
                        <p>
                            <img src="/images/bank/{{ $model->image_logo }}" class="img-responsive" />
                        </p>
                        {!! Form::file('image_logo', ['accept'=>'image/x-png, image/jpeg', 'value'=>"{{ old('image_logo') }}"]) !!}

                        @if($errors->has('image_logo'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('image_logo') }}
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