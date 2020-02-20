<div class="row">
	<div class="col-xs-12">
		<h2 class="page-header">
			<i class="fa fa-cube"></i> {{ $model->asset_name }}
			<small class="pull-right">Status: {!! $model->status !!} </small>
		</h2>
	</div>
</div>
<div class="row invoice-info">
	<div class="col-sm-4 invoice-col">
		Owner
		<address>
			<strong>{{ $model->owner_name }}</strong><br>
		</address>
	</div>
	<div class="col-sm-4 invoice-col">
		Category
		<address>
			<strong>{{ ucwords($model->category) }}</strong><br>
		</address>
	</div>
	<div class="col-sm-4 invoice-col">
		<strong>Invoice #{{ $model->transaction_number }}</strong><br>
		<strong>Trans. Date : </strong>{{ $model->date }}
	</div>
</div>
<div class="row">
	<div class="col-xs-12 table-responsive">
		<table class="table table-striped">
			<tbody>
				<tr>
					<th width="20%">Amount</th>
					<td width="30%">{{ $model->amount }}</td>
					<th width="20%">Interest Precentage</th>
					<td>{{ $model->number_interest }}</td>
				</tr>
				<tr>
					<th width="20%">Tenor</th>
					<td>{{ $model->invest_tenor }}</td>
					<th width="20%">Total Interest</th>
					<td width="30%">{{ $model->total_interest }}</td>
				</tr>
				<tr>
					<th width="20%">Revenue Start Date</th>
					<td>{{ $model->revenue_start_date }}</td>
					<th width="20%">Tax (8%)</th>
					<td>{{ $model->tax }}</td>
				</tr>
				<tr>
					<th width="20%">Revenue End Date</th>
					<td>{{ $model->revenue_end_date }}</td>
					<th width="20%">After Tax</th>
					<td>{{ $model->after_tax }}</td>
				</tr>
			</tbody>
		</table>
		<hr>
		<table class="table table-striped">
			<tbody>
				<tr>
					<th width="20%">Daily Interest</th>
					<td>{{ $model->daily_interest }}</td>
					<th width="20%">Interest Count</th>
					<td>{!! $model->interest_count !!}</td>
				</tr>
				<tr>
					<th width="20%">Interest Paid</th>
					<td>{{ $model->interest_paid }}</td>
					<th width="20%">Total Amount Paid</th>
					<td>{{ $model->total_amount_paid }}</td>
				</tr>
				<tr>
					<th width="20%">Rest Interest</th>
					<td>{{ $model->rest_paid }}</td>
					<td></td>
					<td></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>