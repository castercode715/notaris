@extends('base.main')
@section('title') Voucher Cashback - Add {{ $language->language }} Description @endsection
@section('page_icon') <i class="fa fa-money"></i> @endsection
@section('page_title') Voucher Cashback - Add {{ $language->language }} Description @endsection
@section('page_subtitle') add language @endsection
@section('menu')
<div class="box box-solid" style="text-align:right;">
    <div class="box-body">
        <a href="{{ route('cashback.show', $id) }}" class="btn btn-success" title="Show Voucher Cashback">
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
    <form action="{{ route('cashback.store-new') }}" method="post" enctype="multipart/form-data">
        @csrf
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
                <input type="hidden" name="id" value="{{ $id }}">
                <input type="hidden" name="code" value="{{ $language->code }}">
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
                            <label for="desc" class="control-label">Information*</label>
                            {!! Form::textArea('desc', null, ['class'=>'form-control', 'id'=>'editor1']) !!}

                            @if($errors->has('desc'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('desc') }}
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-footer">
            <div class="box-body">
                {!! Form::submit('Create', ['class'=>'btn btn-lg btn-primary pull-right']) !!}
            </div>
        </div>
    </form>
</div>
@endsection