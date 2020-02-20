@extends('base.main')
@section('title') View Voucher Cashback @endsection
@section('page_icon') <i class="fa fa-money"></i> @endsection
@section('page_title') View Voucher Cashback @endsection
@section('page_subtitle') view @endsection
@section('menu')
<div class="box box-solid" style="text-align:right;">
    <div class="box-body">
        @if($model->allowToPublish())
        <a href="{{ route('cashback.publish', $model->id) }}" class="btn btn-primary btn-publish" title="Publish">
            <i class="fa fa-check"></i> Publish
        </a>
        @endif
        @if($model->status == 'PUBLISHED')
        <a href="{{ route('cashback.cancel', $model->id) }}" class="btn btn-warning btn-cancel" title="Canceled">
            <i class="fa fa-close"></i> Cancel
        </a>
        @endif
        @if($model->status != 'PUBLISHED')
        <a href="{{ route('cashback.delete', $model->id) }}" class="btn btn-danger btn-delete2" title="Delete">
            <i class="fa fa-eraser"></i> Delete
        </a>
        @endif
        @if($model->status == 'DRAFT')
        <a href="{{ route('cashback.edit', $model->id) }}" class="btn btn-success" title="Edit">
            <i class="fa fa-edit"></i> Edit
        </a>
        @endif
        <a href="{{ route('cashback.create') }}" class="btn btn-success" title="Create">
            <i class="fa fa-plus"></i> Create
        </a>
        <a href="{{ route('cashback.index') }}" class="btn btn-success" title="Manage">
            <i class="fa fa-list"></i> Manage
        </a>
    </div>
</div>
@endsection

@section('content')
@if (!$model->allowToPublish() && $model->status!='PUBLISHED' && $model->status!='CANCELED')
<div class="alert alert-warning alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h4><i class="icon fa fa-check"></i> Alert!</h4>
    Lengkapi bahasa yang lain dulu sebelum dipublish.
