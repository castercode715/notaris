@extends('base.main')
@section('title') APT Asset @endsection
@section('page_icon') <i class="fa fa-building"></i> @endsection
@section('page_title') APT Asset @endsection
@section('page_subtitle') list @endsection

@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
           <a href="{{ route('apt-asset.create') }}" class="btn bg-aqua btn-sm pull-right"><i class="fa fa-plus"></i> Create</a>
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
                            <th width="10%">Code </th>
                            <th>Asset Name</th>
                            <th width="15%">Type Asset</th>
                            <th width="12%">Status</th>
                            <th width="10%"><i class="fa fa-cog"> Action</i></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
  @include('master.apt-asset.script')
@endpush
