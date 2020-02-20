@extends('base.main')
@section('title') VA BCA Payment Confirmation @endsection
@section('page_icon') <i class="fa fa-cog"></i> @endsection
@section('page_title') VA BCA Payment Confirmation @endsection
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
/*.hide {
	visibility: none;
}*/
</style>
@endpush

@section('content')
<div class="row">
	<div class="col-lg-3 col-xs-6">
		<div class="small-box bg-green">
			<div class="inner">
				<div class="loader"></div>
				<h3 id="success-value">
					{{ $success }}
				</h3>
				<p><b>Success</b></p>
			</div>
			<div class="icon">
				<i class="fa fa-check"></i>
			</div>
			<a href="javascript:void(0)" class="small-box-footer" id="success" data-value="success">
				Show data <i class="fa fa-arrow-circle-right"></i>
			</a>
		</div>
	</div>
	<div class="col-lg-3 col-xs-6">
		<div class="small-box bg-yellow">
			<div class="inner">
				<div class="loader"></div>
				<h3 id="failed-value">
					{{ $failed }}
				</h3>
				<p><b>Failed</b></p>
			</div>
			<div class="icon">
				<i class="fa fa-close"></i>
			</div>
			<a href="javascript:void(0)" class="small-box-footer" id="failed" data-value="failed">
				Show data <i class="fa fa-arrow-circle-right"></i>
			</a>
		</div>
	</div>
</div>
<div class="box box-solid">
	<div class="box-header">
        <button class="btn bg-aqua btn-sm" id="btn-reload"><i class="fa fa-refresh"></i> Reload Data</button>
        <button class="btn bg-grey btn-sm" id="btn-clear-filter"><i class="fa fa-filter"></i> Clear Filter</button>
        <div class="box-tools pull-right">
            {{-- <button class="btn bg-grey btn-sm" id="btn-hint" data-toggle="modal" data-target="#modal-hint"><i class="fa  fa-info-circle"></i></button> --}}
            <div class="margin">
                <span id="box-last-reload"><i class="fa fa-clock-o"></i> {{ date('d/m/Y H:i:s').' WIB' }} </span>
            </div>
        </div>
    </div>
	<div class="box-body">
		<div class="box-body">
			<table id="datatable" class="table table-hover table-bordered">
				<thead>
					<tr>
						<th width="10%">Tran. Date</th>
						<th width="15%">Customer Number</th>
						<th width="25%">Customer Name</th>
						<th width="10%">Total Amount</th>
						<th width="5%">Status</th>
						<th width="10%">Created At</th>
						<th width="5%" align="center">Action</th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>
	@include('transaction.va_bca.modal')
@endsection

@include('transaction.va_bca.script')
