@extends('base.main')
@section('title') Cashback Voucher @endsection
@section('page_icon') <i class="fa fa-id-card"></i> @endsection
@section('page_title') Cashback Voucher @endsection
@section('page_subtitle') list @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <a href="{{ route('cashback.create') }}" class=" btn btn-success" title="Create Voucher">
                <i class="fa fa-plus"></i> Create
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="box box-solid">
    <div class="box-body">
        <div class="box-body">
            <table id="datatable" class="table table-bordered">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Rem. Quota</th>
                        <th>Status</th>
                        <th width="5%"></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $('#datatable').DataTable({
        responsive : true,
        processing : true,
        // order: [[ 0, 'desc' ]],
        serverSide : true,
        ajax: "{{ route('cashback.data') }}",
        columns: [
            {data : 'DT_Row_Index', name : 'DT_Row_Index'},
            {data : 'title', name : 'title'},
            {data : 'type', name : 'type'},
            {data : 'amount', name : 'amount'},
            {data : 'remain_quota', name : 'remain_quota'},
            {data : 'status', name : 'status'},
            {data : 'action', name : 'action'}
        ]
    });
</script>
@endpush