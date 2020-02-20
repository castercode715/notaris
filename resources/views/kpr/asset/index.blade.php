@extends('base.main')
@section('title') KPR Asset @endsection
@section('page_icon') <i class="fa fa-building"></i> @endsection
@section('page_title') KPR Asset @endsection
@section('page_subtitle') list @endsection

@section('content')
@include('kpr.asset.widget')

<div class="text-right" style="margin-bottom:15px;">
  <a href="{{ route('asset.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Create</a>
</div>

<div class="box box-solid">
  <div class="box-header">
    <button class="btn bg-aqua btn-sm pull-right" id="btn-reload"><i class="fa fa-refresh"></i> Reload Data</button>
    <button class="btn bg-grey btn-sm pull-right" id="btn-clear-filter"><i class="fa fa-filter"></i> Clear Filter</button>
  </div>
    <div class="box-body" style="padding:20px;">
        <div class="table-responsive">
            <table class="table table-bordered" id="datatable">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Booked for</th>
                        <th>Installment</th>
                        <th width="10%">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
  @include('kpr.asset.script')
@endpush