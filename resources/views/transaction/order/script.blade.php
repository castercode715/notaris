<script type="text/javascript">
    window.table = $('#datatable').DataTable({
        responsive : true,
        processing : true,
        serverSide : true,
        order: [[ 0, "desc" ]],
        ajax: "{{ route('table.order.ecommerce') }}",
        columns: [
            {data : 'DT_Row_Index', name : 'id'},
            {data : 'no_order', name : 'no_order'},
            {data : 'date_transaction', name : 'date_transaction'},
            {data : 'investor_id', name : 'investor_id'},
            {data : 'status', name : 'status'},
            {data : 'action', name : 'action'}
        ]
    });

    $('#new').on('click', function(){
        table.columns(4).search('NEW').draw();
        // table.columns(2).search(today).draw();
    });

    $('#process').on('click', function(){
        table.columns(4).search('PROCESS').draw();
        // table.columns(2).search(today).draw();
    });

    $('#delivery').on('click', function(){
        table.columns(4).search('DELIVERY').draw();
        // table.columns(2).search(today).draw();
    });

    $('#received').on('click', function(){
        table.columns(4).search('RECEIVED').draw();
        // table.columns(2).search(today).draw();
    });

    setInterval( function () {
      table.ajax.reload( null, false ); // user paging is not reset on reload
      reloadWidget();
      updateLastReloadDate();
    }, 20000 );

    
</script>