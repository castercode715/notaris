@extends('base.main')
@section('title') Privacy Policy @endsection
@section('page_icon') <i class="fa fa-drivers-license"></i> @endsection
@section('page_title') Privacy Policy @endsection
@section('page_subtitle') Detail @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <a href="{{ route('privacy-policy.edit', base64_encode($model->id) ) }}" class="btn btn-success" title="Edit Privacy Policy">
                <i class="fa fa-edit"></i> Update
            </a>
            <a href="{{ route('privacy-policy.delete', base64_encode($model->id)) }}" class="btn btn-danger btn-delete2" title="Delete">
                <i class="fa fa-trash"></i> Delete
            </a>
            <a href="{{ route('privacy-policy.create') }}" class="btn btn-success" title="Create Privacy Policy">
                <i class="fa fa-plus"></i> Create
            </a>
            <a href="{{ route('privacy-policy.index') }}" class="btn btn-success" title="Manage">
                <i class="fa fa-list"></i> Manage
            </a>
        </div>
    </div>
@endsection

@section('content')
@if( !$pp->isComplete() )
<div class="alert alert-warning alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h4><i class="icon fa fa-warning"></i> Please add description for other language</h4>
    Asset won't be active if you not complete the description
</div>
@endif
<div class="box box-solid">
    <div class="box-body">
        <!-- start accordion -->
        <div class="box-group" id="accordion">
            <div class="panel box box-solid">
                <div class="box-header with-border">
                    <h4 class="box-title"><strong>
                        <a href="#collapse1" data-toggle="collapse" data-parent="#accordion">Indonesia</a>
                    </strong></h4>
                    <div class="box-tools pull-right">
                        <a href="{{ route('privacy-policy.edit-new', [base64_encode($model->privacy_policy_lang_id)]) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>
                    </div>
                </div>
                <div id="collapse1" class="panel-collapse collapse in">
                    <div class="box-body">
                        <h3><strong>{{ $model->title }}</strong></h3>
                        {!! html_entity_decode($model->description) !!}
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                @if($model->image != '')
                                <img src="/images/privacy-policy/{{ $model->image }}" class="img-responsive">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @php $no = 2; @endphp
            @foreach($language as $l)
            @php 
            $data = $pp->getData($l->code);
            @endphp
            <div class="panel box box-solid">
                <div class="box-header with-border">
                    <h4 class="box-title"><strong>
                        <a href="#collapse{{ $no }}" data-toggle="collapse" data-parent="#accordion">{!! ucwords(strtolower($l->language)) !!}</a>
                    </strong></h4>
                    @if($data)
                    <div class="box-tools pull-right">
                        <a href="{{ route('privacy-policy.edit-new', [base64_encode($data->id)]) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>
                    </div>
                    @endif
                </div>
                <div id="collapse{{ $no }}" class="panel-collapse collapse">
                    <div class="box-body">
                        @if($data)
                        <h3><strong>{{ $data->title }}</strong></h3>
                        {!! html_entity_decode($data->description) !!}
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                @if($data->image != '')
                                <img src="/images/privacy-policy/{{ $data->image }}" class="img-responsive">
                                @endif
                            </div>
                        </div>
                        @else
                        <p align="center">Please add description using this language</p>
                        <p align="center"><a href="{{ route('privacy-policy.create-new',[ base64_encode($model->id), $l->code]) }}" class="btn btn-sm btn-default"><i class="fa fa-plus"></i> Add Description</a></p>
                        @endif
                    </div>
                </div>
            </div>
            @php $no++; @endphp
            @endforeach
        </div>
        <!-- end accordion -->
    </div>
    <div class="box-footer">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-list"></i> Detail</h3>
            </div>
            <div class="box-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th width="30%">Sort</th>
                            <td>{{ $model->sort }}</td>
                        </tr>
                        <tr>
                            <th width="30%">Active</th>
                            <td>
                                @if($model->active == 1)
                                    <span class="badge bg-blue">ACTIVE</span>
                                @else
                                    <span class="badge bg-red">INACTIVE</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Created by</th>
                            <td>{{ $uti->getUser($model->created_by) }}</td>
                        </tr>
                        <tr>
                            <th>Updated By</th>
                            <td>{{ $uti->getUser($model->updated_by) }}</td>
                        </tr>
                        <tr>
                            <th>Created Date</th>
                            <td>{{ date('d-m-Y H:i:s', strtotime($model->created_at)) }}</td>
                        </tr>
                        <tr>
                            <th>Updated Date</th>
                            <td>{{ date('d-m-Y H:i:s', strtotime($model->updated_at)) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection