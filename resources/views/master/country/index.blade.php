@extends('base.main')
@section('title') Country @endsection
@section('page_icon') <i class="fa fa-user"></i> @endsection
@section('page_title') Country @endsection
@section('page_subtitle') list @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <a href="{{ route('countries.create') }}" class="btn btn-success modal-show2" title="Create Country">
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
                            <th>Code</th>
                            <th>Name</th>
                            <th>Language</th>
                            <th>Currency</th>
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
            ajax: "{{ route('table.countries') }}",
            columns: [
                // {data : 'checkbox', name : 'checkbox'},
                {data : 'DT_Row_Index', name : 'id'},
                {data : 'id', name : 'id'},
                {data : 'name', name : 'name'},
                {data : 'language', name : 'language'},
                {data : 'currency', name : 'currency'},
                {data : 'action', name : 'action'}
            ]
        });
    </script>
@endpush