<script type="text/javascript">
   $('#dataUnitFloor').DataTable({
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
</script>