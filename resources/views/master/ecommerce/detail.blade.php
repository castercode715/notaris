@extends('base.main')
@section('title') Product Ecommerce @endsection
@section('page_icon') <i class="fa fa-opencart"></i> @endsection
@section('page_title') Product Ecommerce @endsection
@section('page_subtitle') Detail @endsection
@section('menu')
    <div class="box box-default" style="text-align:right;">
        <div class="box-body">
            <a href="{{ route('product.edit', base64_encode($model->id) ) }}" class="btn btn-success" title="Edit About Us">
                <i class="fa fa-edit"></i> Update
            </a>
            <a href="{{ route('product.ecommerce.delete', base64_encode($model->id)) }}" class="btn btn-danger btn-delete2" title="Delete">
                <i class="fa fa-trash"></i> Delete
            </a>
            <a href="{{ route('product.create') }}" class="btn btn-success" title="Create About Us">
                <i class="fa fa-plus"></i> Create
            </a>
            <a href="{{ route('product.index') }}" class="btn btn-success" title="Manage">
                <i class="fa fa-list"></i> Manage
            </a>
        </div>
    </div>
@endsection

@section('content')

<div class="nav-tabs-custom">
    <ul class="nav nav-tabs tabs-up" id="friends">
        <li>
            <a href="" data-toggle="tab" data-target="#tab_detail" class="active" id="detail_tab" rel="tooltip"> <i class="fa fa-cube"></i>&nbsp;&nbsp; Detail   </a>
        </li>
        
    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="tab_detail">
            @include('master.ecommerce.tab.detail')
        </div>
        <div class="tab-pane" id="tab_unit">
            @include('master.ecommerce.tab.index')
        </div>
        
    </div>
</div>

@endsection


