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
					<td>
						@if($model->is_dummy == 1)
							{{ ucwords($model->full_name) }} <i><span class='label label-default'>dummy</span></i>
						@else
							{{ ucwords($model->full_name) }}
						@endif
					</td>
				</tr>
				<tr>
					<th>Current Status</th>
					<td>{{ $model->status }}</td>
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
					<th>Total Amount</th>
					<td>{{ $model->currency_code=='IDR' ? "Rp ".number_format($model->total_amount,0,',','.') : "$ ".number_format($model->total_amount,2,',','.') }}</td>
				</tr>

				<tr>
					<th>Cut Off</th>
					<td>
						@if($model->verified == 'Y')
							<i><span class='label label-success'>Yes</span></i>
						@else
							<i><span class='label label-danger'>No</span></i>
						@endif
					</td>
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
					<th>No</th>
					<th>Tran. Number Detail</th>
					<th>Asset</th>
					<th>Invest Tenor</th>
					<th>Amount</th>
					<th>Interest</th>
					<th>Canceled At</th>
					<th>Status</th>
					
				</tr>
			</thead>
			<tbody>
				@php $no = 1; @endphp
				@foreach($data as $result)
					<tr>
						<td><center>{{ $no }}</center></td>
						<td>{{ $result->transaction_number_detail }}</td>
						<td>{{ $result->asset_name }}</td>
						<td>{{ $result->invest_tenor }} Days</td>
						<td>Rp. {{ number_format($result->amount) }}</td>
						<td>{{ $result->number_interest }} %</td>
						<td>
							@if($result->canceled == 2)
								{{ date('d M Y H:i', strtotime($result->canceled_at)).' WIB' }}
							@else

							@endif
						</td>
						<td>{{ $result->status }}</td>
					</tr>
				@php $no++ @endphp
				@endforeach()
			</tbody>
		</table>
	</div>
	
</div>
<div id="btn-action" style="text-align: center;">
	
</div>
