<table class="table table-striped table-condensed">
	<tr>
		<th>Product Name</th>
		<td>{{ $model->getNameProduct($result->product_id) }}</td>
	</tr>

	@foreach($attr as $key)
		<tr>
			<th>{{ $key->name }}</th>
			<td>{{ $key->value }}</td>
		</tr>
	@endforeach
	
</table>