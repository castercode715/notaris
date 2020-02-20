<div class="nav-tabs-custom">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab_1" data-toggle="tab">Payment Confirmation Detail</a></li>
		<li><a href="#tab_2" data-toggle="tab">Inquiry Detail</a></li>
		<li><a href="#tab_3" data-toggle="tab">Investor Detail</a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="tab_1">
			<table class="table table-striped table-bordered">
				<tr><th>ID</th><td>{{ $payment->id }}</td></tr>
				<tr><th>Company Code</th><td>{{ $payment->company_code }}</td></tr>
				<tr><th>Customer Number</th><td>{{ $payment->customer_number }}</td></tr>
				<tr><th>Request ID</th><td>{{ $payment->request_id }}</td></tr>
				<tr><th>Channel Type</th><td>{{ $payment->channel_type }}</td></tr>
				<tr><th>Customer Name</th><td>{{ $payment->customer_name }}</td></tr>
				<tr><th>Currency Code</th><td>{{ $payment->currency_code }}</td></tr>
				<tr><th>Paid Amount</th><td>{{ $payment->paid_amount }}</td></tr>
				<tr><th>Total Amount</th><td>{{ $payment->total_amount }}</td></tr>
				<tr><th>Sub Company</th><td>{{ $payment->sub_company }}</td></tr>
				<tr><th>Transaction Date</th><td>{{ $payment->transaction_date }}</td></tr>
				<tr><th>Reference</th><td>{{ $payment->reference }}</td></tr>
				<tr><th>Detail Bills</th><td>{{ $payment->detail_bills }}</td></tr>
				<tr><th>Flag Advice</th><td>{{ $payment->flag_advice }}</td></tr>
				<tr><th>Additional Data</th><td>{{ $payment->additional_data }}</td></tr>
				<tr><th>Raw Request</th><td><textarea readonly="readonly" rows="3" class="form-control">{{ $payment->raw_request }}</textarea></td></tr>
				<tr><th>Payment Flag Status</th><td>{{ $payment->payment_flag_status }}</td></tr>
				<tr><th>Raw Response</th><td><textarea readonly="readonly" rows="3" class="form-control">{{ $payment->raw_response }}</textarea></td></tr>
				<tr><th>Created At</th><td>{{ $payment->created_at }}</td></tr>
			</table>
		</div>
		<!-- /.tab-pane -->
		<div class="tab-pane" id="tab_2">
			<table class="table table-striped table-bordered">
				<tbody>
					<tr><th>ID</th><td>{{ $inquiry->id }}</td></tr>
					<tr><th>Company Code</th><td>{{ $inquiry->company_code }}</td></tr>
					<tr><th>Customer Number</th><td>{{ $inquiry->customer_number }}</td></tr>
					<tr><th>Request ID</th><td>{{ $inquiry->request_id }}</td></tr>
					<tr><th>Channel Type</th><td>{{ $inquiry->channel_type }}</td></tr>
					<tr><th>Transaction Date</th><td>{{ $inquiry->transaction_date }}</td></tr>
					<tr><th>Additional Data</th><td>{{ $inquiry->additional_data }}</td></tr>
					<tr><th>Raw Request</th><td><textarea readonly="readonly" rows="3" class="form-control">{{ $inquiry->raw_request }}</textarea></td></tr>
					<tr><th>Inquiry Status</th><td>{{ $inquiry->inquiry_status }}</td></tr>
					<tr><th>Raw Response</th><td><textarea readonly="readonly" rows="3" class="form-control">{{ $inquiry->raw_response }}</textarea></td></tr>
					<tr><th>Created At</th><td>{{ $inquiry->created_at }}</td></tr>
					<tr><th>Status</th><td>{{ $inquiry->status }}</td></tr>
				</tbody>
			</table>
		</div>
		<!-- /.tab-pane -->
		<div class="tab-pane" id="tab_3">
			<table class="table table-striped table-bordered">
				<tbody>
					<tr><th>Fullname</th><td>{{ $investor->full_name }}</td></tr>
					<tr><th>Nickname</th><td>{{ $investor->nickname }}</td></tr>
					<tr><th>Username</th><td>{{ $investor->username }}</td></tr>
					<tr><th>Member ID</th><td>{{ $investor->member_id }}</td></tr>
					<tr><th>Active</th><td>{!! $investor->active == 1 ? '<span class="label label-success">Active</span>':'<span class="label label-danger">Non Active</span>' !!}</td></tr>
					<tr><th>Is Completed</th><td>{!! $investor->is_completed == 1 ? '<span class="label label-success">Yes</span>':'<span class="label label-danger">No</span>' !!}</td></tr>
					<tr><th>Is Dummy</th><td>{!! $investor->is_dummy == 1 ? '<span class="label label-success">Yes</span>':'<span class="label label-danger">No</span>' !!}</td></tr>
					<tr><th>ID</th><td>{{ $investor->id }}</td></tr>
					<tr><th>Code</th><td>{{ $investor->code }}</td></tr>
					<tr><th>Email</th><td>{{ $investor->email }}</td></tr>
					<tr><th>Phone</th><td>{{ $investor->phone }}</td></tr>
					<tr><th>Photo</th><td><img src="/images/investor/{{ $investor->photo }}" class="img-responsive"></td></tr>
					<tr><th>Register On</th><td>{{ $investor->register_on }}</td></tr>
					<tr><th>KTP Number</th><td>{{ $investor->ktp_number }}</td></tr>
					<tr><th>KTP Photo</th><td><img src="/images/investor/foto_berkas/{{ $investor->ktp_photo }}" class="img-responsive"></td></tr>
					<tr><th>NPWP Number</th><td>{{ $investor->npwp_number }}</td></tr>
					<tr><th>NPWP Photo</th><td><img src="/images/investor/foto_berkas/{{ $investor->npwp_photo }}" class="img-responsive"></td></tr>
					<tr><th>Register At</th><td>{{ $investor->created_at_investor }}</td></tr>
				</tbody>
			</table>
		</div>
		<!-- /.tab-pane -->
	</div>
	<!-- /.tab-content -->
</div>