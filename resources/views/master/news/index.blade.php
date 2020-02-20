@extends('base.main')
@section('title') News @endsection
@section('page_icon') <i class="fa fa-newspaper-o"></i> @endsection
@section('page_title') News @endsection

@section('menu')
   <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <a href="{{ route('news.create') }}" class="btn btn-success" title="Create">
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
                            <th>Title</th>                            
                            <th width="5%">Views</th>
                            <th width="5%">Active</th>
                            <th width="15%">Created At</th>
                            <th width="15%">Created By</th>
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
        ajax: "{{ route('table.news') }}",
        columns: [
            {data : 'DT_Row_Index', name : 'id'},
            {data : 'title', name : 'title'},
            {data : 'view_count', name : 'view_count'},
            {data : 'active', name : 'active'},
            {data : 'created_at', name : 'created_at'},
            {data : 'created_by', name : 'created_by'},
            {data : 'action', name : 'action'}
        ]
    });
</script>
@endpush