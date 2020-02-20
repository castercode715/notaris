@extends('base.main')
@section('title') Help @endsection
@section('page_icon') <i class="fa fa-user"></i> @endsection
@section('page_title') Edit Help {{ $model->title }} @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <a href="{{ route('help.show', base64_encode($model->id)) }}" class="btn btn-success" title="Show">
                <i class="fa fa-search"></i> Show
            </a>
            <a href="{{ route('help.delete', base64_encode($model->id)) }}" class="btn btn-danger btn-delete2" title="Delete">
                <i class="fa fa-trash"></i> Delete
            </a>
        	<a href="{{ route('help.create') }}" class="btn btn-success" title="Create Help">
                <i class="fa fa-plus"></i> Create
            </a>
            <a href="{{ route('help.index') }}" class="btn btn-success" title="Manage">
                <i class="fa fa-list"></i> Manage
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="box box-solid">
    {!! Form::model($model, [
        'route' => ['help.update', base64_encode($model->id)],
        'method'=> 'put',
    ]) !!}
    <div class="box-body">
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

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="sort" class="control-label">Sort*</label>
                        {!! Form::text('sort', null, ['class'=>'form-control', 'id'=>'sort', 'required'=>'required']) !!}

                        @if($errors->has('sort'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('sort') }}
                            </span>
                        @endif
                    </div>
                </div>
                @if( $model->isComplete() )
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="active" class="control-label">Active*</label>
                        {!! Form::select('active', [1=>'Active', 0=>'Inactive'], null, ['class'=>'form-control', 'id'=>'active', 'required'=>'required']) !!}

                        @if($errors->has('active'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('active') }}
                            </span>
                        @endif
                    </div>
                </div>
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

@push('scripts')
<script>
    $('.img-close').click(function(e){
        e.preventDefault();
        var key = $(this).data('key'),
            token = $('input[name="_token"]').val();
        var ithis = $(this);
        
        swal({
            title : 'Are you sure ?',
            type : 'warning',
            showCancelButton : true,
            confirmButtonColor : '#3085d6',
            cancelButtonColor : '#d33',
            confirmButtonText : 'Yes, delete!'
        }).then((result)=>{
            if(result.value){

                $.ajax({
                    url : "{{ route('help.remove-image') }}",
                    method : 'post',
                    data : {
                        value : key,
                        _token : token
                    },
                    success : function(r){
                        status = true;

                        ithis.closest('.img-wrap').remove();

                        swal({
                            type : 'success',
                            title : 'Success',
                            text : 'Deleted'
                        });
                    },
                    error : function(er){
                        swal({
                            type : 'error',
                            title : 'Failed',
                            text : 'Failed'
                        });
                    }
                });
            }
        });

    });
</script>
@endpush
