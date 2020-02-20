<table class="table table-striped table-condensed">
	<tr>
		<th>Module</th>
		<td>{{ $model->page }}</td>
	</tr>
	<tr>
		<th>Role</th>
		<td>{{ $model->page2 }}</td>
	</tr>
	<tr>
		<th>Access Create</th>
		<td>{{ $model->access_create == 1 ? 'Ya' : 'Tidak' }}</td>
	</tr>
	<tr>
		<th>Access Read</th>
		<td>{{ $model->access_read == 1 ? 'Ya' : 'Tidak' }}</td>
	</tr>
	<tr>
		<th>Access Update</th>
		<td>{{ $model->access_update == 1 ? 'Ya' : 'Tidak' }}</td>
	</tr>
	<tr>
		<th>Access Delete</th>
		<td>{{ $model->access_delete == 1 ? 'Ya' : 'Tidak' }}</td>
	</tr>
	<tr>
		<th>Access View</th>
		<td>{{ $model->access_view == 1 ? 'Ya' : 'Tidak' }}</td>
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