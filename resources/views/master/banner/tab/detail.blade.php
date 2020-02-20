<div class="row">
	<div class="col-md-6">
		<table class="table table-condensed table-striped">
			<tbody>
				<tr>
					<th>Title</th>
					<td>{!! $model->title !!}</td>
				</tr>
				<tr>
					<th>Subtitle</th>
					<td>{!! $model->subtitle !!}</td>
				</tr>
				<tr>
					<th>Description</th>
					<td>{!! html_entity_decode($model->desc) !!}</td>
				</tr>
				<tr>
					<th>Active</th>
					<td>{{ $model->active == 1 ? 'Ya' : 'Tidak' }}</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="col-md-6">
		<table class="table table-condensed table-striped">
			<tbody>
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
</div>

<h4>Position</h4>
<table class="table table-striped table-condensed">
	<thead>
		<tr>
			<th>Page</th>
			<th>Position</th>
			<th>Order</th>
		</tr>
	</thead>
	<tbody>
		@foreach($detail as $row)
			<tr>
				<td>{{ $row->page }}</td>
				<td>{{ $row->position }}</td>
				<td>{{ $row->order }}</td>
			</tr>
		@endforeach
	</tbody>
</table>