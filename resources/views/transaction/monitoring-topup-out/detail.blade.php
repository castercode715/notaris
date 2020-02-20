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
					<td>{{ $model->currency_code=='IDR' ? "Rp ".number_format($model->amount,0,',','.') : "$ ".number_format($model->amount,2,',','.') }}</td>
				</tr>
				<tr>
					<th>Bank</th>
					<td>{{ $model->bank }}<br>{{ $model->account_number.' - '.$model->account_holder_name }}</td>
				</tr>
				<tr>
					<th>Information</th>
					<td>{{ $model->information }}</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="col-md-12">
		<h4> History </h4>
		<table class="table table-hover table-bordered table-condensed" id="datatable-history" width="100%">
			<thead>
				<tr>
					<th>Date</th>
					<th>User</th>
					<th>Status</th>
					<th>Information</th>
				</tr>
			</thead>
		</table>
	</div>
</div>
<div id="btn-action" style="text-align: center;">
	
</div>
<center>
	{{-- @if($model->status == 'SUCCESS') --}}
		<a href="{{ route('monitoring-cashout.process', $model->id) }}" class="btn btn-lg btn-warning" id="btn-process" data-id="{{ $model->id }}" style="display: none;">
			<i class="fa fa-share"></i> Process
		</a>
	{{-- @elseif($model->status == 'PROCESS') --}}
		<a href="{{ route('monitoring-cashout.verify', $model->id) }}" class="btn btn-lg btn-success" id="btn-verify" data-id="{{ $model->id }}" style="display: none;">
			<i class="fa fa-check"></i> Verify
		</a>
    	<a href="{{ route('monitoring-cashout.reject', $model->id) }}" class="btn btn-lg btn-danger" id="btn-reject" data-id="{{ $model->id }}" style="display: none;">
			<i class="fa fa-ban"></i> Reject
		</a>
	{{-- @endif --}}
</center>