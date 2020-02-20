<div class="box-body">
	<a href="{{ route('investor.internet-banking.create', $model->id) }}" class="btn btn-primary btn-xs pull-right modal-show" title="Add New Internet Banking">
        <i class="fa fa-plus"></i> Add New Internet Banking
    </a>
	<table id="datatable-internet_banking" class="table table-bordered table-hover table-condensed" style="width: 100%;">
        <thead>
            <tr>
                <th>No</th>
                <th>Bank</th>
                <th>Account Name</th>
                <th>Account Number</th>
                <th>Payment Methode</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
</div>

@push('scripts')
<script>
$('#datatable-internet_banking').DataTable({
    responsive : true,
    processing : true,
    serverSide : true,
    ajax: "{{ route('table.investor.internet-banking', $model->id) }}",
    columns: [
        {data : 'DT_Row_Index', name : 'id'},
        {data : 'bank', name : 'bank'},
        {data : 'account_holder_name', name : 'account_holder_name'},
        {data : 'account_number', name : 'account_number'},
        {data : 'payment_method', name : 'payment_method'},
        {data : 'action', name : 'action'}
    ]
});
</script>
@endpush