<div class="row">
	<div class="col-md-6">
		<table class="table table-bordered">
			<tbody>
				<tr>
					<th width="30%">Date</th>
					<td>{{ $model->created_at }}</td>
				</tr>
				<tr>
					<th>Name</th>
					<td>{{ $model->name }}</td>
				</tr>
				<tr>
					<th>HP</th>
					<td>{{ $model->hp }}</td>
				</tr>
				<tr>
					<th>Email</th>
					<td>{{ $model->email }}</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="col-md-6">
		<table class="table table-bordered">
			<tbody>
				<tr>
					<th width="30%">Location</th>
					<td>{{ $model->location }}</td>
				</tr>
				<tr>
					<th>Guarantee</th>
					<td>{{ $model->guarantee }}</td>
				</tr>
				<tr>
					<th>Status</th>
					<td id="lrStatus">{{ $model->status }}</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<div id="btn-action" style="text-align: center;">
	
</div>
<center>
	@if ($model->status == 'NEW')
		<a href="{{ route('loan-request.process', $model->id) }}" class="btn btn-lg btn-success" id="btn-process" data-id="{{ $model->id }}">
			<i class="fa fa-hand"></i> Process
		</a>
		<a href="{{ route('loan-request.decline', $model->id) }}" class="btn btn-lg btn-danger" id="btn-decline" data-id="{{ $model->id }}">
			<i class="fa fa-share"></i> Decline
		</a>
	@elseif ($model->status == 'PROCESS')
		<a href="{{ route('loan-request.decline', $model->id) }}" class="btn btn-lg btn-danger" id="btn-decline" data-id="{{ $model->id }}">
			<i class="fa fa-share"></i> Decline
		</a>
	@endif
</center>