<script>


window.table = $('#datatable').DataTable({
    responsive : true,
    processing : true,
    serverSide : true,
    order: [[ 0, 'desc' ]],
    ajax: "{{ route('table.kpr.asset') }}",
    columns: [
        {data : 'code', name : 'code'},
        {data : 'name', name : 'name'},
        {data : 'status', name : 'status'},
        {data : 'booked_by', name : 'booked_by'},
        {data : 'installment', name : 'installment'},
        {data : 'action', name : 'action'}
    ]
});

$('#published').on('click', function(){
    table.columns(2).search('Published').draw();
});

$('#draft').on('click', function(){
    table.columns(2).search('Draft').draw();
});

$('#booked').on('click', function(){
    table.columns(2).search('Sold').draw();
});

$('#un').on('click', function(){
    table.columns(2).search('Unpublish').draw();
});

$('#btn-reload').on('click', function(){
    table.ajax.reload();
    // reloadWidget();
});

$('#btn-clear-filter').on('click', function(){
    clearFilter();
    // window.location.href= "{{ route('monitoring-topup.index') }}";
});

setInterval( function () {
    table.ajax.reload( null, false ); // user paging is not reset on reload
    // reloadWidget();
}, 10000 );

function clearFilter() {
  table.columns(2).search("").draw();
}

</script>