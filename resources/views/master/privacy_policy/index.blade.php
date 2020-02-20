@extends('base.main')
@section('title') Privacy Policy @endsection
@section('page_icon') <i class="fa fa-user"></i> @endsection
@section('page_title') Privacy Policy @endsection
@section('page_subtitle') list @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <a href="{{ route('privacy-policy.create') }}" class="btn btn-success" title="Create Privacy Policy">
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
            ajax: "{{ route('table.privacy-policy') }}",
            columns: [
                {data : 'DT_Row_Index', name : 'id'},
                {data : 'title', name : 'title'},
                {data : 'action', name : 'action'}
            ]
        });
    </script>
@endpush