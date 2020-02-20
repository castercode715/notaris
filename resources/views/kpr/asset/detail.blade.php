@extends('base.main')
@section('title') KPR Detail @endsection
@section('page_icon') <i class="fa fa-cubes"></i> @endsection
@section('page_title') KPR Detail @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <a href="{{ route('asset.edit', base64_encode($model->code) ) }}" class="btn btn-success" title="Edit About Us">
                <i class="fa fa-edit"></i> Update
            </a>
            <a href="{{ route('kpr.asset.delete', base64_encode($model->code)) }}" class="btn btn-danger btn-delete2" title="Delete">
                <i class="fa fa-trash"></i> Delete
            </a>
            <a href="{{ route('asset.create') }}" class="btn btn-success" title="Create About Us">
                <i class="fa fa-plus"></i> Create
            </a>
            <a href="{{ route('asset.index') }}" class="btn btn-success" title="Manage">
                <i class="fa fa-list"></i> Manage
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs tabs-up" id="friends">
        <li>
            <a href="{{ route('kpr.asset-detail.pane') }}" data-toggle="tab" data-target="#tab_detail" class="active" id="detail_tab" rel="tooltip"> <i class="fa fa-cubes"></i>&nbsp;&nbsp; Detail Asset </a>
        </li>
        <li>
            <a href="{{ route('kpr.investor.pane') }}" data-toggle="tabajax-investor" data-target="#tab_investor" id="investor_tab" rel="tooltip"> <i class="fa fa-users"></i>&nbsp;&nbsp; Investor </a>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="tab_detail">
            @include('kpr.asset.tab.detail')
        </div>
        <div class="tab-pane" id="tab_investor">
            @include('kpr.asset.tab.investor')
        </div>
        
    </div>
</div>
@endsection

@push('scripts')
  @include('kpr.asset.tab.script')
@endpush