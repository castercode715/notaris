<script type="text/javascript">
   $("#floor").keypress(function (e) {
            
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                return false;
            }
        });
</script>
<script type="text/javascript">
    /* TAB ACTION */

$('.active[data-toggle="tab"]').each(function(e) {
    var $this = $(this);

    $this.tab('show');
    return false;
});

$('[data-toggle="tabajax-unit"]').click(function(e) {
    var $this = $(this),
        loadurl = $this.attr('href'),
        targ = $this.attr('data-target');

    $.get(loadurl, function(data) {
        $(targ).html(data);

        $('#dataUnit').DataTable({
            responsive : true,
            processing : true,
            serverSide : true,
            ajax: "{{ route('table.apt.floor', $model->code_apt) }}",
            columns: [
                {data : 'DT_Row_Index', name : 'code_floor'},
                {data : 'name', name : 'name'},
                {data : 'action', name : 'action'}
            ],
            order : [[0, 'desc']]
        });
    });

    $this.tab('show');
    return false;
});
</script>