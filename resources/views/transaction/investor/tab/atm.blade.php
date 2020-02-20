<div class="box-body">
    <div>
        <a href="{{ route('atm.create') }}" class="btn btn-primary btn-xs pull-right modal-show2" title="Add New ATM" data-id="{{ $model->id }}"> 
            <i class="fa fa-plus"></i> Add New ATM
        </a>
    </div>
	<table id="datatable-atm" class="table table-bordered table-hover" style="width: 100%;">
        <thead>
            <tr>
                {{-- <th width="50px">No</th> --}}
                <th>Bank</th>
                <th>Account Name</th>
                <th>Account Number</th>
                <th width="80px">Action</th>
            </tr>
        </thead>
    </table>
</div>

@push('scripts')
<script>
$('#datatable-atm').DataTable({
    responsive : true,
    processing : true,
    serverSide : true,
    ajax: "{{ route('table.atm', $model->id) }}",
    columns: [
        // {data : 'DT_Row_Index', name : 'id'},
        {data : 'bank', name : 'bank'},
        {data : 'account_holder_name', name : 'account_holder_name'},
        {data : 'account_number', name : 'account_number'},
        {data : 'action', name : 'action'}
    ]
});

</script>
@endpush