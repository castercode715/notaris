<table class="table table-bordered" id="datatable">
	<thead>
		<tr>
			<th>Country</th>
			<th>Province</th>
			<th>Regency</th>
			<th>District</th>
			<th>ID</th>
			<th>Name</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
		@if(!empty($data))
			@foreach($data as $r)
			<tr>
				<td>{{ $r->country }}</td>
				<td>{{ $r->province }}</td>
				<td>{{ $r->regency }}</td>
				<td>{{ $r->district }}</td>
				<td>{{ $r->villages_id }}</td>
				<td>{{ $r->village }}</td>
				<td>
					<a href="{{ route('village.edit', $r->villages_id) }}" class="modal-show-village btn btn-xs btn-primary edit" title="Edit {{ $r->village }}"><i class="fa fa-edit"></i></a>
					<a href="{{ route('village.destroy', $r->villages_id) }}" class="btn-delete-village btn btn-xs btn-danger" title="Delete {{ $r->village }}"><i class="fa fa-trash"></i></a>
				</td>
			</tr>
			@endforeach
		@else
			<tr>
				<td colspan="7">
					<center><h4>Data not found, try other key</h4></center>
				</td>
			</tr>
		@endif
	</tbody>
</table>