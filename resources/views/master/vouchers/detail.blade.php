@extends('base.main')
@section('title') Voucher @endsection
@section('page_icon') <i class="fa fa-id-card"></i> @endsection
@section('page_title') Detail Voucher @endsection

@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
        	@if($model->status != 'PUBLISHED' && $model->status != 'CANCELED')
	        	<a href="{{ route('vouchers.publish', base64_encode($model->id)) }}" class="btn btn-primary btn-publish" title="Publish">
	                <i class="fa fa-check"></i> Publish
	            </a>
        	@endif
        	@if($model->status != 'CANCELED')
	            <a href="{{ route('vouchers.cancel', base64_encode($model->id)) }}" class="btn btn-danger btn-delete2" title="Canceled">
	                <i class="fa fa-close"></i> Cancel
	            </a>
            @endif
        	<!-- @if($model->status == 'DRAFT') -->
	        	<a href="{{ route('vouchers.edit', base64_encode($model->id) ) }}" class="btn btn-success" title="Edit Security Guide">
	                <i class="fa fa-edit"></i> Update
	            </a>
            <!-- @endif -->
        	<a href="{{ route('vouchers.create') }}" class="btn btn-success" title="Create">
                <i class="fa fa-plus"></i> Create
            </a>
            <a href="{{ route('vouchers.index') }}" class="btn btn-success" title="Manage">
                <i class="fa fa-list"></i> Manage
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="nav-tabs-custom">
	<ul class="nav nav-tabs tabs-up" id="friends">
		<li>
			<a href="#tab_detail" data-toggle="tab" class="active" id="detail_tab" rel="tooltip"> <i class="fa fa-list"></i>&nbsp;&nbsp; Detail </a>
		</li>
		<li>
			<a href="{{ route('vouchers.investor', $model->id) }}" id="balance_tab" data-target="#tab_balance" data-toggle="tabajax-balance" rel="tooltip"> <i class="fa fa-users"></i>&nbsp;&nbsp; Investor </a>
		</li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="tab_detail">
			@include('master.vouchers.tab.detail')
		</div>
		<div class="tab-pane" id="tab_balance">
		</div>
	</div>
</div>
@endsection

@push('scripts')
<script>
	$('[data-toggle="tabajax-balance"]').click(function(e) {
	    var $this = $(this),
	        loadurl = $this.attr('href'),
	        targ = $this.attr('data-target');

	    $.get(loadurl, function(data) {
	        $(targ).html(data);

			$('#datatable').DataTable({
		        responsive : true,
		        processing : true,
		        serverSide : true,
		        ajax: "{{ route('table.vouchers.investor', $model->id) }}",
		        columns: [
		            {data : 'DT_Row_Index', name : 'id'},
		            {data : 'id', name : 'id'},
		            {data : 'full_name', name : 'full_name'},
		            {data : 'used', name : 'used'}
		        ]
		    });
	    });

	    $this.tab('show');
	    return false;
	});

	$('.active[data-toggle="tab"]').each(function(e) {
	    var $this = $(this);

	    $this.tab('show');
	    return false;
	});

	$('body').on('click', '.btn-publish', function(e){
	    e.preventDefault();

	    var link = $(this).attr('href');

	    swal({
	        title : 'Are you sure ?',
	        type : 'warning',
	        showCancelButton : true,
	        confirmButtonColor : '#3085d6',
	        cancelButtonColor : '#d33',
	        confirmButtonText : 'Ya, publish'
	    }).then((result)=>{
	        if(result.value){
	            window.location = link;
	        }
	    });
	});

</script>
@endpush