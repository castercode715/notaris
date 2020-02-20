<script type="text/javascript">
    $(document).ready(function(){

        $("#tenor").keypress(function (e) {
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                return false;
            }
        });

        $( '#price' ).mask('000,000,000,000', {reverse: true});
        $( '#installment' ).mask('000,000,000,000', {reverse: true});
        $( '#maintenance' ).mask('000,000,000,000', {reverse: true});

    })

    $('.dynamic').change(function(){
        if($(this).val() != ''){
            var id = $(this).attr('id'),
                value = $(this).val(),
                table = $(this).data('table'),
                key = $(this).data('key'),
                token = $('input[name="_token"]').val();

            $.ajax({
                url : "{{ route('employee.fetch') }}",
                method : 'post',
                data : {
                    id : id,
                    value : value,
                    _token : token,
                    table : table,
                    key : key
                },
                success : function(result){
                    $('#'+table).html(result);
                }
            });
        }
    });
</script>

<script type="text/javascript">
    Dropzone.options.apake3 = false;
    var apake3 = new Dropzone('#apake3', {
      url: '{{ route('apt.asset.file-asset') }}',
      params: { _token: $('meta[name="csrf-token"]').attr('content') },
      paramName: 'img',
      acceptedFiles: 'application/pdf',
      maxFiles: 1,
      maxFilesize: 128,
      addRemoveLinks: true,
      parallelUploads: 1
    });

    apake3.on('success', function(file, data) {
      $('#file').val(data);
    });

    apake3.on('removedfile', function(file) {
      $('#file').val('');
    });
</script>

<script type="text/javascript">
    Dropzone.options.apake2 = false;
    var apake2 = new Dropzone('#apake2', {
      url: '{{ route('apt.asset.featured') }}',
      params: { _token: $('meta[name="csrf-token"]').attr('content') },
      paramName: 'img',
      acceptedFiles: 'image/*',
      maxFiles: 1,
      maxFilesize: 128,
      addRemoveLinks: true,
      parallelUploads: 1
    });

    apake2.on('success', function(file, data) {
      $('#featured').val(data);
    });

    apake2.on('removedfile', function(file) {
      $('#featured').val('');
    });
</script>

<script type="text/javascript">
    Dropzone.options.apake = false;

    var apake = new Dropzone('#apake', {
        url: '{{ route('apt.asset.images') }}',
        params: { _token: $('meta[name="csrf-token"]').attr('content') },
        paramName: 'img',
        acceptedFiles: 'image/*',
        maxFiles: 7,
        maxFilesize: 128,
        addRemoveLinks: true,
        parallelUploads: 2
    });

    apake.on('success', function(file, data) {
        file.bebas = $('#bebasNo').val();
        $('#terserah').append('<input type="hidden" id="img_' + $('#bebasNo').val() + '" name="image[]" value="' + data + '">');
        $('#bebasNo').val(parseInt($('#bebasNo').val()) + 1);
    });

    apake.on('removedfile', function(file) {
        $('#img_' + file.bebas).remove();
    });
</script>

<script>
    window.table = $('#datatable').DataTable({
        responsive : true,
        processing : true,
        serverSide : true,
        ajax: "{{ route('table.apt.asset') }}",
        columns: [
            {data : 'DT_Row_Index', name : 'code_apt'},
            {data : 'code_apt', name : 'code_apt'},
            {data : 'name', name : 'name'},
            {data : 'type_apt', name : 'type_apt'},
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