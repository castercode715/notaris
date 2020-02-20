@extends('base.main')
@section('title') Product Ecommerce @endsection
@section('page_icon') <i class="fa fa-opencart"></i> @endsection
@section('page_title') Product Ecommerce @endsection
@section('page_subtitle') list @endsection

@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
           <a href="{{ route('product.create') }}" class="btn bg-aqua btn-sm pull-right"><i class="fa fa-plus"></i> Create</a>
        </div>
    </div>
@endsection

@section('content')
<div class="box box-info">
        <div class="box-body">
            
            <div class="box-body">
                <table id="datatable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Product Name </th>
                            <th width="8%">Status</th>
                            <th width="12%">Created At</th>
                            <th width="12%">Created By</th>
                            <th width="10%"><i class="fa fa-cog"> Action</i></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
  @include('master.ecommerce.script')
@endpush
