<table class="table table-bordered">
	<tr>
		<th>Name</th>
		<td>{{ $model->name }}</td>
	</tr>

	<tr>
		<th>Sort</th>
		<td>{{ $model->sort }}</td>
	</tr>

	<tr>
        <th>Created Date</th>
        <td>{{ date('d-m-Y H:i', strtotime($model->created_at)) }} WIB</td>
    </tr>

	<tr>
        <th>Created by</th>
        <td>{{ $uti->getUser($model->created_by) }}</td>
    </tr>

    @if($model->updated_by != NULL)
    	<tr>
	        <th>Updated Date</th>
	        <td>{{ date('d-m-Y H:i', strtotime($model->updated_at)) }} WIB</td>
	    </tr>

	    <tr>
	        <th>Updated By</th>
	        <td>{{ $uti->getUser($model->updated_by) }}</td>
	    </tr>
    @endif
</table>