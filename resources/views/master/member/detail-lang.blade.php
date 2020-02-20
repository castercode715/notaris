<table class="table table-striped table-condensed">
	<h3 style="margin-top: -7px;"><i class="fa fa-language"></i> 	{{ $row->language }}</h3>
	
	<tr>
		<th>Description</th>
		<td>{{ $row->description }}</td>
	</tr>
	<tr>
		<th>Image</th>
		<td><img style="width: 250px; height: 100px;" src="/images/member/{{ $row->image }}" class="img-thumbnail"></td>
	</tr>
	
	<tr>
		<th>Active</th>
		<td>{{ $row->active == 1 ? 'Ya' : 'Tidak' }}</td>
	</tr>
	<tr>
		<th>Created By</th>
		<td>{{ $uti->getUser($row->created_by) }}</td>
	</tr>
	<tr>
		<th>Created Date</th>
		<td>{{ date('d-m-Y H:i:s', strtotime($row->created_at)) }}</td>
	</tr>

	
	
	
</table>