@extends('base.main')
@section('title') Data News @endsection
@section('page_icon') <i class="fa fa-users"></i> @endsection
@section('page_title') News {{ $model->title }} @endsection
@section('page_subtitle') Detail News @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
        	<a href="{{ route('news.edit', $model->id) }}" class="btn btn-success" title="Edit Employee">
                <i class="fa fa-edit"></i> Update
            </a>
             {{-- onclick="return confirm('Anda yakin?')" --}}
            <a href="{{ route('news.delete', $model->id) }}" class="btn btn-danger btn-delete2" title="Delete">
                <i class="fa fa-trash"></i> Delete
            </a>
        	<a href="{{ route('news.create') }}" class="btn btn-success" title="Create Employee">
                <i class="fa fa-plus"></i> Create
            </a>
            <a href="{{ route('news.index') }}" class="btn btn-success" title="Manage">
                <i class="fa fa-list"></i> Manage
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="box box-solid">
	<div class="box-body">
		<h3>Detail News</h3>
		<div class="row">
			<div class="col-md-6">
				<table class="table table-condensed table-striped">
					<tbody>
						<tr>
							<th>Title</th>
							<td>{{ $model->title }}</td>
						</tr>
						<tr>
							<th>Sub Title</th>
							<td>{{ $model->sub_title }}</td>
						</tr>
						<tr>
							<th>Description</th>
							<td>{!! html_entity_decode($model->desc) !!}</td>
						</tr>
						<tr>
							<th>Iframe</th>
							<td>{{ $model->iframe }}</td>
						</tr>
						<tr>
							<th>Aktif</th>
							<td>{{ $model->active == 1 ? 'Ya' : 'Tidak' }}</td>
						</tr>
						<tr>
							<th>Created By</th>
							<td>{{ $uti->getUser($model->created_by) }}</td>
						</tr>
						<tr>
							<th>Created Date</th>
							<td>{{ date('d-m-Y H:i:s', strtotime($model->created_at)) }}</td>
						</tr>
						<tr>
							<th>Updated By</th>
							<td>{{ $uti->getUser($model->updated_by) }}</td>
						</tr>
						<tr>
							<th>Updated Date</th>
							<td>{{ date('d-m-Y H:i:s', strtotime($model->updated_at)) }}</td>
						</tr>
						
					</tbody>
				</table>
			</div>
			<div class="col-md-3">
				@if($model->image == null)
					<img src="/images/no-img.png" class="img-responsive img-thumbnail">
				@else
					<img src="/images/news/{{ $model->image }}" class="img-responsive img-thumbnail">
				@endif
			</div>
		</div>

		
	</div>
</div>
@endsection