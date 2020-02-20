@extends('base.main')
@section('title') Member @endsection
@section('page_icon') <i class="fa fa-user"></i> @endsection
@section('page_title') Detail Member @endsection

@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <a href="{{ route('member.edit', base64_encode($model->id)) }}" class="btn btn-success" title="Edit member">
                <i class="fa fa-edit"></i> Edit
            </a>
            <a href="{{ route('member.delete', base64_encode($model->id)) }}" class="btn btn-danger btn-delete2" title="Delete">
                <i class="fa fa-trash"></i> Delete
            </a>
            <a href="{{ route('member.create') }}" class="btn btn-success" title="Create member">
                <i class="fa fa-plus"></i> Create
            </a>
            <a href="{{ route('member.index') }}" class="btn btn-success" title="Manage member">
                <i class="fa fa-list"></i> Manage
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="box box-solid">
    <div class="box-body">
        <div class="box-body">
            <table id="datatable" class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th width="50px">No</th>
                        <th>Language</th>
                        <th>Name</th>
                        <th width="200px">Icon</th>
                        {{-- <th width="60px">Action</th> --}}
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
            ajax: "{{ route('table.memberlang', base64_encode($model->id)) }}",
            columns: [
                {data : 'DT_Row_Index', name : 'id'},
                {data : 'language', name : 'language'},
                {data : 'description', name : 'description'},
                {data : 'image', name : 'image'},
            ]
        });
    </script>
@endpush