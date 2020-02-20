@extends('base.main')
@section('title') Voucher Cashback - Edit {{ $language->language }} Description @endsection
@section('page_icon') <i class="fa fa-money"></i> @endsection
@section('page_title') Voucher Cashback - Edit {{ $language->language }} Description @endsection
@section('page_subtitle') edit language @endsection
@section('menu')
<div class="box box-solid" style="text-align:right;">
    <div class="box-body">
        <a href="{{ route('cashback.show', $model->redeem_voucher_id) }}" class="btn btn-success" title="Show Voucher Cashback">
            <i class="fa fa-arrow-left"></i> Back to view
        </a>
        <a href="{{ route('cashback.index') }}" class="btn btn-success" title="Manage Voucher Cashback">
            <i class="fa fa-list"></i> Manage
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="box box-solid">
{!! Form::model($model, [
    'route' => ['cashback.update-new', $model->id ],
    'method'=> 'put',
    'enctype'   => 'multipart/form-data'
]) !!}
    <div class="box-body">
        <div class="box-body">
            <p class="note"><i>Fields with <span class="required">*</span> are required.</i></p>
            @if(count($errors) > 0)
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <ul>
                    @foreach($errors->all() as $error)
                    <li>{{  $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="title" class="control-label">Title*</label>
                        {!! Form::text('title', null, ['class'=>'form-control input-lg', 'id'=>'title']) !!}
                        @if($errors->has('title'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('title') }}
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label for="image" class="control-label">Image*</label>
                    <img src="{{ asset('images/voucher/'.$model->image) }}" alt="" class="img-responsive" />
                    <input type="file" name="image" id="image" accept="image/x-png,image/gif,image/jpeg" />

                    @if($errors->has('image'))
                    <span class="invalid-feedback" role="alert">
                        {{ $errors->first('image') }}
                    </span>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="description" class="control-label">Information*</label>
                        {!! Form::textArea('description', null, ['class'=>'form-control', 'id'=>'editor1']) !!}

                        @if($errors->has('description'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('description') }}
                        </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="box-footer">
        <div class="box-body">
            {!! Form::submit('Save', ['class'=>'btn btn-lg btn-primary pull-right']) !!}
        </div>
    </div>
{!! Form::close() !!}
</div>
@endsection