</div>
@endif
<div class="row">
    <div class="col-md-7">
        <div class="box box-solid box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-edit"></i> Description</h3>
            </div>
            <div class="box-body">
                <div class="box-group" id="accordion">
                    <div class="panel box box-solid">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                <strong>
                                    <a href="#collapse1" data-toggle="collapse" data-parent="#accordion">INDONESIA</a>
                                </strong>
                            </h4>
                            <div class="box-tools pull-right">
                                <a href="{{ route('cashback.edit-new', $ind->id) }}" class="btn btn-sm bg-yellow"><i
                                        class="fa fa-edit"></i> Edit</a>
                            </div>
                        </div>
                        <div id="collapse1" class="panel-collapse collapse in">
                            <div class="box-body">
                                <h3>
                                    <strong>{{ $ind->title }}</strong>
                                </h3>
                                @if($ind->image != '')
                                <img src="/images/voucher/{{ $ind->image }}" class="img-responsive"
                                    style="width:100%; padding:10px; border:1px solid #f2f2f2; margin-bottom:15px;" />
                                @endif
                                {!! html_entity_decode($ind->description) !!}
                            </div>
                        </div>
                    </div>
                    @php $no = 2; @endphp
                    @foreach ($other as $item)
                    @php $content = $model->otherLang($item->code); @endphp
                    <div class="panel box box-solid">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                <strong>
                                    <a href="#collapse{{ $no }}" data-toggle="collapse"
                                        data-parent="#accordion">{{ $item->language }}</a>
                                </strong>
                            </h4>
                            @if ($content)
                            <div class="box-tools pull-right">
                                <a href="{{ route('cashback.edit-new', $content->id) }}" class="btn btn-sm bg-yellow"><i
                                        class="fa fa-edit"></i> Edit</a>
                            </div>
                            @else
                            <div class="box-tools pull-right">
                                <a href="{{ route('cashback.create-new', [$model->id, $item->code]) }}"
                                    class="btn btn-sm bg-blue"><i class="fa fa-plus"></i> Create</a>
                            </div>
                            @endif
                        </div>
                        @if ($content)
                        <div id="collapse{{ $no }}" class="panel-collapse collapse">
                            <div class="box-body">
                                <h3><strong>{{ $content->title }}</strong></h3>
                                @if($content->image != '')
                                <img src="/images/voucher/{{ $content->image }}" class="img-responsive"
                                    style="width:100%; padding:10px; border:1px solid #f2f2f2; margin-bottom:15px;" />
                                @endif
                                {!! html_entity_decode($content->description) !!}
                            </div>
                        </div>
                        @endif
                    </div>
                    @php $no++; @endphp
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="box box-solid box-primary">
            <div class="box-header">
                <h3 class="box-title"><i class="fa fa-list-alt"></i> Detail</h3>
            </div>
            <div class="box-body">
                <div class="box-body">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th>Status</th>
                                <td>{!! $model->status !!}</td>
                            </tr>
                            <tr>
                                <th>Amount</th>
                                <td>{!! number_format($model->amount, 0, ',', '.').' IDR' !!}</td>
                            </tr>
                            <tr>
                                <th>Remain Quota</th>
                                <td>{!! ($model->quota - $model->remain_quota).' of '.$model->quota !!}</td>
                            </tr>
                            <tr>
                                <th>Type</th>
                                <td>{!! $model->type !!}</td>
                            </tr>
                            <tr>
                                <th>Date</th>
                                <td>{!! date('d M Y', strtotime($model->date_start)).' - '.date('d M Y',
                                    strtotime($model->date_end)) !!}</td>
                            </tr>
                            <tr>
                                <th>Created By</th>
                                <td>{!! $model->employeeCreate->full_name !!}</td>
                            </tr>
                            <tr>
                                <th>Created At</th>
                                <td>{!! date('d M Y', strtotime($model->created_at)) !!}</td>
                            </tr>
                            <tr>
                                <th>Updated By</th>
                                <td>{!! $model->updated_by == null ? null : $model->employeeUpdate->full_name !!}</td>
                            </tr>
                            <tr>
                                <th>Updated At</th>
                                <td>{!! date('d M Y', strtotime($model->updated_at)) !!}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs" id="investors">
                <li>
                    <a href="" id="redeemed_tab" class="active" data-target="#tab_redeemed" data-toggle="tab"
                        rel="tooltip">
                        Redeemed
                    </a>
                </li>
                <li>
                    <a href="" id="list_tab" data-target="#tab_list" data-toggle="tabajax-list" rel="tooltip">
                        List
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_redeemed">
                    <table id="datatable-redeemed" class="table table-bordered table-striped" width="100%">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Full Name</th>
                                <th>Redeem Date</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="tab-pane active" id="tab_list">
                    <table id="datatable-list" class="table table-bordered table-striped" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Full Name</th>
                                <th>Used</th>
                                <th width="30px"></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>

$('body').on('click','.btn-publish',function(e){
    e.preventDefault();

    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Publish now'
    }).then((result) => {
        if (result.value) {
            window.location.href = $(this).attr('href');
        }
    })
});

$('body').on('click','.btn-cancel',function(e){
    e.preventDefault();

    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Cancel it'
        }).then((result) => {
        if (result.value) {
            window.location.href = $(this).attr('href');
        }
    })
});

// tab ajax
let table_redeemed, table_list;
$('.active[data-toggle="tab"]').each(function(e) {
    $(this).tab('show');

    table_redeemed = $('#datatable-redeemed').DataTable({
        responsive : true,
        processing : true,
        serverSide : true,
        ajax: "{{ route('cashback.redeemed', $model->id) }}",
        columns: [
            {data : 'DT_Row_Index', name : 'DT_Row_Index'},
            {data : 'full_name', name : 'full_name'},
            {data : 'redeem_date', name : 'redeem_date'},
            {data : 'amount', name : 'amount'}
        ]
    });

    table_list = $('#datatable-list').DataTable({
        responsive : true,
        processing : true,
        serverSide : true,
        ajax: "{{ route('cashback.investors', $model->id) }}",
        columns: [
            {data : 'id', name : 'id'},
            {data : 'full_name', name : 'full_name'},
            {data : 'used', name : 'used'},
            {data : 'action', name : 'action'}
        ]
    });

    return false;
});

$('[data-toggle="tabajax-list"]').click(function(e) {
    var $this = $(this),
        targ = $this.attr('data-target');
    $this.tab('show');
    table_list.ajax.reload();
    return false;
});

$('[data-toggle="tab"]').click(function(e) {
    var $this = $(this),
        targ = $this.attr('data-target');
    $this.tab('show');
    table_redeemed.ajax.reload();
    return false;
});

</script>
@endpush