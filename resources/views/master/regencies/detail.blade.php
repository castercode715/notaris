@extends('base.main')
@section('title') Regencies @endsection
@section('page_icon') <i class="fa fa-map"></i> @endsection
@section('page_title') Regencies @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
        	<a href="{{ route('regencies.edit', base64_encode($model->id) ) }}" class="btn btn-success" title="Edit regencies">
                <i class="fa fa-edit"></i> Update
            </a>
            <a href="{{ route('regencies.delete', base64_encode($model->id)) }}" class="btn btn-danger btn-delete2" title="Delete">
                <i class="fa fa-trash"></i> Delete
            </a>
        	<a href="{{ route('regencies.create') }}" class="btn btn-success" title="Create regencies">
                <i class="fa fa-plus"></i> Create
            </a>
            <a href="{{ route('regencies.index') }}" class="btn btn-success" title="Manage">
                <i class="fa fa-list"></i> Manage
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="box box-solid">
	<div class="box-body">
		<div class="box-body">
			<table class="table table-hover table-bordered">
                <tbody>
                	<tr>
                		<th width="20%">ID</th>
                		<td>{{ $regencies->id }}</td>
                	</tr>
                	<tr>
                		<th width="20%">Province</th>
                		<td>{{ $province->name }}</td>
                	</tr>
                </tbody>
			</table>
			<hr>
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
            ajax: "{{ route('table.regencies-detail', $model->id) }}",
            columns: [
                {data : 'DT_Row_Index', name : 'id'},
                {data : 'language', name : 'language'},
                {data : 'name', name : 'name'},
            ]
        });
    </script>
@endpush