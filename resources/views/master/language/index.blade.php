@extends('base.main')
@section('title') Language @endsection
@section('page_icon') <i class="fa fa-language"></i> @endsection
@section('page_title') Language @endsection
@section('page_subtitle') list @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <a href="{{ route('language.create') }}" class="btn btn-success modal-show" title="Create Category News">
                <i class="fa fa-plus"></i> Create
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
                        {{-- <th></th> --}}
                        <th>No</th>
                        <th>Code</th>
                        <th>Language</th>
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
            ajax: "{{ route('table.MstLanguage') }}",
            columns: [
                // {data : 'checkbox', name : 'checkbox'},
                {data : 'DT_Row_Index', name : 'code'},
                {data : 'code', name : 'code'},
                {data : 'language', name : 'language'},
                {data : 'action', name : 'action'}
            ]
        });
    </script>
@endpush