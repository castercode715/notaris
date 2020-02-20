@push('scripts')
<script>
document.body.classList.remove("sidebar-mini-expand-feature");
document.body.classList.add("sidebar-collapse");

var table = $('#datatable').DataTable({
    responsive : true,
    processing : true,
    serverSide : true,
    ajax: "{{ route('table.vabca-inquiry') }}",
    columns: [
        // {data : 'id', name : 'id'},
        {data : 'transaction_date', name : 'transaction_date'},
        {data : 'customer_number', name : 'customer_number'},
        {data : 'customer_name', name : 'customer_name'},
        {data : 'total_amount', name : 'total_amount'},
        {data : 'payment_flag_status', name : 'payment_flag_status'},
        {data : 'created_at', name : 'created_at'},
        {data : 'action', name : 'action'}
    ]
});

$('#btn-reload').on('click', function(){
    table.ajax.reload();
    reloadWidget();
    updateLastReloadDate();
});

function updateLastReloadDate() {
	var today = new Date();
	var dd = today.getDate();
	var mm = today.getMonth()+1; //January is 0!
	var yyyy = today.getFullYear();
	var hour = today.getHours()<10 ? '0'+today.getHours() : today.getHours(),
	  minute = today.getMinutes()<10 ? '0'+today.getMinutes() : today.getMinutes(),
	  second = today.getSeconds()<10 ? '0'+today.getSeconds() : today.getSeconds();

	if(dd<10) {
	dd = '0'+dd
	} 

	if(mm<10) {
	  mm = '0'+mm
	}

	today = dd + '/' + mm + '/' + yyyy+' '+hour+':'+minute+':'+second;
	document.getElementById('box-last-reload').innerHTML = '<i class="fa fa-clock-o"></i> '+today+' WIB';
}

function reloadWidget() {
    $.ajax({
		url : 'widget/vabca-inquiry',
		method : 'get',
		dataType : 'json',
		beforeSend : function(){
			$('.loader').show();
			$('#success-value').addClass('hide');
			$('#failed-value').addClass('hide');
		},
		complete : function(){
			$('.loader').hide();
			$('#success-value').removeClass('hide');
			$('#failed-value').removeClass('hide');
		},
		success : function(r) {
			$('#success-value').html(r.success);
			$('#failed-value').html(r.failed);
		},
		error : function(e) {
			console.log(e);
		}
    });
}

setInterval( function () {
	table.ajax.reload( null, false ); // user paging is not reset on reload
	reloadWidget();
	updateLastReloadDate();
}, 20000 );

$('body').on('click', '.btn-detail', function(e){
    e.preventDefault();

    let id = $(this).data('id'),
    	title = $(this).attr('title');

    $('#modal-title').html(title);

	$.ajax({
		url: 'vabca-inquiry/detail/'+id,
		method: 'get',
		beforeSend: function(){
			$('.loader-big').show();
		},
		complete: function(){
			$('.loader-big').hide();
		},
		success: function(r){
			$('#box-detail').html(r);
			$('#modal-detail').modal('show');
		},
		error: function(e){}
	});
});
</script> 
@endpush