@extends('base.main')
@section('title') Voucher @endsection
@section('page_icon') <i class="fa fa-id-card"></i> @endsection
@section('page_title') Voucher @endsection
@section('page_subtitle') list @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <a href="{{ route('vouchers.create') }}" class=" btn btn-success" title="Create Voucher">
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
	                        <th>Value</th>
                            <th>Remain Quota</th>
	                        <th>Time Of Use</th>
                            <th>Active</th>
	                        <th>Status</th>
	                        <th width="10%"></th>
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
            order: [[ 0, 'desc' ]],
            serverSide : true,
            ajax: "{{ route('table.vouchers') }}",
            columns: [
                // {data : 'checkbox', name : 'checkbox'},
                {data : 'DT_Row_Index', name : 'id'},
                {data : 'title', name : 'title'},
                {data : 'type', name : 'type'},
                {data : 'value', name : 'value'},
                {data : 'remain_quota', name : 'remain_quota'},
                {data : 'time_of_use', name : 'time_of_use'},
                {data : 'active', name : 'active'},
                {data : 'status', name : 'status'},
                {data : 'action', name : 'action'}
            ]
        });
    </script>
@endpush