<table class="table">
	<tbody>
		<tr>
			<td rowspan="3">
				<img src="/images/investor/{{ $model->photo }}" class="profile-user-img img-responsive img-circle" style="width: 90px; height: 90px;" />
				<p class="text-muted text-center">
					@if ($model->is_dummy == 1)
						{!! "<span class=\"label label-default\"> DUMMY </span>" !!}
					@endif
				</p>
			</td>
			<th>Name</th>
			<td>{{ $model->full_name }}</td>
			<th>Class</th>
			<td>{{ $model->member->members->where('code','IND')->first()->description }}</td>
		</tr>
		<tr>
			<th>Status</th>
			<td>{{ $model->active == 1 ? 'ACTIVE' : 'INACTIVE' }}</td>
			<th>Completed</th>
			<td>{{ $model->is_completed == 1 ? 'YES' : 'NO' }}</td>
		</tr>
		<tr>
			<th>Register Date</th>
			<td>{{ date('d/m/Y H:i', strtotime($model->created_at_investor)) }}</td>
			<th>Register On</th>
			<td>{{ $model->register_on }}</td>
		</tr>
	</tbody>
</table>
<hr>
<div class="row">
	<div class="col-md-4">
		<div class="info-box bg-purple">
			<span class="info-box-icon"><i class="ion ion-ios-pricetag-outline"></i></span>
			<div class="info-box-content">
				<span class="info-box-text">Balance</span>
				<span class="info-box-number">{{ number_format($balance) }} IDR</span>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="info-box bg-yellow">
			<span class="info-box-icon"><i class="ion ion-ios-pricetag-outline"></i></span>
			<div class="info-box-content">
				<span class="info-box-text">Investment</span>
				<span class="info-box-number">{{ number_format($investment) }} IDR</span>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="info-box bg-red">
			<span class="info-box-icon"><i class="ion ion-ios-pricetag-outline"></i></span>
			<div class="info-box-content">
				<span class="info-box-text">Total</span>
				<span class="info-box-number">{{ number_format($total) }} IDR</span>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-4">
		<div class="info-box bg-green">
			<span class="info-box-icon"><i class="ion ion-ios-pricetag-outline"></i></span>
			<div class="info-box-content">
				<span class="info-box-text">active investment</span>
				<span class="info-box-number">{{ $asset['active'] }}</span>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="info-box bg-aqua">
			<span class="info-box-icon"><i class="ion ion-ios-pricetag-outline"></i></span>
			<div class="info-box-content">
				<span class="info-box-text">complete investment</span>
				<span class="info-box-number">{{ $asset['inactive'] }}</span>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="info-box bg-teal">
			<span class="info-box-icon"><i class="ion ion-ios-pricetag-outline"></i></span>
			<div class="info-box-content">
				<span class="info-box-text">canceled investment</span>
				<span class="info-box-number">{{ $asset['canceled'] }}</span>
			</div>
		</div>
	</div>
</div>
<hr>
<div class="row">
	
</div>