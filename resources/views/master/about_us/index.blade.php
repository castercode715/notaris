@extends('base.main')
@section('title') About Us @endsection
@section('page_icon') <i class="fa fa-folder"></i> @endsection
@section('page_title') About Us @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <a href="{{ route('about-us.create') }}" class="btn btn-success" title="Create">
                <i class="fa fa-plus"></i> Create
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="box box-solid">
        <div class="box-body">
            <div class="box-body">
                <table id="datatable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Title</th>
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
            ajax: "{{ route('table.about-us') }}",
            columns: [
                {data : 'DT_Row_Index', name : 'id'},
                {data : 'title', name : 'title'},
                {data : 'action', name : 'action'}
            ]
        });
    </script>
@endpush