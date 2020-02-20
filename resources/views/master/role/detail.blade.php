<table class="table table-striped table-condensed">
	<tr>
		<th>Nama Role</th>
		<td>{{ $model->role }}</td>
	</tr>
	<tr>
		<th>Code</th>
		<td>{{ $model->code }}</td>
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