

@extends('base.main')
@section('title') Terms & Conditions @endsection
@section('page_icon') <i class="fa fa-folder"></i> @endsection
@section('page_title') Detail Terms & Conditions @endsection

@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
           
            <a href="{{ route('terms-conds.index') }}" class="btn btn-success" title="Manage">
                <i class="fa fa-list"></i> Manage
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="box box-solid">
        <div class="box-header">
            <button class="btn btn-sm btn-danger btn-mass-delete"><i class="fa fa-trash"></i></button>
        </div>
        <div class="box-body">
            <table id="datatable" class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Title</th>
                        <th>Language</th>
                        <th>Created Date</th>
                        <th>Action</th>
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
            ajax: "{{ route('table.terms-conds-lang',$model->id) }}",
            columns: [
                // {data : 'checkbox', name : 'checkbox'},
                {data : 'DT_Row_Index', name : 'id'},
                {data : 'title', name : 'title'},
                {data : 'language', name : 'language'},
                {data : 'created_at', name : 'created_at'},
                {data : 'action', name : 'action'}
            ]
        });
    </script>
@endpush
