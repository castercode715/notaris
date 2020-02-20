@extends('base.main')
@section('title') Voucher @endsection
@section('page_icon') <i class="fa fa-id-card"></i> @endsection
@section('page_title') Voucher @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <a href="{{ route('voucher.index') }}" class="btn btn-success" title="Manage">
                <i class="fa fa-list"></i> Manage
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="box box-solid">
    {!! Form::model($model, [
        'route' => 'voucher.store',
        'method'=> 'post',
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

        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label for="category_asset_id" class="control-label">Asset Name*</label>
                    {!! Form::select('asset_id', [''=>'- Select -'] + $page, null, ['class'=>'form-control select2 dynamic', 'id'=>'asset_id']) !!}

                    @if($errors->has('asset_id'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('asset_id') }}
                        </span>
                    @endif
                </div>
            </div>
            <div class="col-md-4">
                    <div class="form-group">
                        <label for="date_available" class="control-label">Available Date*</label>
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input name="date_start" type="text" class="form-control pull-right" id="datepicker">
                        </div>

                        @if($errors->has('date_start'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('date_start') }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                    <label for="title" class="control-label">Number Interest*</label>
                    {!! Form::text('number_interest', null, ['class'=>'form-control', 'id'=>'number_interest']) !!}

                    @if($errors->has('number_interest'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('number_interest') }}
                        </span>
                    @endif
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                    <label for="title" class="control-label">Long Promo*</label>
                    {!! Form::text('long_promo', null, ['class'=>'form-control', 'id'=>'long_promo']) !!}

                    @if($errors->has('long_promo'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('long_promo') }}
                        </span>
                    @endif
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="date_expired" class="control-label">Expired Date*</label>
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input name="date_expired" type="text" class="form-control pull-right" id="datepicker2">
                        </div>

                        @if($errors->has('date_expired'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('date_expired') }}
                            </span>
                        @endif
                    </div>
                </div>
                

                <div class="col-md-4">
                    <div class="form-group">
                    <label for="title" class="control-label">Quota*</label>
                    {!! Form::text('quota', null, ['class'=>'form-control', 'id'=>'quota']) !!}

                    @if($errors->has('quota'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('quota') }}
                        </span>
                    @endif
                    </div>
                </div>

                <div class="col-md-4">
                <div class="form-group">
                    <label for="gender" class="control-label">Status*</label>
                    {!! Form::select('status', [''=>'- Select -']+['Active'=>'Active','Inactive'=>'Inactive'], null, ['class'=>'form-control', 'id'=>'status']) !!}

                    @if($errors->has('status'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('status') }}
                        </span>
                    @endif
                </div>
            </div>
            
        </div>
        <br>
        @php $no = 1; @endphp
        @foreach($lang as $language)
        <hr>
        <h3><i class="fa fa-language"></i> <input type='hidden' name='code[]' value='{{ $language->code }}'><b>{{ $language->language }}</b></h3>
        <div class="row">
            <div class="col-md-7">
                <div class="form-group">
                <label for="title" class="control-label">Title*</label>
                {!! Form::text('title[]', null, ['class'=>'form-control', 'id'=>'title']) !!}

                @if($errors->has('title'))
                    <span class="invalid-feedback" role="alert">
                        {{ $errors->first('title') }}
                    </span>
                @endif
                </div>
            </div>

            <div class="col-md-12">
            <label for="desc" class="control-label">Description*</label>
            {!! Form::textarea('desc[]', null, ['class'=>'form-control', 'id'=>'editor'.$no, 'rows'=>6]) !!}

            @if($errors->has('desc'))
                <span class="invalid-feedback" role="alert">
                    {{ $errors->first('desc') }}
                </span>
            @endif
        </div>
            
                

                
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="image" class="control-label">Image</label>
                    {!! Form::file('image[]', ['accept'=>'image/x-png, image/jpeg, image/jpg', 'required'=>'required']) !!}

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
                    {!! Form::textarea('iframe[]', null, ['class'=>'form-control', 'id'=>'iframe', 'rows'=>2]) !!}

                    @if($errors->has('iframe'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('iframe') }}
                        </span>
                    @endif
                </div>
            </div>
            
        </div>
        <br>
        @php $no++; @endphp
        @endforeach
    </div>
    <div class="box-footer">
        {!! Form::submit('Create', ['class'=>'btn btn-primary pull-right']) !!}
    </div>
    {!! Form::close() !!}
</div>
@endsection