

@extends('base.main')
@section('title') Category Product @endsection
@section('page_icon') <i class="fa fa-bookmark-o"></i> @endsection
@section('page_title') Category Product @endsection
@section('page_subtitle') list @endsection


@section('content')

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

<div class="panel panel-primary">
    <div class="panel-heading">Category Product</div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-6">
                <h3>Category List</h3>
                <ul id="tree1" >
                    @foreach($categories as $category)
                    <li >
                        @if(count($model->childs($category->id)))
                        <i class="fa fa-plus-circle"></i>
                        @endif

                        {{ $category->name }} &nbsp;&nbsp;&nbsp;<i id="btn-editCat" data-id="{{ $category->id }}" data-hreff="{{ route('product-category.edit', $category->id) }}" class="fa fa-edit"></i> | <i id="btn-deleteCat" data-hreff="{{ route('product-category.destroy', $category->id) }}" data-title="{{ $category->name }}" data-id="{{ $category->id }}" class="fa fa-trash"></i>

                        @if(count($model->childs($category->id)))
                            @include('master.product-category.manageChild',['childs' => $model->childs($category->id)])
                        @endif
                    </li>
                    @endforeach
                </ul>
            </div>

            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                      <h3 class="box-title">Add Category</h3>
                  </div>

                  {!! Form::model($model, [
                    'route' => 'product-category.store',
                    'method'=> 'post',
                    'class' => 'form-horizontal'
                    ]) !!}

                    <div class="box-body">
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Category Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="name" name="name" placeholder="Category Name">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-2 control-label">Parent</label>

                            <div class="col-sm-10">
                                {!! Form::select('parent_id', $allCategory, null, ['class'=>'form-control select2']) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-2 control-label">Icon</label>

                            <div class="col-sm-10">
                                <div class="dropzone" id="apake2"></div>
                                <input type="hidden" name="icon_category" id="icon_category"></input>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">

                                <label>
                                    <button type="submit" class="btn btn-primary pull-right">Save Category</button>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

            {!! Form::close() !!}
        </div>
    </div>
</div>
</div>
</div>

@endsection

@push('scripts')
    @include('master.product-category.script')
@endpush
