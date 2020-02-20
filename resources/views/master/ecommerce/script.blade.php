
<script>
    $('#treeview-checkbox-demo').treeview({
        debug : true,
        // data : ['1', '15']
    });

    $('#treeview-checkbox-demo').on('click', function(){ 
        $('#values').val(
            $('#treeview-checkbox-demo').treeview('selectedValues')
        );
    });


</script>

<script type="text/javascript">

    $(document).ready(function(){

        $('#tree').fancytree({
            checkbox: true,
            select: function(event, data) {
              var selNodes = data.tree.getSelectedNodes();

              var selKeys = $.map(selNodes, function(node) {
                return '<input type="hidden" name="class_id[]" value="' + node.key + '">';
              });

              $('#class_ids').html(selKeys);
            }
          });

        // var SampleJSONData = [
        //     {
        //         "id": 0,
        //         "title": 'Horse'
        //     }, {
        //         "id": 1,
        //         "title": 'Birds',
        //         "subs": [
        //             {
        //                 "id": 10,
        //                 "title": 'Piegon'
        //             }, {
        //                 "id": 11,
        //                 "title": 'Parrot'
        //             }
        //         ]
        //     }
        // ];

        // var SampleJSONData = '';
        var comboTree1, comboTree3;
        
        $.getJSON({ 
            url: "{{ route('product.ecommerce.getChild') }}",
            context: document.body,
            success: function(data){
                SampleJSONData = data;

                comboTree3 = $('#justAnInputBox1').comboTree({
                    source : SampleJSONData,
                    isMultiple: true,
                    cascadeSelect: true,
                    collapse: true,
                    selected: []
                });

                
            }

        });

        var selectedTitles = comboTree3.getSelectedItemsTitle();


    });

    

    

   


</script>
<script type="text/javascript">
    

    // $(document).ready(function(){
    //     var SampleJSONData = null;
    //     $.ajax({ 
    //         url: "{{ route('product.ecommerce.getChild') }}",
    //         context: document.body,
    //         success: function(data){
    //                 SampleJSONData = data.data
    //     }});
    // });

    // var comboTree1, comboTree2;

    // jQuery(document).ready(function($) {

    //         comboTree1 = $('#justAnInputBox').comboTree({
    //             source : SampleJSONData,
    //             isMultiple: true,
    //             cascadeSelect: false,
    //             collapse: true
    //         });
            
    //         comboTree3 = $('#justAnInputBox1').comboTree({
    //             source : SampleJSONData,
    //             isMultiple: true,
    //             cascadeSelect: true,
    //             collapse: false
    //         });

    //         comboTree3.setSource(SampleJSONData2);


    //         comboTree2 = $('#justAnotherInputBox').comboTree({
    //             source : SampleJSONData,
    //             isMultiple: false
    //         });
    // });




    $('#tenor').change(function(){
        var id = $(this).val();
        console.log(id);
        $.ajax({
            url: '/ecommerce/product/getvalue/'+id,
            type: 'get',
            success: function(data){
                $('#bunga').val(data.bunga);
            },
        });
    });
    </script>



<script>
	$(document).ready(function(){

        $("#tenor").keypress(function (e) {
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                return false;
            }
        });

        // $("#bunga").keypress(function (e) {
        //     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        //         return false;
        //     }
        // });

        $("#discount").keypress(function (e) {
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                return false;
            }
        });

        $( '#price' ).mask('000,000,000,000', {reverse: true});
        

    })

	/* Script datatable */

	window.table = $('#datatable').DataTable({
	    responsive : true,
	    processing : true,
	    serverSide : true,
	    ajax: "{{ route('table.ecommerce.product') }}",
	    columns: [
	        {data : 'DT_Row_Index', name : 'id'},
	        {data : 'name', name : 'name'},
	        {data : 'status', name : 'status'},
	        {data : 'created_at', name : 'created_at'},
	        {data : 'created_by', name : 'created_by'},
	        {data : 'action', name : 'action'}
	    ]
	});

	/* End Script datatable */
</script>

<script type="text/javascript">
	/* Script Dropzone */

    Dropzone.options.apake2 = false;
    var apake2 = new Dropzone('#apake2', {
      url: '{{ route('product.ecommerce.featured') }}',
      params: { _token: $('meta[name="csrf-token"]').attr('content') },
      paramName: 'img',
      acceptedFiles: 'image/*',
      maxFiles: 1,
      maxFilesize: 200,
      addRemoveLinks: true,
      parallelUploads: 1
    });

    apake2.on('success', function(file, data) {
      $('#featured').val(data);
    });

    apake2.on('removedfile', function(file) {
      $('#featured').val('');
    });

    //////////////////////////////////////////

    Dropzone.options.apake = false;
    var apake = new Dropzone('#apake', {
        url: '{{ route('product.ecommerce.images') }}',
        params: { _token: $('meta[name="csrf-token"]').attr('content') },
        paramName: 'img',
        acceptedFiles: 'image/*',
        maxFiles: 7,
        maxFilesize: 200,
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

    /* End Script Dropzone */
</script>

<script type="text/javascript">

	var maxField = 10,
        addButton = $('.add_button'),
		addAttrButton = $('.add_attr_button'),
		attr_wrapper = $('.attributes_wrapper'),
        x = 0,
        y = 1;

    $(addAttrButton).click(function(){
    	
        if(y < maxField){
            y++;
            $(attr_wrapper).append('<div class="attributes_wrapper attr_row"><div class="col-md-3"><label for="images" class="control-label"> </label><select name="attr_name[]" class="form-control select attr_name attr_'+y+'" id="attr_name" required="required"></select></div><div class="col-md-7"><label for="images" class="control-label"> </label><input type="text" name="attr_value[]" class="form-control" required="required"></div><div class="col-md-2"><a href="javascript:void(0);" class="remove_attr_button btn btn-sm btn-danger" style="margin-top: 20px;" title="Remove"><i class="fa fa-close"></i></a></div></div>');

            // var value = $('#category_asset_id').val();
            var token = $('input[name="_token"]').val();

            $.ajax({
                url : "{{ route('product.ecommerce.fetch-attributes') }}",
                method : 'post',
                data : {
                    _token : token
                },
                success : function(result){
                    $('.attr_'+y).html(result);
                }
            });
        }

    });

    $(attr_wrapper).on('click', '.remove_attr_button', function(e){
    	
	    e.preventDefault();
	    $(this).closest('.attr_row').remove();
	    y--;
	});


</script>
