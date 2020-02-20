@extends('base.main')
@section('title') APT Asset Detail @endsection
@section('page_icon') <i class="fa fa-cubes"></i> @endsection
@section('page_title') APT Asset Detail @endsection
@section('menu')
    <div class="box box-default" style="text-align:right;">
        <div class="box-body">
            <a href="{{ route('apt-asset.edit', base64_encode($model->code_apt) ) }}" class="btn btn-success" title="Edit About Us">
                <i class="fa fa-edit"></i> Update
            </a>
            <a href="{{ route('apt.asset.delete', base64_encode($model->code_apt)) }}" class="btn btn-danger btn-delete2" title="Delete">
                <i class="fa fa-trash"></i> Delete
            </a>
            <a href="{{ route('apt-asset.create') }}" class="btn btn-success" title="Create About Us">
                <i class="fa fa-plus"></i> Create
            </a>
            <a href="{{ route('apt-asset.index') }}" class="btn btn-success" title="Manage">
                <i class="fa fa-list"></i> Manage
            </a>
        </div>
    </div>
@endsection

@section('content')

<div class="nav-tabs-custom">
    <ul class="nav nav-tabs tabs-up" id="friends">
        <li>
            <a href="{{ route('apt.asset-detail.pane') }}" data-toggle="tab" data-target="#tab_detail" class="active" id="detail_tab" rel="tooltip"> <i class="fa fa-cubes"></i>&nbsp;&nbsp; Asset Detail </a>
        </li>
        <li>
            <a href="{{ route('apt.asset-unit.pane', $model->code_apt) }}" data-toggle="tabajax-unit" data-target="#tab_unit" id="unit_tab" rel="tooltip"> <i class="fa fa-code-fork"></i>&nbsp;&nbsp; Floor </a>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="tab_detail">
            @include('master.apt-asset.tab.detail')
        </div>
        <div class="tab-pane" id="tab_unit">
            @include('master.apt-asset.floor.index')
        </div>
        
    </div>
</div>

@endsection

@push('scripts')
  @include('master.apt-asset.tab.script')
@endpush

