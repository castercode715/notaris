@extends('base.main')
@section('title') Member @endsection
@section('page_icon') <i class="fa fa-users"></i> @endsection
@section('page_title') Member Update  @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <a href="{{ route('member.show', base64_encode($model->member_id)) }}" class="btn btn-success" title="Manage">
                <i class="fa fa-list"></i> Manage
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="box box-solid">
    {!! Form::model($model, [
        'route' => ['member.update', base64_encode($model->id)],
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


        <hr>
        <h3><i class="fa fa-language"></i> <input type='hidden' name='code' value='{{ $model->code }}'><b>{{ $model->language }}</b></h3>
        
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <label for="image" class="control-label">Code</label>
                     <input type='text' disabled class="form-control" name='ck[]' value='{{ $model->code }}'>
                    

                    @if($errors->has('code'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('code') }}
                        </span>
                    @endif
                </div>
            </div>  
            <div class="col-md-4">
                <div class="form-group">
                    <label for="image" class="control-label">Image</label>
                    <br>
                    <img style="width: 250px; height: 100px;" src="/images/member/{{ $model->image }}" class="img-thumbnail">
                    {!! Form::file('image', ['accept'=>'image/x-png, image/jpeg' ]) !!}

                    <span class="invalid-feedback" role="alert">
                            <i>*File ekstensi: jpg, jpeg, png</i><br>
                    </span>

                    @if($errors->has('image'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('image') }}
                        </span>
                    @endif
                </div>
            </div>  
            <div class="col-md-6">
                <div class="form-group">
                    <label for="image" class="control-label">Description</label>
                    {!! Form::text('description', null, ['class'=>'form-control', 'id'=>'description']) !!}

                    

                    @if($errors->has('description'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('description') }}
                        </span>
                    @endif
                </div>
            </div>  
        </div>
        
        
    <div class="box-footer">
        {!! Form::submit('Update', ['class'=>'btn btn-primary pull-right']) !!}
    </div>
    {!! Form::close() !!}
</div>
@endsection