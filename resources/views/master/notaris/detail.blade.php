<table class="table table-bordered">
	<tr>
		<th width="30%">Name</th>
		<td>{{ $model->name }}</td>
	</tr>

	<tr>
		<th width="30%">Information</th>
		<td><p>{{ $model->information }}</p></td>
	</tr>

	<tr>
        <th width="30%">Created Date</th>
        <td>{{ date('d-m-Y H:i', strtotime($model->created_at)) }} WIB</td>
    </tr>

	<tr>
        <th width="30%">Created by</th>
        <td>{{ $uti->getUser($model->created_by) }}</td>
    </tr>

    @if($model->updated_by != NULL)
    	<tr>
	        <th width="30%">Updated Date</th>
	        <td>{{ date('d-m-Y H:i', strtotime($model->updated_at)) }} WIB</td>
	    </tr>

	    <tr>
	        <th width="30%">Updated By</th>
	        <td>{{ $uti->getUser($model->updated_by) }}</td>
	    </tr>
    @endif
</table>