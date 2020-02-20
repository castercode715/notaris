<table class="table table-striped table-condensed">
	<tr>
		<th>Nama Bank</th>
		<td>{{ $model->name }}</td>
	</tr>
	<tr>
		<th>Jenis Kartu</th>
		<td>{{ $model->getCardType() }}</td>
	</tr>
	<tr>
		<th>Logo</th>
		<td><img src='/images/bank/{{ $model->image_logo }}' width='100px' /></td>
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