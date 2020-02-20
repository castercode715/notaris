<div class="row">
	<div class="col-md-6">
		<table class="table table-bordered">
			<tbody>
				<tr>
					<th width="30%">Asset Name</th>
					<td>{{ $model->kpr_name }}</td>
				</tr>
				<tr>
					<th>Tran. Date</th>
					<td>{{ date('d M Y H:i:s', strtotime($model->date_)) }} WIB</td>
				</tr>
				<tr>
					<th>Investor</th>
					<td>{{ ucwords($model->full_name) }}</td>
				</tr>
				<tr>
					<th>Current Status</th>
					<td>
						@if($model->status_ == "NEW")
							<span class='label label-success'>NEW</span>
						@elseif($model->status_ == "APPROVE")
							<span class='label label-info'>APPROVE</span>
						@elseif($model->status_ == "REJECT")
							<span class='label label-warning'>REJECT</span>
						@elseif($model->status_ == "CANCEL")
							<span class='label label-danger'>CANCEL</span>
						@else
							<span class='label label-default'>SURVEY</span>
						@endif
					</td>
				</tr>
				@if($model->status_ == "APPROVE")
					<tr>
						<th>Approved at</th>
						<td>{{ date('d M Y H:i', strtotime($model->approved_at)) }} WIB</td>
					</tr>
				@elseif($model->status_ == "REJECT")
					<tr>
						<th>Rejected at</th>
						<td>{{ date('d M Y H:i', strtotime($model->rejected_at)) }} WIB</td>
					</tr>
				@else

				@endif
			</tbody>
		</table>
	</div>
	<div class="col-md-6">
		<table class="table table-bordered">
			<tbody>
				<tr>
					<th width="30%">Price</th>
					<td>{{ number_format($model->price_) }} IDR</td>
				</tr>
				<tr>
					<th>Installment</th>
					<td>{{ number_format($model->installment_) }} IDR</td>
				</tr>
				<tr>
					<th>Tenor</th>
					<td>{{ $model->tenor_ }} Month</td>
				</tr>
				
			</tbody>
		</table>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<table class="table table-bordered">
			<tbody>
				<tr>
					<th width="30%">Surveyor</th>
					<td>{{ ucwords($model->surveyor) }}</td>
				</tr>
				<tr>
					<th>Surveyor Phone</th>
					<td>{{ $model->surveyor_phone }}</td>
				</tr>
				<tr>
					<th>Date Start</th>
					<td>{{ date('d M Y H:i:s', strtotime($model->survey_start_date)) }} WIB</td>
				</tr>
				<tr>
					<th>Date End</th>
					<td>{{ date('d M Y H:i:s', strtotime($model->survey_end_date)) }} WIB</td>
				</tr>
				
				<tr>
					<th>Note</th>
					<td>
						<textarea class="form-control">{{ $model->note }}</textarea>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	
</div>

<div id="btn-action" style="text-align: center;">
	
</div>

<center>
<a href="" class="btn btn-lg btn-success" id="btn-verify" data-id="" >
	<i class="fa fa-check"></i> Approve
</a>
<a href="javascript:void(0)" class="btn btn-lg btn-danger" id="btn-reject" data-id="" >
	<i class="fa fa-close"></i> Reject
</a>
</center>
