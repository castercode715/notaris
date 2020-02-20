<table class="table table-striped table-condensed">
	<tr>
		<th>Module</th>
		<td>{{ $model->module }}</td>
	</tr>
	<tr>
		<th>Link</th>
		<td>{{ $model->link }}</td>
	</tr>
	<tr>
		<th>Icon</th>
		<td>{{ $model->icon }}</td>
	</tr>
	<tr>
		<th>Sort</th>
		<td>{{ $model->sort }}</td>
	</tr>
	<tr>
		<th>Parent</th>
		<td>{{ $model->parent_id == null ? '' : $model->getParent() }}</td>
	</tr>
	
	<tr>
		<th>Active</th>
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
</table>