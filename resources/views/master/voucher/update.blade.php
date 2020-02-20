@extends('base.main')
@section('title') Edit Voucher @endsection
@section('page_icon') <i class="fa fa-folder"></i> @endsection
@section('page_title') Edit Voucher @endsection
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
        'route' => ['voucher.update', $model->id],
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
        <h3 style="margin-top: 2px;"><i class="fa fa-language"></i> Voucher ~ {{ $lang->language }}</h3>
        <input type="hidden" name='code' value="{{ $model->code }}">


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
                    {!! Form::textarea('desc', null, ['class'=>'form-control', 'id'=>'editor1', 'rows'=>6]) !!}

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
                    <br>
                    <img width='300' height="200" src="/images/voucher/{{ $model->image }}" class="img-thumbnail">

                    {!! Form::file('image', ['accept'=>'image/x-png, image/jpeg, image/jpg']) !!}

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
                    <label for="iframe" class="control-label">iFrame</label>
                    {!! Form::textarea('iframe', null, ['class'=>'form-control', 'id'=>'iframe', 'rows'=>2]) !!}

                    @if($errors->has('iframe'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('iframe') }}
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