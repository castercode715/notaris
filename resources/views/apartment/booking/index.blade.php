@extends('base.main')
@section('title') Booking @endsection
@section('page_icon') <i class="fa fa-building"></i> @endsection
@section('page_title') Booking @endsection
@section('page_subtitle') list @endsection
@push('css')
<style type="text/css">
    .loader {
        background-size: contain;
        background-image: url('/images/loading.svg');
        background-repeat: no-repeat;
        display: none;
        height: 40px;
    }
    table#datatable tbody tr td {
        vertical-align: middle;
    }
</style>
@endpush
@section('content')
<div class="row">
    <div class="col-lg-2">
        <div class="small-box bg-aqua">
            <div class="inner">
                <div class="loader"></div>
                <h3 id="value-new" class="value"></h3>
                <p>NEW</p>
            </div>
            <a href="#" class="small-box-footer btn-filter" data-status="NEW">SHOW ME <i class="fa fa-search"></i></a>
        </div>
    </div>
    <div class="col-lg-2">
        <div class="small-box bg-yellow">
            <div class="inner">
                <div class="loader"></div>
                <h3 id="value-interview" class="value"></h3>
                <p>INTERVIEW</p>
            </div>
            <a href="#" class="small-box-footer btn-filter" data-status="INTERVIEW">SHOW ME <i
                    class="fa fa-search"></i></a>
        </div>
    </div>
    <div class="col-lg-2">
        <div class="small-box bg-green">
            <div class="inner">
                <div class="loader"></div>
                <h3 id="value-approved" class="value"></h3>
                <p>APPROVED</p>
            </div>
            <a href="#" class="small-box-footer btn-filter" data-status="APPROVED">SHOW ME <i
                    class="fa fa-search"></i></a>
        </div>
    </div>
    <div class="col-lg-2">
        <div class="small-box bg-red">
            <div class="inner">
                <div class="loader"></div>
                <h3 id="value-rejected" class="value"></h3>
                <p>REJECTED</p>
            </div>
            <a href="#" class="small-box-footer btn-filter" data-status="REJECTED">SHOW ME <i
                    class="fa fa-search"></i></a>
        </div>
    </div>
    <div class="col-lg-2">
        <div class="small-box bg-purple">
            <div class="inner">
                <div class="loader"></div>
                <h3 id="value-canceledadm" class="value"></h3>
                <p>CANCELED BY ADMIN</p>
            </div>
            <a href="#" class="small-box-footer btn-filter" data-status="CANCELEDADM">SHOW ME <i
                    class="fa fa-search"></i></a>
        </div>
    </div>
    <div class="col-lg-2">
        <div class="small-box bg-teal">
            <div class="inner">
                <div class="loader"></div>
                <h3 id="value-canceled" class="value"></h3>
                <p>CANCELED BY USER</p>
            </div>
            <a href="#" class="small-box-footer btn-filter" data-status="CANCELED">SHOW ME <i
                    class="fa fa-search"></i></a>
        </div>
    </div>
</div>

<div class="box box-solid">
    <div class="box-header">
        <div class="row">
            <div class="col-lg-6">
                <button class="btn btn-sm bg-yellow" id="btn-reset">RESET</button>
                <button class="btn btn-sm bg-gray" id="btn-refresh">REFRESH</button>
            </div>
            {{-- <div class="col-lg-6">
                <div class="input-group">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" class="form-control pull-right" id="date-range-filter">
                    <span class="input-group-btn">
                        <button type="button" id="btn-daterange-filter" class="btn bg-aqua">Search</button>
                    </span>
                </div>
            </div> --}}
        </div>
    </div>
    <div class="box-body" style="padding:20px;">
        <div class="table-responsive">
            <table class="table table-bordered" id="datatable">
                <thead>
                    <tr>
                        <th width="80px">ID</th>
                        <th width="120px">Booking date</th>
                        <th>Full Name</th>
                        <th>Asset Code</th>
                        <th>Asset</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-detail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modal-detail-title"></h4>
            </div>
            <div class="modal-body" id="box-detail"></div>
            <div class="modal-footer" id="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-assign" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Assign Surveyor</h4>
            </div>
            <div class="modal-body" id="modal-form-assign">
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" id="btn-submit-surveyor">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
var table = $('#datatable').DataTable({
    responsive : true,
    processing : true,
    serverSide : true,
    order: [[ 0, 'desc' ]],
    ajax: "{{ route('booking.data') }}",
    columns: [
        {data : 'id', name : 'id'},
        {data : 'booking_date', name : 'booking_date'},
        {data : 'full_name', name : 'full_name'},
        {data : 'code_unit', name : 'code_unit'},
        {data : 'unit', name : 'unit'},
        {data : 'status', name : 'status'},
        {data : 'action', name : 'action'}
    ]
});

var newentry = $('#value-new');
var interview   = $('#value-interview');
var approved = $('#value-approved');
var rejected = $('#value-rejected');
var canceled = $('#value-canceled');
var canceledadm = $('#value-canceledadm');

function reloadWidget() {
    $.ajax({
        url: 'booking/widget',
        method: 'get',
        dataType: 'JSON',
        beforeSend : function(){
			$('.loader').show();
			$('.value').addClass('hide');
		},
        success: function(response){
            newentry.html(response.new);
            interview.html(response.interview);
            approved.html(response.approved);
            rejected.html(response.rejected);
            canceled.html(response.canceled);
            canceledadm.html(response.canceledadm);
        },
        error: function(xhr, status, error){
            var err = eval("(" + xhr.responseText + ")");
            swal.fire(
                'Error 500',
                err.Message,
                'error'
            );
        },
		complete : function(){
			$('.loader').hide();
			$('.value').removeClass('hide');
		},
    });
}

$('#btn-refresh').on('click', function(e){
    e.preventDefault();
    table.ajax.reload();
    reloadWidget();
});

$('#btn-reset').on('click', function(e){
    e.preventDefault();
    table.columns().search("").draw();
});

$('.btn-filter').on('click', function(e){
    e.preventDefault();
    let status = $(this).data('status');
    table.columns(5).search(status).draw();
});

$(window).bind("load", function() {
    setTimeout(reloadWidget(), 4000);
});

setInterval( function () {
	table.ajax.reload( null, false );
	reloadWidget();
}, 20000 );

$('.modal').on('show.bs.modal', function () {
    $('.modal').not($(this)).each(function () {
        $(this).modal('hide');
    });
});

$('body').on('click', '.btn-detail', function(e){
    e.preventDefault();
    let id = $(this).data('id'),
        url = $(this).attr('href');

    $.ajax({
        url : url,
        type: 'get',
        dataType: 'html',
        beforeSend: function(){
			$('.loader-big').show();
		},
        success: function(response){
            $('#modal-detail').modal('show');
            $('#modal-detail-title').text('Detail Booking ID #'+id);
            $('#box-detail').html(response);
        },
        error: function(xhr, status, error) {
            var err = eval("(" + xhr.responseText + ")");
            swal.fire(
                'Error 500',
                err.Message,
                'error'
            );
        },
		complete: function(){
			$('.loader-big').hide();
		},
    });
});

</script>
@endpush