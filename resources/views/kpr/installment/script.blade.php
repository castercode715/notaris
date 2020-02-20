<script>
    window.table = $('#datatable').DataTable({
        responsive : true,
        processing : true,
        serverSide : true,
        ajax: "{{ route('table.kpr.installment') }}",
        columns: [
            {data : 'DT_Row_Index', name : 'app_number'},
            {data : 'app_number', name : 'app_number'},
            {data : 'investor_id', name : 'investor_id'},
            {data : 'code_kpr', name : 'code_kpr'},
            {data : 'booked_date', name : 'booked_date'},
            {data : 'status', name : 'status'},
            {data : 'action', name : 'action'}
        ]
    });

    $('#btn-reload').on('click', function(){
        table.ajax.reload();
    });

    $('#btn-clear-filter').on('click', function(){
        clearFilter();
        // window.location.href= "{{ route('monitoring-topup.index') }}";
    });

    function clearFilter() {
      table.columns(2).search("").draw();
    }

    setInterval( function () {
        table.ajax.reload( null, false ); 
    }, 10000 );

    
</script>