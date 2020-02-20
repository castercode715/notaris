<script type="text/javascript">
    /* TAB ACTION */

$('.active[data-toggle="tab"]').each(function(e) {
    var $this = $(this);

    $this.tab('show');
    return false;
});

$('[data-toggle="tabajax-investor"]').click(function(e) {
    var $this = $(this),
        loadurl = $this.attr('href'),
        targ = $this.attr('data-target');

    $.get(loadurl, function(data) {
        $(targ).html(data);

        $('#dataInvestor').DataTable({
            responsive : true,
            processing : true,
            serverSide : true,
            ajax: "{{ route('table.investor.kpr', $model->code) }}",
            columns: [
                // {data : 'DT_RowIndex', name : 'id'},
                {data : 'investor_name', name : 'investor_name'},
                {data : 'date_', name : 'date_'},
                {data : 'price_', name : 'price_'},
                {data : 'installment2', name : 'installment2'},
                {data : 'tenor_', name : 'tenor_'},
                {data : 'status_', name : 'status_'},
                {data : 'action', name : 'action'}
            ],
            order : [[1, 'desc']]
        });
    });

    $this.tab('show');
    return false;
});
</script>