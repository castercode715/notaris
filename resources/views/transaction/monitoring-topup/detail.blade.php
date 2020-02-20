<div class="row">
	<div class="col-md-6">
		<table class="table table-bordered">
			<tbody>
				<tr>
					<th width="30%">Tran. Number</th>
					<td>{{ $model->transaction_number }}</td>
				</tr>
				<tr>
					<th>Tran. Date</th>
					<td>{{ $model->date }}</td>
				</tr>
				<tr>
					<th>Investor</th>
					<td>{{ $model->investor }}</td>
				</tr>
				<tr>
					<th>Current Status</th>
					<td>{!! $status !!}</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="col-md-6">
		<table class="table table-bordered">
			<tbody>
				<tr>
					<th width="30%">Currency</th>
					<td>{{ $model->currency_code }}</td>
				</tr>
				<tr>
					<th>Amount</th>
					<td>
						@if($model->currency_code=='IDR')
							{{ "Rp ".number_format($model->amount,0,',','.') }}
						@elseif($model->currency_code=='USD')
							{{ "$ ".number_format($model->amount,2,',','.') }}
						@else
							{{ "Rp ".number_format($model->amount,0,',','.') }}
						@endif
					</td>
				</tr>
				<tr>
					<th>Information</th>
					<td>{{ $model->information }}</td>
				</tr>
				<tr>
					<th>Method</th>
					<td>{!! $method !!}</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="col-md-12">
		<h4> History </h4>
		<div style="overflow-x: scroll; width: 100%;">
			<div style="width: 1000px;">
				<table class="table table-hover table-bordered table-condensed" id="datatable-history" width="100%">
					<thead>
						<tr>
							<th width="15%">Date</th>
							<th>User</th>
							<th>Status</th>
							<th>Information</th>
							<th width="30%">Tran. Receipt File</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>
<div id="btn-action" style="text-align: center;">
	
</div>
<center>
	<a href="{{ route('monitoring-topup.verify', $model->id) }}" class="btn btn-lg btn-success" id="btn-verify" data-id="{{ $model->id }}" style="display: none;">
		<i class="fa fa-check"></i> Verify
	</a>
	<a href="javascript:void(0)" class="btn btn-lg btn-danger" id="btn-reject" data-id="{{ $model->id }}" style="display: none;">
		<i class="fa fa-close"></i> Reject
	</a>
	<a href="javascript:void(0)" class="btn btn-lg btn-warning" id="btn-recheck" data-id="{{ $model->id }}" style="display: none;">
		<i class="fa fa-reply"></i> Recheck
	</a>
</center>