@extends('base.main')
@section('title') Investor @endsection
@section('page_icon') <i class="fa fa-money"></i> @endsection
@section('page_title') Add Balance  @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
        	<a href="{{ route('investor.show', base64_encode($id)) }}" class="btn btn-success" title="Detail">
                <i class="fa fa-search"></i> Detail
            </a>
            <a href="{{ route('investor.index') }}" class="btn btn-success" title="Manage">
                <i class="fa fa-list"></i> Manage
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="box box-solid">
	{!! Form::model($model, [
	    'route' => 'balance.store',
	    'method'=> 'post',
	    'enctype'	=> 'multipart/form-data'
	]) !!}