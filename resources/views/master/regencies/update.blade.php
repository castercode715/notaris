@extends('base.main')
@section('title') Regencies @endsection
@section('page_icon') <i class="fa fa-map"></i> @endsection
@section('page_title') Edit Regencies @endsection

@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <a href="{{ route('regencies.show', base64_encode($model->id)) }}" class="btn btn-success" title="Detail">
                <i class="fa fa-search"></i> Detail
            </a>
            <a href="{{ route('regencies.delete', base64_encode($model->id)) }}" class="btn btn-danger btn-delete2" title="Delete">
                <i class="fa fa-trash"></i> Delete
            </a>
            <a href="{{ route('regencies.create') }}" class="btn btn-success" title="Create">
                <i class="fa fa-plus"></i> Create
            </a>
            <a href="{{ route('regencies.index') }}" class="btn btn-success" title="Manage">
                <i class="fa fa-list"></i> Manage
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="box box-solid">
    {!! Form::model($model, [
        'route' => ['regencies.update', base64_encode($model->id)],
        'method'=> 'put',
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
                    <label for="provinces_id" class="control-label">Province*</label>
                    {!! Form::select('provinces_id', [''=>'- Select -']+$provinces, null, ['class'=>'form-control select2', 'id'=>'provinces_id', 'required'=>'required']) !!}

                    @if($errors->has('provinces_id'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('provinces_id') }}
                        </span>
                    @endif
                </div>
            </div>
            <hr>
            @php $no = 0; @endphp
            @foreach($data as $key => $r)
                {!! Form::hidden('id_reg[]', $r->id_reg, []) !!}
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            @if($no < 1) <label for="code" class="control-label">Language</label> @endif
                            <input type="text" name="bahasa" value="{{ $r->language }}" disabled="disabled" class="form-control">
                            {!! Form::hidden('code[]', $r->code, []) !!}
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            @if($no < 1)<label for="name" class="control-label">Name*</label> @endif
                            {!! Form::text('name[]', $r->name, ['class'=>'form-control', 'id'=>'name', 'required'=>'required']) !!}

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
        {!! Form::submit('Save', ['class'=>'btn btn-primary pull-right']) !!}
    </div>
    {!! Form::close() !!}
</div>
@endsection

@push('scripts')
<script>
    
</script>
@endpush