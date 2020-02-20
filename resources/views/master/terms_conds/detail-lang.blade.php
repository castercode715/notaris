<table class="table table-striped table-condensed">
	<h3 style="margin-top: -7px;"><i class="fa fa-language"></i> 	{{ $row->language }}</h3>
	<tr>
		<th>Title</th>
		<td>{{ $row->title }}</td>
	</tr>

	<tr>
		<th>Description</th>
		<td>{!! html_entity_decode($row->description) !!}</td>
	</tr>
	<tr>
		<th><b>View</b></th>
		<td>{{ $row->view }}</td>
	</tr>
	<tr>
		<th>Sort</th>
		<td>{{ $row->sort }}</td>
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