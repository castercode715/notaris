@extends('base.main')
@section('title') Category News @endsection
@section('page_icon') <i class="fa fa-newspaper-o"></i> @endsection
@section('page_title') Category News @endsection
@section('page_subtitle') list @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <a href="{{ route('news-category.create') }}" class="btn btn-success" title="Create Category News">
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
                            <th width="6%">No</th>
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
            ajax: "{{ route('table.news-category') }}",
            columns: [
                // {data : 'checkbox', name : 'checkbox'},
                {data : 'DT_Row_Index', name : 'id'},
                {data : 'name', name : 'name'},
                {data : 'action', name : 'action'}
            ]
        });
    </script>
@endpush