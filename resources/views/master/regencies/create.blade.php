@extends('base.main')
@section('title') Regencies @endsection
@section('page_icon') <i class="fa fa-map"></i> @endsection
@section('page_title') Regencies @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <a href="{{ route('regencies.index') }}" class="btn btn-default"><i class="fa fa-list"></i> Manage</a>
        </div>
    </div>
@endsection

@section('content')
<div class="box box-solid">
    {!! Form::model($model, [
        'route' => 'regencies.store',
        'method'=> 'post',
        'enctype'   => 'multipart/form-data'
    ]) !!}

    <div class="box-body">
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
                <div class="col-md-4 form-group">
                    <label for="id" class="control-label">ID*</label>
                    {!! Form::text('id', null, ['class'=>'form-control']) !!}

                    @if($errors->has('id'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('id') }}
                        </span>
                    @endif
                </div>
                <div class="col-md-4 form-group">
                    <label for="province" class="control-label">Province*</label>
                    {!! Form::select('province', [''=>'- Select -']+$provinces, null, ['class'=>'form-control select2', 'id'=>'province', 'required'=>'required']) !!}

                    @if($errors->has('province'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('province') }}
                        </span>
                    @endif
                </div>
            </div>
            <hr>
            @php $no = 0; @endphp
            @foreach($language as $r)
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            @if($no < 1) <label for="code" class="control-label">Language*</label> @endif
                            <input type="text" name="bahasa" value="{{ $r->language }}" disabled="disabled" class="form-control">
                            {!! Form::hidden('code[]', $r->code, []) !!}
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            @if($no < 1)<label for="name" class="control-label">Name*</label> @endif
                            {!! Form::text('name[]', null, ['class'=>'form-control', 'id'=>'name', 'required'=>'required']) !!}

                            @if($errors->has('name.*'))
                                <span class="invalid-feedback" role="alert">
                                    {{ $errors->first('name.*') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                @php $no++; @endphp
            @endforeach

        </div>
    </div>
    <div class="box-footer">
        {!! Form::submit('Create', ['class'=>'btn btn-primary pull-right']) !!}
    </div>

    {!! Form::close() !!}
</div>
@endsection