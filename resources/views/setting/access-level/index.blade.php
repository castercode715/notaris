@extends('base.main')
@section('title') Access Level @endsection
@section('page_icon') <i class="fa fa-cog"></i> @endsection
@section('page_title') Access Level @endsection
@section('page_subtitle') list @endsection

@section('content')
    <div class="box box-solid">
        <div class="box-body">
            <table id="datatable" class="table table-hover table-condensed table-bordered">
                <thead>
                    <tr>
                        <th width="50px">No</th>
                        <th>Role</th>
                        <th width="100px">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('#datatable').DataTable({
            responsive : true,
            processing : true,
            serverSide : true,
            ajax: "{{ route('table.access-level') }}",
            columns: [
                {data : 'DT_Row_Index', name : 'id'},
                {data : 'role', name : 'role'},
                {data : 'action', name : 'action'}
            ]
        });
    </script>
@endpush