@if(count($model) != 0)
<div class="alert alert-info">
    <strong>{{ $count }}</strong>  Investors added this asset to their Favorite Asset List
</div>
<table class="table table-bordered table-hovered">
	<thead>
		<tr>
			<th width="25%">Investor Name</th>
			<th>Comment</th>
			<th width="16%">Date</th>
		</tr>
	</thead>
	<tbody>
		@foreach($model as $r)
		<tr>
			<td>{{ $r->full_name }}</td>
			<td>{{ $r->comment }}</td>
			<td>{!! date('d-m-Y H:i', strtotime($r->created_at)).' WIB' !!}</td>
		</tr>
		@endforeach
	</tbody>
</table>
@else
<div class="alert alert-info alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <i class="fa fa-warning"></i> <strong>Alert!</strong> No one added this asset to their favorite asset list
</div>
@endif