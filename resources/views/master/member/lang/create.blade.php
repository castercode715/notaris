@extends('base.main')
@section('title') Member @endsection
@section('page_icon') <i class="fa fa-book"></i> @endsection
@section('page_title') Add New Language @endsection
@section('page_subtitle') create @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
        	<a href="{{ route('member.show', base64_encode($model->member_id)) }}" class="btn btn-success"><i class="fa fa-list"></i> Detail </a>
            <a href="{{ route('member.create') }}" class="btn btn-success"><i class="fa fa-list"></i> Create </a>
            <a href="{{ route('member.index') }}" class="btn btn-default"><i class="fa fa-list"></i> Manage</a>
        </div>
    </div>
@endsection

@section('content')
<div class="box box-solid">
    {!! Form::model($model, [
        'route' => 'member.store',
        'method'=> 'post',
        'enctype'   => 'multipart/form-data'
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

            
        </div>
    <div class="box-footer">
        {!! Form::submit('Create', ['class'=>'btn btn-primary pull-right']) !!}
    </div>

    {!! Form::close() !!}
</div>
@endsection