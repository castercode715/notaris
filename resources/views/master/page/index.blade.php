@extends('base.main')
@section('title') Page @endsection
@section('page_icon') <i class="fa fa-file"></i> @endsection
@section('page_title') Page @endsection
@section('page_subtitle') list @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <a href="{{ route('page.create') }}" class="btn btn-success modal-show2" title="Create Page">
                <i class="fa fa-plus"></i> Create
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="box box-solid">
        {{-- <div class="box-header">
            <button class="btn btn-sm btn-danger btn-mass-delete"><i class="fa fa-trash"></i></button>
        </div> --}}
        <div class="box-body">
            <div class="box-body">
                <table id="datatable" class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            {{-- <th></th> --}}
                            <th width="50px">No</th>
                            <th>Name</th>
                            <th>Created By</th>
                            <th>Created Date</th>
                            <th width="100px">Action</th>
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
            ajax: "{{ route('table.page') }}",
            columns: [
                // {data : 'checkbox', name : 'checkbox'},
                {data : 'DT_Row_Index', name : 'id'},
                {data : 'description', name : 'description'},
                {data : 'created_by', name : 'created_by'},
                {data : 'created_at', name : 'created_at'},
                {data : 'action', name : 'action'}
            ]
        });
    </script>
@endpush