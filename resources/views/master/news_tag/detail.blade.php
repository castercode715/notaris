@extends('base.main')
@section('title') News Tag @endsection
@section('page_icon') <i class="fa fa-newspaper-o"></i> @endsection
@section('page_title') News Tag @endsection

@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
        	<a href="{{ route('news-tag.edit', base64_encode($model->id)) }}" class="btn btn-success" title="Edit Asset tag">
                <i class="fa fa-edit"></i> Edit
            </a>
            <a href="{{ route('news-tag.delete', base64_encode($model->id)) }}" class="btn btn-danger btn-delete2" title="Delete">
                <i class="fa fa-trash"></i> Delete
            </a>
        	<a href="{{ route('news-tag.create') }}" class="btn btn-success" title="Create Asset tag">
                <i class="fa fa-plus"></i> Create
            </a>
            <a href="{{ route('news-tag.index') }}" class="btn btn-success" title="Manage Asset tag">
                <i class="fa fa-list"></i> Manage
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="box box-solid">
    <div class="box-body">
        <div class="box-body">
            <h4>News Tag</h4>
            <table class="table table-bordered table-hover">
                <tr>
                    <th width="200px">Active</th>
                    <td>{{ $model->active == 1 ? 'Yes' : 'No' }}</td>
                </tr>
            </table>
            <hr>
            <h4>Language</h4>
            <table id="datatable" class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th width="50px">No</th>
                        <th>Language</th>
                        <th>Name</th>
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
            ajax: "{{ route('table.news-tag-detail', base64_encode($model->id)) }}",
            columns: [
                {data : 'DT_Row_Index', name : 'id'},
                {data : 'language', name : 'language'},
                {data : 'description', name : 'description'},
            ]
        });
    </script>
@endpush