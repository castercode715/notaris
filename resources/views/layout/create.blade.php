@extends('base.main')
@section('title') Layout @endsection
@section('page_icon') <i class="fa fa-book"></i> @endsection
@section('page_title') Layout @endsection
@section('page_subtitle') create @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <a href="{{ route('layout.index') }}" class="btn btn-default"><i class="fa fa-list"></i> Manage</a>
        </div>
    </div>
@endsection

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="box box-solid">
        <form action="{{ route('layout.store') }}" method="post">
            @csrf
            <div class="box-body">
                <div class="form-group">
                    <label for="nama">Nama<span class="required">*</span></label>
                    <input type="text" name="nama" id="nama" class="form-control">
                </div>
                <div class="form-group">
                    <label for="active">Active<span class="required">*</span></label>
                    <input type="text" name="active" id="active" class="form-control" value="0">
                </div>
                <div class="form-group">
                    <label for="created_by">Created by<span class="required">*</span></label>
                    <input type="text" name="created_by" id="created_by" class="form-control">
                </div>
                <div class="form-group">
                    <label for="created_by">Updated by<span class="required">*</span></label>
                    <input type="text" name="updated_by" id="updated_by" class="form-control">
                </div>
            </div>
            <div class="box-footer" style="text-align:right;">
                <input type="submit" value="Save" class="btn btn-primary">
            </div>
        </form>
    </div>
@endsection