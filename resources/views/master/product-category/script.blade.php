<script type="text/javascript">

    /* ==========================================
        ACTION CLICKED 
    ============================================ */ 
    $('body').on('click', '#btn-editCat', function () {

        var id = $(this).data('id');
        var url = $(this).data('hreff');

        $.ajax({
            url: url,
            dataType: 'html',
            success: function(response)
            {
                $('#modal-category').modal('show');
                $('#modal-body-category').html(response);
                
            },
            error : function(error)
            {
                $('#modal-category').modal('hide');
                swal({
                    type : 'error',
                    title : 'Error 401',
                    text : 'Ajax Error'
                });
            }
        });

    });

    $('body').on('click', '#btn-deleteCat', function(e){
    e.preventDefault();

    var me = $(this),
        url = me.data('hreff'),
        title = me.data('title'),
        csrf_token = $('meta[name="csrf-token"]').attr('content');
    
    swal({
        title : 'Are you sure delete '+title+' ?',
        type : 'warning',
        showCancelButton : true,
        confirmButtonColor : '#3085d6',
        cancelButtonColor : '#d33',
        confirmButtonText : 'Ya, hapus!'
    }).then((result)=>{
        if(result.value){
            $.ajax({
                url : url,
                type : 'post',
                data : {
                    '_method': 'DELETE',
                    '_token' : csrf_token
                },
                success : function(r){
                    location.reload();
                    swal({
                        type : 'success',
                        title : 'Success',
                        text : 'Data berhasil dihapus'
                    });
                },
                error : function(er){
                    if(er.status == 400)
                    {
                        swal({
                            type : 'error',
                            title : 'Failed',
                            text : 'Can not be deleted, parent has related with others!'
                        });

                    }else{

                        swal({
                            type : 'error',
                            title : 'Failed',
                            text : 'Data gagal dihapus'
                        });
                        
                    }
                }
            });
        }
    });

});
</script>


<script type="text/javascript">
       
        $.fn.extend({
            treed: function (o) {
      
                var openedClass = 'fa fa-minus-circle';
                var closedClass = 'fa fa-plus-circle';
              
                if (typeof o != 'undefined'){

                    if (typeof o.openedClass != 'undefined'){
                        openedClass = o.openedClass;
                    }

                    if (typeof o.closedClass != 'undefined'){
                        closedClass = o.closedClass;
                    }
                };
      
        /* initialize each of the top levels */
        var tree = $(this);
        tree.addClass("tree");
        tree.find('li').has("ul").each(function () {
            var branch = $(this);
            branch.prepend("");
            branch.addClass('branch');
            branch.on('click', function (e) {
                if (this == e.target) {
                    var icon = $(this).children('i:first');
                    icon.toggleClass(openedClass + " " + closedClass);
                    $(this).children().children().toggle();
                }
            })
            branch.children().children().toggle();
        });
        /* fire event from the dynamically added icon */
        tree.find('.branch .indicator').each(function(){
            $(this).on('click', function () {
                $(this).closest('li').click();
            });
        });
        /* fire event to open branch if the li contains an anchor instead of text */
        tree.find('.branch>a').each(function () {
            $(this).on('click', function (e) {
                $(this).closest('li').click();
                e.preventDefault();
            });
        });
        /* fire event to open branch if the li contains a button instead of text */
        tree.find('.branch>button').each(function () {
            $(this).on('click', function (e) {
                $(this).closest('li').click();
                e.preventDefault();
            });
        });
    }
});
/* Initialization of treeviews */
$('#tree1').treed();
    </script>

    <script type="text/javascript">
        Dropzone.options.apake2 = false;
        var apake2 = new Dropzone('#apake2', {
          url: '{{ route('product.category.icon') }}',
          params: { _token: $('meta[name="csrf-token"]').attr('content') },
          paramName: 'img',
          acceptedFiles: 'image/*',
          maxFiles: 1,
          maxFilesize: 128,
          addRemoveLinks: true,
          parallelUploads: 1
        });

        apake2.on('success', function(file, data) {
          $('#icon_category').val(data);
        });

        apake2.on('removedfile', function(file) {
          $('#icon_category').val('');
        });
    </script>

    <script>
        $('#datatable').DataTable({
            responsive : true,
            processing : true,
            serverSide : true,
            ajax: "{{ route('table.product-category') }}",
            columns: [
                // {data : 'checkbox', name : 'checkbox'},
                {data : 'DT_Row_Index', name : 'id'},
                {data : 'name', name : 'name'},
                {data : 'created_by', name : 'created_by'},
                {data : 'created_at', name : 'created_at'},
                {data : 'action', name : 'action'}
            ]
        });
    </script>