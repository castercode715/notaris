@extends('base.main')
@section('title') Offering Purchase @endsection
@section('page_icon') <i class="fa fa-envelope"></i> @endsection
@section('page_title') Offering Purchase @endsection

@section('content')
    <div class="box box-solid">
        <div class="box-body">
            <div class="box-body">
            	<table id="datatable" class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>From</th>
                            <th>Send Date</th>
                            <th width="5%">Action</th>
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
        ajax: "{{ route('table.offering-purchase') }}",
        columns: [
            {data : 'DT_Row_Index', name : 'id'},
            {data : 'investor', name : 'investor'},
            {data : 'created_at', name : 'created_at'},
            {data : 'action', name : 'action'}
        ]
    });
</script>
@endpush