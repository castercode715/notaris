@extends('base.main')
@section('title') Book @endsection
@section('page_icon') <i class="fa fa-book"></i> @endsection
@section('page_title') Book @endsection
@section('page_subtitle') edit @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <a href="{{ route('book.destroy', $book->id) }}" class="btn btn-danger"><i class="fa fa-eraser"></i> Delete</a>
            <a href="{{ route('book.create') }}" class="btn btn-default"><i class="fa fa-plus"></i> Create</a>
            <a href="{{ route('book.index') }}" class="btn btn-default"><i class="fa fa-list"></i> Manage</a>
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
        <form action="{{ route('book.update', $book->id) }}" method="post">
            @method('patch')
            @csrf
            <div class="box-body">
                <div class="form-group">
                    <label for="title">Title<span class="required">*</span></label>
                    <input type="text" name="title" id="title" class="form-control" value="{{ $book->title }}">
                </div>
                <div class="form-group">
                    <label for="author">Author<span class="required">*</span></label>
                    <input type="text" name="author" id="author" class="form-control" value="{{ $book->author }}">
                </div>
                <div class="form-group">
                    <label for="year">Year<span class="required">*</span></label>
                    <input type="text" name="year" id="year" class="form-control" maxlength="4" value="{{ $book->year }}">
                </div>
                <div class="form-group">
                    <label for="price">Price<span class="required">*</span></label>
                    <input type="text" name="price" id="price" class="form-control" value="{{ $book->price }}">
                </div>
            </div>
            <div class="box-footer" style="text-align:right;">
                <input type="submit" value="Save" class="btn btn-primary">
            </div>
        </form>
    </div>
@endsection