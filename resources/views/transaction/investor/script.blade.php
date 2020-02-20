@push('scripts')
<script>

	$('body').on('click','#btn-topup', function(e){
		e.preventDefault();

		var me = $(this),
			url = me.attr('href'),
			investor = me.data('investor');

		$.ajax({
			url : url,
			method : 'get',
			success : function(r) {
				$('#modal-topup').modal('show');
				document.getElementById('investor_id').val()
			},
			error : function(e) {
				$('#modal-topup').modal('hide');
	            swal({
	                type : 'error',
	                title : 'Error 401',
	                text : 'Unauthorized action'
	            });
			}
		});
	});

	$('.active[data-toggle="tab"]').each(function(e) {
	    var $this = $(this);

	    $this.tab('show');
	    return false;
	});

	$('[data-toggle="tabajax-asset-fav"]').click(function(e) {
	    var $this = $(this),
	        loadurl = $this.attr('href'),
	        targ = $this.attr('data-target');

	    $.get(loadurl, function(data) {
	        $(targ).html(data);

	        $('#datatable-asset-fav').DataTable({
			    responsive : true,
			    processing : true,
			    serverSide : true,
			    ajax: "{{ route('table.investor-asset-fav', $model->id) }}",
			    columns: [
			        {data : 'DT_Row_Index', name : 'id'},
			        {data : 'asset_name', name : 'asset_name'},
			        {data : 'comment', name : 'comment'}
			    ]
			});
	    });

	    $this.tab('show');
	    return false;
	});

	$('[data-toggle="tabajax-asset"]').click(function(e) {
	    var $this = $(this),
	        loadurl = $this.attr('href'),
	        targ = $this.attr('data-target');

	    $.get(loadurl, function(data) {
	        $(targ).html(data);

	        $('#datatable-asset').DataTable({
			    responsive : true,
			    processing : true,
			    serverSide : true,
			    ajax: "{{ route('table.investor-asset', $model->id) }}",
			    columns: [
			        {data : 'asset_name', name : 'asset_name'},
			        {data : 'date_start', name : 'date_start'},
			        {data : 'date_end', name : 'date_end'},
			        {data : 'invest_tenor', name : 'invest_tenor'},
			        {data : 'number_interest', name : 'number_interest'},
			        {data : 'amount', name : 'amount'},
			        {data : 'status', name : 'status'},
			        {data : 'action', name : 'action'}
			    ]
			});
	    });

	    $this.tab('show');
	    return false;
	});

	$('[data-toggle="tabajax-referral"]').click(function(e){
		var $this = $(this),
	        loadurl = $this.attr('href'),
	        targ = $this.attr('data-target');
		
		$.get(loadurl, function(data) {
			$(targ).html(data);

			$('#datatable-parent').DataTable({
			    responsive : true,
			    processing : true,
			    serverSide : true,
			    ajax: "{{ route('referral.parent', $model->id) }}",
			    columns: [
			        {data : 'DT_Row_Index', name : 'DT_Row_Index'},
			        {data : 'full_name', name : 'full_name'},
			        {data : 'used_date', name : 'used_date'},
			        {data : 'amount', name : 'amount'},
			    ],
			    order : [[0, 'desc']]
			});

			$('#datatable-child').DataTable({
			    responsive : true,
			    processing : true,
			    serverSide : true,
			    ajax: "{{ route('referral.child', $model->id) }}",
			    columns: [
			        {data : 'DT_Row_Index', name : 'DT_Row_Index'},
			        {data : 'full_name', name : 'full_name'},
			        {data : 'used_date', name : 'used_date'},
			        {data : 'amount', name : 'amount'},
			    ],
			    order : [[0, 'desc']]
			});

		});

	    $this.tab('show');
	    return false;
	});

	$('[data-toggle="tabajax-transaction"]').click(function(e) {
	    var $this = $(this),
	        loadurl = $this.attr('href'),
	        targ = $this.attr('data-target');

	    $.get(loadurl, function(data) {
	        $(targ).html(data);

	        $('#datatable-transaction').DataTable({
			    responsive : true,
			    processing : true,
			    serverSide : true,
			    ajax: "{{ route('table.transaction', $model->id) }}",
			    columns: [
			        {data : 'DT_Row_Index', name : 'id'},
			        {data : 'date', name : 'date'},
			        {data : 'asset_name', name : 'asset_name'},
			        {data : 'full_name', name : 'full_name'},
			        {data : 'amount', name : 'amount'},
			    ],
			    order : [[0, 'desc']]
			});
	    });

	    $this.tab('show');
	    return false;
	});

	$('[data-toggle="tabajax-balance"]').click(function(e) {
	    var $this = $(this),
	        loadurl = $this.attr('href'),
	        targ = $this.attr('data-target'),
	        investor = $this.attr('data-investor');

	    $.get(loadurl, function(data) {
	        $(targ).html(data);

	        $('#datatable-balance').DataTable({
			    responsive : true,
			    processing : true,
			    serverSide : true,
			    ajax: "{{ route('table.balance', $model->id) }}",
			    columns: [
			        {data : 'DT_Row_Index', name : 'id'},
			        {data : 'date', name : 'date'},
			        {data : 'credit', name : 'credit'},
			        {data : 'debit', name : 'debit'},
			        {data : 'balance', name : 'balance'},
			        {data : 'information', name : 'information'},
			        // {orderFixed : ['id', 'desc']}
			    ]
			});

			$.ajax({
				url : '{{ route('balance.get', $model->id) }}',
				method : 'get',
				success : function(r){
					document.getElementById("balance-number").innerHTML = currencyFormatter.format(r);
				}
			});

			$.ajax({
				url : '{{ route('balance.getInvest', $model->id) }}',
				method : 'get',
				success : function(r){
					document.getElementById("active-invesment").innerHTML = r + ' Assets';
				}
			});

	    });

	    $this.tab('show');
	    return false;
	});

	const currencyFormatter = new Intl.NumberFormat('in-ID', { 
		style: 'currency', 
		currency: 'IDR' 
	});

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

    function stopRKey(evt) 
    {
        var evt = (evt) ? evt : ((event) ? event : null);
        var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
        if ((evt.keyCode == 13) && (node.type=='text'))  {return false;}
    }

    document.onkeypress = stopRKey;

    $(document).on('click','.profile-submit-btn',function()
    {
        $('#profile-form').submit();
    });

</script>
@endpush