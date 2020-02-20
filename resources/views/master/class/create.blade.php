@extends('base.main')
@section('title') Class @endsection
@section('page_icon') <i class="fa fa-user"></i> @endsection
@section('page_title') Create Class @endsection

@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <a href="{{ route('class.index') }}" class="btn btn-success" title="Manage Class">
                <i class="fa fa-list"></i> Manage
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="box box-solid">
    {!! Form::model($model, [
        'route' => 'class.store',
        'method'=> 'post',
        'enctype'   => 'multipart/form-data'
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
        
        @php $no = 0; @endphp
        @foreach($language as $r)
            <div class="box-body row">
                <div class="col-md-2">
                    <div class="form-group">
                        @if($no < 1) <label for="code" class="control-label">Language</label> @endif
                        <input type="text" name="bahasa" value="{{ $r->language }}" disabled="disabled" class="form-control">
                        {!! Form::hidden('code[]', $r->code, []) !!}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        @if($no < 1)<label for="description" class="control-label">Name*</label> @endif
                        {!! Form::text('description[]', null, ['class'=>'form-control', 'id'=>'description', 'required'=>'required']) !!}

                        @if($errors->has('description.*'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('description.*') }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        @if($no < 1) <p style="margin-top: 25px; font-style: italic; color: #ccc; text-align: center;"> @else <p style="margin-top: 5px; font-style: italic; color: #ccc; text-align: center;"> @endif
                            No image
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        @if($no < 1)<label for="image" class="control-label">Icon*</label> @endif
                        {!! Form::file('image[]', ['accept'=>'image/x-png, image/jpeg, image/jpg', 'required'=>'required']) !!}

                        @if($errors->has('image.*'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('image.*') }}
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
        {!! Form::submit('Create', ['class'=>'btn btn-primary pull-right']) !!}
    </div>
    {!! Form::close() !!}
</div>
@endsection

@push('scripts')
<script>

</script>
@endpush