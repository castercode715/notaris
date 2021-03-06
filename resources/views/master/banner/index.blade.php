@extends('base.main')
@section('title') Banner @endsection
@section('page_icon') <i class="fa fa-image"></i> @endsection
@section('page_title') Banner @endsection
@section('page_subtitle') list @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <a href="{{ route('banner.create') }}" class="btn btn-success" title="Create Banner">
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
                            {{-- <th>No</th> --}}
                            <th>Title</th>
                            <th width="150px">Created By</th>
                            <th width="120px">Created Date</th>
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
            ajax: "{{ route('table.banner') }}",
            columns: [
                // {data : 'checkbox', name : 'checkbox'},
                // {data : 'DT_Row_Index', name : 'id'},
                {data : 'title', name : 'title'},
                {data : 'created_by', name : 'created_by'},
                {data : 'created_at', name : 'created_at'},
                {data : 'action', name : 'action'}
            ]
        });
    </script>
@endpush