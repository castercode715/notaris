<table class="table table-striped table-condensed">
	<tr>
		<th>Attribute Name</th>
		<td>{{ $model->name }}</td>
	</tr>
	
	<tr>
		<th>Created By</th>
		<td>{{ $uti->getUser($model->created_by) }}</td>
	</tr>
	<tr>
		<th>Created Date</th>
		<td>{{ date('d-m-Y H:i:s', strtotime($model->created_at)) }}</td>
	</tr>
	@if($uti->getUser($model->updated_by != null))
		
		<tr>
			<th>Updated By</th>
			<td>{{ $uti->getUser($model->updated_by) }}</td>
		</tr>
		<tr>
			<th>Updated Date</th>
			<td>{{ date('d-m-Y H:i:s', strtotime($model->updated_at)) }}</td>
		</tr> 
	@else
		
	@endif
	
</table>