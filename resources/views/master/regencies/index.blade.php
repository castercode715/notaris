@extends('base.main')
@section('title') Regencies @endsection
@section('page_icon') <i class="fa fa-map"></i> @endsection
@section('page_title') Regencies @endsection
@section('page_subtitle') list @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
			<a href="/master/regency/import"> <img src="/images/icon-excel.png" height="35"></a>&nbsp;&nbsp; 
            <a href="{{ route('regencies.create') }}" class="btn btn-success" title="Create">
                <i class="fa fa-plus"></i> Create
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="box box-solid">
	<div class="box-body">
		<div class="box-body">
	        <table id="datatable" class="table table-hover table-bordered">
	            <thead>
	                <tr>
	                	<th width="5%">No</th>
                        <th>ID</th>
                        <th>Province</th>
                        <th>Name</th>
                        <th width="10%">Action</th>
	                </tr>
	            </thead>
	        </table>
	    </div>
	</div>
</div>
@endsection

@push('scripts')
<script>
    $('#datatable').DataTable({
        responsive : true,
        processing : true,
        serverSide : true,
        ajax: "{{ route('table.regencies') }}",
        columns: [
            {data : 'DT_Row_Index', name : 'id'},
            {data : 'id', name : 'id'},
            {data : 'province', name : 'province'},
            {data : 'regency', name : 'regency'},
            {data : 'action', name : 'action'}
        ]
    });
</script>
@endpush