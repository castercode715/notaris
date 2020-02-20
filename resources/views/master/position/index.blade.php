@extends('base.main')
@section('title') Position  @endsection
@section('page_icon') <i class="fa fa-folder"></i> @endsection
@section('page_title') Position @endsection
@section('page_subtitle') list @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <a href="{{ route('position.create') }}" class="btn btn-success modal-show2" title="Create Position">
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
                            <th>Name</th>
                            <th>Code</th>
                            <th>Page</th>
                            <th width="80px">Action</th>
                            {{-- <th></th> --}}
                            {{-- <th>No</th> --}}
                            {{-- <th>Created By</th>
                            <th>Created Date</th> --}}
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
            ajax: "{{ route('table.position') }}",
            columns: [
                {data : 'description', name : 'description'},
                {data : 'position_code', name : 'position_code'},
                {data : 'page', name : 'page'},
                {data : 'action', name : 'action'}
                // {data : 'checkbox', name : 'checkbox'},
                // {data : 'DT_Row_Index', name : 'id'},
                /*{data : 'created_by', name : 'created_by'},
                {data : 'created_at', name : 'created_at'},*/
            ]
        });
    </script>
@endpush